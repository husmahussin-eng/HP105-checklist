# 🚀 HP-105 Checklist System - Deployment

## Overview
This application allows users to:
- **Login** to access the full dashboard (admin/superadmin)
- **View Only** to see the calendar without login (public access)

## Quick Deployment (Railway - Recommended)

### Prerequisites
- GitHub account
- Railway account (free tier available)

### Steps

1. **Push to GitHub**:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/YOUR_USERNAME/hp105-checklist.git
   git push -u origin main
   ```

2. **Deploy on Railway**:
   - Go to https://railway.app
   - Sign up with GitHub
   - New Project → Deploy from GitHub repo
   - Add MySQL database
   - Configure environment variables
   - Deploy!

3. **Initialize Database**:
   - Import `database/schema.sql` to Railway MySQL

4. **Access Your App**:
   - Railway provides a URL like: `https://your-app.railway.app`
   - Visit: `https://your-app.railway.app/login.html`
   - Click "View Only" to test public access

## Files Structure

```
├── login.html              # Login page with "View Only" button
├── calendar-viewer.html    # Public calendar (no login required)
├── index.html              # Dashboard (requires login)
├── api/                    # PHP backend APIs
│   ├── config.php          # Database config (supports env vars)
│   ├── calendar-events.php # Calendar API (GET doesn't require auth)
│   └── ...
├── js/
│   └── api-client.js       # Frontend API client
├── Dockerfile              # Docker configuration
├── docker-compose.yml      # Local development setup
└── database/
    └── schema.sql          # Database schema
```

## Key Features

### Public Access (No Login)
- ✅ `calendar-viewer.html` - View calendar events
- ✅ `login.html` - Access login page
- ✅ "View Only" button works without authentication

### Protected Access (Login Required)
- 🔒 `index.html` - Dashboard
- 🔒 All other pages require authentication
- 🔒 API endpoints (except calendar GET) require authentication

## Environment Variables (Production)

Set these in Railway/Render:

```
DB_HOST=<MySQL host>
DB_NAME=<Database name>
DB_USER=<Database user>
DB_PASS=<Database password>
```

The `api/config.php` automatically uses these if available, otherwise falls back to Docker defaults.

## Testing

1. **Test Public Access**:
   - Visit: `https://your-app.railway.app/login.html`
   - Click "View Only"
   - Calendar should load (may be empty if no events)

2. **Test Login**:
   - Enter credentials
   - Should redirect to dashboard
   - Full functionality available

## Support

- See `DEPLOYMENT_GUIDE.md` for detailed instructions
- See `QUICK_START.md` for 5-minute setup
- Check Railway logs for errors
- Verify database connection in Railway dashboard

## Notes

- The calendar viewer (`calendar-viewer.html`) is designed to work without authentication
- The `calendar-events.php` GET endpoint doesn't require authentication
- All other API endpoints require login
- Database must be initialized with `database/schema.sql`

