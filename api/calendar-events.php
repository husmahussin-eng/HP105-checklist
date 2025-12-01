<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            // Get all calendar events
            $month = $_GET['month'] ?? '';
            $day = $_GET['day'] ?? '';
            
            if ($month && $day) {
                // Get events for specific day
                $stmt = $pdo->prepare("
                    SELECT id, month, day, event_time, event_title, venue, created_by, created_at 
                    FROM calendar_events 
                    WHERE month = ? AND day = ?
                    ORDER BY event_time ASC
                ");
                $stmt->execute([$month, $day]);
            } else {
                // Get all events
                $stmt = $pdo->query("
                    SELECT id, month, day, event_time, event_title, venue, created_by, created_at 
                    FROM calendar_events 
                    ORDER BY month, day, event_time ASC
                ");
            }
            
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $events
            ]);
            break;
            
        case 'POST':
            // Create new calendar event
            $month = $input['month'] ?? '';
            $day = $input['day'] ?? 0;
            $eventTime = $input['event_time'] ?? '';
            $eventTitle = $input['event_title'] ?? '';
            $venue = $input['venue'] ?? '';
            $createdBy = $input['created_by'] ?? 'System';
            
            $stmt = $pdo->prepare("
                INSERT INTO calendar_events (month, day, event_time, event_title, venue, created_by) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$month, $day, $eventTime, $eventTitle, $venue, $createdBy]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Calendar event added successfully',
                'event_id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Update calendar event (Super Admin Only)
            $id = $input['id'] ?? 0;
            $eventTime = $input['event_time'] ?? '';
            $eventTitle = $input['event_title'] ?? '';
            $venue = $input['venue'] ?? '';
            $username = $input['username'] ?? '';
            
            // Verify user is super admin
            if (empty($username)) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Authentication required'
                ]);
                break;
            }
            
            $userStmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
            $userStmt->execute([$username]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || $user['role'] !== 'super_admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Access denied. Only Super Admin can edit calendar events.'
                ]);
                break;
            }
            
            $stmt = $pdo->prepare("
                UPDATE calendar_events 
                SET event_time = ?, event_title = ?, venue = ?
                WHERE id = ?
            ");
            $stmt->execute([$eventTime, $eventTitle, $venue, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Calendar event updated successfully'
            ]);
            break;
            
        case 'DELETE':
            // Delete calendar event (Super Admin Only)
            $id = $input['id'] ?? 0;
            $username = $input['username'] ?? '';
            
            // Verify user is super admin
            if (empty($username)) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Authentication required'
                ]);
                break;
            }
            
            $userStmt = $pdo->prepare("SELECT role FROM users WHERE username = ?");
            $userStmt->execute([$username]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || $user['role'] !== 'super_admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Access denied. Only Super Admin can delete calendar events.'
                ]);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM calendar_events WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Calendar event deleted successfully'
            ]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>



