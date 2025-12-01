-- SQL to create budget_items table
-- Run this in your MySQL database to add support for budget management

CREATE TABLE IF NOT EXISTS `budget_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT 0,
  `unit_price` decimal(10,2) DEFAULT 0,
  `total` decimal(10,2) DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT 'System',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

