<?php
require_once 'config.php';

$data = getPostData();
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    sendResponse(false, 'Username and password are required');
}

try {
    $conn = getDBConnection();
    
    // Check user credentials (case-insensitive username)
    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && $user['password'] === $password) {
        // Normalize role - convert 'user' to 'admin' for display
        $normalizedRole = $user['role'] === 'super_admin' ? 'super_admin' : 'admin';
        
        // Log the login activity
        $logStmt = $conn->prepare("INSERT INTO activity_log (user_id, username, action, type) VALUES (?, ?, ?, ?)");
        $logStmt->execute([$user['id'], $user['username'], 'Successful login to system', 'login']);
        
        sendResponse(true, 'Login successful', [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $normalizedRole,
            'is_super_admin' => $normalizedRole === 'super_admin'
        ]);
    } else {
        sendResponse(false, 'Invalid username or password');
    }
} catch(PDOException $e) {
    sendResponse(false, 'Login failed: ' . $e->getMessage());
}
?>

