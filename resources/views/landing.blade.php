<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ZipLMS - Nền tảng quản lý học tập hiện đại</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <style>
    body {
      /* Generated a subtle striped background using CSS gradients.
         - background-color: A dark gray base.
         - background-image: Two repeating linear gradients to create a subtle, cross-hatched pattern of white lines.
      */
      background-color: #1a1a1a;
      /* Dark gray base color */
      background-image:
        repeating-linear-gradient(45deg,
          transparent,
          transparent 30px,
          rgba(255, 255, 255, 0.05) 30px,
          rgba(255, 255, 255, 0.05) 31px),
        repeating-linear-gradient(-45deg,
          transparent,
          transparent 30px,
          rgba(255, 255, 255, 0.05) 30px,
          rgba(255, 255, 255, 0.05) 31px);

      color: #fff;
      font-family: 'Instrument Sans', sans-serif;
      min-height: 100vh;
      margin: 0;
    }

    .center {
      /* Added a semi-transparent overlay to the main container to improve text readability */
      background-color: rgba(0, 0, 0, 0.4);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 2rem 1rem;
      /* Added padding for better spacing on small screens */
    }

    .gradient-title {
      font-size: clamp(2.8rem, 1.5vw, 3rem);
      font-weight: bold;
      font-size: 12rem;
      margin: 5px;
      background: linear-gradient(to right,
          #c169e4ff 20%,
          #00affa 30%,
          #5bb2d7ff 70%,
          #e155c2ff 80%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      text-fill-color: transparent;
      background-size: 500% auto;
      animation: textShine 5s ease-in-out infinite alternate;
    }

    @keyframes textShine {
      0% {
        background-position: 0% 50%;
      }

      100% {
        background-position: 100% 50%;
      }
    }

    .gradient-title:hover {
      font-variation-settings: "wght" 100, "ital" 0;
      text-shadow: none;
    }

    .subtitle {
      font-size: 2rem;
      font-weight: 500;
      margin-top: 12px;
      margin-bottom: 18px;
      text-align: center;
    }

    .desc {
      font-size: 1.1rem;
      max-width: 540px;
      text-align: center;
      margin-bottom: 32px;
      color: #cfcfcf;
    }

    .access-btn {
      background: linear-gradient(90deg, #7F56D9, #FF6B6B);
      color: #fff;
      font-size: 1.3rem;
      font-weight: 700;
      padding: 18px 54px;
      border: none;
      border-radius: 32px;
      box-shadow: 0 0 32px 8px #7F56D980;
      cursor: pointer;
      margin-top: 36px;
      margin-bottom: 48px;
      transition: background 0.2s, transform 0.2s;
    }

    .access-btn:hover {
      background: linear-gradient(90deg, #FF6B6B, #7F56D9);
      transform: scale(1.07);
      box-shadow: 0 0 48px 12px #FF6B6B80;
    }

    .timeline {
      width: 100%;
      max-width: 700px;
      margin: 0 auto 48px auto;
      position: relative;
    }

    .timeline-step {
      display: flex;
      align-items: flex-start;
      margin-bottom: 48px;
      position: relative;
    }

    .timeline-dot {
      min-width: 44px;
      min-height: 44px;
      background: linear-gradient(135deg, #FF6B6B, #7F56D9);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
      box-shadow: 0 0 18px 4px #7F56D980;
      margin-right: 24px;
      z-index: 1;
      /* Ensure dot is above the line */
    }

    .timeline-content {
      flex: 1;
      background: rgba(255, 255, 255, 0.04);
      border-radius: 16px;
      padding: 18px 24px;
      box-shadow: 0 2px 16px rgba(127, 86, 217, 0.10);
      backdrop-filter: blur(5px);
      /* Added blur effect for content boxes */
    }

    .timeline-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 6px;
    }

    .timeline-desc {
      font-size: 1rem;
      color: #cfcfcf;
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 22px;
      top: 44px;
      width: 4px;
      height: calc(100% - 44px);
      background: linear-gradient(180deg, #7F56D9 0%, #FF6B6B 100%);
      border-radius: 2px;
      z-index: 0;
    }

    .footer {
      text-align: center;
      color: #aaa;
      font-size: 0.95rem;
      margin-top: 64px;
      margin-bottom: 16px;
    }

    @media (max-width: 600px) {
      .gradient-title {
        font-size: 2.8rem;
      }

      .subtitle {
        font-size: 1.2rem;
      }

      .timeline-step {
        flex-direction: column;
        align-items: center;
      }

      .timeline-dot {
        margin-bottom: 12px;
        margin-right: 0;
      }

      .timeline-content {
        padding: 14px 10px;
      }
    }
  </style>
</head>

<body>
  <div class="center">
    <h1 class="gradient-title">ZipLMS</h1>
    <div class="subtitle">Nền tảng quản lý học tập hiện đại</div>
    <div class="desc">
      ZipLMS giúp tổ chức giáo dục và doanh nghiệp quản lý, phân phối, kiểm tra và mở rộng hoạt động học tập trực tuyến một cách bảo mật, hiệu quả và dễ dàng.
    </div>
    <a href="/app">
      <button class="access-btn">Truy cập</button>
    </a>
    <div class="timeline">
      <div class="timeline-step">
        <div class="timeline-dot">1</div>
        <div class="timeline-content">
          <div class="timeline-title">Quản lý khóa học & bài kiểm tra</div>
          <div class="timeline-desc">Tạo, phân phối, và theo dõi tiến độ học tập dễ dàng cho mọi đối tượng.</div>
        </div>
      </div>
      <div class="timeline-step">
        <div class="timeline-dot">2</div>
        <div class="timeline-content">
          <div class="timeline-title">Phân quyền vai trò linh hoạt</div>
          <div class="timeline-desc">Quản trị viên, giáo viên, học sinh với quyền truy cập và chức năng riêng biệt.</div>
        </div>
      </div>
      <div class="timeline-step">
        <div class="timeline-dot">3</div>
        <div class="timeline-content">
          <div class="timeline-title">Giao diện quản trị Filament</div>
          <div class="timeline-desc">Tùy chỉnh, trực quan, dễ sử dụng cho quản lý dữ liệu và báo cáo.</div>
        </div>
      </div>
      <div class="timeline-step">
        <div class="timeline-dot">4</div>
        <div class="timeline-content">
          <div class="timeline-title">Kiểm thử & bảo mật</div>
          <div class="timeline-desc">Kiểm thử tự động, phân quyền, bảo vệ dữ liệu người dùng và hệ thống.</div>
        </div>
      </div>
      <div class="timeline-step">
        <div class="timeline-dot">5</div>
        <div class="timeline-content">
          <div class="timeline-title">Đa ngôn ngữ & thông báo</div>
          <div class="timeline-desc">Hỗ trợ nhiều ngôn ngữ, gửi thông báo và sự kiện cho người dùng.</div>
        </div>
      </div>
      <div class="timeline-step">
        <div class="timeline-dot">6</div>
        <div class="timeline-content">
          <div class="timeline-title">Hỗ trợ mở rộng</div>
          <div class="timeline-desc">Tích hợp các package, dễ dàng nâng cấp và mở rộng tính năng.</div>
        </div>
      </div>
    </div>
    <div class="footer">© 2025 ZipLMS. All rights reserved.</div>
  </div>
</body>

</html>