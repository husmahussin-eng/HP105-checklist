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
        $stmt = $pdo->query("SELECT bil, perkara, keterangan, perbelanjaan, updated_by, updated_at FROM budget ORDER BY CAST(bil AS UNSIGNED), bil");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        respond(['success' => true, 'data' => $rows]);
    }

    if ($method === 'POST' || $method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['bil'])) {
            respond(['success' => false, 'error' => 'bil is required']);
        }
        $bil = $input['bil'];
        $perkara = isset($input['perkara']) ? $input['perkara'] : null;
        $keterangan = isset($input['keterangan']) ? $input['keterangan'] : null;
        // Allow empty perbelanjaan; default to 0.0; cast to float to avoid SQL errors
        $perbelanjaan = 0.0;
        if (isset($input['perbelanjaan']) && $input['perbelanjaan'] !== '') {
            $perbelanjaan = floatval($input['perbelanjaan']);
            if (is_nan($perbelanjaan)) {
                $perbelanjaan = 0.0;
            }
        }
        $user = isset($input['username']) ? $input['username'] : 'System';

        $stmt = $pdo->prepare("
            INSERT INTO budget (bil, perkara, keterangan, perbelanjaan, updated_by)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                perkara = VALUES(perkara),
                keterangan = VALUES(keterangan),
                perbelanjaan = VALUES(perbelanjaan),
                updated_by = VALUES(updated_by),
                updated_at = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$bil, $perkara, $keterangan, $perbelanjaan, $user]);

        respond(['success' => true, 'message' => 'Budget updated']);
    }

    respond(['success' => false, 'error' => 'Method not allowed']);
} catch (Exception $e) {
    respond(['success' => false, 'error' => $e->getMessage()]);
}
?>

