import dotenv from "dotenv";
import { spawn } from "cross-spawn";
import config from "./config.js";

// Load environment variables from the path specified in config
dotenv.config({ path: config.paths.env });

/**
 * Executes the 'php artisan reverb:start' command.
 * The --debug flag is added if APP_DEBUG is true in the .env file.
 * @returns {Promise<void>}
 */
function executeReverb() {
    return new Promise((resolve, reject) => {
        // Base command arguments
        const args = ["artisan", "reverb:start"];

        // Conditionally add the --debug flag
        const isDebug = process.env.APP_DEBUG === 'true';
        if (isDebug) {
            args.push("--debug");
        }

        const commandString = `${config.executables.php} ${args.join(" ")}`;
        console.log(`🚀 Executing: ${commandString}`);

        // Spawn the Reverb process
        const reverbProcess = spawn(config.executables.php, args, {
            cwd: config.paths.root,
            stdio: "inherit",
        });

        reverbProcess.on("error", (error) => {
            reject(new Error(`Failed to start Reverb: ${error.message}`));
        });

        reverbProcess.on("exit", (code) => {
            code === 0 ? resolve() : reject(new Error(`Reverb server exited with code ${code}.`));
        });
    });
}

/**
 * Main entry point of the script.
 */
async function main() {
    try {
        await executeReverb();
        console.log("✅ Reverb server has stopped.");
    } catch (error) {
        console.error("❌ An error occurred with the Reverb server:", error.message);
        process.exit(1);
    }
}

// Run the main function
main();
