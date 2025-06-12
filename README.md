# **ZipLMS \- Multi-Platform Learning Management System**

**ZipLMS** là một hệ thống quản lý học tập (LMS) mã nguồn mở, hiện đại và linh hoạt, được xây dựng trên nền tảng Laravel. Dự án được thiết kế với kiến trúc đa tổ chức, cho phép một cài đặt duy nhất có thể phục vụ nhiều trường học, trung tâm đào tạo hoặc các cơ sở giáo dục khác nhau, mỗi đơn vị có không gian làm việc và quản lý riêng.

## **✨ Key Features (Tính năng nổi bật)**

* **Multi-organization Architecture:** Hỗ trợ quản lý nhiều tổ chức độc lập trên cùng một hệ thống.  
* **Flexible Academic Structure:** Dễ dàng định nghĩa các cấu trúc phức tạp như Khoa, Chuyên ngành, Lớp học, và Khối lớp.  
* **Role-Based Access Control (RBAC):** Hệ thống phân quyền mạnh mẽ với các vai trò Super Admin, Admin Tổ chức, Quản lý, Giáo viên, và Học sinh, được quản lý bởi spatie/laravel-permission.  
* **Comprehensive Course Management:** Tạo và quản lý các khóa học, bài giảng, tài liệu đính kèm. Hỗ trợ ghi danh sinh viên và phân công giáo viên.  
* **Advanced Assessment System:** Bao gồm Ngân hàng câu hỏi, tạo Bài kiểm tra và Bài tập, cùng với hệ thống làm bài và chấm điểm chi tiết.  
* **Modern Admin Panel:** Giao diện quản trị được xây dựng bằng **Filament v3** và **Livewire/Volt**, mang lại trải nghiệm nhanh, tương tác cao và dễ tùy chỉnh.  
* **Extensible and Maintainable:** Tận dụng sức mạnh của các thư viện hàng đầu từ Spatie và cộng đồng Laravel để đảm bảo code sạch, dễ bảo trì và mở rộng.  
* **Dynamic Settings & PWA:** Admin có thể tùy chỉnh cài đặt ứng dụng và cấu hình Progressive Web App (PWA) một cách linh hoạt.

## **🚀 Tech Stack (Công nghệ sử dụng)**

* **Backend:** Laravel 11.x, PHP 8.3+  
* **Admin Panel:** Filament 3.x, Livewire 3.x / Volt  
* **Server:** Laravel Octane (với Swoole)  
* **Database:** MySQL  
* **Key Libraries:**  
  * spatie/laravel-permission (Roles & Permissions)  
  * spatie/laravel-medialibrary (File Management)  
  * spatie/laravel-settings (Application Settings)  
  * spatie/laravel-tags (Tagging & Categorization)  
  * spatie/laravel-model-states (State Management)  
  * spatie/laravel-translatable (Multilingual Support)  
  * spatie/laravel-activitylog (Activity Logging)  
  * spatie/laravel-backup (Database & File Backups)  
  * spatie/laravel-health (Application Health Monitoring)  
  * spatie/laravel-one-time-password (2FA)  
  * maatwebsite/excel (Excel/CSV Import & Export)  
  * eraga/laravel-pwa (Progressive Web App)  
* **Frontend Build & Dependencies:** Vite, Tailwind CSS (thông qua Filament), PNPM

## **👥 Our Team (Thành viên Nhóm)**

Dự án này được xây dựng bởi một nhóm các nhà phát triển tâm huyết.

| Tên Thành viên | Vai trò                         | GitHub                                   |
| -------------- | ------------------------------- | ---------------------------------------- |
| Lộc Shino      | Project Manager / Backend Dev   | [@locshino](https://github.com/locshino) |
| Tạ Dương       | Full-stack Dev                  |                                          |
| Long Vũ        | Backend Dev                     |                                          |
| Tạ Huy         | Frontend / UI-UX                |                                          |
| Tuấn Trần      | Tester / QA                     |                                          |
| Hoàng Việt     | Documentation                   |                                          |

## **🛠️ Installation Guide (Hướng dẫn cài đặt)**

### **Prerequisites**

***Docker Desktop:** Cho môi trường Laravel Sail.
***Composer:** Quản lý thư viện PHP.
***PNPM:** Quản lý thư viện JavaScript (khuyến nghị, hoặc NPM/Yarn).

### **Setup Steps**

1. **Clone the repository:**

    ```bash
    git clone https://github.com/locshino/ziplms.git
    cd ziplms
    ```

2. **Copy environment file:**
    Sử dụng file `.env.docker.example` làm cơ sở cho môi trường Sail:

    ```bash
    cp .env.docker.example .env
    ```

    *Sau đó, mở file `.env` và cập nhật các biến môi trường cần thiết, đặc biệt là `APP_NAME`, `APP_URL` (ví dụ: `http://localhost:8080` nếu `APP_PORT=8080`), và thông tin kết nối CSDL (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

3. **Start Laravel Sail:**
    Lệnh này sẽ build Docker images (lần đầu) và khởi chạy các container trong chế độ detached (-d).

    ```bash
    ./vendor/bin/sail up -d
    ```

4. **Install PHP dependencies (via Sail):**

    ```bash
    sail composer install
    ```

5. **Generate application key (via Sail):**

    ```bash
    sail php artisan key:generate
    ```

6. **Run database migrations (via Sail):**

    ```bash
    sail php artisan migrate
    ```

7. **Install frontend dependencies and build assets (via Sail):**

    ```bash
    sail pnpm install
    sail pnpm build
    ```

8. **Run library-specific commands (via Sail):**

    ```bash
    # Discover settings classes (for spatie/laravel-settings)
    sail php artisan settings:discover
    ```

    *(Bạn có thể cần chạy các lệnh publish khác cho các thư viện nếu cần thiết, ví dụ: `sail php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`)*

9. **Access the application:**
    * Mặc định, ứng dụng sẽ chạy tại URL bạn đã cấu hình trong `APP_URL` của file `.env` (ví dụ: `http://localhost:8080` nếu `APP_PORT=8080`).
    * **Quan trọng:** Lần đầu tiên truy cập, bạn có thể được chuyển hướng đến trang `/initial-setup` để thực hiện các cài đặt cơ bản cho hệ thống.

## **🚀 Development Workflow (Quy trình Phát triển)**

Sử dụng các lệnh sau bên trong Sail để phát triển:

* **Start all development services (web server, queue, Vite):**
    Lệnh này sử dụng `concurrently` để chạy các tác vụ song song (được định nghĩa trong script `dev` của `composer.json`).

    ```bash
    sail composer dev
    ```

* **Run Vite development server (for frontend hot reloading) separately:**

    ```bash
    sail pnpm dev
    ```

* **Run queue worker:**

    ```bash
    sail php artisan queue:work
    ```

* **Optimize application (clear and cache config, routes, views, Filament):**
    Sử dụng script `optimize` (hoặc `optimize-dev` tùy theo tên bạn đặt trong `composer.json`).

    ```bash
    sail composer optimize
    ```

## **📦 Deployment Process (Quy trình Triển khai)**

Khi triển khai ứng dụng lên môi trường production:

1. **Ensure your server has Docker and necessary tools.**
2. **Clone the repository or pull the latest changes.**
3. **Install PHP dependencies for production (no dev dependencies, optimized autoloader):**

    ```bash
    sail composer install --no-dev --optimize-autoloader
    ```

4. **Run the deployment script (recommended):**
    Tạo một script `deploy` trong `composer.json` của bạn để tự động hóa các bước sau. Ví dụ:

    ```json
    // In your composer.json "scripts"
    "deploy": [
        "@php -r \"if(!file_exists('.env')) { if(file_exists('.env.docker.example')) { copy('.env.docker.example', '.env'); echo 'Copied .env.docker.example to .env'.PHP_EOL; } elseif(file_exists('.env.example')) { copy('.env.example', '.env'); echo 'Copied .env.example to .env'.PHP_EOL; } else { echo '.env file is missing and no example found. Please create one manually.'.PHP_EOL; exit(1); } }\"",
        "@php artisan key:generate --ansi --force",
        "@php artisan storage:link",
        "@php artisan migrate --force --graceful --ansi",
        "@composer optimize", // Assumes you have an "optimize" script
        "pnpm install --prod", // Install production frontend dependencies
        "pnpm build" // Build frontend assets
    ],
    ```

    Sau đó chạy:

    ```bash
    sail composer deploy
    ```

    *Đảm bảo file `.env` trên server production được cấu hình chính xác (`APP_ENV=production`, `APP_DEBUG=false`, DB credentials, etc.).*

5. **Configure your web server** (e.g., Nginx) to point to the `public` directory.
6. **Set up Supervisor** to keep queue workers running: `sail php artisan queue:work --daemon`.
7. **Set up Cron job** for Laravel Scheduler: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1` (chạy lệnh này bên trong container hoặc trỏ tới PHP của container).

## **🧪 Running Tests & Code Style**

* **To run the test suite:**

    ```bash
    sail php artisan test
    ```

* **To check for code style issues:**  

    ```bash
    sail pint \--test
    ```

* **To automatically fix code style issues:**  

    ```bash
    sail pint
    ```

## **🤝 Contributing (Đóng góp)**

Chúng tôi rất hoan nghênh các đóng góp\! Vui lòng xem CONTRIBUTING.md để biết chi tiết về quy trình đóng góp và các tiêu chuẩn code của chúng tôi.

1. Fork the Project  
2. Create your Feature Branch (git checkout \-b feature/AmazingFeature)  
3. Commit your Changes (git commit \-m 'Add some AmazingFeature')  
4. Push to the Branch (git push origin feature/AmazingFeature)  
5. Open a Pull Request

## **📄 License (Giấy phép)**

Dự án này được cấp phép theo **MIT License**. Xem file LICENSE.md để biết thêm chi tiết.

## **📬 Contact (Liên hệ)**

Lộc Shino – [@Loc\_Shino](https://x.com/Loc_Shino) – <locshino123@gmail.com>

Project Link: [https://github.com/locshino/ziplms](https://github.com/locshino/ziplms)
