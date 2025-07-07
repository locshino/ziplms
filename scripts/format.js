import { spawn } from "cross-spawn";
import config from "./config.js";

function executePint() {
    return new Promise((resolve, reject) => {
        // Use executables from the config file
        const pintProcess = spawn(config.executables.php, [config.executables.pint], {
            stdio: "inherit",
        });
        pintProcess.on("error", (error) => reject(new Error(`Failed to run Pint: ${error.message}`)));
        pintProcess.on("exit", (code) => {
            code === 0 ? resolve() : reject(new Error(`Pint exited with code ${code}.`));
        });
    });
}

async function main() {
    console.log("🚀 Running Laravel Pint to format code...");
    try {
        await executePint();
        console.log("✅ Pint process finished successfully.");
    } catch (error) {
        console.error("❌ An error occurred during formatting:", error.message);
        process.exit(1);
    }
}

main();
