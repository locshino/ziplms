#!/bin/bash

# --- Đọc các biến từ file .env ---
ENV_FILE="../../.env"
echo "Reading configuration from $ENV_FILE..."

# Tải từng dòng biến môi trường (bỏ qua dòng comment # và dòng trống)
export $(grep -v '^#' "$ENV_FILE" | grep -v '^\s*$' | xargs)

echo
echo "==================================================="
echo "Starting ZipLMS Queue Workers..."
echo
echo " - Default Worker -> Queue: $QUEUE_NAME"
echo " - Media Worker   -> Queue: $QUEUE_MEDIA_NAME"
echo " - Batch Worker   -> Queue: $QUEUE_BATCH_NAME"
echo "==================================================="
echo

# --- Di chuyển về thư mục Laravel root ---
cd "$(dirname "$0")/../.."

# --- Chạy các worker ở background ---
# Mở mỗi worker ở 1 terminal tab/window là tùy OS, nên ở đây chạy nền (&)
php artisan queue:work "$QUEUE_CONNECTION" --queue="$QUEUE_NAME" --sleep=3 --tries=3 &
php artisan queue:work "$QUEUE_MEDIA_CONNECTION" --queue="$QUEUE_MEDIA_NAME" --sleep=3 --tries=3 &
php artisan queue:work "$QUEUE_BATCH_CONNECTION" --queue="$QUEUE_BATCH_NAME" --sleep=3 --tries=3 &

# Optional: giữ script chạy nếu cần theo dõi logs
wait
