# GitHub Setup Guide - Docker Desktop

This guide will help you push your HP-105 Checklist System to GitHub and deploy it.

---

## Step 1: Install Git

### Option A: Install Git for Windows
1. Download Git: https://git-scm.com/download/win
2. Run the installer
3. Use default settings (recommended)
4. Restart your terminal/PowerShell after installation

### Option B: Use GitHub Desktop (Easier - GUI)
1. Download: https://desktop.github.com/
2. Install and sign in with your GitHub account
3. Much easier to use than command line!

---

## Step 2: Create GitHub Repository

### Using GitHub Website:
1. Go to: https://github.com/new
2. Repository name: `hp105-checklist` (or any name you like)
3. Description: "HP-105 Checklist System with Docker"
4. Choose: **Public** (free) or **Private**
5. **DO NOT** check "Initialize with README"
6. Click "Create repository"

### Copy the repository URL:
- You'll see: `https://github.com/YOUR_USERNAME/hp105-checklist.git`
- Save this URL for later

---

## Step 3: Push Code to GitHub

### Method A: Using Command Line (Git)

Open PowerShell in your project folder and run:

```powershell
# Navigate to your project (if not already there)
cd "C:\xampp\htdocs\HP105 checklist"

# Initialize Git repository
git init

# Add all files
git add .

# Create first commit
git commit -m "Initial commit - HP105 Checklist System"

# Add GitHub remote (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/hp105-checklist.git

# Rename branch to main
git branch -M main

# Push to GitHub
git push -u origin main
```

**Note:** You'll be asked for GitHub username and password (use Personal Access Token, not password)

### Method B: Using GitHub Desktop (Easier!)

1. Open GitHub Desktop
2. Click "File" → "Add Local Repository"
3. Browse to: `C:\xampp\htdocs\HP105 checklist`
4. Click "Create a Repository"
5. Name: `hp105-checklist`
6. Click "Create Repository"
7. Click "Publish repository" (top right)
8. Choose your GitHub account
9. Click "Publish Repository"

**Done!** Your code is now on GitHub!

---

## Step 4: Create GitHub Personal Access Token (For Command Line)

If using command line, you need a token instead of password:

1. Go to: https://github.com/settings/tokens
2. Click "Generate new token" → "Generate new token (classic)"
3. Name: `HP105 Deployment`
4. Select scopes:
   - ✅ `repo` (full control)
5. Click "Generate token"
6. **Copy the token immediately** (you won't see it again!)
7. Use this token as your password when pushing

---

## Step 5: Verify Your Files on GitHub

1. Go to: `https://github.com/YOUR_USERNAME/hp105-checklist`
2. You should see all your files:
   - ✅ `login.html`
   - ✅ `calendar-viewer.html`
   - ✅ `Dockerfile`
   - ✅ `docker-compose.yml`
   - ✅ `api/` folder
   - ✅ `js/` folder
   - ✅ etc.

---

## Step 6: Test Your Docker Setup Locally

Before deploying, test locally:

```powershell
# Make sure Docker Desktop is running
# Navigate to your project
cd "C:\xampp\htdocs\HP105 checklist"

# Build and start containers
docker-compose up -d

# Check if running
docker-compose ps

# View logs
docker-compose logs

# Stop when done
docker-compose down
```

Your app should be at: `http://localhost:8080`

---

## Step 7: Deploy to Cloud (Optional)

Now that your code is on GitHub, you can deploy:

### Option 1: Railway (Easiest - Recommended)
1. Go to: https://railway.app
2. Sign up with GitHub
3. "New Project" → "Deploy from GitHub repo"
4. Select your `hp105-checklist` repository
5. Railway will auto-detect Dockerfile and deploy!

### Option 2: Render
1. Go to: https://render.com
2. Sign up with GitHub
3. "New" → "Web Service"
4. Connect your GitHub repo
5. Render will use your Dockerfile

### Option 3: Fly.io
1. Go to: https://fly.io
2. Install Fly CLI
3. Run: `fly launch`
4. Follow prompts

---

## Important Files Checklist

Make sure these are in your GitHub repo:

✅ `Dockerfile` - Docker configuration
✅ `docker-compose.yml` - Local development setup
✅ `api/config.php` - Database config (supports env vars)
✅ `login.html` - Login page
✅ `calendar-viewer.html` - Public calendar
✅ `js/api-client.js` - Frontend API client
✅ `database/schema.sql` - Database schema
✅ `.gitignore` - Excludes sensitive files

---

## Troubleshooting

### "git is not recognized"
- Install Git: https://git-scm.com/download/win
- Or use GitHub Desktop instead

### "Permission denied" when pushing
- Use Personal Access Token instead of password
- See Step 4 above

### "Repository not found"
- Check repository name is correct
- Make sure repository exists on GitHub
- Verify you have access

### Docker not working
- Make sure Docker Desktop is running
- Check: `docker --version` in PowerShell
- Restart Docker Desktop if needed

---

## Next Steps After GitHub Setup

1. ✅ Code is on GitHub
2. ✅ Test locally with Docker Desktop
3. ✅ Deploy to Railway/Render (optional)
4. ✅ Share GitHub repo with team
5. ✅ Set up CI/CD (optional)

---

## Quick Reference Commands

```powershell
# Check Git status
git status

# Add files
git add .

# Commit changes
git commit -m "Your message here"

# Push to GitHub
git push

# Pull latest changes
git pull

# View commit history
git log
```

---

## Need Help?

- Git Documentation: https://git-scm.com/doc
- GitHub Docs: https://docs.github.com
- Docker Docs: https://docs.docker.com
- Railway Docs: https://docs.railway.app

---

## Summary

1. ✅ Install Git or GitHub Desktop
2. ✅ Create GitHub repository
3. ✅ Push your code
4. ✅ Verify files on GitHub
5. ✅ Test with Docker Desktop
6. ✅ Deploy to cloud (optional)

Your code is now safely stored on GitHub and ready to deploy! 🚀

