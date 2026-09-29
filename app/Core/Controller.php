<?php
namespace App\Core;

abstract class Controller {
    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        $file = __DIR__ . "/../../resources/views/{$viewPath}.php";
        if (!file_exists($file)) {
            Response::abort(500, "View [{$viewPath}] not found.");
        }
        require $file;
    }

    protected function json(array $data, int $statusCode = 200): void {
        Response::json($data, $statusCode);
    }

    protected function redirect(string $url): void {
        Response::redirect($url);
    }

    protected function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    protected function admin(): ?array {
        return $_SESSION['admin'] ?? null;
    }
}
