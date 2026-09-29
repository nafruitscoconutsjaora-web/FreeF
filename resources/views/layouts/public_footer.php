<?php
/**
 * Public Visitor Footer Layout
 */
?>
<footer class="mt-auto border-t border-gray-800/80 bg-[#0B0E14] py-10 text-xs text-gray-500">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center font-black text-white text-base">
                        FF
                    </div>
                    <span class="text-base font-black text-white">FF Panel Store</span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Premium Free Fire digital voucher, diamonds, and game services top-up platform. 100% automated and secure with instant delivery.
                </p>
                <div class="flex items-center gap-3 text-gray-400 pt-1">
                    <span class="w-8 h-8 rounded-lg bg-[#111723] flex items-center justify-center hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></span>
                    <span class="w-8 h-8 rounded-lg bg-[#111723] flex items-center justify-center hover:text-white transition"><i class="fa-brands fa-telegram"></i></span>
                    <span class="w-8 h-8 rounded-lg bg-[#111723] flex items-center justify-center hover:text-white transition"><i class="fa-brands fa-discord"></i></span>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Quick Navigation</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-rose-400 transition">Home</a></li>
                    <li><a href="/services" class="hover:text-rose-400 transition">All Services</a></li>
                    <li><a href="/about" class="hover:text-rose-400 transition">About Company</a></li>
                    <li><a href="/contact" class="hover:text-rose-400 transition">Support & Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Legal & Policies</h4>
                <ul class="space-y-2">
                    <li><a href="/terms" class="hover:text-rose-400 transition">Terms & Conditions</a></li>
                    <li><a href="/privacy" class="hover:text-rose-400 transition">Privacy Policy</a></li>
                    <li><a href="/refund" class="hover:text-rose-400 transition">Refund & Cancellation</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white text-xs uppercase tracking-wider mb-3">Secure Payments</h4>
                <p class="text-[11px] text-gray-400 mb-2">We support Instant UPI, Razorpay Gateway, and Digital Wallet checkout.</p>
                <div class="flex items-center gap-2 text-gray-400 text-lg">
                    <i class="fa-brands fa-cc-visa"></i>
                    <i class="fa-brands fa-cc-mastercard"></i>
                    <i class="fa-solid fa-qrcode"></i>
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-sm"></i>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800/80 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-[11px] text-gray-500">
            <p>© <?= date('Y') ?> FF Panel Store. All rights reserved.</p>
            <p>Direct top-up services for Garena Free Fire player accounts. Not officially affiliated with Garena.</p>
        </div>
    </div>
</footer>
</body>
</html>
