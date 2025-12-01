-- SQL to create calendar_events table
-- Run this in your MySQL/phpMyAdmin to add support for multiple calendar entries per day

CREATE TABLE IF NOT EXISTS `calendar_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(10) NOT NULL,
  `day` int(11) NOT NULL,
  `event_time` varchar(20) DEFAULT NULL,
  `event_title` varchar(255) NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT 'System',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `month_day` (`month`, `day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



