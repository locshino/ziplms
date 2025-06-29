import path from "path";
import { spawn } from "cross-spawn";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Build command: php vendor/bin/pint
const phpBinary = "php";
const pintScript = path.join(__dirname, "..", "vendor", "bin", "pint");

const processFormat = spawn(phpBinary, [pintScript], {
    stdio: "inherit",
});

processFormat.on("error", (error) => {
    console.error("❌ Failed to run Pint:", error);
    process.exit(1);
});

processFormat.on("exit", (code) => {
    if (code === 0) {
        // Pint's output is already inherited, but we add a final confirmation.
        console.log("✅ Pint process finished successfully.");
        process.exit(0); // Explicitly exit with a success code.
    } else {
        console.error(`❌ Pint exited with code ${code}.`);
        process.exit(code); // Exit with the error code from Pint.
    }
});
