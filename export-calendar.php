<?php
/**
 * Export Calendar Events to JSON
 * 
 * This script reads calendar events from your Docker database
 * and exports them to calendar-events.json for the static viewer page.
 * 
 * Usage:
 *   1. Make sure your Docker database is running
 *   2. Run: php export-calendar.php
 *   3. The calendar-events.json file will be created/updated
 *   4. Commit and push to GitHub
 */

// Database Configuration (for Docker Desktop)
// Adjust these if your Docker setup uses different values
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'rbpf_checklist');
define('DB_USER', getenv('DB_USER') ?: 'rbpf_user');
define('DB_PASS', getenv('DB_PASS') ?: 'rbpf_password_2026');
define('DB_PORT', getenv('DB_PORT') ?: '3308'); // Docker uses port 3308

// Database Connection Function
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $conn = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        throw new Exception('Database connection failed: ' . $e->getMessage());
    }
}

try {
    echo "🔌 Connecting to database...\n";
    $conn = getDBConnection();
    echo "✅ Connected!\n";
    
    // Fetch all calendar events
    $stmt = $conn->query("
        SELECT 
            month,
            day,
            event_title,
            event_time,
            venue
        FROM calendar_events
        WHERE month IN ('dec', 'December', '12', 'jan', 'January', '1')
        ORDER BY 
            CASE 
                WHEN month IN ('dec', 'December', '12') THEN 1
                WHEN month IN ('jan', 'January', '1') THEN 2
                ELSE 3
            END,
            day ASC
    ");
    
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Normalize month values to 'dec' or 'jan'
    $normalizedEvents = [];
    foreach ($events as $event) {
        $month = strtolower($event['month']);
        
        // Normalize month names
        if (in_array($month, ['december', '12', 'dec'])) {
            $month = 'dec';
        } elseif (in_array($month, ['january', '1', 'jan'])) {
            $month = 'jan';
        } else {
            continue; // Skip invalid months
        }
        
        $normalizedEvents[] = [
            'month' => $month,
            'day' => (int)$event['day'],
            'event_title' => $event['event_title'] ?? '',
            'event_time' => $event['event_time'] ?? null,
            'venue' => $event['venue'] ?? null
        ];
    }
    
    // Create JSON structure
    $jsonData = [
        'events' => $normalizedEvents,
        'last_updated' => date('c') // ISO 8601 format
    ];
    
    // Write to file
    $jsonFile = __DIR__ . '/calendar-events.json';
    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($jsonFile, $jsonString) !== false) {
        echo "✅ Success! Exported " . count($normalizedEvents) . " events to calendar-events.json\n";
        echo "📁 File location: $jsonFile\n";
        echo "🔄 Next step: Commit and push to GitHub\n";
    } else {
        echo "❌ Error: Could not write to calendar-events.json\n";
        echo "   Make sure the file is writable.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Make sure your Docker database is running and config.php is correct.\n";
    exit(1);
}

