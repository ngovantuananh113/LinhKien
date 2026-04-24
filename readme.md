📘 README - Website Quản Lý Bán Linh Kiện Máy Tính
1. Giới thiệu

Đây là hệ thống website bán linh kiện máy tính cơ bản, phục vụ cho mục đích học tập và làm đồ án.

Hệ thống cho phép:

Người dùng xem và mua sản phẩm
Quản trị viên quản lý sản phẩm, danh mục và đơn hàng

Công nghệ sử dụng:

Laravel (PHP Framework)
MySQL
Blade Template (Frontend tích hợp trong Laravel)
MVC Architecture
2. Chức năng hệ thống
2.1. Người dùng (User)
Xác thực
Đăng ký
Đăng nhập / đăng xuất
Sản phẩm
Xem danh sách sản phẩm
Tìm kiếm sản phẩm
Xem chi tiết sản phẩm
Giỏ hàng
Thêm sản phẩm vào giỏ
Cập nhật số lượng
Xóa sản phẩm
Đặt hàng
Nhập thông tin nhận hàng
Xác nhận đặt hàng
Đơn hàng
Xem danh sách đơn đã đặt
Xem trạng thái đơn hàng
2.2. Quản trị viên (Admin)
Dashboard
Tổng số sản phẩm
Tổng số đơn hàng
Tổng số user
Quản lý danh mục
Thêm / sửa / xóa danh mục
Quản lý sản phẩm
Thêm / sửa / xóa sản phẩm
Upload hình ảnh
Quản lý đơn hàng
Xem danh sách đơn
Cập nhật trạng thái:
Pending
Processing
Completed
Cancelled
Quản lý người dùng
Xem danh sách user
3. Thiết kế cơ sở dữ liệu (MySQL)
users
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
email VARCHAR(255) UNIQUE,
password VARCHAR(255),
role ENUM('admin','user') DEFAULT 'user',
created_at TIMESTAMP,
updated_at TIMESTAMP
categories
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
description TEXT,
created_at TIMESTAMP,
updated_at TIMESTAMP
products
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255),
price DECIMAL(10,2),
quantity INT,
image VARCHAR(255),
description TEXT,
category_id INT,
created_at TIMESTAMP,
updated_at TIMESTAMP
carts
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
created_at TIMESTAMP,
updated_at TIMESTAMP
cart_items
id INT AUTO_INCREMENT PRIMARY KEY,
cart_id INT,
product_id INT,
quantity INT
orders
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
total_price DECIMAL(10,2),
status VARCHAR(50),
address TEXT,
phone VARCHAR(20),
created_at TIMESTAMP,
updated_at TIMESTAMP
order_items
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT,
product_id INT,
quantity INT,
price DECIMAL(10,2)
4. Kiến trúc MVC
Model
User
Product
Category
Order
Cart
View
Blade template
Chia thành:
user/
admin/
layouts/
Controller
User:
HomeController
ProductController
CartController
OrderController
Admin:
DashboardController
ProductController
CategoryController
OrderController
5. Cấu trúc thư mục
app/
 ├── Models/
 ├── Http/Controllers/
 │    ├── Admin/
 │    └── ...
resources/
 ├── views/
 │    ├── user/
 │    ├── admin/
 │    └── layouts/
routes/
 └── web.php
public/
 └── images/
6. Luồng hoạt động
6.1. Người dùng mua hàng
Đăng nhập
Xem sản phẩm
Thêm vào giỏ hàng
Thanh toán
Tạo đơn hàng
6.2. Admin xử lý đơn
Đăng nhập admin
Xem danh sách đơn
Cập nhật trạng thái đơn
7. Cài đặt và chạy dự án
7.1. Yêu cầu môi trường
PHP >= 8.x
Composer
MySQL
Laravel CLI (optional)
7.2. Cài đặt

Bước 1: Clone project

git clone <link-repo>
cd project

Bước 2: Cài thư viện

composer install

Bước 3: Tạo file môi trường

cp .env.example .env

Bước 4: Cấu hình database trong .env

DB_DATABASE=linhkien_db
DB_USERNAME=root
DB_PASSWORD=123456

Bước 5: Generate key

php artisan key:generate

Bước 6: Migration

php artisan migrate

(Nếu có seed)

php artisan db:seed
7.3. Chạy project
php artisan serve

Truy cập:

http://127.0.0.1:8000
8. Tài khoản mẫu

Admin

email: admin@gmail.com
password: 123456

User

email: user@gmail.com
password: 123456
9. Điểm nổi bật
Áp dụng mô hình MVC
CRUD đầy đủ
Phân quyền user / admin
Có luồng đặt hàng hoàn chỉnh
10. Hạn chế
Chưa tích hợp thanh toán online
Chưa có realtime
UI đơn giản (đừng mơ đẹp như Shopee)
11. Hướng phát triển
Tích hợp thanh toán VNPay / Momo
Thêm đánh giá sản phẩm
Thêm báo cáo thống kê
Nâng cấp UI/UX