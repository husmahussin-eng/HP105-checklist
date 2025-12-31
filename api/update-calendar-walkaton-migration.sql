-- Migration script to move Walkaton event from January 17 to January 24
-- Date: 2025-01-05
-- Description: 
--   1. Delete Walkaton event from January 17
--   2. Add Walkaton event to January 24
--   3. Ensure January 5 has Latihan Perbarisan event

-- Step 1: Delete Walkaton event from January 17
DELETE FROM calendar_events 
WHERE month IN ('jan', 'January', '1') 
  AND day = 17 
  AND (event_title LIKE '%Walkaton%' OR event_title LIKE '%walkaton%');

-- Step 2: Add Walkaton event to January 24 (if it doesn't already exist)
INSERT INTO calendar_events (month, day, event_time, event_title, venue, status, description, activity, created_by)
SELECT 'jan', 24, '', 'Aktiviti Daerah BM : Walkaton', 'Taman Rekreasi Jalan Menteri Besar (Health Promotion Center)', 'not-started', '', 'BM', 'System'
WHERE NOT EXISTS (
    SELECT 1 FROM calendar_events 
    WHERE month IN ('jan', 'January', '1') 
      AND day = 24 
      AND event_title LIKE '%Walkaton%'
);

-- Step 3: Ensure January 5 has Latihan Perbarisan event (if it doesn't exist)
INSERT INTO calendar_events (month, day, event_time, event_title, venue, status, description, activity, created_by)
SELECT 'jan', 5, '', 'Latihan Perbarisan', 'PTC', 'not-started', '', 'Perbarisan', 'System'
WHERE NOT EXISTS (
    SELECT 1 FROM calendar_events 
    WHERE month IN ('jan', 'January', '1') 
      AND day = 5 
      AND event_title LIKE '%Latihan Perbarisan%'
);

