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
            // Get all meetings
            $stmt = $pdo->query("
                SELECT id, title, meeting_date, meeting_time, venue, chaired_by, 
                       document_name, document_data, created_by, created_at 
                FROM meetings 
                ORDER BY meeting_date ASC, meeting_time ASC
            ");
            $meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $meetings
            ]);
            break;
            
        case 'POST':
            // Create new meeting
            $action = $input['action'] ?? 'create';
            
            if ($action === 'create') {
                $stmt = $pdo->prepare("
                    INSERT INTO meetings (title, meeting_date, meeting_time, venue, chaired_by, 
                                        document_name, document_data, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->execute([
                    $input['title'],
                    $input['date'],
                    $input['time'],
                    $input['venue'],
                    $input['chaired_by'],
                    $input['document_name'] ?? null,
                    $input['document_data'] ?? null,
                    $input['created_by'] ?? 'System'
                ]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Meeting added successfully',
                    'meeting_id' => $pdo->lastInsertId()
                ]);
            }
            break;
            
        case 'DELETE':
            // Delete meeting (Super Admin Only)
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
                    'message' => 'Access denied. Only Super Admin can delete meetings.'
                ]);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM meetings WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Meeting deleted successfully'
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

