-- ============================================================
-- Kon Tum Plus Evoucher DB Performance Index Optimization
-- Target Database: MySQL 8.0 / MariaDB 10.3+
-- ============================================================

-- 1. Optimize Voucher Campaign Lookup by Status & Stock
ALTER TABLE `evoucher_campaigns` 
ADD INDEX `idx_status_qty_dates` (`status`, `available_qty`, `start_date`, `expiry_date`);

-- 2. Optimize User Voucher Queries by User Hash & Status
ALTER TABLE `user_vouchers` 
ADD INDEX `idx_user_hash_status` (`user_hash`, `status`),
ADD INDEX `idx_code_status` (`voucher_code`, `status`);

-- 3. Optimize External API Client Verification
ALTER TABLE `api_clients` 
ADD INDEX `idx_api_key_status` (`api_key`, `status`);

-- 4. Optimize Rate Limiting Checks
ALTER TABLE `api_rate_limits` 
ADD INDEX `idx_client_window` (`client_id`, `window_start`);
