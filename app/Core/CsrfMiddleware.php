<?php
namespace App\Core;

class CsrfMiddleware implements MiddlewareInterface {
    public function handle(Request $request): void {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            if (str_contains($request->uri(), '/webhook')) {
                return;
            }
            if (!$request->validateCsrf()) {
                Response::json(['success' => false, 'message' => 'Invalid or expired CSRF token.'], 419);
            }
        }
    }
}
