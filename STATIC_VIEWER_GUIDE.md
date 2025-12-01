# Static Viewer Page Guide

## Overview
The `calendar-viewer-static.html` is a **static HTML page** that can be hosted on **GitHub Pages for FREE**. It includes:
- ✅ Dynamic countdown timer (pure client-side JavaScript, auto-updates every second)
- ✅ Calendar display (December 2025 & January 2026)
- ✅ Checklist table
- ✅ No login required
- ✅ No API/database connection needed (loads from JSON file)

---

## How It Works

### 1. Static HTML File
- `calendar-viewer-static.html` - The viewer page
- Loads calendar events from `calendar-events.json`
- Countdown timer updates automatically using JavaScript

### 2. JSON Data File
- `calendar-events.json` - Contains calendar events
- Updated by running `export-calendar.php`
- Committed to GitHub along with the HTML

### 3. Update Workflow
```
1. Update database locally (Docker Desktop)
   ↓
2. Run: php export-calendar.php
   ↓
3. Commit & push to GitHub (GitHub Desktop)
   ↓
4. GitHub Pages automatically updates
   ↓
5. Viewers see updated content!
```

---

## Setup Instructions

### Step 1: Enable GitHub Pages
1. Go to your GitHub repository: `https://github.com/husmahussin-eng/HP105-checklist`
2. Click **"Settings"** (top menu)
3. Click **"Pages"** (left sidebar)
4. Under **"Source"**, select:
   - Branch: `main`
   - Folder: `/ (root)`
5. Click **"Save"**
6. Wait 1-2 minutes for GitHub to build

### Step 2: Access Your Page
After GitHub Pages is enabled, your page will be at:
```
https://husmahussin-eng.github.io/HP105-checklist/calendar-viewer-static.html
```

---

## Updating Calendar Events

### Method 1: Using Export Script (Recommended)

1. **Update your database** (via Docker Desktop or your admin panel)

2. **Run the export script:**
   ```bash
   php export-calendar.php
   ```
   
   Or double-click: `export-calendar.bat` (Windows)

3. **Check the output:**
   - Should see: "✅ Success! Exported X events to calendar-events.json"

4. **Commit and push to GitHub:**
   - Open GitHub Desktop
   - You'll see `calendar-events.json` in the changes
   - Add commit message: "Update calendar events"
   - Click "Commit to main"
   - Click "Push origin"

5. **Wait 1-2 minutes** for GitHub Pages to update

6. **Refresh the viewer page** - new events will appear!

---

### Method 2: Manual JSON Edit

If you prefer to edit the JSON file directly:

1. Open `calendar-events.json`
2. Edit the events array
3. Update `last_updated` timestamp
4. Commit and push to GitHub

**JSON Format:**
```json
{
  "events": [
    {
      "month": "dec",
      "day": 15,
      "event_title": "Nama Acara",
      "event_time": "09:00",
      "venue": "Lokasi"
    },
    {
      "month": "jan",
      "day": 15,
      "event_title": "Majlis Perbarisan HP105",
      "event_time": "08:00",
      "venue": "Stadium Polis"
    }
  ],
  "last_updated": "2025-01-01T00:00:00Z"
}
```

**Month values:** Use `"dec"` for December, `"jan"` for January

---

## Troubleshooting

### Countdown Timer Not Updating
- ✅ The timer uses **pure JavaScript** and updates every second
- ✅ No server or API needed
- ✅ Works offline once the page is loaded

### Calendar Events Not Showing
1. Check if `calendar-events.json` exists in your repository
2. Check browser console (F12) for errors
3. Verify JSON format is correct (use JSON validator)
4. Make sure you've pushed the JSON file to GitHub

### GitHub Pages Shows 404
1. Make sure GitHub Pages is enabled (Settings → Pages)
2. Wait 2-3 minutes after enabling
3. Check the file path: `calendar-viewer-static.html` (not `calendar-viewer.html`)
4. Try: `https://husmahussin-eng.github.io/HP105-checklist/calendar-viewer-static.html`

### Export Script Fails
1. Make sure Docker database is running
2. Check `api/config.php` has correct database credentials
3. Verify you can access the database from PHP
4. Check file permissions (JSON file must be writable)

---

## Files Overview

| File | Purpose |
|------|---------|
| `calendar-viewer-static.html` | Static viewer page (hosted on GitHub Pages) |
| `calendar-events.json` | Calendar events data (updated via export script) |
| `export-calendar.php` | Script to export database → JSON |
| `export-calendar.bat` | Windows batch file to run export script |

---

## Benefits

✅ **FREE hosting** (GitHub Pages)  
✅ **No server needed** (static files only)  
✅ **Fast loading** (served from CDN)  
✅ **Easy updates** (just push JSON file)  
✅ **Works offline** (after initial load)  
✅ **No login required** (public access)

---

## Next Steps

1. ✅ Enable GitHub Pages
2. ✅ Test the viewer page
3. ✅ Run export script to create initial JSON
4. ✅ Commit and push to GitHub
5. ✅ Share the link with viewers!

---

## Questions?

If you encounter any issues:
1. Check the browser console (F12) for errors
2. Verify all files are committed to GitHub
3. Make sure GitHub Pages is enabled
4. Wait a few minutes after pushing changes

