<?php
namespace App\Core;

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
