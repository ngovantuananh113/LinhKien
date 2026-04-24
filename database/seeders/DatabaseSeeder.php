<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123456');

        Schema::disableForeignKeyConstraints();
        OrderItem::query()->delete();
        Order::query()->delete();
        CartItem::query()->delete();
        Cart::query()->delete();
        Product::query()->delete();
        Category::query()->delete();
        User::query()->whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->delete();
        Schema::enableForeignKeyConstraints();

        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên',
                'phone' => '0901000001',
                'password' => $password,
                'role' => 'admin',
            ]
        );

        $user = User::query()->updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Nguyễn Văn A',
                'phone' => '0901000002',
                'password' => $password,
                'role' => 'user',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'demo.synth@gmail.com'],
            [
                'name' => 'Trần Thị B',
                'phone' => '0901000003',
                'password' => $password,
                'role' => 'user',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'architect.test@gmail.com'],
            [
                'name' => 'Lê Minh C',
                'phone' => '0901000004',
                'password' => $password,
                'role' => 'user',
            ]
        );

        $categories = [
            ['name' => 'CPU', 'description' => 'Bộ xử lý đa nhân'],
            ['name' => 'VGA', 'description' => 'Card đồ họa hiệu năng cao'],
            ['name' => 'RAM', 'description' => 'Bộ nhớ trong DDR4/DDR5'],
            ['name' => 'SSD', 'description' => 'Ổ cứng thể rắn NVMe'],
            ['name' => 'Mainboard', 'description' => 'Bo mạch chủ'],
            ['name' => 'Nguồn', 'description' => 'Nguồn máy tính'],
            ['name' => 'Tản nhiệt', 'description' => 'Tản khí & tản nước'],
            ['name' => 'Case', 'description' => 'Vỏ case máy tính'],
            ['name' => 'Chuột', 'description' => 'Chuột gaming'],
            ['name' => 'Bàn phím', 'description' => 'Bàn phím cơ'],
        ];

        $catIds = [];
        foreach ($categories as $c) {
            $cat = Category::query()->create([
                'name' => $c['name'],
                'description' => $c['description'],
            ]);
            $catIds[$c['name']] = $cat->id;
        }

        $productsData = [
            [
                'name' => 'AMD Ryzen 7 7800X3D',
                'price' => 8990000,
                'quantity' => 12,
                'category' => 'CPU',
                'description' => "CPU gaming AM5 với 3D V-Cache 96MB, tối ưu độ trễ khung hình.\nHiệu năng tham chiếu: ổn định 1440p/165Hz với VGA tầm trung trở lên.",
            ],
            [
                'name' => 'Intel Core i5-14600KF',
                'price' => 6790000,
                'quantity' => 18,
                'category' => 'CPU',
                'description' => "14 nhân (6P+8E), xung boost cao, phù hợp đa nhiệm và render nhẹ.\nTương thích socket LGA1700, khuyến nghị tản khí 240mm trở lên.",
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4070 SUPER 12GB',
                'price' => 14990000,
                'quantity' => 7,
                'category' => 'VGA',
                'description' => "Kiến trúc Ada Lovelace, DLSS 3, ray tracing thế hệ mới.\nVRAM 12GB GDDR6X — phù hợp QHD và content creator.",
            ],
            [
                'name' => 'AMD Radeon RX 7800 XT 16GB',
                'price' => 11290000,
                'quantity' => 5,
                'category' => 'VGA',
                'description' => "RDNA 3, băng thông bộ nhớ rộng, hiệu năng/Watt tốt.\nPhù hợp game 1440p và máy chỉnh sửa video.",
            ],
            [
                'name' => 'G.Skill Trident Z5 RGB 32GB (2x16) DDR5-6000',
                'price' => 3290000,
                'quantity' => 25,
                'category' => 'RAM',
                'description' => "Kit 2 kênh, XMP/EXPO hỗ trợ, đèn RGB đồng bộ mainboard.\nTốc độ 6000MT/s — cân bằng độ trễ và ổn định.",
            ],
            [
                'name' => 'Corsair Vengeance LPX 16GB (2x8) DDR4-3200',
                'price' => 990000,
                'quantity' => 40,
                'category' => 'RAM',
                'description' => "Profile XMP 2.0, heatsink thấp — tương thích nhiều case nhỏ.\nPhù hợp nâng cấp máy AM4 / Intel thế hệ 10–12.",
            ],
            [
                'name' => 'Samsung 990 PRO 2TB NVMe PCIe 4.0',
                'price' => 4590000,
                'quantity' => 15,
                'category' => 'SSD',
                'description' => "Đọc/ghi cực nhanh, phù hợp hệ điều hành + game + project lớn.\nDRAM cache, endurance cao cho workload nặng.",
            ],
            [
                'name' => 'WD Black SN850X 1TB',
                'price' => 2190000,
                'quantity' => 22,
                'category' => 'SSD',
                'description' => "PCIe 4.0, hiệu năng ổn định khi nhiệt độ tăng.\nLựa chọn phổ biến cho máy gaming và workstation nhẹ.",
            ],
            [
                'name' => 'ASUS TUF Gaming B650-PLUS WIFI',
                'price' => 4990000,
                'quantity' => 9,
                'category' => 'Mainboard',
                'description' => "Chipset B650, WiFi 6E, VRM mạnh cho Ryzen 7000/8000.\nHỗ trợ DDR5, PCIe 5.0 cho SSD và GPU thế hệ mới.",
            ],
            [
                'name' => 'MSI MAG A750GL PCIE5',
                'price' => 1890000,
                'quantity' => 14,
                'category' => 'Nguồn',
                'description' => "750W 80 Plus Gold, modular, đầu ra 12VHPWR cho VGA mới.\nQuạt FDB yên tĩnh, bảo vệ đa tầng.",
            ],
            [
                'name' => 'Corsair RM850e 850W',
                'price' => 2290000,
                'quantity' => 11,
                'category' => 'Nguồn',
                'description' => "850W Gold, hiệu suất cao ở tải 50%, dây modular gọn.\nPhù hợp cấu hình RTX 4070 Ti / RX 7900 class.",
            ],
            [
                'name' => 'DeepCool AK620 Zero Dark',
                'price' => 1190000,
                'quantity' => 20,
                'category' => 'Tản nhiệt',
                'description' => "Tản khí đôi 120mm, RAM clearance tốt, cân bằng hiệu năng/tiếng ồn.\nTương thích socket AM4/AM5 và LGA1700.",
            ],
            [
                'name' => 'Lian Li Lancool 216 RGB',
                'price' => 2490000,
                'quantity' => 8,
                'category' => 'Case',
                'description' => "Luồng khí tốt sẵn 2 fan 160mm, hỗ trợ radiator 360mm top.\nKính cường lực, dễ build và quản lý cáp.",
            ],
            [
                'name' => 'Logitech G Pro X Superlight 2',
                'price' => 3290000,
                'quantity' => 16,
                'category' => 'Chuột',
                'description' => "Cảm biến HERO 2, siêu nhẹ, phù hợp esport.\nPin lâu, đế PTFE mượt — kết nối Lightspeed.",
            ],
            [
                'name' => 'Keychron Q1 Pro QMK',
                'price' => 4190000,
                'quantity' => 10,
                'category' => 'Bàn phím',
                'description' => "Layout 75%, khung nhôm, hot-swap, kết nối Bluetooth/USB.\nFirmware QMK/VIA — tùy biến layer và macro sâu.",
            ],
        ];

        foreach ($productsData as $p) {
            Product::query()->create([
                'name' => $p['name'],
                'price' => $p['price'],
                'quantity' => $p['quantity'],
                'category_id' => $catIds[$p['category']],
                'description' => $p['description'],
                'image' => null,
            ]);
        }

        $allProducts = Product::query()->orderBy('id')->get();
        $p1 = $allProducts->get(2);
        $p2 = $allProducts->get(4);

        $cart = $user->currentCart();
        CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $p1->id,
            'quantity' => 1,
        ]);
        CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $p2->id,
            'quantity' => 2,
        ]);

        $demoUser = User::query()->where('email', 'demo.synth@gmail.com')->first();
        if ($demoUser) {
            $order1 = Order::query()->create([
                'user_id' => $demoUser->id,
                'recipient_name' => 'Trần Thị B',
                'total_price' => $allProducts->get(0)->price * 1 + $allProducts->get(6)->price * 1,
                'status' => Order::STATUS_COMPLETED,
                'address' => '123 Đường Synth, Phường Bến Nghé',
                'city' => 'TP. Hồ Chí Minh',
                'postal_code' => '700000',
                'phone' => '0901000003',
            ]);
            OrderItem::query()->create([
                'order_id' => $order1->id,
                'product_id' => $allProducts->get(0)->id,
                'quantity' => 1,
                'price' => $allProducts->get(0)->price,
            ]);
            OrderItem::query()->create([
                'order_id' => $order1->id,
                'product_id' => $allProducts->get(6)->id,
                'quantity' => 1,
                'price' => $allProducts->get(6)->price,
            ]);
        }

        $order2 = Order::query()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Nguyễn Văn A',
            'total_price' => $allProducts->get(3)->price * 1,
            'status' => Order::STATUS_PROCESSING,
            'address' => '456 Phố Kiến trúc, Phường Dịch Vọng',
            'city' => 'Hà Nội',
            'postal_code' => '100000',
            'phone' => '0901000002',
        ]);
        OrderItem::query()->create([
            'order_id' => $order2->id,
            'product_id' => $allProducts->get(3)->id,
            'quantity' => 1,
            'price' => $allProducts->get(3)->price,
        ]);
    }
}
