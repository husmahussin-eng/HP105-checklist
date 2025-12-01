<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn = getDBConnection();
    
    switch ($method) {
        case 'GET':
            // Get notes for a specific activity page
            $activityPage = $_GET['activity_page'] ?? '';
            
            if (empty($activityPage)) {
                sendResponse(false, 'Activity page parameter is required');
            }
            
            $stmt = $conn->prepare("SELECT * FROM notes WHERE activity_page = ? ORDER BY created_at DESC");
            $stmt->execute([$activityPage]);
            $notes = $stmt->fetchAll();
            
            // Format timestamps
            foreach ($notes as &$note) {
                $date = new DateTime($note['created_at']);
                $dayNames = ["Ahad", "Isnin", "Selasa", "Rabu", "Khamis", "Jumaat", "Sabtu"];
                $note['displayTime'] = $dayNames[$date->format('w')] . ', ' . $date->format('d/m/Y H:i:s');
            }
            
            sendResponse(true, 'Notes retrieved successfully', $notes);
            break;
            
        case 'POST':
            // Create new note
            $data = getPostData();
            $activityPage = $data['activityPage'] ?? '';
            $noteText = $data['noteText'] ?? '';
            $username = $data['username'] ?? '';
            
            if (empty($activityPage) || empty($noteText) || empty($username)) {
                sendResponse(false, 'Activity page, note text and username are required');
            }
            
            $stmt = $conn->prepare("INSERT INTO notes (activity_page, note_text, username) VALUES (?, ?, ?)");
            $stmt->execute([$activityPage, $noteText, $username]);
            
            // Log the activity
            $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
            $logStmt->execute([$username, "Added note in $activityPage", 'notes']);
            
            sendResponse(true, 'Note saved successfully', ['note_id' => $conn->lastInsertId()]);
            break;
            
        case 'PUT':
            // Update note
            $data = getPostData();
            $noteId = $data['noteId'] ?? 0;
            $noteText = $data['noteText'] ?? '';
            $username = $data['username'] ?? '';
            
            if (empty($noteId) || empty($noteText)) {
                sendResponse(false, 'Note ID and text are required');
            }
            
            $stmt = $conn->prepare("UPDATE notes SET note_text = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$noteText, $noteId]);
            
            // Log the activity
            if (!empty($username)) {
                $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
                $logStmt->execute([$username, "Updated note (ID: $noteId)", 'notes']);
            }
            
            sendResponse(true, 'Note updated successfully');
            break;
            
        case 'DELETE':
            // Delete note
            $data = getPostData();
            $noteId = $data['noteId'] ?? 0;
            $username = $data['username'] ?? '';
            
            if (empty($noteId)) {
                sendResponse(false, 'Note ID is required');
            }
            
            $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
            $stmt->execute([$noteId]);
            
            // Log the activity
            if (!empty($username)) {
                $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
                $logStmt->execute([$username, "Deleted note (ID: $noteId)", 'notes']);
            }
            
            sendResponse(true, 'Note deleted successfully');
            break;
            
        default:
            sendResponse(false, 'Method not allowed');
    }
} catch(PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
}
?>

