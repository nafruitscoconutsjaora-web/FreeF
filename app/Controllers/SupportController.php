<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use Exception;

class SupportController extends Controller {
    public function index(Request $request): void {
        $userId = $_SESSION['user_id'];
        $tickets = Database::fetchAll(
            "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY id DESC",
            [$userId]
        );

        $this->view('support/index', ['tickets' => $tickets]);
    }

    public function createTicket(Request $request): void {
        $userId = $_SESSION['user_id'];
        $subject = trim((string)$request->input('subject'));
        $category = (string)$request->input('category', 'recharge_delay');
        $priority = (string)$request->input('priority', 'medium');
        $message = trim((string)$request->input('message'));
        $orderId = (int)$request->input('order_id', 0) ?: null;

        if (empty($subject) || empty($message)) {
            $this->json(['success' => false, 'message' => 'Subject and message are required.'], 422);
        }

        $ticketNumber = 'TICK-' . strtoupper(substr(uniqid(), -6));

        Database::beginTransaction();
        try {
            $ticketId = Database::insert(
                "INSERT INTO support_tickets (ticket_number, user_id, category, subject, priority, status, order_id)
                 VALUES (?, ?, ?, ?, ?, 'open', ?)",
                [$ticketNumber, $userId, $category, $subject, $priority, $orderId]
            );

            Database::insert(
                "INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
                 VALUES (?, 'user', ?, ?)",
                [$ticketId, $userId, $message]
            );

            Database::commit();

            $this->json(['success' => true, 'message' => 'Support ticket created.', 'ticket_number' => $ticketNumber]);
        } catch (Exception $e) {
            Database::rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detail(Request $request, array $params): void {
        $userId = $_SESSION['user_id'];
        $ticketId = (int)($params['id'] ?? 0);

        $ticket = Database::fetch(
            "SELECT t.*, u.name as user_name, u.email as user_email 
             FROM support_tickets t 
             JOIN users u ON t.user_id = u.id 
             WHERE t.id = ? AND t.user_id = ?",
            [$ticketId, $userId]
        );

        if (!$ticket) {
            $this->redirect('/support');
            return;
        }

        $messages = Database::fetchAll(
            "SELECT * FROM support_messages WHERE ticket_id = ? ORDER BY id ASC",
            [$ticketId]
        );

        $this->view('support/detail', [
            'ticket' => $ticket,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, array $params): void {
        $userId = $_SESSION['user_id'];
        $ticketId = (int)($params['id'] ?? 0);
        $message = trim((string)$request->input('message'));

        $ticket = Database::fetch(
            "SELECT * FROM support_tickets WHERE id = ? AND user_id = ?",
            [$ticketId, $userId]
        );

        if (!$ticket || empty($message)) {
            $this->redirect('/support/' . $ticketId);
            return;
        }

        Database::insert(
            "INSERT INTO support_messages (ticket_id, sender_type, sender_id, message) VALUES (?, 'user', ?, ?)",
            [$ticketId, $userId, $message]
        );

        Database::query("UPDATE support_tickets SET status = 'open', updated_at = NOW() WHERE id = ?", [$ticketId]);

        $this->redirect('/support/' . $ticketId);
    }
}
