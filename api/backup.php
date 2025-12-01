<?php
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn = getDBConnection();
    
    switch ($method) {
        case 'GET':
            // Get all backups
            $stmt = $conn->query("SELECT id, backup_name, file_size, created_by, created_at FROM backups ORDER BY created_at DESC");
            $backups = $stmt->fetchAll();
            
            // Format timestamps
            foreach ($backups as &$backup) {
                $date = new DateTime($backup['created_at']);
                $dayNames = ["Ahad", "Isnin", "Selasa", "Rabu", "Khamis", "Jumaat", "Sabtu"];
                $backup['displayTime'] = $dayNames[$date->format('w')] . ', ' . $date->format('d/m/Y H:i:s');
            }
            
            sendResponse(true, 'Backups retrieved successfully', $backups);
            break;
            
        case 'POST':
            $action = $_GET['action'] ?? 'create';
            
            if ($action === 'create') {
                // Create new backup
                $data = getPostData();
                $backupName = $data['backupName'] ?? '';
                $createdBy = $data['createdBy'] ?? 'System';
                
                if (empty($backupName)) {
                    sendResponse(false, 'Backup name is required');
                }
                
                // Get all data for backup
                $backupData = [
                    'users' => $conn->query("SELECT * FROM users")->fetchAll(),
                    'activity_log' => $conn->query("SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 1000")->fetchAll(),
                    'notes' => $conn->query("SELECT * FROM notes")->fetchAll()
                ];
                
                $backupJson = json_encode($backupData);
                $fileSize = strlen($backupJson);
                
                $stmt = $conn->prepare("INSERT INTO backups (backup_name, backup_data, file_size, created_by) VALUES (?, ?, ?, ?)");
                $stmt->execute([$backupName, $backupJson, $fileSize, $createdBy]);
                
                // Log the activity
                $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
                $logStmt->execute([$createdBy, "Created backup: $backupName", 'general']);
                
                sendResponse(true, 'Backup created successfully', ['backup_id' => $conn->lastInsertId(), 'size' => $fileSize]);
                
            } elseif ($action === 'download') {
                // Download backup
                $data = getPostData();
                $backupId = $data['backupId'] ?? 0;
                
                if (empty($backupId)) {
                    sendResponse(false, 'Backup ID is required');
                }
                
                $stmt = $conn->prepare("SELECT backup_name, backup_data FROM backups WHERE id = ?");
                $stmt->execute([$backupId]);
                $backup = $stmt->fetch();
                
                if (!$backup) {
                    sendResponse(false, 'Backup not found');
                }
                
                sendResponse(true, 'Backup data retrieved', [
                    'name' => $backup['backup_name'],
                    'data' => $backup['backup_data']
                ]);
                
            } elseif ($action === 'restore') {
                // Restore from backup
                $data = getPostData();
                $backupId = $data['backupId'] ?? 0;
                $restoredBy = $data['restoredBy'] ?? 'System';
                
                if (empty($backupId)) {
                    sendResponse(false, 'Backup ID is required');
                }
                
                $stmt = $conn->prepare("SELECT backup_name, backup_data FROM backups WHERE id = ?");
                $stmt->execute([$backupId]);
                $backup = $stmt->fetch();
                
                if (!$backup) {
                    sendResponse(false, 'Backup not found');
                }
                
                $backupData = json_decode($backup['backup_data'], true);
                
                // Start transaction
                $conn->beginTransaction();
                
                try {
                    // Clear existing data (except super admin)
                    $conn->exec("DELETE FROM notes");
                    $conn->exec("DELETE FROM activity_log");
                    $conn->exec("DELETE FROM users WHERE role != 'super_admin'");
                    
                    // Restore users (skip if username already exists)
                    if (isset($backupData['users'])) {
                        $userStmt = $conn->prepare("INSERT IGNORE INTO users (id, full_name, jawatan, username, password, role, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        foreach ($backupData['users'] as $user) {
                            $userStmt->execute([
                                $user['id'], $user['full_name'], $user['jawatan'], 
                                $user['username'], $user['password'], $user['role'], 
                                $user['created_at'], $user['created_by']
                            ]);
                        }
                    }
                    
                    // Restore activity logs
                    if (isset($backupData['activity_log'])) {
                        $activityStmt = $conn->prepare("INSERT INTO activity_log (id, user_id, username, action, type, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
                        foreach ($backupData['activity_log'] as $activity) {
                            $activityStmt->execute([
                                $activity['id'], $activity['user_id'], $activity['username'], 
                                $activity['action'], $activity['type'], $activity['timestamp']
                            ]);
                        }
                    }
                    
                    // Restore notes
                    if (isset($backupData['notes'])) {
                        $noteStmt = $conn->prepare("INSERT INTO notes (id, activity_page, note_text, username, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
                        foreach ($backupData['notes'] as $note) {
                            $noteStmt->execute([
                                $note['id'], $note['activity_page'], $note['note_text'], 
                                $note['username'], $note['created_at'], $note['updated_at']
                            ]);
                        }
                    }
                    
                    // Log the restore activity
                    $logStmt = $conn->prepare("INSERT INTO activity_log (username, action, type) VALUES (?, ?, ?)");
                    $logStmt->execute([$restoredBy, "Restored backup: " . $backup['backup_name'], 'general']);
                    
                    $conn->commit();
                    sendResponse(true, 'Backup restored successfully');
                    
                } catch (Exception $e) {
                    $conn->rollBack();
                    sendResponse(false, 'Restore failed: ' . $e->getMessage());
                }
            }
            break;
            
        default:
            sendResponse(false, 'Method not allowed');
    }
} catch(PDOException $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
}
?>

