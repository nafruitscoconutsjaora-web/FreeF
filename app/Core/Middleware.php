<?php
namespace App\Core;

interface MiddlewareInterface {
    public function handle(Request $request): void;
}

class AuthMiddleware implements MiddlewareInterface {
    public function handle(Request $request): void {
        if (empty($_SESSION['user_id'])) {
            if ($request->method() === 'POST' || str_starts_with($request->uri(), '/api/')) {
                Response::json(['success' => false, 'message' => 'Authentication required.'], 401);
            }
            Response::redirect('/login?redirect=' . urlencode($request->uri()));
        }
    }
}

class AdminMiddleware implements MiddlewareInterface {
    public function handle(Request $request): void {
        if (empty($_SESSION['admin_id'])) {
            if ($request->method() === 'POST' || str_starts_with($request->uri(), '/api/')) {
                Response::json(['success' => false, 'message' => 'Admin authorization required.'], 403);
            }
            Response::redirect('/admin/login');
        }
    }
}

class CsrfMiddleware implements MiddlewareInterface {
    public function handle(Request $request): void {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            // Skip webhook routes if razorpay handles signature
            if (str_contains($request->uri(), '/webhook')) {
                return;
            }
            if (!$request->validateCsrf()) {
                Response::json(['success' => false, 'message' => 'Invalid or expired CSRF token.'], 419);
            }
        }
    }
}
