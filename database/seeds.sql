-- ============================================================
-- FF PANEL STORE - PRODUCTION SEEDS
-- Matches Reference UI: Categories, Products, Banner, Settings
-- ============================================================

-- 1. DEFAULT SETTINGS
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('store_name', 'FF Panel Store', 'Brand store name'),
('store_tagline', 'Fast • Safe • Reliable', 'Brand tagline'),
('store_currency', 'INR', 'Default store currency symbol/code'),
('currency_symbol', '₹', 'Currency display symbol'),
('provider_currency', 'USD', 'Default provider currency code'),
('usd_inr_exchange_rate', '85.50', 'USD to INR exchange rate'),
('default_markup_percent', '15.00', 'Default markup on imported provider services'),
('razorpay_key_id', 'rzp_test_placeholder_key', 'Razorpay API Key ID'),
('razorpay_key_secret', 'rzp_test_placeholder_secret', 'Razorpay API Key Secret (Server-side only)'),
('referral_commission_percent', '5.00', 'Percentage of order given to referrer'),
('support_whatsapp', '+919876543210', 'Official Support Contact'),
('live_orders_enabled', '1', 'Display live order activity ticker on front store'),
('maintenance_mode', '0', 'Toggle site maintenance mode');

-- 2. CATEGORIES (Matching Reference Screenshot)
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `status`) VALUES
(1, 'Diamonds', 'diamonds', 'gem', 1, 'active'),
(2, 'Membership', 'membership', 'crown', 2, 'active'),
(3, 'Elite Pass', 'elite-pass', 'ticket', 3, 'active'),
(4, 'Character', 'character', 'user-check', 4, 'active'),
(5, 'Weapon Skin', 'weapon-skin', 'crosshair', 5, 'active'),
(6, 'Bundle', 'bundle', 'box', 6, 'active'),
(7, 'Pet', 'pet', 'heart', 7, 'active'),
(8, 'ID / UID', 'id-uid', 'credit-card', 8, 'active'),
(9, 'Special Offers', 'special-offers', 'percent', 9, 'active');

-- 3. PRODUCTS (Exact items shown on the reference screenshot)
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `short_description`, `delivery_type`, `service_mode`, `badge`, `badge_variant`, `price`, `original_price`, `requires_player_uid`, `sort_order`, `is_popular`, `is_quick_recharge`, `status`) VALUES
(1, 1, '100 Diamonds', '100-diamonds', 'Free Fire Diamonds', 'instant', 'manual', 'Top Selling', 'red', 20.00, 25.00, 1, 1, 1, 1, 'active'),
(2, 1, '520 Diamonds', '520-diamonds', 'Free Fire Diamonds', 'instant', 'manual', 'Popular', 'red', 95.00, 110.00, 1, 2, 1, 1, 'active'),
(3, 1, '1060 Diamonds', '1060-diamonds', 'Free Fire Diamonds', 'instant', 'manual', 'Best Value', 'green', 180.00, 210.00, 1, 3, 1, 1, 'active'),
(4, 1, '2180 Diamonds', '2180-diamonds', 'Free Fire Diamonds', 'instant', 'manual', 'High Demand', 'red', 340.00, 400.00, 1, 4, 1, 1, 'active'),
(5, 2, 'Weekly Membership', 'weekly-membership', 'Free Fire Membership', 'instant', 'manual', 'Popular', 'red', 70.00, 90.00, 1, 5, 1, 0, 'active'),
(6, 2, 'Monthly Membership', 'monthly-membership', 'Free Fire Membership', 'instant', 'manual', 'Best Value', 'gold', 199.00, 250.00, 1, 6, 1, 0, 'active'),
(7, 3, 'Elite Pass', 'elite-pass', 'Free Fire Elite Pass', 'instant', 'manual', 'Trending', 'red', 120.00, 150.00, 1, 7, 1, 0, 'active'),
(8, 4, 'Character - Alok', 'character-alok', 'Free Fire Character', 'instant', 'manual', 'Popular', 'red', 299.00, 399.00, 1, 8, 1, 0, 'active');

-- 4. HERO BANNER (Matching reference banner)
INSERT INTO `banners` (`badge_text`, `title`, `subtitle`, `button_text`, `button_link`, `features_text`, `sort_order`, `status`) VALUES
('Best Place For Free Fire Services', 'Get Your Free Fire Services Instantly', 'Diamonds, Memberships, Elite Pass, UID, and more — all at the best prices.', 'Explore Services', '/services', 'Fast Delivery • Safe • 24/7 Support', 1, 'active');

-- 5. COUPONS
INSERT INTO `coupons` (`code`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `usage_limit`, `per_user_limit`, `status`) VALUES
('FFSAVE10', 'percentage', 10.00, 50.00, 50.00, 1000, 2, 'active'),
('WELCOME20', 'fixed', 20.00, 150.00, 20.00, 500, 1, 'active');
