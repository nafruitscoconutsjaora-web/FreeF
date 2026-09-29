<?php
namespace App\Core;

interface MiddlewareInterface {
    public function handle(Request $request): void;
}

require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/AdminMiddleware.php';
require_once __DIR__ . '/CsrfMiddleware.php';
