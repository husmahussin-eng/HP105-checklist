<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn = getDBConnection();
    
    switch ($method) {
        case 'GET':
            // Get all users
            $stmt = $conn->query("SELECT id, full_name, jawatan, username, password, role, created_at, created_by FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll();
            
            // Normalize roles - convert 'user' to 'admin' for display
            foreach ($users as &$user) {
                if ($user['role'] !== 'super_admin') {
                    $user['role'] = 'admin';
                }
            }
            
            sendResponse(true, 'Users retrieved successfully', $users);
            break;
            
        case 'POST':
            // Create new user
            $data = getPostData();
            $fullName = trim($data['fullName'] ?? '');
            $jawatan = trim($data['jawatan'] ?? '');
            $username = trim($data['username'] ?? '');
            $password = $data['password'] ?? '';
            $role = $data['role'] ?? 'admin';
            $createdBy = $data['createdBy'] ?? 'System';
            
            if (empty($fullName) || empty($username) || empty($password)) {
                sendResponse(false, 'Full name, username and password are required');
            }
            
            // Normalize role - only allow 'admin' or 'super_admin', default to 'admin'
            // Prevent creating super_admin through API (only one should exist)
            if ($role !== 'super_admin') {
                $role = 'admin';
            } else {
                // Check if super_admin already exists
                $superAdminCheck = $conn->query("SELECT id FROM users WHERE role = 'super_admin'");
                if ($superAdminCheck->fetch()) {
                    sendResponse(false, 'Super admin already exists. Only one super admin is allowed.');
                }
            }
            
            // Check if username already exists
            $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $checkStmt->execute([$username]);
            if ($checkStmt->fetch()) {
                sendResponse(false, 'Username already exists');
            }
            
            $stmt = $conn->prepare("INSERT INTO users (full_name, jawatan, username, password, role, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fullName, $jawatan, $username, $password, $role, $createdBy]);
            
            // Log the activity
            $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
            $logStmt->execute([$createdBy, "Created new user: $fullName", 'general']);
            
            sendResponse(true, 'User created successfully', ['user_id' => $conn->lastInsertId()]);
            break;
            
        case 'PUT':
            // Update user (reset credentials)
            $data = getPostData();
            $userId = $data['userId'] ?? 0;
            $newPassword = $data['password'] ?? '';
            $updatedBy = $data['updatedBy'] ?? 'System';
            
            if (empty($userId) || empty($newPassword)) {
                sendResponse(false, 'User ID and new password are required');
            }
            
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPassword, $userId]);
            
            // Get username for logging
            $userStmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch();
            
            // Log the activity
            $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
            $logStmt->execute([$updatedBy, "Changed password for user: " . $user['full_name'], 'general']);
            
            sendResponse(true, 'Password changed successfully');
            break;
            
        default:
            sendResponse(false, 'Method not allowed');
    }
} catch(PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
}
?>

