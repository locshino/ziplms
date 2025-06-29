import os from "os";
import path from "path";
import { fileURLToPath } from "url";
import spawn from "cross-spawn";

// Resolve __dirname in ESM context
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// OS-specific configuration
const platformScripts = {
    win32: {
        name: "Windows",
        dir: "win",
        file: "start-workers.bat",
        options: { shell: true }, // Needed to run .bat files on Windows
        preExecute: () => Promise.resolve(), // No pre-execution needed on Windows
    },
    default: {
        name: "macOS/Linux",
        dir: "mac",
        file: "start-workers.sh",
        options: { shell: "/bin/bash" },
        preExecute: async (scriptPath) => {
            return new Promise((resolve, reject) => {
                const chmod = spawn("chmod", ["+x", scriptPath], {
                    stdio: "inherit",
                });
                chmod.on("close", (code) => {
                    code === 0 ? resolve() : reject(new Error("chmod failed"));
                });
            });
        },
    },
};

/**
 * Main function to start worker scripts.
 */
async function startWorkers() {
    const platformKey = os.platform();
    const config = platformScripts[platformKey] || platformScripts.default;

    console.log(`Platform detected: ${platformKey}`);
    console.log(`Executing ${config.name} script...`);

    const scriptDir = path.join(__dirname, config.dir);
    const scriptPath = path.join(scriptDir, config.file);
    const execOptions = { cwd: scriptDir, stdio: "inherit", ...config.options };

    try {
        // Optional pre-execution step (e.g., chmod +x)
        await config.preExecute(scriptPath);

        // Execute the worker script
        const subprocess = spawn(scriptPath, [], execOptions);

        subprocess.on("exit", (code) => {
            if (code === 0) {
                console.log(
                    "✅ Worker start commands dispatched successfully."
                );
            } else {
                console.error(`❌ Worker script exited with code ${code}.`);
                process.exit(code);
            }
        });

        subprocess.on("error", (error) => {
            console.error("❌ Failed to start workers:", error.message);
            process.exit(1);
        });
    } catch (err) {
        console.error("❌ Pre-execution failed:", err.message);
        process.exit(1);
    }
}

// Execute
startWorkers();
