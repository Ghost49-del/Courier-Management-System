@echo off
echo Setting up database for the web app...

REM Replace these with your MySQL credentials
set MYSQL_USER=root
set MYSQL_PASSWORD=""

REM Path to your MySQL installation
set MYSQL_PATH="C:\wamp64\bin\mysql\mysql8.3.0\bin\mysql"

REM Run the SQL script
%MYSQL_PATH% -u %MYSQL_USER% -p%MYSQL_PASSWORD% < cms_setup.sql

echo Database setup complete!
pause
