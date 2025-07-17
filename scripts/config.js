import path from "path";
import { fileURLToPath } from "url";

// --- Base Paths ---
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
// Define the project root as the parent directory of the 'scripts' folder
const projectRoot = path.resolve(__dirname, "..");

// --- Main Configuration Object ---
export default {
    /** General paths used across scripts */
    paths: {
        root: projectRoot,
        scripts: path.join(projectRoot, "scripts"),
        env: path.join(projectRoot, ".env"),
    },

    /** Executable commands and paths */
    executables: {
        php: "php",
        pint: path.join(projectRoot, "vendor/bin/pint"),
    },

    /** Configuration for queue workers */
    workers: [
        {
            name: "Default",
            connection: process.env.QUEUE_CONNECTION,
            queueEnvVar: "QUEUE_NAME", // The key for the .env variable
        },
        {
            name: "Media",
            connection: process.env.QUEUE_MEDIA_CONNECTION,
            queueEnvVar: "QUEUE_MEDIA_NAME",
        },
        {
            name: "Batch",
            connection: process.env.QUEUE_BATCH_CONNECTION,
            queueEnvVar: "QUEUE_BATCH_NAME",
        },
        {
            name: "exporter",
            connection: process.env.QUEUE_EXPORTER_CONNECTION,
            queueEnvVar: "QUEUE_EXPORTER_NAME",
        },
    ],
};
