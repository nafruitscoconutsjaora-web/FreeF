<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use Exception;

class AuthController extends Controller {
    public function showLogin(Request $request): void {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in'])) {
            $this->redirect('/dashboard');
        }
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        $this->view('auth/login', ['error' => $error]);
    }

    public function login(Request $request): void {
        $loginInput = trim((string)$request->input('email', $request->input('username', '')));
        $password = (string)$request->input('password', '');
        $isAjax = $request->input('ajax') || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');

        if (empty($loginInput) || empty($password)) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Email and password are required.'], 422);
            }
            $_SESSION['auth_error'] = 'Email and password are required.';
            $this->redirect('/login');
        }

        // Support lookup by email or phone
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1",
            [$loginInput, $loginInput]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
            }
            $_SESSION['auth_error'] = 'Invalid email or password.';
            $this->redirect('/login');
        }

        if ($user['status'] !== 'active') {
            $msg = 'Your account has been suspended or banned. Please contact support.';
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $msg], 403);
            }
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/login');
        }

        // Regenerate session to prevent fixation
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'] ?? '',
            'referral_code' => $user['referral_code'],
            'ff_uid' => $user['ff_uid'],
        ];

        Database::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        if ($isAjax) {
            $this->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => '/dashboard'
            ]);
        }

        $this->redirect('/dashboard');
    }

    public function showRegister(Request $request): void {
        if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_logged_in'])) {
            $this->redirect('/dashboard');
        }
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        $this->view('auth/register', ['error' => $error]);
    }

    public function register(Request $request): void {
        $name = trim((string)$request->input('name', ''));
        $email = trim((string)$request->input('email', ''));
        $phone = trim((string)$request->input('phone', ''));
        $password = (string)$request->input('password', '');
        $ffUid = trim((string)$request->input('ff_uid', ''));
        $referral = trim((string)$request->input('referral_code', ''));
        $isAjax = $request->input('ajax') || !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');

        if (strlen($name) < 2) {
            $msg = 'Please enter your full name (minimum 2 characters).';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 422);
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'Please enter a valid email address.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 422);
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/register');
        }

        if (strlen($password) < 6) {
            $msg = 'Password must be at least 6 characters long.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 422);
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/register');
        }

        $existing = Database::fetch("SELECT id FROM users WHERE email = ? LIMIT 1", [$email]);
        if ($existing) {
            $msg = 'An account with this email address already exists. Please sign in instead.';
            if ($isAjax) $this->json(['success' => false, 'message' => $msg], 409);
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/register');
        }

        $referredBy = null;
        if (!empty($referral)) {
            $refUser = Database::fetch("SELECT id FROM users WHERE referral_code = ? LIMIT 1", [$referral]);
            if ($refUser) {
                $referredBy = $refUser['id'];
            }
        }

        $myReferralCode = strtoupper(substr(md5(uniqid($email, true)), 0, 8));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        Database::beginTransaction();
        try {
            $userId = Database::insert(
                "INSERT INTO users (name, email, phone, password, ff_uid, referral_code, referred_by, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'active')",
                [$name, $email, $phone, $hashedPassword, $ffUid, $myReferralCode, $referredBy]
            );

            // Initialize user wallet with 0 balance
            Database::insert(
                "INSERT INTO wallets (user_id, balance, total_credited, total_spent) VALUES (?, 0, 0, 0)",
                [$userId]
            );

            // Record referral if applicable
            if ($referredBy) {
                Database::insert(
                    "INSERT INTO referrals (referrer_id, referred_user_id, status) VALUES (?, ?, 'active')",
                    [$referredBy, $userId]
                );
            }

            Database::commit();

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$userId;
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user'] = [
                'id' => (int)$userId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'referral_code' => $myReferralCode,
                'ff_uid' => $ffUid,
            ];

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'message' => 'Account registered successfully',
                    'redirect' => '/dashboard'
                ]);
            }

            $this->redirect('/dashboard');
        } catch (Exception $e) {
            Database::rollBack();
            $msg = 'Registration error: ' . $e->getMessage();
            if ($isAjax) {
                $this->json(['success' => false, 'message' => $msg], 500);
            }
            $_SESSION['auth_error'] = $msg;
            $this->redirect('/register');
        }
    }

    public function logout(Request $request): void {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_logged_in']);
        unset($_SESSION['user']);
        session_regenerate_id(true);
        $this->redirect('/login');
    }
}
