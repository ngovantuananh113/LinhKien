<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->indexData($request);
        $formFields = $this->formFieldValues($request, $data['editingProduct']);

        return view('admin.products.index', array_merge($data, $formFields, [
            'productsCurrentPage' => $data['products']->currentPage(),
        ]));
    }

    /**
     * Trả JSON: form + bảng + số liệu (dùng cho AJAX, không tải lại trang).
     */
    public function fragments(Request $request): JsonResponse
    {
        return response()->json($this->buildFragmentPayload($request));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $this->validatedProduct($request);
        } catch (ValidationException $e) {
            if ($this->wantsProductFragments($request)) {
                return $this->productFormErrorResponse($request, $e, null);
            }
            throw $e;
        }

        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::query()->create($data);

        if ($this->wantsProductFragments($request)) {
            return $this->productSuccessJson($request, 'Đã thêm sản phẩm.');
        }

        return redirect()->route('admin.products.index', $this->filterQueryParams(['q' => $request->input('return_q')]))->with('success', 'Đã thêm sản phẩm.');
    }

    public function edit(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.index', array_filter([
            'edit' => $product->id,
            'q' => request('q'),
            'page' => request('page'),
        ], fn ($v) => $v !== null && $v !== ''));
    }

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        try {
            $data = $this->validatedProduct($request);
        } catch (ValidationException $e) {
            if ($this->wantsProductFragments($request)) {
                return $this->productFormErrorResponse($request, $e, $product);
            }
            throw $e;
        }

        unset($data['image']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        if ($this->wantsProductFragments($request)) {
            return $this->productSuccessJson($request, 'Đã cập nhật sản phẩm.');
        }

        return redirect()->route('admin.products.index', $this->filterQueryParams(['q' => $request->input('return_q')]))->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $backQ = ['q' => $request->input('return_q')];
        if ($product->orderItems()->exists()) {
            if ($this->wantsProductFragments($request)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Không xóa được: sản phẩm đã có trong đơn hàng.',
                ], 422);
            }

            return redirect()->route('admin.products.index', $this->filterQueryParams($backQ))->with('error', 'Không xóa được: sản phẩm đã có trong đơn hàng.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        if ($this->wantsProductFragments($request)) {
            return $this->productSuccessJson($request, 'Đã xóa sản phẩm.');
        }

        return redirect()->route('admin.products.index', $this->filterQueryParams($backQ))->with('success', 'Đã xóa sản phẩm.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexData(Request $request, ?Product $editingOverride = null): array
    {
        $q = $request->string('q')->trim();
        $products = Product::query()
            ->with('category')
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();
        $globalStock = (int) Product::query()->sum('quantity');
        $activeSku = Product::query()->count();

        $editingProduct = $editingOverride;
        if ($editingProduct === null && $request->filled('edit')) {
            $editingProduct = Product::query()->with('category')->find($request->integer('edit'));
        }

        return compact('products', 'categories', 'globalStock', 'activeSku', 'editingProduct');
    }

    /**
     * @return array<string, mixed>
     */
    private function formFieldValues(Request $request, ?Product $editingProduct, bool $preferRequest = false): array
    {
        $e = $editingProduct;
        if ($preferRequest) {
            return [
                'fvName' => $request->input('name', $e?->name ?? ''),
                'fvPrice' => $request->input('price', $e !== null ? (string) (int) $e->price : ''),
                'fvQty' => $request->input('quantity', $e !== null ? (string) $e->quantity : '0'),
                'fvCat' => $request->input('category_id', $e?->category_id),
                'fvDesc' => $request->input('description', $e?->description ?? ''),
                'isEdit' => $e !== null,
            ];
        }

        return [
            'fvName' => old('name', $e?->name),
            'fvPrice' => old('price', $e !== null ? (string) (int) $e->price : ''),
            'fvQty' => old('quantity', $e !== null ? (string) $e->quantity : '0'),
            'fvCat' => old('category_id', $e?->category_id),
            'fvDesc' => old('description', $e?->description ?? ''),
            'isEdit' => $e !== null,
        ];
    }

    /**
     * @return array{form: string, table: string, globalStock: int, activeSku: int}
     */
    private function buildFragmentPayload(Request $request): array
    {
        $data = $this->indexData($request);
        $formData = array_merge(
            $data,
            $this->formFieldValues($request, $data['editingProduct']),
            ['productsCurrentPage' => $data['products']->currentPage()]
        );

        return [
            'form' => view('admin.products.partials.form', $formData)->render(),
            'table' => view('admin.products.partials.table', $data)->render(),
            'globalStock' => $data['globalStock'],
            'activeSku' => $data['activeSku'],
        ];
    }

    private function wantsProductFragments(Request $request): bool
    {
        return $request->header('X-Product-Fragments') === '1';
    }

    private function productSuccessJson(Request $request, string $message): JsonResponse
    {
        $query = array_filter([
            'q' => $request->input('return_q'),
            'page' => $request->input('return_page', 1),
        ], fn ($v) => $v !== null && $v !== '');

        $sub = Request::create(route('admin.products.fragments'), 'GET', $query);

        return response()->json(array_merge(
            $this->buildFragmentPayload($sub),
            [
                'ok' => true,
                'message' => $message,
            ]
        ));
    }

    private function productFormErrorResponse(Request $request, ValidationException $e, ?Product $editing): JsonResponse
    {
        $data = $this->indexData($request, $editing);
        $formData = array_merge(
            $data,
            $this->formFieldValues($request, $data['editingProduct'], true),
            ['productsCurrentPage' => $data['products']->currentPage()]
        );

        return response()->json([
            'message' => collect($e->errors())->flatten()->first() ?? 'Dữ liệu không hợp lệ.',
            'errors' => $e->errors(),
            'form' => view('admin.products.partials.form', $formData)->withErrors($e->validator)->render(),
        ], 422);
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function filterQueryParams(array $params): array
    {
        return array_filter($params, fn ($v) => $v !== null && $v !== '');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
