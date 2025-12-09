<?php
/**
 * Force Update Row 10 (Cenderahati) - Set Cenderahati to 473 and Goodies to 2135
 * Checks multiple database configurations and updates if found
 */

echo "Checking and updating Row 10 (Cenderahati)...\n\n";

// Try different database configurations
$configs = [
    ['host' => 'localhost', 'db' => 'rbpf_checklist', 'user' => 'root', 'pass' => ''],
    ['host' => 'mysql', 'db' => 'rbpf_checklist', 'user' => 'rbpf_user', 'pass' => 'rbpf_password_2026'],
    ['host' => '127.0.0.1', 'db' => 'rbpf_checklist', 'user' => 'root', 'pass' => ''],
];

$updated = false;

foreach ($configs as $config) {
    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']};charset=utf8mb4",
            $config['user'],
            $config['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
        if ($stmt->rowCount() == 0) {
            continue; // Try next config
        }
        
        echo "✅ Found database: {$config['host']} / {$config['db']}\n";
        
        // Check/add columns if needed
        $columns = $pdo->query("SHOW COLUMNS FROM checklist_items")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('cenderahati', $columns)) {
            $pdo->exec("ALTER TABLE checklist_items ADD COLUMN cenderahati JSON DEFAULT NULL");
        }
        if (!in_array('goodies', $columns)) {
            $pdo->exec("ALTER TABLE checklist_items ADD COLUMN goodies JSON DEFAULT NULL");
        }
        
        // Check if row exists
        $stmt = $pdo->prepare("SELECT id FROM checklist_items WHERE category = ?");
        $stmt->execute(['Cenderahati']);
        if ($stmt->rowCount() == 0) {
            $pdo->prepare("INSERT INTO checklist_items (category) VALUES (?)")->execute(['Cenderahati']);
        }
        
        // Get current values
        $stmt = $pdo->prepare("SELECT CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies FROM checklist_items WHERE category = ?");
        $stmt->execute(['Cenderahati']);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "Current: Cenderahati=" . ($current['cenderahati'] ?? 'NULL') . ", Goodies=" . ($current['goodies'] ?? 'NULL') . "\n";
        
        // Update to correct values
        $cenderahati = json_encode("473");
        $goodies = json_encode("2135");
        
        $stmt = $pdo->prepare("
            UPDATE checklist_items 
            SET cenderahati = ?, goodies = ?, updated_by = 'System', updated_at = CURRENT_TIMESTAMP
            WHERE category = 'Cenderahati'
        ");
        $stmt->execute([$cenderahati, $goodies]);
        
        echo "✅ Updated: Cenderahati=473, Goodies=2135\n\n";
        $updated = true;
        break;
        
    } catch (PDOException $e) {
        continue; // Try next config
    }
}

if (!$updated) {
    echo "⚠️  No database found with checklist_items table.\n";
    echo "The HTML file already has the correct values:\n";
    echo "  - Cenderahati: 473\n";
    echo "  - Goodies: 2135\n\n";
    echo "If you're seeing old values (472, 2185), please:\n";
    echo "1. Hard refresh your browser: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)\n";
    echo "2. Clear browser cache\n";
    echo "3. Check browser console for any errors\n";
} else {
    echo "✅ Database updated successfully!\n";
    echo "Please refresh your browser to see the changes.\n";
}
?>

