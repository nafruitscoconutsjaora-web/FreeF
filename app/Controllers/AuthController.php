<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use Exception;

class AuthController extends Controller {
    public function showLogin(Request $request): void {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/login');
    }

    public function login(Request $request): void {
        $email = trim((string)$request->input('email'));
        $password = (string)$request->input('password');

        if (empty($email) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Email and password are required.'], 422);
        }

        $user = Database::fetch("SELECT * FROM users WHERE email = ?", [$email]);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
        }

        if ($user['status'] !== 'active') {
            $this->json(['success' => false, 'message' => 'Your account is suspended or banned.'], 403);
        }

        // Regenerate session to prevent session fixation
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'referral_code' => $user['referral_code'],
            'ff_uid' => $user['ff_uid'],
        ];

        Database::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        $this->json(['success' => true, 'message' => 'Login successful', 'redirect' => '/']);
    }

    public function showRegister(Request $request): void {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/register');
    }

    public function register(Request $request): void {
        $name = trim((string)$request->input('name'));
        $email = trim((string)$request->input('email'));
        $phone = trim((string)$request->input('phone'));
        $password = (string)$request->input('password');
        $ffUid = trim((string)$request->input('ff_uid'));
        $referral = trim((string)$request->input('referral_code'));

        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $this->json(['success' => false, 'message' => 'Please provide valid information. Password must be at least 6 characters.'], 422);
        }

        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            $this->json(['success' => false, 'message' => 'An account with this email already exists.'], 409);
        }

        $referredBy = null;
        if (!empty($referral)) {
            $refUser = Database::fetch("SELECT id FROM users WHERE referral_code = ?", [$referral]);
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

            // Initialize wallet
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
            $_SESSION['user_id'] = $userId;
            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'referral_code' => $myReferralCode,
                'ff_uid' => $ffUid,
            ];

            $this->json(['success' => true, 'message' => 'Account registered successfully', 'redirect' => '/']);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => 'Registration error: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request): void {
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        session_destroy();
        $this->redirect('/login');
    }
}
