# Simple Guide: Push Your Code to GitHub

Your repository is already created: `husmahussin-eng/HP105-checklist`

## Easiest Method: Use GitHub Desktop (No Command Line!)

### Step 1: Download GitHub Desktop
1. Go to: https://desktop.github.com/
2. Click "Download for Windows"
3. Install it (just click Next, Next, Install)
4. Open GitHub Desktop

### Step 2: Sign In
1. Open GitHub Desktop
2. Sign in with your GitHub account (husmahussin-eng)

### Step 3: Add Your Project
1. In GitHub Desktop, click **"File"** → **"Add Local Repository"**
2. Click **"Choose..."** button
3. Navigate to: `C:\xampp\htdocs\HP105 checklist`
4. Click **"Add Repository"**

### Step 4: Publish to GitHub
1. At the top, you'll see "Publish repository" button
2. Click it
3. Make sure:
   - ✅ Repository name: `HP105-checklist`
   - ✅ ✅ Keep this code private (uncheck if you want it public)
   - ✅ Description: (optional) "HP-105 Checklist System"
4. Click **"Publish Repository"**

**DONE!** Your code is now on GitHub! 🎉

---

## Alternative: Command Line (If you prefer)

### Step 1: Install Git
1. Download: https://git-scm.com/download/win
2. Install (use all default settings)
3. Restart PowerShell

### Step 2: Open PowerShell in Your Project
1. Press `Windows Key + X`
2. Click "Windows PowerShell" or "Terminal"
3. Type: `cd "C:\xampp\htdocs\HP105 checklist"`
4. Press Enter

### Step 3: Push Your Code
Copy and paste these commands one by one:

```powershell
# Initialize Git
git init

# Add all files
git add .

# Create commit
git commit -m "Initial commit - HP105 Checklist System"

# Connect to your GitHub repository
git remote add origin https://github.com/husmahussin-eng/HP105-checklist.git

# Rename branch
git branch -M main

# Push to GitHub
git push -u origin main
```

**Note:** When asked for username, enter: `husmahussin-eng`
**Note:** When asked for password, use a **Personal Access Token** (not your password)

### How to Get Personal Access Token:
1. Go to: https://github.com/settings/tokens
2. Click "Generate new token" → "Generate new token (classic)"
3. Name: `HP105 Push`
4. Check: ✅ `repo` (full control)
5. Click "Generate token"
6. **Copy the token** (you won't see it again!)
7. Use this token as your password when pushing

---

## After Pushing - Verify

1. Go to: https://github.com/husmahussin-eng/HP105-checklist
2. You should see all your files:
   - ✅ login.html
   - ✅ calendar-viewer.html
   - ✅ Dockerfile
   - ✅ docker-compose.yml
   - ✅ api/ folder
   - ✅ js/ folder
   - ✅ etc.

---

## Troubleshooting

### "Git is not recognized"
→ Install Git: https://git-scm.com/download/win

### "Permission denied"
→ Use Personal Access Token instead of password

### "Repository not found"
→ Make sure repository name is exactly: `HP105-checklist`

### "Already exists" error
→ Run: `git remote remove origin` then try again

---

## Recommended: Use GitHub Desktop!

It's much easier and you don't need to remember commands. Just click buttons! 😊


