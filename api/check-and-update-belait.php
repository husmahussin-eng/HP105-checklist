<?php
/**
 * Check database connection and update Row 7 (Daerah Belait)
 * Tries multiple database configurations
 */

echo "Checking database connection...\n\n";

// Try different database configurations
$configs = [
    ['host' => 'localhost', 'db' => 'rbpf_checklist', 'user' => 'root', 'pass' => ''],
    ['host' => 'mysql', 'db' => 'rbpf_checklist', 'user' => 'rbpf_user', 'pass' => 'rbpf_password_2026'],
    ['host' => '127.0.0.1', 'db' => 'rbpf_checklist', 'user' => 'root', 'pass' => ''],
];

$pdo = null;
$usedConfig = null;

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
        $usedConfig = $config;
        echo "✅ Connected using: {$config['host']} / {$config['db']}\n\n";
        break;
    } catch (PDOException $e) {
        continue;
    }
}

if (!$pdo) {
    echo "❌ Could not connect to database with any configuration.\n";
    echo "Please check:\n";
    echo "1. MySQL is running\n";
    echo "2. Database 'rbpf_checklist' exists\n";
    echo "3. Correct credentials\n";
    exit(1);
}

try {
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
    if ($stmt->rowCount() == 0) {
        echo "⚠️  Table 'checklist_items' does not exist.\n";
        echo "The live calendar might be using static data or a different storage method.\n";
        echo "\nSince the HTML file already has the correct values (Cenderahati=1, Goodies=500),\n";
        echo "the issue might be browser cache. Try:\n";
        echo "1. Hard refresh: Ctrl+F5\n";
        echo "2. Clear browser cache\n";
        echo "3. Check if data is loaded from database or static fallback\n";
        exit(0);
    }
    
    // Check if columns exist
    $columns = $pdo->query("SHOW COLUMNS FROM checklist_items")->fetchAll(PDO::FETCH_COLUMN);
    $hasCenderahati = in_array('cenderahati', $columns);
    $hasGoodies = in_array('goodies', $columns);
    
    if (!$hasCenderahati) {
        echo "Adding 'cenderahati' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN cenderahati JSON DEFAULT NULL");
        echo "✅ Added.\n";
    }
    
    if (!$hasGoodies) {
        echo "Adding 'goodies' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN goodies JSON DEFAULT NULL");
        echo "✅ Added.\n";
    }
    
    // Check if Daerah Belait exists
    $stmt = $pdo->prepare("SELECT id FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    if ($stmt->rowCount() == 0) {
        echo "Creating 'Daerah Belait' row...\n";
        $pdo->prepare("INSERT INTO checklist_items (category) VALUES (?)")->execute(['Daerah Belait']);
        echo "✅ Created.\n";
    }
    
    // Get current values
    $stmt = $pdo->prepare("SELECT category, CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nCurrent values:\n";
    echo "Cenderahati: " . ($current['cenderahati'] ?? 'NULL') . "\n";
    echo "Goodies: " . ($current['goodies'] ?? 'NULL') . "\n\n";
    
    // Update
    $cenderahati = json_encode(["1", "", ""]);
    $goodies = json_encode(["500", "-", "100"]);
    
    $stmt = $pdo->prepare("
        UPDATE checklist_items 
        SET cenderahati = ?, goodies = ?, updated_by = 'System', updated_at = CURRENT_TIMESTAMP
        WHERE category = 'Daerah Belait'
    ");
    $stmt->execute([$cenderahati, $goodies]);
    
    echo "✅ Updated successfully!\n\n";
    
    // Verify
    $stmt = $pdo->prepare("SELECT CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "New values:\n";
    echo "Cenderahati: " . $updated['cenderahati'] . "\n";
    echo "Goodies: " . $updated['goodies'] . "\n\n";
    
    echo "✅ Database updated! Refresh your browser to see changes.\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>

