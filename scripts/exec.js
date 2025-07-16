import { spawn } from "cross-spawn";
import fs from "fs";
import path from "path";
import config from "./config.js";

function executeScript(scriptName) {
    return new Promise((resolve, reject) => {
        // Use the scripts path from the config file
        const scriptPath = path.join(config.paths.scripts, `${scriptName}.js`);

        if (!fs.existsSync(scriptPath)) {
            return reject(new Error(`Script not found at: ${scriptPath}`));
        }
        console.log(`🚀 Executing: ${scriptName}.js`);
        const subprocess = spawn("node", [scriptPath], { stdio: "inherit" });
        subprocess.on("error", (err) => reject(err));
        subprocess.on("exit", (code) => {
            code === 0 ? resolve() : reject(new Error(`Script '${scriptName}' exited with code ${code}.`));
        });
    });
}

async function main() {
    const scriptName = process.argv[2];
    if (!scriptName) {
        console.error("❌ Error: Please provide a script name. Usage: pnpm run task <script-name>");
        process.exit(1);
    }
    try {
        await executeScript(scriptName);
        console.log(`✅ Script '${scriptName}' finished successfully.`);
    } catch (error) {
        console.error("❌ An error occurred:", error.message);
        process.exit(1);
    }
}

main();
