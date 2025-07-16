// File: scripts/start-dev.js

import concurrently from 'concurrently';
import config from './config.js'; // Import config to potentially use paths

/**
 * Main function to start all development services concurrently.
 */
async function main() {
    console.log("🚀 Starting all development services...");

    // Define all commands based on your npx command
    const commands = [
        {
            command: `${config.executables.php} artisan serve`,
            name: 'server',
            prefixColor: '#93c5fd',
        },
        {
            // Assuming 'task' is defined in package.json as 'node exec.js'
            command: 'pnpm run task start-workers',
            name: 'queue',
            prefixColor: '#c4b5fd',
        },
        {
            command: 'pnpm run dev', // Vite or other frontend build tool
            name: 'vite',
            prefixColor: '#fdba74',
        },
        {
            command: 'pnpm run task start-reverb',
            name: 'reverb',
            prefixColor: '#a7f3d0',
        }
    ];

    try {
        // Run all commands concurrently and wait for them to complete
        await concurrently(commands, {
            // Options here, e.g., restartTries: 3
        }).result;

        console.log("✅ All development services have been closed.");

    } catch (error) {
        // This catch block will be triggered if any command fails
        console.error("\n❌ A service has failed. Stopping all other services.");
        // Concurrently handles stopping other processes, so we just need to exit.
        process.exit(1);
    }
}

// Run the main function
main();
