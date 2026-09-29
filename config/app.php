<?php
/**
 * Application Configuration
 */

return [
    'name'        => getenv('APP_NAME') ?: 'FF Panel Store',
    'env'         => getenv('APP_ENV') ?: 'production',
    'url'         => getenv('APP_URL') ?: 'http://localhost:3000',
    'key'         => getenv('APP_KEY') ?: 'ff_secret_key_change_in_production',
    'timezone'    => 'Asia/Kolkata',
    'currency'    => 'INR',
    'symbol'      => '₹',
    'session_name'=> 'ff_store_session',
    'session_lifetime' => 86400 * 7, // 7 days
];
