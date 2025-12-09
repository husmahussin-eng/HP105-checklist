<?php
/**
 * Update Row 7 (Daerah Belait) - Set Cenderahati to ["1","",""] and Goodies to ["500","-","100"]
 * This updates the Open day activity: Cenderahati = 1, Goodies = 500
 * 
 * This script will:
 * 1. Check if columns exist, add them if missing
 * 2. Update the Daerah Belait row with correct values
 */

// Use XAMPP MySQL connection (localhost)
$host = 'localhost';
$dbname = 'rbpf_checklist';
$username = 'root';
$password = ''; // XAMPP default is empty password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    echo "✅ Connected to database successfully.\n\n";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Error: Table 'checklist_items' does not exist.\n";
        echo "Please run the migration script first: api/migrate_checklist.sql\n";
        exit(1);
    }
    
    // Check if columns exist, add them if missing
    $stmt = $pdo->query("SHOW COLUMNS FROM checklist_items LIKE 'cenderahati'");
    if ($stmt->rowCount() == 0) {
        echo "Adding 'cenderahati' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN cenderahati JSON DEFAULT NULL AFTER keterangan");
        echo "✅ Added 'cenderahati' column.\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM checklist_items LIKE 'goodies'");
    if ($stmt->rowCount() == 0) {
        echo "Adding 'goodies' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN goodies JSON DEFAULT NULL AFTER cenderahati");
        echo "✅ Added 'goodies' column.\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM checklist_items LIKE 'tindakan'");
    if ($stmt->rowCount() == 0) {
        echo "Adding 'tindakan' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN tindakan JSON DEFAULT NULL AFTER category");
        echo "✅ Added 'tindakan' column.\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM checklist_items LIKE 'keterangan'");
    if ($stmt->rowCount() == 0) {
        echo "Adding 'keterangan' column...\n";
        $pdo->exec("ALTER TABLE checklist_items ADD COLUMN keterangan TEXT DEFAULT NULL AFTER activity4");
        echo "✅ Added 'keterangan' column.\n";
    }
    
    echo "\n";
    
    // Check if Daerah Belait exists, create if not
    $stmt = $pdo->prepare("SELECT id FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    if ($stmt->rowCount() == 0) {
        echo "Creating 'Daerah Belait' row...\n";
        $pdo->prepare("INSERT INTO checklist_items (category) VALUES (?)")->execute(['Daerah Belait']);
        echo "✅ Created 'Daerah Belait' row.\n\n";
    }
    
    // Check current values
    $stmt = $pdo->prepare("SELECT category, CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Current values:\n";
    echo "Category: " . $current['category'] . "\n";
    echo "Cenderahati: " . ($current['cenderahati'] ?? 'NULL') . "\n";
    echo "Goodies: " . ($current['goodies'] ?? 'NULL') . "\n\n";
    
    // Update the values
    $cenderahati = json_encode(["1", "", ""]);
    $goodies = json_encode(["500", "-", "100"]);
    
    $stmt = $pdo->prepare("
        UPDATE checklist_items 
        SET 
            cenderahati = ?,
            goodies = ?,
            updated_by = 'System',
            updated_at = CURRENT_TIMESTAMP
        WHERE category = 'Daerah Belait'
    ");
    
    $stmt->execute([$cenderahati, $goodies]);
    
    echo "✅ Update executed successfully!\n\n";
    
    // Verify the update
    $stmt = $pdo->prepare("SELECT category, CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies, updated_at FROM checklist_items WHERE category = ?");
    $stmt->execute(['Daerah Belait']);
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Updated values:\n";
    echo "Category: " . $updated['category'] . "\n";
    echo "Cenderahati: " . $updated['cenderahati'] . "\n";
    echo "Goodies: " . $updated['goodies'] . "\n";
    echo "Updated at: " . $updated['updated_at'] . "\n\n";
    
    echo "✅ Successfully updated Daerah Belait:\n";
    echo "   - Open day: Cenderahati = 1, Goodies = 500\n";
    echo "   - Gotong Royong: Cenderahati = empty, Goodies = -\n";
    echo "   - Kempen Derma Darah: Cenderahati = empty, Goodies = 100\n";
    echo "\nPlease refresh your browser to see the changes.\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Make sure XAMPP MySQL is running\n";
    echo "2. Check if database 'rbpf_checklist' exists\n";
    echo "3. Verify username/password (default: root with empty password)\n";
    echo "4. If database doesn't exist, create it: CREATE DATABASE rbpf_checklist;\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
