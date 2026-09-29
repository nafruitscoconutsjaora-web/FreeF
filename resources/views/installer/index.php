<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'FF Panel Store - Web Installer') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
      body { background-color: #080B11; color: #F3F4F6; font-family: system-ui, -apple-system, sans-serif; overflow-x: hidden; }
      .step-active { background: linear-gradient(135deg, #E11D48, #BE123C); color: white; border-color: #F43F5E; }
      .step-done { background-color: #10B981; color: white; border-color: #10B981; }
    </style>
</head>
<body class="bg-[#080B11] text-gray-100 min-h-screen flex flex-col antialiased">

    <!-- Header -->
    <header class="bg-[#0B0E14] border-b border-gray-800 px-4 sm:px-6 lg:px-8 py-3.5 relative">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-base shadow-md shadow-rose-600/30">
                    FF
                </div>
                <div>
                    <span class="text-base font-black text-white tracking-wide block leading-tight">FF Panel Store</span>
                    <span class="text-[10px] text-gray-400 block font-medium">Automated Web Installer</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    <i class="fa-solid fa-server"></i> PHP + MySQL Deployment
                </span>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8 space-y-8">
        
        <!-- Step Indicator -->
        <div class="bg-[#0D111A] border border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xl">
            <div class="flex items-center justify-between overflow-x-auto pb-2 sm:pb-0 gap-2 text-xs">
                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer" data-step="1">
                    <span class="step-badge w-7 h-7 rounded-full flex items-center justify-center font-bold text-[11px] step-active">1</span>
                    <span class="font-semibold text-white hidden md:inline">Requirements</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="2">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">2</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Database</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="3">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">3</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Settings</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="4">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">4</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Schema</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="5">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">5</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Admin</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="6">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">6</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Razorpay</span>
                </div>
                <div class="h-0.5 w-6 bg-gray-800 shrink-0"></div>

                <div class="step-indicator flex items-center gap-2 shrink-0 cursor-pointer opacity-50" data-step="7">
                    <span class="step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]">7</span>
                    <span class="font-semibold text-gray-400 hidden md:inline">Finish</span>
                </div>
            </div>
        </div>

        <!-- Form Wrapper -->
        <form id="installerForm" class="space-y-6">

            <!-- STEP 1: REQUIREMENTS -->
            <div id="step1" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl">
                <div>
                    <h2 class="text-lg font-black text-white">Step 1 — Server & Environment Check</h2>
                    <p class="text-xs text-gray-400 mt-1">Verifying server environment, PHP extensions, and writable folders.</p>
                </div>

                <!-- PHP Version -->
                <div class="p-4 rounded-2xl bg-[#121824] border border-gray-800 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-white block">PHP Version (>= 8.0 required)</span>
                        <span class="text-gray-400 text-[11px]">Detected: PHP <?= htmlspecialchars($requirements['phpVersion']) ?></span>
                    </div>
                    <?php if ($requirements['phpOk']): ?>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold flex items-center gap-1">
                            <i class="fa-solid fa-check"></i> Passed
                        </span>
                    <?php else: ?>
                        <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold flex items-center gap-1">
                            <i class="fa-solid fa-xmark"></i> Failed
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Extensions -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold text-gray-300 uppercase tracking-wider">PHP Extensions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <?php foreach ($requirements['extensions'] as $key => $ext): ?>
                            <div class="p-3 bg-[#121824] rounded-xl border border-gray-800 flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-gray-200 font-medium"><?= htmlspecialchars($ext['name']) ?></span>
                                    <?php if ($ext['required']): ?>
                                        <span class="text-[9px] text-rose-400 block">Required</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($ext['status']): ?>
                                    <span class="text-emerald-400 text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> Passed
                                    </span>
                                <?php else: ?>
                                    <span class="<?= $ext['required'] ? 'text-rose-400' : 'text-yellow-400' ?> text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> <?= $ext['required'] ? 'Failed' : 'Optional' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Directory Permissions -->
                <div class="space-y-2">
                    <h3 class="text-xs font-bold text-gray-300 uppercase tracking-wider">File & Directory Permissions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <?php foreach ($requirements['directories'] as $key => $dir): ?>
                            <div class="p-3 bg-[#121824] rounded-xl border border-gray-800 flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-gray-200 font-mono"><?= htmlspecialchars($dir['path']) ?></span>
                                    <span class="text-[9px] text-gray-500 block">Writable</span>
                                </div>
                                <?php if ($dir['status']): ?>
                                    <span class="text-emerald-400 text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> Passed
                                    </span>
                                <?php else: ?>
                                    <span class="text-rose-400 text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> Failed
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <span class="text-xs text-gray-400">
                        <?php if ($requirements['allPassed']): ?>
                            <i class="fa-solid fa-circle-check text-emerald-400"></i> All mandatory requirements satisfied.
                        <?php else: ?>
                            <i class="fa-solid fa-circle-exclamation text-rose-400"></i> Please resolve mandatory requirements to continue.
                        <?php endif; ?>
                    </span>
                    <button type="button" class="btn-next h-10 px-5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition" data-next="2" <?= !$requirements['allPassed'] ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>>
                        Continue to Database <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2: DATABASE CONFIGURATION -->
            <div id="step2" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl hidden">
                <div>
                    <h2 class="text-lg font-black text-white">Step 2 — Database Connection</h2>
                    <p class="text-xs text-gray-400 mt-1">Configure your MySQL / MariaDB database server credentials.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Database Host *</label>
                        <input type="text" name="db_host" id="db_host" value="127.0.0.1" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                        <span class="text-[10px] text-gray-500 mt-1 block">Usually 127.0.0.1 or localhost</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Database Port *</label>
                        <input type="text" name="db_port" id="db_port" value="3306" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Database Name *</label>
                        <input type="text" name="db_name" id="db_name" value="ff_panel_store" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Database Username *</label>
                        <input type="text" name="db_user" id="db_user" value="root" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Database Password</label>
                        <input type="password" name="db_pass" id="db_pass" placeholder="Enter password (leave blank if none)"
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <!-- Test Connection Feedback -->
                <div id="dbTestFeedback" class="hidden p-3.5 rounded-xl text-xs"></div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-prev h-10 px-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition" data-prev="1">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                        </button>
                        <button type="button" id="btnTestDb" class="h-10 px-4 rounded-xl bg-[#121824] border border-gray-700 hover:border-gray-600 text-gray-200 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-plug text-xs"></i> Test Connection
                        </button>
                    </div>

                    <button type="button" class="btn-next h-10 px-5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition" data-next="3">
                        Continue to Settings <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 3: APPLICATION SETTINGS -->
            <div id="step3" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl hidden">
                <div>
                    <h2 class="text-lg font-black text-white">Step 3 — Application Settings</h2>
                    <p class="text-xs text-gray-400 mt-1">Configure brand details, base URLs, timezones, and auto-generated encryption keys.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Website Name *</label>
                        <input type="text" name="app_name" id="app_name" value="FF Panel Store" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Website URL *</label>
                        <input type="url" name="app_url" id="app_url" value="<?= htmlspecialchars($currentUrl) ?>" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">App Environment *</label>
                        <select name="app_env" id="app_env" class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                            <option value="production" selected>Production (Recommended)</option>
                            <option value="local">Local Development</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Default Currency *</label>
                        <input type="text" name="app_currency" id="app_currency" value="INR" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Timezone *</label>
                        <input type="text" name="app_timezone" id="app_timezone" value="Asia/Kolkata" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Auto-Generated Application Key (APP_KEY) *</label>
                        <div class="flex gap-2">
                            <input type="text" name="app_key" id="app_key" value="<?= htmlspecialchars($generatedKey) ?>" readonly
                                   class="flex-1 h-10 bg-[#121824] border border-gray-800 rounded-xl px-3 text-xs text-emerald-400 font-mono">
                            <button type="button" id="btnRegenKey" class="h-10 px-3 bg-gray-800 hover:bg-gray-700 text-xs text-white font-bold rounded-xl transition" title="Regenerate Key">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <button type="button" class="btn-prev h-10 px-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition" data-prev="2">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                    </button>
                    <button type="button" class="btn-next h-10 px-5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition" data-next="4">
                        Continue to Database Setup <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 4: DATABASE INSTALLATION OVERVIEW -->
            <div id="step4" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl hidden">
                <div>
                    <h2 class="text-lg font-black text-white">Step 4 — Database Schema & Configuration</h2>
                    <p class="text-xs text-gray-400 mt-1">Review genuine production schema to be imported upon submission.</p>
                </div>

                <div class="p-4 bg-[#121824] rounded-2xl border border-gray-800/80 space-y-3 text-xs">
                    <div class="flex items-center gap-2 text-white font-bold">
                        <i class="fa-solid fa-database text-rose-500"></i> Genuine Schema Architecture (MySQL 8.0+ / MariaDB)
                    </div>
                    <p class="text-gray-300 leading-relaxed text-[11px]">
                        The installer will execute <span class="text-rose-400 font-mono">database/schema.sql</span> and genuine defaults from <span class="text-rose-400 font-mono">database/seeds.sql</span>.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-2">
                        <div class="p-2.5 bg-[#0B0E14] rounded-xl border border-gray-800/60 text-[11px]">
                            <span class="text-white font-bold block">29 Tables</span>
                            <span class="text-gray-500">Users, Orders, Wallet, Admins</span>
                        </div>
                        <div class="p-2.5 bg-[#0B0E14] rounded-xl border border-gray-800/60 text-[11px]">
                            <span class="text-white font-bold block">Foreign Keys</span>
                            <span class="text-gray-500">Relational integrity & constraints</span>
                        </div>
                        <div class="p-2.5 bg-[#0B0E14] rounded-xl border border-gray-800/60 text-[11px]">
                            <span class="text-white font-bold block">Real System Data</span>
                            <span class="text-gray-500">No fake users or fake balances</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <button type="button" class="btn-prev h-10 px-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition" data-prev="3">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                    </button>
                    <button type="button" class="btn-next h-10 px-5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition" data-next="5">
                        Continue to Admin Account <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 5: CREATE ADMINISTRATOR ACCOUNT -->
            <div id="step5" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl hidden">
                <div>
                    <h2 class="text-lg font-black text-white">Step 5 — Create Administrator Account</h2>
                    <p class="text-xs text-gray-400 mt-1">Set up your super administrator login credentials with secure bcrypt hashing.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Admin Full Name *</label>
                        <input type="text" name="admin_name" id="admin_name" value="Super Admin" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Admin Email Address *</label>
                        <input type="email" name="admin_email" id="admin_email" placeholder="admin@ffpanelstore.com" required
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Password (Min 8 Characters) *</label>
                        <input type="password" name="admin_password" id="admin_password" placeholder="••••••••••••" required minlength="8"
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Confirm Password *</label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" placeholder="••••••••••••" required minlength="8"
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <button type="button" class="btn-prev h-10 px-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition" data-prev="4">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                    </button>
                    <button type="button" class="btn-next h-10 px-5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition" data-next="6">
                        Continue to Razorpay <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 6: RAZORPAY CONFIGURATION -->
            <div id="step6" class="step-card bg-[#0D111A] border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-2xl hidden">
                <div>
                    <h2 class="text-lg font-black text-white">Step 6 — Razorpay Payment Gateway (Optional)</h2>
                    <p class="text-xs text-gray-400 mt-1">Configure your Razorpay API keys. Secrets are stored strictly on the server.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" id="razorpay_key_id" placeholder="rzp_live_xxxxxxxxxxxx or rzp_test_xxxxxxxxxxxx"
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1.5">Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" id="razorpay_key_secret" placeholder="••••••••••••••••••••••••"
                               class="w-full h-10 bg-[#121824] border border-gray-800 rounded-xl px-3.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono">
                        <span class="text-[10px] text-gray-500 mt-1 block">Can also be added or updated later in Admin Settings.</span>
                    </div>
                </div>

                <!-- Process Alert Box -->
                <div id="processAlertBox" class="hidden p-3.5 rounded-xl text-xs"></div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <button type="button" class="btn-prev h-10 px-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-bold text-xs inline-flex items-center gap-1.5 transition" data-prev="5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
                    </button>
                    <button type="submit" id="btnSubmitInstall" class="h-10 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 text-white font-bold text-xs inline-flex items-center gap-2 shadow-lg transition">
                        <i class="fa-solid fa-rocket text-xs"></i> Complete & Install Store
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
    // Tab switching and validation
    let currentStep = 1;

    function goToStep(step) {
        document.querySelectorAll('.step-card').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById('step' + step);
        if (target) {
            target.classList.remove('hidden');
            currentStep = step;

            // Update step indicators
            document.querySelectorAll('.step-indicator').forEach(ind => {
                const s = parseInt(ind.getAttribute('data-step'), 10);
                const badge = ind.querySelector('.step-badge');
                if (s < step) {
                    ind.classList.remove('opacity-50');
                    badge.className = 'step-badge w-7 h-7 rounded-full flex items-center justify-center font-bold text-[11px] step-done';
                    badge.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i>';
                } else if (s === step) {
                    ind.classList.remove('opacity-50');
                    badge.className = 'step-badge w-7 h-7 rounded-full flex items-center justify-center font-bold text-[11px] step-active';
                    badge.innerText = s;
                } else {
                    ind.classList.add('opacity-50');
                    badge.className = 'step-badge w-7 h-7 rounded-full bg-gray-800 text-gray-400 flex items-center justify-center font-bold text-[11px]';
                    badge.innerText = s;
                }
            });
        }
    }

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', () => {
            const nextStep = parseInt(btn.getAttribute('data-next'), 10);
            goToStep(nextStep);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', () => {
            const prevStep = parseInt(btn.getAttribute('data-prev'), 10);
            goToStep(prevStep);
        });
    });

    // Key regenerator
    document.getElementById('btnRegenKey')?.addEventListener('click', () => {
        const rand = Array.from(crypto.getRandomValues(new Uint8Array(24)))
            .map(b => String.fromCharCode(b)).join('');
        document.getElementById('app_key').value = 'base64:' + btoa(rand);
    });

    // Test DB Connection
    document.getElementById('btnTestDb')?.addEventListener('click', async () => {
        const btn = document.getElementById('btnTestDb');
        const feedback = document.getElementById('dbTestFeedback');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Testing...';

        const formData = new FormData();
        formData.append('db_host', document.getElementById('db_host').value);
        formData.append('db_port', document.getElementById('db_port').value);
        formData.append('db_name', document.getElementById('db_name').value);
        formData.append('db_user', document.getElementById('db_user').value);
        formData.append('db_pass', document.getElementById('db_pass').value);

        try {
            const res = await fetch('/install/test-db', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            feedback.classList.remove('hidden');

            if (data.success) {
                feedback.className = 'p-3.5 rounded-xl text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-400';
                feedback.innerHTML = '<i class="fa-solid fa-circle-check mr-1.5"></i> ' + data.message;
            } else {
                feedback.className = 'p-3.5 rounded-xl text-xs bg-rose-500/10 border border-rose-500/30 text-rose-400';
                feedback.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1.5"></i> ' + (data.message || 'Connection failed.');
            }
        } catch (err) {
            feedback.classList.remove('hidden');
            feedback.className = 'p-3.5 rounded-xl text-xs bg-rose-500/10 border border-rose-500/30 text-rose-400';
            feedback.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1.5"></i> Network error testing database.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-plug text-xs"></i> Test Connection';
        }
    });

    // Submit complete installation
    document.getElementById('installerForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = document.getElementById('btnSubmitInstall');
        const alertBox = document.getElementById('processAlertBox');

        // Validation for step 5
        const pass = document.getElementById('admin_password').value;
        const confirm = document.getElementById('admin_password_confirmation').value;
        if (pass.length < 8) {
            goToStep(5);
            alert('Admin password must be at least 8 characters long.');
            return;
        }
        if (pass !== confirm) {
            goToStep(5);
            alert('Admin passwords do not match.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Installing Database & Settings...';
        alertBox.classList.add('hidden');

        const formData = new FormData(document.getElementById('installerForm'));

        try {
            const res = await fetch('/install/process', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                alertBox.classList.remove('hidden');
                alertBox.className = 'p-4 rounded-xl text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 space-y-2';
                alertBox.innerHTML = '<div class="font-bold"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</div>'
                    + '<div class="text-gray-300">Lock file created. Redirecting to completion screen...</div>';

                setTimeout(() => {
                    window.location.href = data.redirect || '/install/finish';
                }, 1200);
            } else {
                alertBox.classList.remove('hidden');
                alertBox.className = 'p-4 rounded-xl text-xs bg-rose-500/10 border border-rose-500/30 text-rose-400';
                alertBox.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1.5"></i> ' + (data.message || 'Installation encountered an error.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-rocket text-xs"></i> Complete & Install Store';
            }
        } catch (err) {
            alertBox.classList.remove('hidden');
            alertBox.className = 'p-4 rounded-xl text-xs bg-rose-500/10 border border-rose-500/30 text-rose-400';
            alertBox.innerHTML = '<i class="fa-solid fa-circle-xmark mr-1.5"></i> Server error processing installation. Please check MySQL server.';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-rocket text-xs"></i> Complete & Install Store';
        }
    });
    </script>
</body>
</html>
