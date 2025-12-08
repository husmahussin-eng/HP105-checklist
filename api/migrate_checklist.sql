-- Checklist table to store Acara Checklist editable rows
CREATE TABLE IF NOT EXISTS checklist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL UNIQUE,
    activity1 VARCHAR(255) DEFAULT NULL,
    activity2 VARCHAR(255) DEFAULT NULL,
    activity3 VARCHAR(255) DEFAULT NULL,
    activity4 VARCHAR(255) DEFAULT NULL,
    updated_by VARCHAR(100) DEFAULT 'System',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional seeds (will only insert if category not present)
INSERT INTO checklist_items (category, activity1, activity2, activity3, activity4)
VALUES
('Perbarisan', NULL, NULL, NULL, NULL),
('Makan Beradat', NULL, NULL, NULL, NULL),
('Acara Daerah Brunei Muara', NULL, NULL, NULL, NULL),
('Acara Daerah Tutong', NULL, NULL, NULL, NULL),
('Acara Daerah Belait', NULL, NULL, NULL, NULL),
('Acara Daerah Temburong', NULL, NULL, NULL, NULL),
('Acara Keugamaan', NULL, NULL, NULL, NULL),
('Pengurus Pameran', NULL, NULL, NULL, NULL),
('Pengurus Permakanan', NULL, NULL, NULL, NULL),
('Pengurus Kelengkapan', NULL, NULL, NULL, NULL),
('Pengurus Media Masa, Jemputan dan buku program', NULL, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE category = VALUES(category);

