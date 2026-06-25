# DCK Solutions - Deployment & Security Checklist

## BEFORE GOING LIVE

### 1. Change ALL default passwords
- [ ] Superadmin password (admin@admin.com) - change from 123456 to a strong password
- [ ] Database password - change from empty to a strong password
- [ ] Update database.php with the new DB password

### 2. Database security
- [ ] Create a dedicated MySQL user (don't use 'root')
- [ ] Grant only necessary permissions (SELECT, INSERT, UPDATE, DELETE)
- [ ] Set a strong MySQL root password
- [ ] Disable remote MySQL access

### 3. File permissions (on Linux hosting)
```
chmod 755 /public_html/
chmod 644 /public_html/.htaccess
chmod 644 /public_html/index.php
chmod 750 /public_html/application/
chmod 640 /public_html/application/config/database.php
chmod 770 /public_html/uploads/
```

### 4. Remove sample/test files before uploading
- [ ] Delete kenya_migration.sql
- [ ] Delete sample_school_data.sql
- [ ] Delete sample_students_bulk.sql
- [ ] Delete sample_complete_school.sql
- [ ] Delete DCK_SCHOOL_SETUP_TEMPLATE.md
- [ ] Delete DCK_SALES_PRESENTATION.md
- [ ] Delete DCK_SOLUTIONS_USER_GUIDE.md
- [ ] Delete DEPLOYMENT_SECURITY_CHECKLIST.md
- [ ] Delete ramom.sql

### 5. Environment settings
- [ ] index.php: ENVIRONMENT = 'production' (already set)
- [ ] index.php: HTTPS redirect enabled (already set)
- [ ] php.ini: display_errors = Off
- [ ] php.ini: error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT

### 6. SSL Certificate
- [ ] Install SSL certificate (most hosts offer free Let's Encrypt)
- [ ] Verify https:// works
- [ ] M-Pesa callback URL must use https://

## HOSTING SETUP STEPS

### Option A: cPanel Hosting (Truehost, HostPinnacle, Sasahost)

1. Buy hosting plan + domain (e.g. dcksolutions.co.ke)
2. Login to cPanel
3. Create MySQL database: `dcksolutions_db`
4. Create MySQL user: `dcksolutions_user` with strong password
5. Assign user to database with ALL PRIVILEGES
6. Upload all files to `public_html/` via File Manager or FTP
7. Edit `application/config/database.php`:
   - hostname: localhost
   - username: dcksolutions_user
   - password: (your strong password)
   - database: dcksolutions_db
8. Import `ramom.sql` via phpMyAdmin (in cPanel)
9. Then import `kenya_migration.sql`
10. Install SSL certificate (cPanel > SSL/TLS > Let's Encrypt)
11. Set domain to point to hosting (update nameservers)
12. Test: https://dcksolutions.co.ke

### Option B: DigitalOcean VPS

1. Create Ubuntu 22.04 droplet ($6/month)
2. SSH into server
3. Install LAMP stack:
```bash
sudo apt update
sudo apt install apache2 mysql-server php8.1 php8.1-mysql php8.1-intl php8.1-gd php8.1-curl php8.1-mbstring php8.1-xml
```
4. Create database and user:
```bash
sudo mysql
CREATE DATABASE dcksolutions_db;
CREATE USER 'dcksolutions_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
GRANT ALL PRIVILEGES ON dcksolutions_db.* TO 'dcksolutions_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
5. Upload files to `/var/www/html/`
6. Import database:
```bash
mysql -u dcksolutions_user -p dcksolutions_db < ramom.sql
mysql -u dcksolutions_user -p dcksolutions_db < kenya_migration.sql
```
7. Set permissions:
```bash
sudo chown -R www-data:www-data /var/www/html/
sudo chmod -R 755 /var/www/html/
sudo chmod -R 770 /var/www/html/uploads/
```
8. Install SSL with Certbot:
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d dcksolutions.co.ke
```
9. Enable mod_rewrite:
```bash
sudo a2enmod rewrite headers
sudo systemctl restart apache2
```

## ONGOING SECURITY

### Daily
- System automatically backs up via cPanel (if enabled)

### Weekly
- [ ] Download database backup (Settings > Backup)
- [ ] Check error logs for unusual activity

### Monthly
- [ ] Review user accounts - disable unused ones
- [ ] Check subscription statuses
- [ ] Update any expired SSL certificates (auto-renewed with Let's Encrypt)

### Security best practices
- Never share superadmin credentials
- Each school admin should have their own unique password
- Use HTTPS always (already enforced in code)
- Keep PHP updated
- Regular database backups stored off-server
- Monitor server access logs for suspicious activity
