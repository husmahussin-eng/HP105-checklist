# RBPF 105th Birthday Checklist System - Docker Deployment

## 📋 Prerequisites

1. **Docker Desktop** installed on your computer
   - Download from: https://www.docker.com/products/docker-desktop
   - Make sure Docker Desktop is running

2. **ngrok** (optional, for external access)
   - Download from: https://ngrok.com/download
   - Create a free account at https://ngrok.com

## 🚀 Quick Start

### Option 1: Using Docker Compose (Recommended)

1. **Open Terminal/PowerShell in the project folder**
   ```bash
   cd "C:\xampp\htdocs\HP105 checklist"
   ```

2. **Build and start the container**
   ```bash
   docker-compose up -d
   ```

3. **Access the application**
   - Open browser: http://localhost:8080
   - Login with Super Admin credentials:
     - Username: `ASP Dk Husma`
     - Password: `531982`

4. **Stop the container**
   ```bash
   docker-compose down
   ```

### Option 2: Using Docker Commands

1. **Build the Docker image**
   ```bash
   docker build -t rbpf-checklist .
   ```

2. **Run the container**
   ```bash
   docker run -d -p 8080:80 --name rbpf-checklist-app rbpf-checklist
   ```

3. **Access the application**
   - Open browser: http://localhost:8080

4. **Stop and remove the container**
   ```bash
   docker stop rbpf-checklist-app
   docker rm rbpf-checklist-app
   ```

## 🌐 Using with ngrok

### Setup ngrok

1. **Install and authenticate ngrok**
   ```bash
   ngrok config add-authtoken YOUR_AUTH_TOKEN
   ```

2. **Start ngrok tunnel (while Docker container is running)**
   ```bash
   ngrok http 8080
   ```

3. **Share the URL**
   - ngrok will display a public URL like: `https://xxxx-xx-xx-xx-xx.ngrok-free.app`
   - Share this URL with your team
   - They can access the checklist system from anywhere!

### Example ngrok output:
```
Session Status                online
Account                       your-email@example.com
Version                       3.x.x
Region                        Asia Pacific (ap)
Latency                       -
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://xxxx-xx-xx-xx-xx.ngrok-free.app -> http://localhost:8080
```

## 🔧 Useful Docker Commands

### View running containers
```bash
docker ps
```

### View logs
```bash
docker-compose logs -f
```

### Rebuild after changes
```bash
docker-compose down
docker-compose up -d --build
```

### Remove everything (fresh start)
```bash
docker-compose down
docker rmi rbpf-checklist
docker-compose up -d --build
```

## 📁 Project Structure

```
HP105 checklist/
├── Dockerfile                    # Docker build instructions
├── docker-compose.yml            # Docker Compose configuration
├── .dockerignore                 # Files to exclude from Docker
├── index.html                    # Main checklist page
├── login.html                    # Login page
├── activity.html                 # Activity log page
├── backup.html                   # Backup manager page
├── create-user.html              # User creation page
├── users.html                    # User list page
├── perbarisan.html               # Perbarisan activity page
├── makan-beradat.html            # Makan Beradat activity page
├── yassin-tahlil.html            # Yassin Tahlil activity page
├── khutbah-jumaat.html           # Khutbah Jumaat activity page
├── pameran.html                  # Pameran activity page
├── aktiviti-daerah.html          # Aktiviti Daerah page
├── aktiviti-tambahan.html        # Aktiviti Tambahan page
└── rbpfnew.jpg                   # RBPF logo image
```

## 🔐 Security Notes

⚠️ **Important:** 
- This setup uses browser localStorage for data persistence
- Data is stored locally in each user's browser
- For production use, consider implementing a proper backend database
- When using ngrok, anyone with the link can access the system
- Use strong passwords and keep ngrok URLs private

## 💡 Tips

1. **Development Mode**: The docker-compose.yml includes volume mounts, so you can edit HTML files and refresh the browser to see changes without rebuilding.

2. **Port Already in Use**: If port 8080 is already in use, edit `docker-compose.yml` and change `"8080:80"` to another port like `"3000:80"`, then access via `http://localhost:3000`

3. **ngrok Free Tier**: Free ngrok tunnels expire after 2 hours. Just restart ngrok to get a new URL.

4. **Custom Domain**: Upgrade ngrok to get a permanent custom domain.

## 🆘 Troubleshooting

### Container won't start
```bash
# Check logs
docker-compose logs

# Restart Docker Desktop
# Then try again
docker-compose up -d
```

### Can't access localhost:8080
- Make sure Docker container is running: `docker ps`
- Check if port is already in use
- Try accessing http://127.0.0.1:8080 instead

### ngrok tunnel fails
- Make sure Docker container is running first
- Check if you've authenticated ngrok
- Try a different port

## 📞 Support

For issues or questions about:
- Docker: https://docs.docker.com
- ngrok: https://ngrok.com/docs

---

**Created by:** DKH  
**System:** RBPF 105th Birthday Checklist  
**Version:** 1.0

