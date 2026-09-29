<?php
$pageTitle = "Contact Support - FF Panel Store";
$activeNav = 'contact';
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_header.php';
} else {
    require __DIR__ . '/../layouts/public_header.php';
}
?>

<div class="max-w-4xl mx-auto px-4 py-12 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-black text-white">Get in Touch With Us</h1>
        <p class="text-gray-400 text-sm">Need help with an order, UID recharge, or partnership inquiry? We are here 24/7.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        <!-- Contact Details -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-6">
            <h3 class="font-bold text-white text-base">Direct Channels</h3>
            
            <div class="space-y-4 text-xs">
                <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#111723] border border-gray-800">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[11px]">WhatsApp Hotline</span>
                        <span class="text-white font-bold text-sm">+91 98765 43210</span>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#111723] border border-gray-800">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center text-lg">
                        <i class="fa-brands fa-telegram"></i>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[11px]">Telegram Updates & Alerts</span>
                        <span class="text-white font-bold text-sm">@FFPanelStore_Official</span>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 p-3 rounded-xl bg-[#111723] border border-gray-800">
                    <div class="w-10 h-10 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[11px]">Support Email</span>
                        <span class="text-white font-bold text-sm">support@ffpanelstore.com</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-4 text-xs text-gray-500">
                Average reply time is under 15 minutes during standard operational hours (9 AM - 11 PM IST).
            </div>
        </div>

        <!-- Contact Message Form -->
        <div class="bg-[#0B0E14] border border-gray-800 rounded-3xl p-6 space-y-4">
            <h3 class="font-bold text-white text-base">Send Us a Direct Message</h3>
            
            <form id="contactForm" class="space-y-3.5">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Your Name *</label>
                    <input type="text" id="contactName" required placeholder="Aaris Ali" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Email Address *</label>
                    <input type="email" id="contactEmail" required placeholder="name@example.com" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Free Fire UID (Optional)</label>
                    <input type="text" id="contactUid" placeholder="5482910482" 
                           class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 font-mono transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Message *</label>
                    <textarea id="contactMessage" rows="3" required placeholder="Describe your query or order ID..." 
                              class="w-full bg-[#111723] border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-rose-500 transition"></textarea>
                </div>

                <div id="contactNotice" class="hidden text-xs rounded-xl p-3"></div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-white font-bold text-xs transition shadow-md shadow-rose-600/30">
                    Send Message <i class="fa-solid fa-paper-plane ml-1"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const notice = document.getElementById('contactNotice');
    notice.className = 'text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 block';
    notice.textContent = "Thank you! Your message has been received. Our support team will reply within 30 minutes.";
    document.getElementById('contactForm').reset();
});
</script>

<?php 
if (!empty($_SESSION['user_id'])) {
    require __DIR__ . '/../layouts/user_footer.php';
} else {
    require __DIR__ . '/../layouts/public_footer.php';
}
?>
