# Calendar Modal Troubleshooting Guide

## Problem: Modal Not Showing When Clicking Calendar Dates

### Step 1: Test the Modal Functionality
1. Open your browser and navigate to: `http://localhost:8080/test-modal.html`
2. Click the **"Open Test Modal"** button
3. **If the test modal opens**: The modal system works, proceed to Step 2
4. **If the test modal doesn't open**: There's a browser cache issue, proceed to Step 4

### Step 2: Run the SQL Command
The calendar events feature requires a new database table. 

**In phpMyAdmin:**
1. Open `http://localhost/phpmyadmin`
2. Select your database (usually `rbpf_checklist`)
3. Click the **SQL** tab
4. Copy and paste this SQL:

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

5. Click **Go** to execute

### Step 3: Clear Browser Cache & Test
1. Press `Ctrl + Shift + Delete` to open Clear Browsing Data
2. Select:
   - ✅ Cookies and other site data
   - ✅ Cached images and files
   - ✅ Hosted app data (if available)
3. Click **Clear Data**
4. **OR** Do a Hard Refresh: `Ctrl + F5` (Windows) or `Cmd + Shift + R` (Mac)
5. Go back to your main app: `http://localhost:8080/index.html`
6. Login and go to the **Calendar** tab
7. Click on any date

### Step 4: Check Browser Console for Errors
1. Press `F12` to open Developer Tools
2. Click on the **Console** tab
3. Go to the Calendar tab in your app
4. Click on a date
5. Look for errors in red

**Common Error Messages:**

#### Error: "CalendarEventsAPI is not defined"
**Fix:** The API file wasn't updated properly. Reload the page with `Ctrl + F5`

#### Error: "Failed to fetch" or "Network error"
**Fix:** 
1. Make sure XAMPP Apache and MySQL are running
2. Check that the file `api/calendar-events.php` exists
3. Verify the file has proper PHP opening tags

#### Error: "calendar_events table doesn't exist"
**Fix:** Run the SQL command from Step 2

### Step 5: Verify Files Exist
Make sure these files exist in your project:

```
C:\xampp\htdocs\HP105 checklist\
├── api/
│   ├── calendar-events.php        ← NEW FILE (must exist)
│   └── create_calendar_events_table.sql
├── js/
│   └── api-client.js              ← UPDATED (must have CalendarEventsAPI)
├── index.html                     ← UPDATED (has modal HTML)
└── test-modal.html                ← NEW TEST FILE
```

### Step 6: Check api-client.js Exports
1. Open `js/api-client.js` in a text editor
2. Scroll to the bottom
3. Verify you see these lines:

```javascript
// Export APIs
window.AuthAPI = AuthAPI;
window.UsersAPI = UsersAPI;
window.ActivityAPI = ActivityAPI;
window.NotesAPI = NotesAPI;
window.MeetingsAPI = MeetingsAPI;
window.CalendarAPI = CalendarAPI;
window.CalendarEventsAPI = CalendarEventsAPI;  ← MUST BE HERE
window.BackupAPI = BackupAPI;
```

If `window.CalendarEventsAPI = CalendarEventsAPI;` is **missing**, add it and save the file.

### Step 7: Check Modal HTML
1. Open `index.html`
2. Search for `id="calendarEventModal"`
3. Verify the modal HTML exists (around line 534)

### Step 8: Test with Console Commands
1. Open your app in browser
2. Press `F12` to open Console
3. Type this and press Enter:

```javascript
editCalendarDate('jan', 15)
```

**If modal opens:** Everything works! The click event might not be properly attached.

**If error shows:** Check the error message and follow the fixes in Step 4.

### Step 9: Force Reload JavaScript
Sometimes browsers cache JavaScript files aggressively.

**Method 1: Add version parameter**
1. Open `index.html`
2. Find the line:
```html
<script src="js/api-client.js?v=5"></script>
```
3. Change `v=5` to `v=6`
4. Save and reload

**Method 2: Disable cache while DevTools is open**
1. Press `F12`
2. Go to **Network** tab
3. Check ✅ **Disable cache**
4. Keep DevTools open and reload the page

### Step 10: Check Apache Error Log
If the API isn't responding:

1. Open: `C:\xampp\apache\logs\error.log`
2. Look for recent PHP errors
3. Common issues:
   - **Syntax errors** in `calendar-events.php`
   - **Database connection errors**
   - **Missing config.php**

## Still Not Working?

### Quick Manual Test
1. In browser console (`F12`), type:

```javascript
// Test if API is available
console.log(typeof CalendarEventsAPI);
// Should show: "object"

// Test if modal exists
console.log(document.getElementById('calendarEventModal'));
// Should show: div#calendarEventModal
```

If either returns `undefined` or `null`, the files weren't loaded properly.

### Last Resort: Re-add the Files
1. Download fresh copies of:
   - `api/calendar-events.php`
   - Updated `js/api-client.js`
   - Updated `index.html`
2. Replace your current files
3. Clear cache and test again

## Success Checklist
- ✅ Database table `calendar_events` created
- ✅ File `api/calendar-events.php` exists
- ✅ File `js/api-client.js` has `CalendarEventsAPI` export
- ✅ Modal HTML exists in `index.html`
- ✅ Browser cache cleared
- ✅ No console errors
- ✅ Test modal (`test-modal.html`) works
- ✅ Clicking calendar date opens modal

## Contact for Help
If still not working, provide:
1. Browser name and version
2. Screenshot of console errors (F12 → Console tab)
3. Result of the manual tests from Step 10

