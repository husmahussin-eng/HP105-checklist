<?php
/**
 * Script to update Walkaton event: Move from January 17 to January 24
 * Run this script once to update the database
 * Date: 2025-01-05
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    $pdo->beginTransaction();
    
    echo "🔄 Starting calendar event update...\n";
    
    // Step 1: Delete Walkaton event from January 17
    $deleteStmt = $pdo->prepare("
        DELETE FROM calendar_events 
        WHERE month IN ('jan', 'January', '1') 
          AND day = 17 
          AND (event_title LIKE '%Walkaton%' OR event_title LIKE '%walkaton%')
    ");
    $deleteStmt->execute();
    $deletedCount = $deleteStmt->rowCount();
    echo "✅ Deleted {$deletedCount} Walkaton event(s) from January 17\n";
    
    // Step 2: Check if Walkaton already exists on January 24
    $checkStmt = $pdo->prepare("
        SELECT id FROM calendar_events 
        WHERE month IN ('jan', 'January', '1') 
          AND day = 24 
          AND (event_title LIKE '%Walkaton%' OR event_title LIKE '%walkaton%')
    ");
    $checkStmt->execute();
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$existing) {
        // Add Walkaton event to January 24
        $insertStmt = $pdo->prepare("
            INSERT INTO calendar_events 
            (month, day, event_time, event_title, venue, status, description, activity, created_by) 
            VALUES ('jan', 24, '', 'Aktiviti Daerah BM : Walkaton', 'Taman Rekreasi Jalan Menteri Besar (Health Promotion Center)', 'not-started', '', 'BM', 'System')
        ");
        $insertStmt->execute();
        echo "✅ Added Walkaton event to January 24\n";
    } else {
        echo "ℹ️  Walkaton event already exists on January 24 (ID: {$existing['id']})\n";
    }
    
    // Step 3: Ensure January 5 has Latihan Perbarisan event
    $checkJan5Stmt = $pdo->prepare("
        SELECT id FROM calendar_events 
        WHERE month IN ('jan', 'January', '1') 
          AND day = 5 
          AND event_title LIKE '%Latihan Perbarisan%'
    ");
    $checkJan5Stmt->execute();
    $jan5Exists = $checkJan5Stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$jan5Exists) {
        $insertJan5Stmt = $pdo->prepare("
            INSERT INTO calendar_events 
            (month, day, event_time, event_title, venue, status, description, activity, created_by) 
            VALUES ('jan', 5, '', 'Latihan Perbarisan', 'PTC', 'not-started', '', 'Perbarisan', 'System')
        ");
        $insertJan5Stmt->execute();
        echo "✅ Added Latihan Perbarisan event to January 5\n";
    } else {
        echo "ℹ️  January 5 event already exists (ID: {$jan5Exists['id']})\n";
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Calendar events updated successfully',
        'deleted_from_jan17' => $deletedCount,
        'added_to_jan24' => !$existing ? 1 : 0,
        'jan5_event_exists' => $jan5Exists ? true : false
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>

