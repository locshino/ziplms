<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Progress Bar Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the appearance and behavior of the NProgress bar.
    |
    | You can customize the color, height, opacity, spinner visibility,
    | spinner size, spinner color, and timeout duration.
    |
    */

    'color' => '#fbbf24',
    'height' => '2px',
    'theme' => 'gradient', // Available themes: default, gradient, center-circle, etc.
    'ajax' => true, // Enable AJAX progress bar
    'document' => true, // Enable document loading progress bar
    'eventLag' => false, // Enable event lag detection
    'restartDelay' => 100, // Delay in milliseconds before restarting the progress bar
    'renderHook' => 'panels::head.end', // Hook to render the progress
];
