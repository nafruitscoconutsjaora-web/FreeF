<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminSupportController extends Controller {
    public function index(Request $request): void {
        $status = $request->input('status');
        $query = "SELECT t.*, u.name as user_name, u.email as user_email 
                  FROM support_tickets t 
                  JOIN users u ON t.user_id = u.id 
                  WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $query .= " AND t.status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY t.id DESC LIMIT 100";
        $tickets = Database::fetchAll($query, $params);

        $this->view('admin/support/index', [
            'tickets' => $tickets,
            'activeStatus' => $status
        ]);
    }

    public function detail(Request $request, array $params): void {
        $ticketId = (int)($params['id'] ?? 0);
        $ticket = Database::fetch(
            "SELECT t.*, u.name as user_name, u.email as user_email 
             FROM support_tickets t 
             JOIN users u ON t.user_id = u.id 
             WHERE t.id = ?",
            [$ticketId]
        );

        if (!$ticket) {
            $this->redirect('/admin/support');
        }

        $messages = Database::fetchAll(
            "SELECT * FROM support_messages WHERE ticket_id = ? ORDER BY id ASC",
            [$ticketId]
        );

        $this->view('admin/support/detail', [
            'ticket' => $ticket,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, array $params): void {
        $ticketId = (int)($params['id'] ?? 0);
        $message = trim((string)$request->input('message'));
        $adminId = $_SESSION['admin_id'] ?? 1;

        if (empty($message)) {
            $this->redirect('/admin/support/' . $ticketId);
            return;
        }

        Database::insert(
            "INSERT INTO support_messages (ticket_id, sender_type, sender_id, message)
             VALUES (?, 'admin', ?, ?)",
            [$ticketId, $adminId, $message]
        );

        Database::query("UPDATE support_tickets SET status = 'answered', updated_at = NOW() WHERE id = ?", [$ticketId]);

        $this->redirect('/admin/support/' . $ticketId);
    }

    public function updateStatus(Request $request, array $params): void {
        $ticketId = (int)($params['id'] ?? 0);
        $status = (string)$request->input('status');

        Database::query("UPDATE support_tickets SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $ticketId]);
        $this->json(['success' => true, 'message' => 'Ticket status updated to ' . $status]);
    }
}
