<?php

return [
  'enable' => [
    'header' => 'Bạn chưa bật xác thực hai yếu tố.',
    'description' => 'Khi bật xác thực hai yếu tố, bạn sẽ được nhắc nhập một mã thông báo ngẫu nhiên, an toàn trong quá trình xác thực. Bạn có thể lấy mã thông báo này từ ứng dụng Google Authenticator trên điện thoại của mình.',
  ],
  'logout' => [
    'button' => 'Đăng xuất',
  ],
  'enabled' => [
    'header' => 'Bạn đã bật xác thực hai yếu tố.',
    'description' => 'Lưu trữ các mã khôi phục này trong một trình quản lý mật khẩu an toàn. Chúng có thể được sử dụng để khôi phục quyền truy cập vào tài khoản của bạn nếu thiết bị xác thực hai yếu tố của bạn bị mất.',
  ],
  'setup_confirmation' => [
    'header' => 'Hoàn tất việc bật xác thực hai yếu tố.',
    'description' => 'Khi bật xác thực hai yếu tố, bạn sẽ được nhắc nhập một mã thông báo ngẫu nhiên, an toàn trong quá trình xác thực. Bạn có thể lấy mã thông báo này từ ứng dụng Google Authenticator trên điện thoại của mình.',
    'scan_qr_code' => 'Để hoàn tất việc bật xác thực hai yếu tố, hãy quét mã QR sau bằng ứng dụng xác thực trên điện thoại của bạn hoặc nhập khóa thiết lập và cung cấp mã OTP đã tạo.',
  ],
  'base' => [
    'wrong_user' => 'Đối tượng người dùng được xác thực phải là một mô hình Filament Auth để cho phép trang hồ sơ cập nhật nó.',
    'rate_limit_exceeded' => 'Quá nhiều yêu cầu',
    'try_again' => 'Vui lòng thử lại sau :seconds giây',
  ],
  '2fa' => [
    'confirm' => 'Xác nhận',
    'cancel' => 'Hủy',
    'enable' => 'Bật',
    'disable' => 'Tắt',
    'confirm_password' => 'Xác nhận mật khẩu',
    'wrong_password' => 'Mật khẩu được cung cấp không chính xác.',
    'code' => 'Mã',
    'setup_key' => 'Khóa thiết lập: :setup_key.',
    'current_password' => 'Mật khẩu hiện tại',
    'regenerate_recovery_codes' => 'Tạo mã khôi phục mới',
  ],
  'passkey' => [
    'add' => 'Tạo Passkey',
    'name' => 'Tên',
    'added' => 'Đã thêm Passkey thành công.',
    'login' => 'Đăng nhập bằng Passkey',
    'tootip' => 'Sử dụng Face ID, vân tay hoặc mã PIN',
    'notice' => [
      'header' => 'Passkey là một phương thức đăng nhập không cần mật khẩu sử dụng xác thực sinh trắc học của thiết bị của bạn. Thay vì nhập mật khẩu, bạn phê duyệt đăng nhập trên thiết bị đáng tin cậy của mình.',
    ],
  ],
];
