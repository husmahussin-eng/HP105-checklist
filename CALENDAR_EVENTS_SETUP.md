# Calendar Events Setup Instructions

## Overview
The calendar has been enhanced to support **multiple events per day** with time, activity title, and venue information displayed in a smart bullet format.

## Database Setup Required

### Step 1: Create the Calendar Events Table

You need to run the following SQL command in your MySQL database (phpMyAdmin or MySQL command line):

```sql
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
```

### Step 2: phpMyAdmin Instructions

1. Open **phpMyAdmin** in your browser (usually at `http://localhost/phpmyadmin`)
2. Select your database (likely `rbpf_checklist`)
3. Click on the **SQL** tab
4. Copy and paste the SQL command above
5. Click **Go** to execute

## Features

### Multiple Events Per Day
- Click on any calendar date to open a modal
- Add multiple events with:
  - **Time** (e.g., 08:00, 14:30)
  - **Activity/Event Title** (e.g., "Perbarisan", "Meeting")
  - **Venue** (e.g., "Padang Kawad", "Bilik Mesyuarat")

### Smart Bullet Display
- Events are displayed in compact bullet points on each calendar day
- Format: `• HH:MM Activity Title`
- Example:
  ```
  • 08:00 Perbarisan
  • 10:00 Meeting
  • 14:30 Makan Beradat
  ```

### Event Management
- **Add**: Click on a date → Fill in the form → Click "Add Event"
- **View**: All events for that day are listed in the modal
- **Delete**: Click the "Delete" button next to any event
- **Edit**: Delete and re-add (future enhancement possible)

## Security Features
- Only **Super Admin** accounts can delete meetings in the Meeting Schedule tab
- Calendar events can be managed by all authenticated users
- All actions are logged with username and timestamp

## Files Modified
1. `index.html` - Added modal and calendar event management UI
2. `js/api-client.js` - Added CalendarEventsAPI
3. `api/calendar-events.php` - New backend API for calendar events
4. `api/meetings.php` - Added Super Admin verification for delete operations
5. `api/create_calendar_events_table.sql` - SQL script for table creation

## Usage Tips

### Adding Multiple Events
1. Navigate to the **Calendar** tab
2. Click on any date (e.g., January 15)
3. Modal opens showing existing events (if any)
4. Fill in the form:
   - Time: 08:00
   - Activity: Majlis Perbarisan
   - Venue: @Padang Kawad PTC
5. Click "Add Event"
6. Repeat to add more events on the same day

### Example: January 18, 2026
You can add multiple events for the celebration day:
```
• 08:00 Majlis Perbarisan @Padang Kawad PTC
• 10:30 Makan Beradat @Dewan Besar
• 14:00 Pameran @Gallery Utama
• 16:00 Bacaan Yassin @Surau
```

## Troubleshooting

### Events Not Showing
1. Make sure you ran the SQL command to create the `calendar_events` table
2. Check browser console (F12) for any JavaScript errors
3. Verify API files are in the correct location: `api/calendar-events.php`

### Cannot Delete Events
- Refresh the page and try again
- Check that you're logged in
- Verify database connection in `api/config.php`

### Modal Not Opening
- Clear browser cache (Ctrl+Shift+Delete)
- Hard refresh (Ctrl+F5)
- Check browser console for errors

## Future Enhancements (Optional)
- Event editing functionality
- Event color coding by type
- Export calendar to PDF
- Event notifications/reminders
- Recurring events



