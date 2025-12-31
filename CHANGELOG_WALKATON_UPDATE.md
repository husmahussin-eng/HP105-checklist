# Calendar Update: Walkaton Event Migration
**Date:** January 5, 2025

## Summary
Moved Walkaton event from January 17 to January 24, and ensured January 5 event is present.

## Changes Made

### 1. Database Migration Scripts
- **Created:** `api/update-calendar-walkaton-migration.sql`
  - SQL script to delete Walkaton from Jan 17
  - SQL script to add Walkaton to Jan 24
  - SQL script to ensure Jan 5 has Latihan Perbarisan event

- **Created:** `api/update-walkaton-event.php`
  - PHP script to update database programmatically
  - Can be run via browser or command line
  - Includes transaction handling and error checking

### 2. Static Calendar Viewer Updates
- **Updated:** `calendar-viewer-static.html`
  - Changed walking icon from day 17 to day 24 (line 2044)
  - Updated staticEvents array: Walkaton event moved from day 17 to day 24 (line 2474)
  - Added filter to prevent any Walkaton events from appearing on day 17
  - Added explicit clearing of jan-17-notes element
  - Added comments documenting the change

### 3. Dashboard Calendar Updates
- **Updated:** `index.html`
  - Changed walking icon from day 17 to day 24 (line 1422)

### 4. View Page Calendar Updates
- **Updated:** `calendar-viewer.html`
  - Changed walking icon from day 17 to day 24 (line 2029)

## Database Changes Required

To update the live database, run one of the following:

### Option 1: Run PHP Script (Recommended)
1. Open in browser: `http://localhost/HP105-checklist/api/update-walkaton-event.php`
2. Or run via command line: `php api/update-walkaton-event.php`

### Option 2: Run SQL Script
1. Open phpMyAdmin
2. Select your database
3. Go to SQL tab
4. Copy and paste contents of `api/update-calendar-walkaton-migration.sql`
5. Click "Go"

## Files Modified
1. `calendar-viewer-static.html` - Static events array and icon logic
2. `index.html` - Dashboard calendar icon
3. `calendar-viewer.html` - View page calendar icon
4. `api/update-calendar-walkaton-migration.sql` - NEW: SQL migration script
5. `api/update-walkaton-event.php` - NEW: PHP update script

## Verification
After running the database update script, verify:
- ✅ January 17 has NO Walkaton event
- ✅ January 24 has Walkaton event
- ✅ January 5 has Latihan Perbarisan event
- ✅ Walking icon appears on January 24 (not January 17)

## Git Commit Message
```
Update calendar: Move Walkaton event from Jan 17 to Jan 24

- Delete Walkaton event from January 17
- Add Walkaton event to January 24
- Ensure January 5 has Latihan Perbarisan event
- Update static calendar viewer with new event dates
- Update walking icon to show on Jan 24 instead of Jan 17
- Add database migration scripts for live calendar update
```

