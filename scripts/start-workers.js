import dotenv from "dotenv";
import { spawn } from "cross-spawn";
import config from "./config.js";

// Load environment variables from the path specified in config
dotenv.config({ path: config.paths.env });

function executeWorker(workerConf) {
    return new Promise((resolve, reject) => {
        const { name, connection, queueEnvVar } = workerConf;
        const queueName = process.env[queueEnvVar];
        const prefix = `[${name}]`;

        if (!queueName) {
            return reject(new Error(`Queue name for '${name}' (${queueEnvVar}) is not defined in .env`));
        }
        console.log(`${prefix} Starting worker for queue: ${queueName}`);
        const args = [
            "artisan", "queue:work",
            connection || process.env.QUEUE_CONNECTION,
            `--queue=${queueName}`, "--sleep=3", "--tries=3",
        ];
        const workerProcess = spawn(config.executables.php, args, { cwd: config.paths.root });

        workerProcess.stdout?.on("data", (data) => console.log(prefix, data.toString().trim()));
        workerProcess.stderr?.on("data", (data) => console.error(prefix, data.toString().trim()));
        workerProcess.on("error", (err) => reject(new Error(`Failed to start worker '${name}': ${err.message}`)));
        workerProcess.on("exit", (code) => code === 0 ? resolve() : reject(new Error(`Worker '${name}' exited with code ${code}.`)));
    });
}

async function main() {
    console.log("===================================================");
    console.log("Starting ZipLMS Queue Workers...");
    console.log("===================================================");
    try {
        // Use the workers array from the config file
        const workerPromises = config.workers.map(executeWorker);
        await Promise.all(workerPromises);
        console.log("✅ All workers have finished their tasks.");
    } catch (error) {
        console.error("\n❌ A worker has failed:", error.message);
        process.exit(1);
    }
}

main();
