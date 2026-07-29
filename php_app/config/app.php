<?php
return [
    'name' => env('APP_NAME', 'Devi Fancy Store'),
    'url' => env('APP_URL', 'http://localhost/devi/php_app'),
    'env' => env('APP_ENV', 'development'),
    'debug' => env('APP_DEBUG', true),
    'jwt_secret' => env('JWT_SECRET', 'devi_fancy_store_jwt_secret_key_2024'),
    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'qvtvmew8'),
        'api_key' => env('CLOUDINARY_API_KEY', '734375793129537'),
        'api_secret' => env('CLOUDINARY_API_SECRET', '6p8rvDNSYC3utZpAJaAPkabh9-c'),
    ],
    'google_sheets' => [
        'client_email' => env('GOOGLE_SHEETS_CLIENT_EMAIL', ''),
        'private_key' => env('GOOGLE_SHEETS_PRIVATE_KEY', ''),
        'spreadsheet_id' => env('GOOGLE_SHEETS_SPREADSHEET_ID', ''),
    ],
];
