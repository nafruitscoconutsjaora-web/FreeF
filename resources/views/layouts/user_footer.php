<?php
/**
 * User Panel Footer
 */
?>
<footer class="mt-auto border-t border-gray-800/80 bg-[#0B0E14] py-6 text-xs text-gray-500">
    <div class="max-w-[1580px] mx-auto px-4 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <span class="text-white font-semibold text-xs">FF Panel Store</span>
            <span>•</span>
            <a href="/services" class="hover:text-gray-300">Services</a>
            <a href="/orders" class="hover:text-gray-300">Orders</a>
            <a href="/wallet" class="hover:text-gray-300">Wallet</a>
            <a href="/support" class="hover:text-gray-300">Support</a>
        </div>
        <p class="text-[11px] text-gray-600">© <?= date('Y') ?> FF Panel Store. Logged in as <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></p>
    </div>
</footer>
</body>
</html>
