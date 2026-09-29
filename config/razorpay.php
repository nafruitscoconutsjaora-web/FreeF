<?php
/**
 * Razorpay Payment Gateway Configuration
 * Secrets are never exposed to the frontend
 */

return [
    'key_id'     => getenv('RAZORPAY_KEY_ID') ?: '',
    'key_secret' => getenv('RAZORPAY_KEY_SECRET') ?: '',
    'currency'   => 'INR',
    'webhook_secret' => getenv('RAZORPAY_WEBHOOK_SECRET') ?: '',
];
