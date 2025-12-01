# RBPF Checklist System - Database Backend

## 🎯 Overview

This system now uses a **MySQL database backend** with **PHP APIs** to enable multi-device access. All users, activity logs, and notes are stored in a centralized database instead of browser localStorage.

## ✨ Key Features

- ✅ **Multi-device login** - Create user on laptop, login from mobile
- ✅ **Shared activity logs** - All devices see the same activity history
- ✅ **Centralized user management** - Users stored in database
- ✅ **Real-time sync** - Notes and activities sync across devices
- ✅ **Super Admin access** - ASP Dk Husma has full system access

## 🚀 Setup Instructions

### Prerequisites
- Docker Desktop installed
- Docker Compose installed

### Step 1: Stop existing containers
```bash
docker stop rbpf-checklist-app
docker rm rbpf-checklist-app
```

### Step 2: Start the new system
```bash
docker-compose down
docker-compose up -d --build
```

This will start:
- **MySQL Database** (port 3306)
- **PHP Apache Web Server** (port 8080)

### Step 3: Wait for database initialization
```bash
docker-compose logs -f mysql
```

Wait until you see: `ready for connections`

### Step 4: Access the system
- Local: `http://localhost:8080`
- Remote (ngrok): `ngrok http 8080`

## 👤 Default Login Credentials

**Super Admin:**
- Username: `ASP Dk Husma`
- Password: `531982`

## 📊 Database Structure

### Tables Created:
1. **users** - All registered users
2. **activity_log** - Login and system activities
3. **notes** - Notes from activity pages
4. **backups** - System backups

## 🔧 API Endpoints

All APIs are located in `/api/` directory:

### Authentication
- `POST /api/login.php` - User login

### Users
- `GET /api/users.php` - Get all users
- `POST /api/users.php` - Create new user
- `PUT /api/users.php` - Reset user password

### Activity Log
- `GET /api/activity.php` - Get activity logs
- `POST /api/activity.php` - Log new activity

### Notes
- `GET /api/notes.php?activity_page=perbarisan` - Get notes for page
- `POST /api/notes.php` - Create note
- `PUT /api/notes.php` - Update note
- `DELETE /api/notes.php` - Delete note

### Backup
- `GET /api/backup.php` - Get all backups
- `POST /api/backup.php?action=create` - Create backup
- `POST /api/backup.php?action=download` - Download backup
- `POST /api/backup.php?action=restore` - Restore backup

## 📱 Multi-Device Usage

### Scenario 1: Create user on laptop, login from mobile

**On Laptop:**
1. Login as super admin (ASP Dk Husma)
2. Click username → "Create New User"
3. Create user: DSP Dennie / password: 123456

**On Mobile (via ngrok):**
1. Open ngrok URL
2. Login with: DSP Dennie / 123456
3. ✅ It works! User is in database

### Scenario 2: View activity logs from any device

**Any Device:**
1. Login as super admin
2. Click username → "Activity"
3. ✅ See ALL login activities from ALL devices

## 🔍 Troubleshooting

### Database connection failed
```bash
# Check MySQL is running
docker ps

# Check MySQL logs
docker logs rbpf-mysql

# Restart containers
docker-compose restart
```

### Cannot login
```bash
# Check API logs
docker logs rbpf-checklist-app

# Verify database has super admin
docker exec -it rbpf-mysql mysql -u rbpf_user -prbpf_password_2026 rbpf_checklist -e "SELECT * FROM users;"
```

### Port conflicts
If port 8080 or 3306 is already in use:

Edit `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Change 8080 to 8081
```

## 🗄️ Database Access

### Direct MySQL Access
```bash
docker exec -it rbpf-mysql mysql -u rbpf_user -prbpf_password_2026 rbpf_checklist
```

### View all users
```sql
SELECT id, full_name, username, role, created_at FROM users;
```

### View recent logins
```sql
SELECT username, action, timestamp FROM activity_log WHERE type='login' ORDER BY timestamp DESC LIMIT 10;
```

## 📦 Backup & Restore

### Manual Database Backup
```bash
docker exec rbpf-mysql mysqldump -u rbpf_user -prbpf_password_2026 rbpf_checklist > backup_$(date +%Y%m%d).sql
```

### Manual Database Restore
```bash
docker exec -i rbpf-mysql mysql -u rbpf_user -prbpf_password_2026 rbpf_checklist < backup_20261118.sql
```

## 🔒 Security Notes

**Important:** For production use:
1. Change default passwords in `docker-compose.yml`
2. Use environment variables for sensitive data
3. Enable HTTPS with SSL certificates
4. Implement proper password hashing (bcrypt)
5. Add rate limiting to prevent brute force attacks

## 📝 Development

### File Structure
```
.
├── api/                    # PHP Backend
│   ├── config.php         # Database config
│   ├── login.php          # Login endpoint
│   ├── users.php          # Users CRUD
│   ├── activity.php       # Activity logging
│   ├── notes.php          # Notes CRUD
│   └── backup.php         # Backup management
├── database/
│   └── schema.sql         # Database schema
├── js/
│   └── api-client.js      # Frontend API client
├── *.html                 # Frontend pages
├── Dockerfile             # PHP Apache container
├── docker-compose.yml     # Multi-container setup
└── README-API.md          # This file
```

### Making Changes

1. Edit PHP files in `api/` directory
2. Edit HTML files in root directory
3. Changes are immediately reflected (volume mounted)
4. No need to rebuild unless changing Dockerfile

## 🎉 Success Indicators

✅ MySQL container running
✅ Web container running  
✅ Can access http://localhost:8080
✅ Can login as ASP Dk Husma
✅ Can create new users
✅ Activity log shows logins
✅ Mobile users can login with created accounts

## 📞 Support

For issues or questions, check:
1. Docker logs: `docker-compose logs`
2. MySQL logs: `docker logs rbpf-mysql`
3. Apache logs: `docker logs rbpf-checklist-app`

