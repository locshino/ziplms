@echo off
setlocal ENABLEDELAYEDEXPANSION

REM --- Configuration: Path to your .env file ---
set ENV_FILE=../../.env

REM --- Read variables from .env file ---
echo Reading configuration from %ENV_FILE%...
for /f "usebackq tokens=1,* delims==" %%a in ("%ENV_FILE%") do (
    set "line=%%a"
    if "!line:~0,1!" NEQ "#" (
        set "%%a=%%b"
    )
)

echo.
echo ===================================================
echo Starting ZipLMS Queue Workers...
echo.
echo  - Default Worker => Queue: %QUEUE_NAME%
echo  - Media Worker   => Queue: %QUEUE_MEDIA_NAME%
echo  - Batch Worker   => Queue: %QUEUE_BATCH_NAME%
echo ===================================================
echo.

REM --- Start Workers in separate windows ---
cd ../../
start "ZipLMS Default Worker" cmd /k "php artisan queue:work %QUEUE_CONNECTION% --queue=%QUEUE_NAME% --sleep=3 --tries=3"
start "ZipLMS Media Worker" cmd /k "php artisan queue:work %QUEUE_MEDIA_CONNECTION% --queue=%QUEUE_MEDIA_NAME% --sleep=3 --tries=3"
start "ZipLMS Batch Worker" cmd /k "php artisan queue:work %QUEUE_BATCH_CONNECTION% --queue=%QUEUE_BATCH_NAME% --sleep=3 --tries=3"

endlocal
