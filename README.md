# FF Panel Store — Free Fire Digital Services Platform

A high-performance, production-ready Free Fire digital store & panel application built strictly with **Plain PHP**, **Tailwind CSS v4**, and **MySQL/MariaDB**.

---

## 🚀 Key Features

- **Reference-Accurate Design**: Dark obsidian theme (`#080B11`), Free Fire pink/red accents (`#E11D48`), rounded glass cards, glowing accents, category grid, quick recharge widget, and live order activity.
- **Strict Plain PHP & MySQL Architecture**: No bloated frameworks. Fast PDO-based singleton with prepared statements, transaction locking for wallet operations, and clean RESTful routing.
- **Razorpay Server-Side Verification**: Cryptographic signature verification using HMAC SHA-256. API secrets are never exposed to client-side code.
- **Dual Service Modes**:
  - **API Mode**: Automatically connects with third-party providers (e.g. SMM / top-up APIs), dispatches orders, logs raw cURL payloads, and syncs status via Cron.
  - **Manual Mode**: Queues orders for admin approval and manual fulfillment.
- **Provider Import Engine & Smart Pricing**:
  - Fetches external provider catalog.
  - Converts currency (`USD` to `INR`).
  - Applies configurable percentage markup + fixed markup + rounding.
  - Preserves separate provider base rate and customer retail price.
- **Transaction-Safe Digital Wallet**:
  - Database row-locking (`FOR UPDATE`) during transactions.
  - Prevents double-spending and duplicate webhook credits.
  - Complete debit/credit audit trail with opening/closing balances.
- **Automated Cron Jobs**:
  - `cron/sync_orders.php`: Every 1 minute background synchronization of external provider order statuses.

---

## 📁 Project Architecture

```
├── app/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminOrderController.php
│   │   │   ├── AdminProductController.php
│   │   │   └── AdminProviderController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── HomeController.php
│   │   ├── OrderController.php
│   │   ├── PaymentController.php
│   │   ├── ProductController.php
│   │   ├── ReferralController.php
│   │   ├── SupportController.php
│   │   └── WalletController.php
│   ├── Core/
│   │   ├── Database.php (PDO Singleton + Transactions)
│   │   ├── Router.php (Clean regex routing)
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Controller.php
│   │   └── Middleware.php (Auth, Admin, CSRF)
│   └── Services/
│       ├── CurrencyService.php
│       ├── OrderService.php
│       ├── ProviderApiService.php
│       ├── RazorpayService.php
│       └── WalletService.php
├── config/
│   ├── app.php
│   ├── database.php
│   └── razorpay.php
├── database/
│   ├── schema.sql (Complete 29-table MySQL schema)
│   └── seeds.sql (Default categories, products, banner, settings)
├── public/
│   └── index.php (Application front controller)
├── resources/
│   └── views/
│       ├── auth/
│       ├── layouts/
│       ├── orders/
│       ├── products/
│       ├── wallet/
│       ├── home.php
│       └── errors/
├── routes/
│   └── web.php
├── cron/
│   └── sync_orders.php
├── .env.example
├── .htaccess
└── README.md
```

---

## 🛠️ Production Server Deployment (cPanel, VPS, Ubuntu LAMP)

### 1. Upload Files
Upload all files to your web server (e.g. `/var/www/ff-panel-store` or `public_html`).
Point your Apache/Nginx document root to the `public/` folder, or keep `.htaccess` at the root.

### 2. Create Database & Import Schema
In phpMyAdmin or MySQL CLI:
```bash
mysql -u your_user -p your_database < database/schema.sql
mysql -u your_user -p your_database < database/seeds.sql
```

### 3. Configure Environment
Copy `.env.example` to `.env` and set your credentials:
```bash
cp .env.example .env
nano .env
```
Fill in:
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`

### 4. Create First Admin
Run this SQL to create your initial administrator (password: `admin123456`):
```sql
INSERT INTO admins (name, email, password, role, status) VALUES 
('Super Admin', 'admin@ffpanel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active');
```
*Note: Make sure to change your password immediately after first login.*

### 5. Setup Cron Job
Add to your Linux crontab (`crontab -e`):
```bash
* * * * * /usr/bin/php /var/www/ff-panel-store/cron/sync_orders.php >> /dev/null 2>&1
```

---

## 🛡️ Security Measures
- **Prepared Statements**: All SQL queries execute via parameterized PDO prepared statements.
- **Bcrypt Password Hashing**: Passwords are never stored in plain text.
- **Session Regeneration**: Session IDs regenerate upon login to mitigate fixation attacks.
- **Strict Role Separation**: Admin endpoints require `AdminMiddleware`.
- **HMAC Signatures**: Razorpay payments are strictly validated server-side.
