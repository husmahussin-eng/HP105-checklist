<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn = getDBConnection();
    
    switch ($method) {
        case 'GET':
            // Get all activities
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $type = isset($_GET['type']) ? $_GET['type'] : null;
            
            if ($type) {
                $stmt = $conn->prepare("SELECT * FROM activity_log WHERE type = ? ORDER BY timestamp DESC LIMIT ?");
                $stmt->execute([$type, $limit]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT ?");
                $stmt->execute([$limit]);
            }
            
            $activities = $stmt->fetchAll();
            
            // Format timestamps
            foreach ($activities as &$activity) {
                $date = new DateTime($activity['timestamp']);
                $dayNames = ["Ahad", "Isnin", "Selasa", "Rabu", "Khamis", "Jumaat", "Sabtu"];
                $activity['displayTime'] = $dayNames[$date->format('w')] . ', ' . $date->format('d/m/Y H:i:s');
            }
            
            sendResponse(true, 'Activities retrieved successfully', $activities);
            break;
            
        case 'POST':
            // Log new activity
            $data = getPostData();
            $username = $data['username'] ?? '';
            $action = $data['action'] ?? '';
            $type = $data['type'] ?? 'general';
            $userId = $data['userId'] ?? null;
            
            if (empty($username) || empty($action)) {
                sendResponse(false, 'Username and action are required');
            }
            
            $stmt = $conn->prepare("INSERT INTO activity_log (user_id, username, action, type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $username, $action, $type]);
            
            sendResponse(true, 'Activity logged successfully', ['activity_id' => $conn->lastInsertId()]);
            break;
            
        default:
            sendResponse(false, 'Method not allowed');
    }
} catch(PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
}
?>

