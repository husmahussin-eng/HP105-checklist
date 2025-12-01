# How to Host Your HP105 Checklist Website Online

## Quick Summary
Your code is on GitHub, but to see the **actual website** (login page, calendar viewer), you need to host it on a platform that supports PHP and MySQL.

---

## Option 1: Render.com (Recommended - FREE)

### Step 1: Create Account
1. Go to https://render.com
2. Click "Get Started for Free"
3. Sign up with your **GitHub account** (easiest way)

### Step 2: Deploy Web Service
1. Click **"New +"** → **"Web Service"**
2. Click **"Connect GitHub"** and authorize Render
3. Select your repository: **`husmahussin-eng/HP105-checklist`**
4. Configure:
   - **Name**: `hp105-checklist` (or any name)
   - **Region**: Choose closest to you
   - **Branch**: `main`
   - **Root Directory**: Leave empty
   - **Environment**: **Docker**
   - **Dockerfile Path**: `Dockerfile`
5. Click **"Create Web Service"**

### Step 3: Add MySQL Database
1. In Render dashboard, click **"New +"** → **"PostgreSQL"** (or MySQL if available)
2. **OR** use Render's MySQL addon:
   - Go to your web service
   - Click **"Add Environment Variable"**
   - Add these variables:
     ```
     DB_HOST=your-db-host
     DB_NAME=your-db-name
     DB_USER=your-db-user
     DB_PASS=your-db-password
     ```
3. Render will provide these values when you create the database

### Step 4: Wait for Deployment
- Render will build your Docker image (takes 5-10 minutes)
- Once done, you'll get a URL like: `https://hp105-checklist.onrender.com`

### Step 5: Access Your Website
- Open the URL in your browser
- You should see your login page!
- The calendar viewer will be at: `https://hp105-checklist.onrender.com/calendar-viewer.html`

---

## Option 2: Railway.app (Alternative - FREE)

### Step 1: Create Account
1. Go to https://railway.app
2. Click "Start a New Project"
3. Sign up with **GitHub**

### Step 2: Deploy
1. Click **"New Project"** → **"Deploy from GitHub repo"**
2. Select: **`HP105-checklist`**
3. Railway will auto-detect your `railway.json` and deploy

### Step 3: Add Database
1. In your project, click **"+ New"** → **"Database"** → **"Add MySQL"**
2. Railway will create a MySQL database automatically
3. Your `config.php` will automatically use Railway's environment variables

### Step 4: Access
- Railway will give you a URL like: `https://hp105-checklist.up.railway.app`

---

## Important Notes

### Database Setup
After deployment, you need to:
1. Import your database schema
2. Go to your database service in Render/Railway
3. Use the database console or import `database/schema.sql`

### Environment Variables
Your `api/config.php` already supports environment variables, so it will work automatically with Render/Railway!

### Free Tier Limits
- **Render**: Free tier has 750 hours/month (enough for testing)
- **Railway**: Free tier has $5 credit/month
- Both may sleep after inactivity (wakes up on first request)

---

## After Deployment

1. **Test the login page**: `https://your-url.com/login.html`
2. **Test the viewer page**: `https://your-url.com/calendar-viewer.html`
3. **Share the viewer link** with anyone - they can view without login!

---

## Need Help?
If you get stuck, tell me:
- Which platform you're using (Render or Railway)
- What error message you see
- What step you're on

