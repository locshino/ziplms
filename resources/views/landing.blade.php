<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ================== SEO Meta Tags ================== -->
    <title>ZipLMS - Hệ thống LMS Mã nguồn mở miễn phí cho Quiz & Bài tập</title>
    <meta name="description"
        content="ZipLMS là hệ thống LMS mã nguồn mở miễn phí, giúp các trung tâm và doanh nghiệp nhỏ tạo, quản lý và chấm điểm các bài quiz và bài tập một cách hiệu quả.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ config('app.url') }}">

    <!-- ================== Favicon Tags ================== -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

    <!-- ================== Social Media Meta Tags ================== -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="ZipLMS - LMS Mã nguồn mở miễn phí cho Quiz & Bài tập">
    <meta property="og:description"
        content="Giải pháp đơn giản, miễn phí để tạo và quản lý bài kiểm tra trực tuyến cho các trung tâm và doanh nghiệp nhỏ.">
    <meta property="og:image" content="{{ config('app.url') }}/images/social-preview.jpg">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="ZipLMS - LMS Mã nguồn mở miễn phí cho Quiz & Bài tập">
    <meta property="twitter:description"
        content="Giải pháp đơn giản, miễn phí để tạo và quản lý bài kiểm tra trực tuyến cho các trung tâm và doanh nghiệp nhỏ.">
    <meta property="twitter:image" content="{{ config('app.url') }}/images/social-preview.jpg">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    {{-- CSS is now loaded via Vite from resources/css/landing.css --}}
    @vite(['resources/css/landing.css'])

    <!-- ================== SEO: Structured Data (Schema.org) ================== -->
    @verbatim
        <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "ZipLMS",
      "applicationCategory": "EducationalApplication",
      "operatingSystem": "Web",
      "description": "ZipLMS là một hệ thống LMS mã nguồn mở và miễn phí, tập trung vào việc giúp các trung tâm và doanh nghiệp nhỏ tạo, quản lý và chấm điểm các bài quiz và bài tập.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "VND"
      },
      "softwareHelp": {
        "@type": "CreativeWork",
        "url": "https://github.com/locshino/ziplms"
      },
      "codeRepository": "https://github.com/locshino/ziplms"
    }
    </script>
    @endverbatim
</head>

<body>
    <main class="container">
        <header>
            <h1 class="gradient-title">ZipLMS</h1>
            <p class="subtitle">Hệ thống LMS mã nguồn mở & miễn phí</p>
        </header>

        <p class="desc">
            Giải pháp đơn giản, dành cho các trung tâm và doanh nghiệp nhỏ cần một công cụ hiệu quả để tạo, quản lý và
            chấm điểm các bài **quiz** và **bài tập** trực tuyến.
        </p>

        <a href="/app" class="access-btn">Truy cập nhanh</a>

        <section class="timeline">
            <div class="timeline-step">
                <div class="timeline-dot">1</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Tạo Quiz và Bài tập Nhanh chóng</h2>
                    <p class="timeline-desc">Xây dựng các bài kiểm tra trắc nghiệm, tự luận và bài tập thực hành một
                        cách trực quan.</p>
                </div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">2</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Quản lý Học viên & Lớp học</h2>
                    <p class="timeline-desc">Dễ dàng thêm học viên, phân chia theo lớp và chỉ định bài tập cho từng
                        nhóm.</p>
                </div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">3</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Hệ thống Chấm điểm & Theo dõi Kết quả</h2>
                    <p class="timeline-desc">Tự động chấm điểm trắc nghiệm và cung cấp công cụ để giáo viên chấm bài tự
                        luận hiệu quả.</p>
                </div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">4</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Mã nguồn mở & Tự do Tùy chỉnh</h2>
                    <p class="timeline-desc">Xây dựng trên Laravel & Filament. Bạn toàn quyền sở hữu, tùy chỉnh và mở
                        rộng hệ thống.</p>
                </div>
            </div>
            <div class="timeline-step">
                <div class="timeline-dot">5</div>
                <div class="timeline-content">
                    <h2 class="timeline-title">Miễn phí và Dễ dàng Triển khai</h2>
                    <p class="timeline-desc">Không chi phí bản quyền. Bạn có thể tự triển khai trên hosting của mình chỉ
                        trong vài bước.</p>
                </div>
            </div>
        </section>

        <footer class="footer">© 2025 ZipLMS - Một sản phẩm mã nguồn mở.</footer>
    </main>
</body>

</html>
