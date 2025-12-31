<?php
// Database Configuration
// Support both Docker (local) and Production (Railway/Render) environments
// Use environment variables if available (Production), otherwise use Docker defaults
// For Docker: use 'mysql' (service name) or 'localhost:3308' (if accessing from host)
// For XAMPP: use 'localhost' with port 3308 (Docker MySQL port)
$defaultHost = 'mysql'; // Docker service name (works inside container)
define('DB_HOST', getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: $defaultHost));
define('DB_NAME', getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: 'rbpf_checklist'));
define('DB_USER', getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: 'rbpf_user'));
define('DB_PASS', getenv('DB_PASS') ?: (getenv('MYSQLPASSWORD') ?: 'rbpf_password_2026'));

// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=UTF-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database Connection
function getDBConnection() {
    // Try connecting - if 'mysql' hostname fails (outside Docker), fallback to localhost:3308
    $hosts = [
        ['host' => DB_HOST, 'port' => null], // Try Docker service name first
        ['host' => 'localhost', 'port' => '3308'] // Fallback for XAMPP accessing Docker MySQL
    ];
    
    $lastError = null;
    foreach ($hosts as $config) {
        try {
            $dsn = "mysql:host={$config['host']}" . 
                   ($config['port'] ? ";port={$config['port']}" : "") . 
                   ";dbname=" . DB_NAME . ";charset=utf8mb4";
            
            $conn = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            return $conn;
        } catch(PDOException $e) {
            $lastError = $e;
            // If this was the first attempt and it's a hostname resolution error, try next host
            if (strpos($e->getMessage(), 'getaddrinfo') !== false || 
                strpos($e->getMessage(), 'No such host') !== false) {
                continue; // Try next host
            }
            // Otherwise, it's a different error (auth, etc.) - fail immediately
            break;
        }
    }
    
    // All connection attempts failed
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database connection failed: ' . ($lastError ? $lastError->getMessage() : 'Unable to connect to database')
    ]);
    exit();
}

// Helper function to send JSON response
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Get POST data
function getPostData() {
    $data = json_decode(file_get_contents('php://input'), true);
    return $data ?: [];
}

// Create global $pdo connection for backwards compatibility
$pdo = getDBConnection();
?>

