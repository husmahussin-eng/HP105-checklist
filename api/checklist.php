<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

function respond($data) {
    echo json_encode($data);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT category, activity1, activity2, activity3, activity4, updated_by, updated_at FROM checklist_items ORDER BY category");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respond(['success' => true, 'data' => $rows]);
    }

    if ($method === 'POST' || $method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['category'])) {
            respond(['success' => false, 'error' => 'category is required']);
        }
        $category = $input['category'];
        $a1 = isset($input['activity1']) ? $input['activity1'] : null;
        $a2 = isset($input['activity2']) ? $input['activity2'] : null;
        $a3 = isset($input['activity3']) ? $input['activity3'] : null;
        $a4 = isset($input['activity4']) ? $input['activity4'] : null;
        $user = isset($input['username']) ? $input['username'] : 'System';

        $stmt = $pdo->prepare("
            INSERT INTO checklist_items (category, activity1, activity2, activity3, activity4, updated_by)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                activity1 = VALUES(activity1),
                activity2 = VALUES(activity2),
                activity3 = VALUES(activity3),
                activity4 = VALUES(activity4),
                updated_by = VALUES(updated_by),
                updated_at = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$category, $a1, $a2, $a3, $a4, $user]);

        respond(['success' => true, 'message' => 'Checklist updated']);
    }

    respond(['success' => false, 'error' => 'Method not allowed']);
} catch (Exception $e) {
    respond(['success' => false, 'error' => $e->getMessage()]);
}
?>

