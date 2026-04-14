# FarmDialogue - Local Development Setup Guide

## Quick Start (Windows)

### Option 1: Using XAMPP (Recommended - Easiest)

#### Step 1: Install XAMPP
1. Download XAMPP from: https://www.apachefriends.org/
2. Choose **XAMPP 8.0+** (includes Apache, MySQL, PHP)
3. Install to default location: `C:\xampp`
4. Run XAMPP Control Panel

#### Step 2: Start Services
1. Open XAMPP Control Panel
2. Click **Start** next to Apache
3. Click **Start** next to MySQL
4. You should see green checkmarks ✅

#### Step 3: Setup Project
1. Copy the `farmdialogue` folder to:
   ```
   C:\xampp\htdocs\farmdialogue
   ```

#### Step 4: Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **New** on the left sidebar
3. Enter database name: `farmdialogue`
4. Click **Create**
5. Once created, click the database name
6. Go to **Import** tab
7. Click **Choose File** and select:
   ```
   C:\xampp\htdocs\farmdialogue\database\schema.sql
   ```
8. Click **Import** (wait for success message)

#### Step 5: Access Application
Open your browser and visit:
```
http://localhost/farmdialogue/front/
```

That's it! You should now see the FarmDialogue homepage. 🎉

---

### Option 2: Using Docker (For Advanced Users)

If you have Docker installed, create a `docker-compose.yml` in the project root:

```yaml
version: '3.8'

services:
  web:
    image: php:8.0-apache
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
    environment:
      - MYSQL_HOST=db
      - MYSQL_DB=farmdialogue
      - MYSQL_USER=root
      - MYSQL_PASSWORD=root

  db:
    image: mysql:5.7
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=farmdialogue
    ports:
      - "3306:3306"
    volumes:
      - ./database/schema.sql:/docker-entrypoint-initdb.d/schema.sql
```

Then run:
```bash
docker-compose up -d
```

Access at: `http://localhost/farmdialogue/front/`

---

### Option 3: Using WAMP (Alternative)

Similar to XAMPP:
1. Download WAMP from: https://www.wampserver.com/
2. Install and start services
3. Copy project to `C:\wamp64\www\farmdialogue`
4. Setup database via phpMyAdmin at `http://localhost/phpmyadmin`
5. Access at `http://localhost/farmdialogue/front/`

---

## Troubleshooting

### Port 80 Already in Use
- Change Apache port in XAMPP Control Panel Config
- Then access at: `http://localhost:8080/farmdialogue/front/`

### Database Connection Error
- Verify MySQL is running (green checkmark in XAMPP)
- Check `back/config.php` has correct credentials:
  - Host: `localhost`
  - User: `root`
  - Password: `` (empty for default XAMPP)
  - Database: `farmdialogue`

### Cannot Find `localhost/phpmyadmin`
- Make sure Apache is running
- Refresh the page or clear browser cache

### Import SQL Error
- Database must be selected/created first
- File path must be correct
- MySQL must be running

---

## Recommended Setup Order

1. ✅ Install XAMPP
2. ✅ Start Apache & MySQL
3. ✅ Copy project folder
4. ✅ Create database
5. ✅ Import schema
6. ✅ Open in browser
7. ✅ Test login/register

---

## What to Test First

1. **Homepage:** `http://localhost/farmdialogue/front/`
   - Should see green & blue gradient design
   - Navigation works

2. **Products Page:** Click "Products" in nav
   - Shows sample products with filters

3. **Learning Hub:** Click "Learn" in nav
   - Shows learning resources with categories

4. **Register:** Click "Get Started"
   - Fill form and test registration

5. **Login:** After registering, test login

6. **Dashboard:** After login, see dashboard

---

## Common Errors & Solutions

| Error | Solution |
|-------|----------|
| PHP not found | Install XAMPP or check PATH |
| MySQL not connecting | Start MySQL in XAMPP Control Panel |
| Port 80 in use | Change Apache port or close other services |
| Blank page | Enable error reporting or check Apache logs |
| CSS not loading | Clear browser cache (Ctrl+Shift+Delete) |

---

## Next Steps After Setup

1. Test the frontend pages
2. Check browser console for JavaScript errors
3. Test API endpoints with Postman
4. Review database structure
5. Start implementing backend logic

---

**Need Help?** Check the README.md in the project folder for more detailed information.
