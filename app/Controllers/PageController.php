<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class PageController extends Controller {
    public function about(Request $request): void {
        $this->view('pages/about', [
            'pageTitle' => 'About Us - FF Panel Store'
        ]);
    }

    public function contact(Request $request): void {
        $this->view('pages/contact', [
            'pageTitle' => 'Contact Support - FF Panel Store'
        ]);
    }

    public function terms(Request $request): void {
        $this->view('pages/terms', [
            'pageTitle' => 'Terms & Conditions - FF Panel Store'
        ]);
    }

    public function privacy(Request $request): void {
        $this->view('pages/privacy', [
            'pageTitle' => 'Privacy Policy - FF Panel Store'
        ]);
    }

    public function refund(Request $request): void {
        $this->view('pages/refund', [
            'pageTitle' => 'Refund & Cancellation Policy - FF Panel Store'
        ]);
    }

    public function maintenance(Request $request): void {
        $this->view('pages/maintenance', [
            'pageTitle' => 'System Maintenance - FF Panel Store'
        ]);
    }
}
