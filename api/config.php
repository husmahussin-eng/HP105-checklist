<?php
// Database Configuration
// Support both Docker (local) and Production (Railway/Render) environments
// Use environment variables if available (Production), otherwise use Docker defaults
define('DB_HOST', getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: 'mysql'));
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
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
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
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
        exit();
    }
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

