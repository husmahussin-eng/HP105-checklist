# Quick Start - Deploy to Railway (5 Minutes)

## Step 1: Push to GitHub

```bash
# Navigate to your project folder
cd "C:\xampp\htdocs\HP105 checklist"

# Initialize Git (if not done)
git init
git add .
git commit -m "Ready for deployment"

# Create repository on GitHub.com, then:
git remote add origin https://github.com/YOUR_USERNAME/hp105-checklist.git
git branch -M main
git push -u origin main
```

## Step 2: Deploy on Railway

1. **Go to**: https://railway.app
2. **Sign up** with GitHub (free)
3. **Click**: "New Project" → "Deploy from GitHub repo"
4. **Select**: Your `hp105-checklist` repository
5. **Add MySQL Database**:
   - Click "+ New" → "Database" → "MySQL"
   - Wait for it to provision
6. **Configure Web Service**:
   - Railway auto-detects your Dockerfile
   - Go to your web service → "Variables" tab
   - Add these (get values from MySQL service):
     ```
     DB_HOST=<from MySQL service Variables tab>
     DB_NAME=<from MySQL service Variables tab>
     DB_USER=<from MySQL service Variables tab>
     DB_PASS=<from MySQL service Variables tab>
     ```
7. **Deploy**: Railway will automatically build and deploy
8. **Get URL**: Railway gives you a URL like `https://your-app.railway.app`

## Step 3: Initialize Database

1. **Get MySQL Connection**:
   - Go to MySQL service → "Connect" tab
   - Copy the connection string or use Railway CLI

2. **Import Schema**:
   - Use MySQL client or Railway's web interface
   - Import `database/schema.sql`

## Step 4: Test

1. Visit: `https://your-app.railway.app/login.html`
2. Click "View Only" → Should see calendar
3. Login with your credentials → Should see dashboard

## Done! 🎉

Your app is now live and accessible to anyone with the URL.

---

## Troubleshooting

**Calendar not loading?**
- Check Railway logs for errors
- Verify database connection
- Ensure `calendar_events` table exists

**Can't connect to database?**
- Verify environment variables are set correctly
- Check MySQL service is running
- Review Railway logs

**Need help?**
- Check Railway documentation: https://docs.railway.app
- Review `DEPLOYMENT_GUIDE.md` for detailed instructions


