<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    
    'pdf' => [
        'enabled' => true,
        'binary'  => '"' . env('WKHTMLTOPDF_BINARY', base_path('vendor/silvertipsoftware/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')) . '"',
        'timeout' => false,
        'options' => [
            'dpi' => 300,
            'zoom' => 1.2,
        ],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => '"' . env('WKHTMLTOIMAGE_BINARY', base_path('vendor/silvertipsoftware/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64')) . '"',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
