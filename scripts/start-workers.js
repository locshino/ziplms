import { execSync } from "child_process";
import os from "os";
import path from "path";
import { fileURLToPath } from "url";

// Lấy __dirname trong môi trường ES module
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Cấu hình cho từng hệ điều hành. Giúp quản lý các kịch bản và lệnh dễ dàng hơn.
const platformScripts = {
    win32: {
        name: "Windows",
        dir: "win",
        file: "start-workers.bat",
        options: {},
        // Không cần lệnh chuẩn bị cho Windows
        preExecute: () => {},
    },
    // Cấu hình mặc định cho các hệ điều hành khác (macOS, Linux)
    default: {
        name: "macOS/Linux",
        dir: "mac",
        file: "start-workers.sh",
        options: { shell: "/bin/bash" },
        // Lệnh cần chạy trước khi thực thi kịch bản chính (ví dụ: cấp quyền thực thi)
        preExecute: (scriptPath) => {
            execSync(`chmod +x "${scriptPath}"`, { stdio: "inherit" });
        },
    },
};

/**
 * Hàm chính để chạy kịch bản khởi động worker.
 */
function startWorkers() {
    const platformKey = os.platform();
    // Lấy cấu hình cho HĐH hiện tại, nếu không có thì dùng 'default'
    const config = platformScripts[platformKey] || platformScripts.default;

    console.log(`Platform detected: ${platformKey}`);
    console.log(`Executing ${config.name} script...`);

    const scriptDir = path.join(__dirname, config.dir);
    const scriptPath = path.join(scriptDir, config.file);
    const execOptions = { cwd: scriptDir, stdio: "inherit", ...config.options };

    try {
        // Chạy các lệnh chuẩn bị nếu có (ví dụ: chmod cho script shell)
        config.preExecute(scriptPath);

        // Thực thi kịch bản chính
        execSync(config.file, execOptions);

        console.log("Worker start commands dispatched successfully.");
    } catch (error) {
        console.error("Failed to start workers:", error.message);
        process.exit(1);
    }
}

// Bắt đầu thực thi
startWorkers();
