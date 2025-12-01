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
            // Get all calendar notes
            $stmt = $pdo->query("
                SELECT id, month, day, note_text, created_by, created_at, updated_at 
                FROM calendar_notes 
                ORDER BY month, day
            ");
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $notes
            ]);
            break;
            
        case 'POST':
            // Create or update calendar note
            $month = $input['month'] ?? '';
            $day = $input['day'] ?? 0;
            $noteText = $input['note_text'] ?? '';
            $createdBy = $input['created_by'] ?? 'System';
            
            // Check if note exists for this date
            $stmt = $pdo->prepare("SELECT id FROM calendar_notes WHERE month = ? AND day = ?");
            $stmt->execute([$month, $day]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Update existing note
                $stmt = $pdo->prepare("
                    UPDATE calendar_notes 
                    SET note_text = ?, created_by = ? 
                    WHERE month = ? AND day = ?
                ");
                $stmt->execute([$noteText, $createdBy, $month, $day]);
                $message = 'Calendar note updated successfully';
            } else {
                // Insert new note
                $stmt = $pdo->prepare("
                    INSERT INTO calendar_notes (month, day, note_text, created_by) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$month, $day, $noteText, $createdBy]);
                $message = 'Calendar note added successfully';
            }
            
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
            break;
            
        case 'DELETE':
            // Delete calendar note
            $month = $input['month'] ?? '';
            $day = $input['day'] ?? 0;
            
            $stmt = $pdo->prepare("DELETE FROM calendar_notes WHERE month = ? AND day = ?");
            $stmt->execute([$month, $day]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Calendar note deleted successfully'
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

