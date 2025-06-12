<?php

use Illuminate\Support\Facades\Route;

Route::get('/opcache-gui', function () {

    // Check if the application is in local environment or debug mode
    if (config('app.env') !== 'local' && config('app.debug') !== true) {
        abort(403, 'OPcache GUI is only available in local environment.');
    }

    // Check if the Zend OPcache extension is loaded and enabled
    if (! extension_loaded('Zend OPcache')) {
        exit('The Zend OPcache extension does not appear to be installed');
    }

    $ocEnabled = ini_get('opcache.enable');
    if (empty($ocEnabled)) {
        exit('The Zend OPcache extension is installed but not active');
    }

    // Set headers to prevent caching
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');

    // Include the Amnuts\Opcache\Service class
    $options = [
        'allow_filelist' => true,                // show/hide the files tab
        'allow_invalidate' => true,                // give a link to invalidate files
        'allow_reset' => true,                // give option to reset the whole cache
        'allow_realtime' => true,                // give option to enable/disable real-time updates
        'refresh_time' => 5,                   // how often the data will refresh, in seconds
        'size_precision' => 2,                   // Digits after decimal point
        'size_space' => false,               // have '1MB' or '1 MB' when showing sizes
        'charts' => true,                // show gauge chart or just big numbers
        'debounce_rate' => 250,                 // milliseconds after key press to send keyup event when filtering
        'per_page' => 200,                 // How many results per page to show in the file list, false for no pagination
        'cookie_name' => 'opcachegui',        // name of cookie
        'cookie_ttl' => 365,                 // days to store cookie
        'datetime_format' => 'D, d M Y H:i:s O',  // Show datetime in this format
        'highlight' => [
            'memory' => true,                      // show the memory chart/big number
            'hits' => true,                      // show the hit rate chart/big number
            'keys' => true,                      // show the keys used chart/big number
            'jit' => true,                       // show the jit buffer chart/big number
        ],
        // json structure of all text strings used, or null for default
        'language_pack' => null,
    ];

    $opcache = (new Amnuts\Opcache\Service($options))->handle();

    return view('opcache-gui', [
        'opcache' => $opcache,
        'options' => $options,
    ]);
});
