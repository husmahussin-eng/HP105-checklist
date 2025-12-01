@echo off
echo ========================================
echo RBPF Checklist - XAMPP MySQL Setup
echo ========================================
echo.
echo This will create the database and tables in your XAMPP MySQL
echo.
pause

cd /d "C:\xampp\mysql\bin"
mysql.exe -u root -p < "C:\xampp\htdocs\HP105 checklist\database\schema.sql"

if %errorlevel%==0 (
    echo.
    echo ========================================
    echo SUCCESS! Database created successfully!
    echo ========================================
    echo.
    echo You can now access your checklist at:
    echo http://localhost:8080
    echo.
    echo Login with:
    echo Username: ASP Dk Husma
    echo Password: 531982
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR! Database setup failed
    echo ========================================
    echo Please check if XAMPP MySQL is running
    echo.
)

pause

