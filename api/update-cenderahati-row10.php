<?php
/**
 * Update Row 10 (Cenderahati) - Set Cenderahati to 473 and Goodies to 2135
 * This ensures the database matches the CSV/Excel file
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
        echo "⚠️  Table 'checklist_items' does not exist.\n";
        echo "The live calendar will use static HTML values which are already correct (473, 2135).\n";
        echo "If you're seeing old values (472, 2185), try:\n";
        echo "1. Hard refresh: Ctrl+F5\n";
        echo "2. Clear browser cache\n";
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
    
    // Check if Cenderahati row exists
    $stmt = $pdo->prepare("SELECT id FROM checklist_items WHERE category = ?");
    $stmt->execute(['Cenderahati']);
    if ($stmt->rowCount() == 0) {
        echo "Creating 'Cenderahati' row...\n";
        $pdo->prepare("INSERT INTO checklist_items (category) VALUES (?)")->execute(['Cenderahati']);
        echo "✅ Created.\n";
    }
    
    // Get current values
    $stmt = $pdo->prepare("SELECT category, CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies FROM checklist_items WHERE category = ?");
    $stmt->execute(['Cenderahati']);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nCurrent values:\n";
    echo "Category: " . $current['category'] . "\n";
    echo "Cenderahati: " . ($current['cenderahati'] ?? 'NULL') . "\n";
    echo "Goodies: " . ($current['goodies'] ?? 'NULL') . "\n\n";
    
    // Update the values - Cenderahati is a single value (not array), Goodies is also single value
    $cenderahati = json_encode("473");
    $goodies = json_encode("2135");
    
    $stmt = $pdo->prepare("
        UPDATE checklist_items 
        SET 
            cenderahati = ?,
            goodies = ?,
            updated_by = 'System',
            updated_at = CURRENT_TIMESTAMP
        WHERE category = 'Cenderahati'
    ");
    
    $stmt->execute([$cenderahati, $goodies]);
    
    echo "✅ Update executed successfully!\n\n";
    
    // Verify the update
    $stmt = $pdo->prepare("SELECT category, CAST(cenderahati AS CHAR) as cenderahati, CAST(goodies AS CHAR) as goodies, updated_at FROM checklist_items WHERE category = ?");
    $stmt->execute(['Cenderahati']);
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Updated values:\n";
    echo "Category: " . $updated['category'] . "\n";
    echo "Cenderahati: " . $updated['cenderahati'] . "\n";
    echo "Goodies: " . $updated['goodies'] . "\n";
    echo "Updated at: " . $updated['updated_at'] . "\n\n";
    
    echo "✅ Successfully updated Cenderahati row:\n";
    echo "   - Cenderahati: 473\n";
    echo "   - Goodies: 2135\n";
    echo "\nPlease refresh your browser to see the changes.\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nThe HTML file already has the correct values (473, 2135).\n";
    echo "If you're seeing old values, try:\n";
    echo "1. Hard refresh: Ctrl+F5\n";
    echo "2. Clear browser cache\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>

