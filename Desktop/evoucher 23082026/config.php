<?php
// ============================================================
// config.php  –  Application bootstrap
// ============================================================
// Credentials are loaded from the .env file (never hard-coded).
// ============================================================

// ---------- Load Composer Autoloader ----------
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// ---------- Load .env file manually (no library needed) ----------
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// ---------- Database constants (read from env) ----------
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: '');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');

// ---------- App constants ----------
define('APP_ENV',         getenv('APP_ENV')         ?: 'production');
define('UPLOAD_MAX_SIZE', (int)(getenv('UPLOAD_MAX_SIZE') ?: 2 * 1024 * 1024)); // 2 MB default
define('UPLOAD_DIR',      __DIR__ . '/uploads/');
define('ALLOWED_MIME',    ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// ---------- Error display (hide errors in production) ----------
if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/error_log');
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// ---------- Database connection ----------
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false); // true prepared statements
} catch (PDOException $e) {
    // Do NOT expose connection details in production
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(503);
    die(APP_ENV === 'production'
        ? 'Dịch vụ tạm thời không khả dụng.'
        : 'Connection failed: ' . $e->getMessage()
    );
}

// ---------- Schema bootstrap (run only once via migrations table) ----------
_runMigrations($pdo);

// ---------- Default admin account ----------
_ensureDefaultAdmin($pdo);

// ============================================================
// Helper functions
// ============================================================

/**
 * Generate a cryptographically-secure random voucher code.
 */
function generateRandomCode(int $length = 5): string
{
    $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max    = strlen($chars) - 1;
    $code   = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, $max)]; // OK: Secure random, replaces rand()
    }
    return $code;
}

/**
 * Generate a unique voucher code for a given campaign.
 */
function generateUniqueCode(PDO $pdo, int $campaignId): string
{
    do {
        $code = generateRandomCode();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM vouchers WHERE campaign_id = ? AND code = ?");
        $stmt->execute([$campaignId, $code]);
    } while ($stmt->fetchColumn() > 0);

    return $code;
}

/**
 * Validate and move an uploaded logo file.
 * Throws RuntimeException on failure.
 *
 * @param  array  $file      A single element from $_FILES (e.g. $_FILES['logo'])
 * @param  string $uploadDir Absolute path to the target directory
 * @return string            Saved filename (not the full path)
 */
function uploadLogo(array $file, string $uploadDir): string
{
    // 1. Basic upload error check
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Lỗi upload file (mã ' . $file['error'] . ').');
    }

    // 2. File size limit
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        $maxMB = UPLOAD_MAX_SIZE / 1024 / 1024;
        throw new RuntimeException("Kích thước file không được vượt quá {$maxMB} MB.");
    }

    // 3. Real MIME type check (not just extension)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, ALLOWED_MIME, true)) {
        throw new RuntimeException('Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP).');
    }

    // 4. Map MIME to safe extension
    $mimeToExt = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    $ext = $mimeToExt[$mimeType];

    // 5. Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 6. Generate unique filename and move
    $fileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $destPath = rtrim($uploadDir, '/') . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Không thể lưu file upload.');
    }

    return $fileName;
}

/**
 * Save a base64-encoded image (from clipboard paste) to the upload directory.
 * Expects the full data-URI: "data:image/png;base64,<data>"
 */
function uploadLogoBase64(string $dataUri, string $uploadDir): string
{
    if (!preg_match('/^data:(image\/[a-z]+);base64,(.+)$/s', $dataUri, $m)) {
        throw new RuntimeException('Dữ liệu ảnh không hợp lệ.');
    }

    $mimeType = $m[1];
    if (!in_array($mimeType, ALLOWED_MIME, true)) {
        throw new RuntimeException('Chỉ chấp nhận ảnh JPEG, PNG, GIF, WebP.');
    }

    $imageData = base64_decode($m[2], true);
    if ($imageData === false || strlen($imageData) === 0) {
        throw new RuntimeException('Không thể giải mã dữ liệu ảnh.');
    }

    if (strlen($imageData) > UPLOAD_MAX_SIZE) {
        $maxMB = UPLOAD_MAX_SIZE / 1024 / 1024;
        throw new RuntimeException("Kích thước ảnh không được vượt quá {$maxMB} MB.");
    }

    // Verify it is actually an image via finfo on raw bytes
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $detected = $finfo->buffer($imageData);
    if (!in_array($detected, ALLOWED_MIME, true)) {
        throw new RuntimeException('Nội dung ảnh không hợp lệ.');
    }

    $mimeToExt = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $ext = $mimeToExt[$detected];

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $destPath = rtrim($uploadDir, '/') . '/' . $fileName;

    if (file_put_contents($destPath, $imageData) === false) {
        throw new RuntimeException('Không thể lưu ảnh từ clipboard.');
    }

    return $fileName;
}

// ============================================================
// Internal bootstrap helpers (private to this file)
// ============================================================

function _runMigrations(PDO $pdo): void
{
    // Create migrations tracker table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            migration  VARCHAR(100) UNIQUE NOT NULL,
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // List of ALL migrations keyed by a unique name
    $migrations = [

        '001_create_core_tables' => "
            CREATE TABLE IF NOT EXISTS users (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                username   VARCHAR(50) UNIQUE NOT NULL,
                password   VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS campaigns (
                id            INT AUTO_INCREMENT PRIMARY KEY,
                sponsor_name  VARCHAR(100) NOT NULL,
                sponsor_short VARCHAR(20)  NOT NULL,
                description   VARCHAR(200) DEFAULT 'TẶNG 1 LY NƯỚC',
                logo          VARCHAR(255),
                start_date    DATE NOT NULL,
                end_date      DATE NOT NULL,
                guide_content TEXT,
                menu_content  TEXT,
                created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS vouchers (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT NOT NULL,
                code        VARCHAR(5) NOT NULL,
                status      ENUM('unused','used','expired') DEFAULT 'unused',
                used_at     TIMESTAMP NULL,
                created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
                UNIQUE KEY unique_campaign_code (campaign_id, code)
            );

            CREATE TABLE IF NOT EXISTS taken_vouchers (
                id          INT AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT NOT NULL,
                quantity    INT NOT NULL,
                taken_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS given_vouchers (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                voucher_id INT NOT NULL,
                given_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS free_vouchers (
                id            INT AUTO_INCREMENT PRIMARY KEY,
                code          VARCHAR(50) NOT NULL,
                sponsor_name  VARCHAR(100) DEFAULT NULL,
                description   VARCHAR(200) DEFAULT 'TẶNG 1 LY NƯỚC',
                logo          VARCHAR(255),
                start_date    DATE NULL,
                end_date      DATE NULL,
                guide_content TEXT,
                menu_content  TEXT,
                status        ENUM('unused','used','expired') DEFAULT 'unused',
                used_at       TIMESTAMP NULL,
                created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_free_code (code)
            );
        ",

        '002_add_indexes' => "
            ALTER TABLE vouchers
                ADD INDEX IF NOT EXISTS idx_status (status),
                ADD INDEX IF NOT EXISTS idx_campaign_status (campaign_id, status);

            ALTER TABLE free_vouchers
                ADD INDEX IF NOT EXISTS idx_fv_status (status),
                ADD INDEX IF NOT EXISTS idx_fv_code (code);
        ",

        '003_create_audit_logs' => "
            CREATE TABLE IF NOT EXISTS audit_logs (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                user_id    INT NULL,
                action     VARCHAR(50) NOT NULL,
                entity     VARCHAR(50) NOT NULL,
                entity_id  INT NULL,
                details    TEXT NULL,
                ip_address VARCHAR(45) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_audit_action (action),
                INDEX idx_audit_entity (entity, entity_id),
                INDEX idx_audit_user (user_id),
                INDEX idx_audit_created (created_at)
            );
        ",

        '004_user_sessions' => "
            CREATE TABLE IF NOT EXISTS user_sessions (
                id         VARCHAR(64) PRIMARY KEY,
                user_id    INT NOT NULL,
                device     VARCHAR(200) DEFAULT NULL,
                ip         VARCHAR(45) DEFAULT NULL,
                last_seen  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_us_user (user_id),
                INDEX idx_us_last_seen (last_seen)
            );
        ",
        '005_settings' => "
            CREATE TABLE IF NOT EXISTS settings (
                `key`      VARCHAR(100) PRIMARY KEY,
                `value`    TEXT NOT NULL DEFAULT '',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
            INSERT IGNORE INTO settings (`key`, `value`) VALUES ('give_message', 'Tang ban e-voucher, link=>');
        ",
        '006_sw_cache_version' => "
            INSERT IGNORE INTO settings (`key`, `value`) VALUES ('sw_cache_version', '3');
        ",
        '007_create_api_clients_table' => "
            CREATE TABLE IF NOT EXISTS api_clients (
                id             INT AUTO_INCREMENT PRIMARY KEY,
                client_name    VARCHAR(100) NOT NULL,
                client_id      VARCHAR(50) NOT NULL,
                api_key        VARCHAR(255) NOT NULL,
                scopes         VARCHAR(255) DEFAULT 'read,claim,report',
                status         ENUM('active','revoked') DEFAULT 'active',
                last_used_at   TIMESTAMP NULL,
                created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_client_id (client_id),
                INDEX idx_api_key (api_key)
            );
            INSERT IGNORE INTO api_clients (client_name, client_id, api_key, scopes) 
            VALUES ('Fan Cứng Kon Tum Pluss', 'fancung_ktp', 'fc_ktp_sec_kontumplus2026', 'read,claim,report');
        ",
        '008_voucher_issuance_tracking' => "
            ALTER TABLE vouchers
                ADD COLUMN IF NOT EXISTS issued_to_client_id  VARCHAR(50) NULL,
                ADD COLUMN IF NOT EXISTS issued_to_user_ref   VARCHAR(100) NULL,
                ADD COLUMN IF NOT EXISTS issued_to_user_name  VARCHAR(100) NULL,
                ADD COLUMN IF NOT EXISTS issued_via_api_at    TIMESTAMP NULL,
                ADD COLUMN IF NOT EXISTS idempotency_key      VARCHAR(200) NULL;

            ALTER TABLE vouchers
                ADD INDEX IF NOT EXISTS idx_issued_user (issued_to_user_ref),
                ADD INDEX IF NOT EXISTS idx_issued_client (issued_to_client_id),
                ADD UNIQUE INDEX IF NOT EXISTS idx_idempotency (idempotency_key);
        ",
        '009_campaign_api_visibility' => "
            ALTER TABLE campaigns
                ADD COLUMN IF NOT EXISTS api_visible TINYINT(1) NOT NULL DEFAULT 0;

            ALTER TABLE campaigns
                ADD INDEX IF NOT EXISTS idx_api_visible (api_visible);
        ",
        '010_api_clients_quota' => "
            ALTER TABLE api_clients
                ADD COLUMN IF NOT EXISTS daily_limit        INT DEFAULT 0 COMMENT '0 = unlimited',
                ADD COLUMN IF NOT EXISTS rate_limit_per_min INT DEFAULT 60,
                ADD COLUMN IF NOT EXISTS allowed_domains    TEXT NULL COMMENT 'JSON array of allowed origins',
                ADD COLUMN IF NOT EXISTS notes              VARCHAR(255) NULL;
        ",
        '011_campaigns_api_quota' => "
            ALTER TABLE campaigns
                ADD COLUMN IF NOT EXISTS api_daily_quota  INT DEFAULT 0 COMMENT '0 = unlimited',
                ADD COLUMN IF NOT EXISTS points_required  INT DEFAULT 0,
                ADD COLUMN IF NOT EXISTS max_per_user     INT DEFAULT 1;
        ",
        '012_seed_default_api_clients' => "
            INSERT IGNORE INTO api_clients (client_name, client_id, api_key, scopes, status) 
            VALUES ('Fan Cứng Kon Tum Pluss (Default Key)', 'fancung_ktp_default', 'ev_live_fancung_kontumplus_default_key', 'read,claim,report', 'active');

            INSERT IGNORE INTO api_clients (client_name, client_id, api_key, scopes, status) 
            VALUES ('Fan Cứng Kon Tum Pluss', 'fancung_ktp', 'fc_ktp_sec_kontumplus2026', 'read,claim,report', 'active');
        ",
    ];

    // Apply each migration only once
    $check = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
    $mark  = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");

    foreach ($migrations as $name => $sql) {
        $check->execute([$name]);
        if ($check->fetchColumn() > 0) {
            continue; // Already applied
        }

        try {
            // Run each statement separately (PDO doesn't support multi-statement well)
            foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
                if ($statement !== '') {
                    $pdo->exec($statement);
                }
            }
            $mark->execute([$name]);
        } catch (PDOException $e) {
            // Log but don't crash – some statements may be harmless no-ops
            error_log("Migration '$name' error: " . $e->getMessage());
        }
    }
}

function _ensureDefaultAdmin(PDO $pdo): void
{
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $check->execute(['admin']);

    if ($check->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $defaultPass = getenv('DEFAULT_ADMIN_PASS') ?: bin2hex(random_bytes(6));
        $insert->execute(['admin', password_hash($defaultPass, PASSWORD_DEFAULT)]);
        if (!getenv('DEFAULT_ADMIN_PASS')) {
            error_log("Default admin created. Password: $defaultPass");
        }
    }
}

// ---------- Load authentication helpers ----------
require_once __DIR__ . '/auth.php';
