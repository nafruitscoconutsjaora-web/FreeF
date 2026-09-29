<?php
/**
 * Global helper functions for FF Panel Store
 */

if (!function_exists('asset')) {
    function asset(string $path): string {
        $cleanPath = '/' . ltrim($path, '/');
        return $cleanPath;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return $_SESSION['csrf_token'] ?? '';
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}

if (!function_exists('auth_check')) {
    function auth_check(): bool {
        return !empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in']);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('admin_check')) {
    function admin_check(): bool {
        return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_logged_in']);
    }
}

if (!function_exists('admin_user')) {
    function admin_user(): ?array {
        return $_SESSION['admin'] ?? null;
    }
}
