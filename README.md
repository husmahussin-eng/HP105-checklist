# HP-105 Checklist System

A comprehensive checklist and calendar management system for the Royal Brunei Police Force 105th Anniversary celebration.

## Features

- 📅 **Calendar Management** - View and manage events for December 2025 and January 2026
- 👥 **User Management** - Role-based access control (Super Admin / Admin)
- 📋 **Activity Checklist** - Track activities by category and committee member
- 📝 **Meeting Minutes** - Record and manage meeting notes
- 💰 **Budget Management** - Track expenses and budgets
- 🔐 **Secure Authentication** - Login system with session management
- 👁️ **Public Viewer** - Calendar view accessible without login

## Tech Stack

- **Frontend**: HTML, CSS (Tailwind), JavaScript
- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Containerization**: Docker & Docker Compose

## Quick Start (Local Development)

### Prerequisites
- Docker Desktop installed and running
- Git (optional, for version control)

### Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/hp105-checklist.git
   cd hp105-checklist
   ```

2. **Start with Docker Compose**:
   ```bash
   docker-compose up -d
   ```

3. **Initialize Database**:
   - Access MySQL: `localhost:3308`
   - Import `database/schema.sql`

4. **Access the application**:
   - Login page: http://localhost:8080/login.html
   - Public calendar: http://localhost:8080/calendar-viewer.html

### Default Credentials
- Check your database for initial admin user
- Or create one using the create-user page (requires super admin)

## Project Structure

```
├── login.html              # Login page with "View Only" option
├── calendar-viewer.html   # Public calendar (no login required)
├── index.html             # Main dashboard (requires login)
├── api/                   # PHP backend APIs
│   ├── config.php         # Database configuration
│   ├── calendar-events.php # Calendar API
│   ├── login.php          # Authentication API
│   └── ...
├── js/
│   └── api-client.js      # Frontend API client
├── database/
│   └── schema.sql         # Database schema
├── Dockerfile             # Docker configuration
└── docker-compose.yml     # Docker Compose setup
```

## Docker Services

- **rbpf-checklist**: PHP Apache web server (port 8080)
- **mysql**: MySQL 8.0 database (port 3308)

## Deployment

See deployment guides:
- `GITHUB_SETUP_GUIDE.md` - How to push to GitHub
- `DEPLOYMENT_GUIDE.md` - Detailed deployment instructions
- `QUICK_START.md` - 5-minute deployment guide

### Recommended Platforms
- **Railway** - Easiest, auto-detects Docker
- **Render** - Good alternative
- **Fly.io** - Fast deployment

## Public Access

The calendar viewer is accessible without login:
- Visit: `http://localhost:8080/login.html`
- Click "View Only" button
- Calendar loads events from database (read-only)

## Development

### Running Locally
```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Rebuild containers
docker-compose up -d --build
```

### Database Access
- Host: `localhost`
- Port: `3308`
- Database: `rbpf_checklist`
- User: `rbpf_user`
- Password: `rbpf_password_2026`

## Environment Variables

For production deployment, set these:
- `DB_HOST` - Database host
- `DB_NAME` - Database name
- `DB_USER` - Database user
- `DB_PASS` - Database password

The `api/config.php` automatically uses these if available.

## Security Notes

- Change default passwords in production
- Use environment variables for sensitive data
- Enable HTTPS in production
- Review CORS settings for production

## License

© 2026 Royal Brunei Police Force (RBPF)

## Support

For issues or questions:
1. Check the troubleshooting guides
2. Review Docker logs: `docker-compose logs`
3. Check browser console for frontend errors
4. Verify database connection

---

**Built with ❤️ for RBPF HP-105 Celebration**


