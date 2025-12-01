# Deployment Guide - HP-105 Checklist System

This guide will help you deploy your application so users can access the "View Only" calendar without login.

## Prerequisites
- GitHub account
- Docker Desktop installed
- Git installed

---

## Option 1: Deploy with Railway (Recommended - Easiest)

Railway supports Docker and MySQL, perfect for your setup.

### Step 1: Prepare GitHub Repository

1. **Initialize Git repository** (if not already done):
   ```bash
   cd "C:\xampp\htdocs\HP105 checklist"
   git init
   git add .
   git commit -m "Initial commit - HP105 Checklist System"
   ```

2. **Create .gitignore file** (to exclude sensitive files):
   ```bash
   # Create .gitignore
   echo "node_modules/" > .gitignore
   echo ".env" >> .gitignore
   echo "*.log" >> .gitignore
   ```

3. **Create GitHub repository**:
   - Go to https://github.com/new
   - Create a new repository (e.g., `hp105-checklist`)
   - **DO NOT** initialize with README

4. **Push to GitHub**:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/hp105-checklist.git
   git branch -M main
   git push -u origin main
   ```

### Step 2: Deploy on Railway

1. **Sign up for Railway**:
   - Go to https://railway.app
   - Sign up with GitHub (free tier available)

2. **Create New Project**:
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose your `hp105-checklist` repository

3. **Add MySQL Database**:
   - In your Railway project, click "+ New"
   - Select "Database" → "MySQL"
   - Railway will create a MySQL instance

4. **Configure Environment Variables**:
   - Go to your MySQL service → "Variables" tab
   - Note down: `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`

5. **Update config.php for Railway**:
   - In Railway, go to your web service → "Variables" tab
   - Add these environment variables:
     ```
     DB_HOST=<MYSQLHOST from MySQL service>
     DB_NAME=<MYSQLDATABASE>
     DB_USER=<MYSQLUSER>
     DB_PASS=<MYSQLPASSWORD>
     ```

6. **Update Dockerfile for Railway**:
   - Railway needs to know about the database connection
   - We'll create a production config file

7. **Deploy**:
   - Railway will automatically detect your `Dockerfile` and `docker-compose.yml`
   - It will build and deploy your app
   - You'll get a URL like: `https://your-app.railway.app`

### Step 3: Update API Config for Production

Create a production-ready config that uses environment variables:

**Update `api/config.php`** to support both Docker and Railway:

```php
<?php
// Database Configuration
// Use environment variables if available (Railway/Production), otherwise use Docker defaults
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_NAME', getenv('DB_NAME') ?: 'rbpf_checklist');
define('DB_USER', getenv('DB_USER') ?: 'rbpf_user');
define('DB_PASS', getenv('DB_PASS') ?: 'rbpf_password_2026');

// ... rest of your config.php code
```

### Step 4: Initialize Database

1. **Access Railway MySQL**:
   - Go to MySQL service → "Connect" tab
   - Use the connection string or Railway CLI

2. **Run database schema**:
   - Import `database/schema.sql` into your Railway MySQL database
   - You can use Railway's web interface or MySQL client

### Step 5: Update Frontend API URLs

Since your app will be on Railway, update the API base URL:

**Update `js/api-client.js`** to detect the environment:

```javascript
// Auto-detect API base URL
const API_BASE_URL = window.location.origin + '/api';
```

This should already work if your API calls are relative!

---

## Option 2: Deploy with Render (Alternative)

1. **Sign up**: https://render.com (free tier available)

2. **Create Web Service**:
   - New → Web Service
   - Connect GitHub repo
   - Build Command: `docker build -t hp105-checklist .`
   - Start Command: `docker-compose up`

3. **Create MySQL Database**:
   - New → PostgreSQL/MySQL
   - Note connection details

4. **Configure Environment Variables**:
   - Same as Railway steps above

---

## Option 3: Hybrid Approach (GitHub Pages + Free PHP Hosting)

If you want to use GitHub Pages for the frontend:

### Frontend (GitHub Pages):
1. Push HTML files to GitHub
2. Enable GitHub Pages in repository settings
3. Your login page will be at: `https://YOUR_USERNAME.github.io/hp105-checklist/login.html`

### Backend (Free PHP Hosting):
1. Use services like:
   - **000webhost.com** (free PHP hosting)
   - **InfinityFree.net** (free PHP hosting)
   - **Heroku** (with PHP buildpack)

2. Upload your `api/` folder to PHP hosting

3. Update `js/api-client.js` to point to your PHP backend:
   ```javascript
   const API_BASE_URL = 'https://your-php-backend.000webhostapp.com/api';
   ```

---

## Testing Your Deployment

1. **Test Login Page**:
   - Visit: `https://your-app.railway.app/login.html`
   - Should see login form

2. **Test View Only**:
   - Click "View Only" button
   - Should open calendar-viewer.html
   - Calendar should load events from database

3. **Test Full Login**:
   - Login with credentials
   - Should redirect to dashboard

---

## Important Notes

### Security Considerations:
1. **Change default passwords** in production
2. **Use environment variables** for sensitive data
3. **Enable HTTPS** (Railway/Render provide this automatically)
4. **Restrict CORS** in production (update `Access-Control-Allow-Origin`)

### Database Setup:
- Make sure to run `database/schema.sql` on your production database
- You may need to create initial admin user manually

### File Permissions:
- Ensure `api/` folder has proper permissions
- Check that PHP can write to any directories that need it

---

## Troubleshooting

### Calendar not loading events:
- Check browser console for API errors
- Verify database connection in Railway/Render logs
- Ensure `calendar_events` table exists

### Login not working:
- Check database has `users` table
- Verify session storage is working
- Check API endpoints are accessible

### CORS errors:
- Update `api/config.php` to allow your domain
- Or use `Access-Control-Allow-Origin: *` for development

---

## Quick Start Commands

```bash
# Initialize Git
git init
git add .
git commit -m "Initial commit"

# Add GitHub remote
git remote add origin https://github.com/YOUR_USERNAME/hp105-checklist.git
git push -u origin main

# Test locally with Docker
docker-compose up
# Visit: http://localhost:8080
```

---

## Next Steps After Deployment

1. **Set up custom domain** (optional):
   - Railway/Render allow custom domains
   - Update DNS settings

2. **Monitor logs**:
   - Check Railway/Render dashboard for errors
   - Monitor database connections

3. **Backup database**:
   - Set up regular backups
   - Railway/Render provide backup options

4. **Update documentation**:
   - Share the public URL with users
   - Document login credentials

---

## Support

If you encounter issues:
1. Check Railway/Render logs
2. Verify environment variables
3. Test database connection
4. Review browser console for frontend errors


