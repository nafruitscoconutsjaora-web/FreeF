<?php
namespace App\Core;

class AuthMiddleware implements MiddlewareInterface {
    public function handle(Request $request): void {
        if (empty($_SESSION['user_id']) || empty($_SESSION['user_logged_in'])) {
            if ($request->method() === 'POST' || str_starts_with($request->uri(), '/api/')) {
                Response::json(['success' => false, 'message' => 'Authentication required.'], 401);
            }
            Response::redirect('/login?redirect=' . urlencode($request->uri()));
        }
    }
}
