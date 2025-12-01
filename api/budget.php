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
            // Get all budget items
            $stmt = $pdo->query("
                SELECT id, item, category, quantity, unit_price, total, remarks, created_by, created_at 
                FROM budget_items 
                ORDER BY id ASC
            ");
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'data' => $items
            ]);
            break;
            
        case 'POST':
            // Save budget items (replace all)
            $items = $input['items'] ?? [];
            $username = $input['username'] ?? 'System';
            
            // Start transaction
            $pdo->beginTransaction();
            
            try {
                // Delete all existing items
                $pdo->exec("DELETE FROM budget_items");
                
                // Insert new items
                $stmt = $pdo->prepare("
                    INSERT INTO budget_items (item, category, quantity, unit_price, total, remarks, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($items as $item) {
                    $stmt->execute([
                        $item['item'] ?? '',
                        $item['category'] ?? '',
                        $item['quantity'] ?? 0,
                        $item['unit_price'] ?? 0,
                        $item['total'] ?? 0,
                        $item['remarks'] ?? '',
                        $username
                    ]);
                }
                
                $pdo->commit();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Budget saved successfully'
                ]);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
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

