# PowerShell Script to Setup GitHub Repository
# Run this script in PowerShell: .\setup-github.ps1

Write-Host "=== HP-105 Checklist - GitHub Setup ===" -ForegroundColor Cyan
Write-Host ""

# Check if Git is installed
try {
    $gitVersion = git --version
    Write-Host "✓ Git is installed: $gitVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Git is NOT installed!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install Git first:" -ForegroundColor Yellow
    Write-Host "1. Download from: https://git-scm.com/download/win" -ForegroundColor Yellow
    Write-Host "2. Or use GitHub Desktop: https://desktop.github.com/" -ForegroundColor Yellow
    Write-Host ""
    exit 1
}

Write-Host ""
Write-Host "Step 1: Initialize Git repository..." -ForegroundColor Cyan

# Check if already a git repository
if (Test-Path .git) {
    Write-Host "⚠ Git repository already exists" -ForegroundColor Yellow
    $continue = Read-Host "Continue anyway? (y/n)"
    if ($continue -ne "y") {
        exit 0
    }
} else {
    git init
    Write-Host "✓ Git repository initialized" -ForegroundColor Green
}

Write-Host ""
Write-Host "Step 2: Adding files..." -ForegroundColor Cyan
git add .
Write-Host "✓ Files added" -ForegroundColor Green

Write-Host ""
Write-Host "Step 3: Creating initial commit..." -ForegroundColor Cyan
git commit -m "Initial commit - HP105 Checklist System"
Write-Host "✓ Initial commit created" -ForegroundColor Green

Write-Host ""
Write-Host "Step 4: Setting up GitHub remote..." -ForegroundColor Cyan
Write-Host ""
$githubUsername = Read-Host "Enter your GitHub username"
$repoName = Read-Host "Enter repository name (default: hp105-checklist)"
if ([string]::IsNullOrWhiteSpace($repoName)) {
    $repoName = "hp105-checklist"
}

$repoUrl = "https://github.com/$githubUsername/$repoName.git"
Write-Host ""
Write-Host "Repository URL: $repoUrl" -ForegroundColor Yellow
Write-Host ""
$confirm = Read-Host "Make sure you've created this repository on GitHub.com. Continue? (y/n)"
if ($confirm -ne "y") {
    Write-Host "Please create the repository on GitHub first, then run this script again." -ForegroundColor Yellow
    exit 0
}

# Check if remote already exists
$existingRemote = git remote get-url origin 2>$null
if ($existingRemote) {
    Write-Host "⚠ Remote 'origin' already exists: $existingRemote" -ForegroundColor Yellow
    $update = Read-Host "Update to new URL? (y/n)"
    if ($update -eq "y") {
        git remote set-url origin $repoUrl
        Write-Host "✓ Remote updated" -ForegroundColor Green
    }
} else {
    git remote add origin $repoUrl
    Write-Host "✓ Remote added" -ForegroundColor Green
}

Write-Host ""
Write-Host "Step 5: Renaming branch to 'main'..." -ForegroundColor Cyan
git branch -M main
Write-Host "✓ Branch renamed to 'main'" -ForegroundColor Green

Write-Host ""
Write-Host "Step 6: Ready to push!" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Make sure you have a Personal Access Token ready" -ForegroundColor Yellow
Write-Host "   (Get it from: https://github.com/settings/tokens)" -ForegroundColor Yellow
Write-Host "2. Run: git push -u origin main" -ForegroundColor Yellow
Write-Host "3. When asked for password, use your Personal Access Token" -ForegroundColor Yellow
Write-Host ""
$pushNow = Read-Host "Push to GitHub now? (y/n)"
if ($pushNow -eq "y") {
    Write-Host ""
    Write-Host "Pushing to GitHub..." -ForegroundColor Cyan
    Write-Host "Note: You'll be asked for username and password (use Personal Access Token)" -ForegroundColor Yellow
    git push -u origin main
    Write-Host ""
    Write-Host "✓ Done! Check your repository at: https://github.com/$githubUsername/$repoName" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "To push later, run: git push -u origin main" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== Setup Complete ===" -ForegroundColor Cyan

