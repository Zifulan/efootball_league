
# Efootball Super League (PHP App)

This is a simple custom league management system for eFootball Mobile, built in PHP and ready to host on cPanel.

## 📂 Project Structure
- `index.php` — Public homepage with standings & fixtures
- `login.php` — Admin login (default: admin / 12345)
- `add_team.php` — Add new teams (admin only)
- `add_fixture.php` — Add match fixtures (admin only)
- `add_result.php` — Input final scores & auto-update standings (admin only)
- `match_history.php` — View logged match results
- `logout.php` — Ends admin session
- `style.css` — Basic styling
- `sample_database.sql` — MySQL structure (3 tables)

## 🛠 Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/cPanel compatible hosting

## 🧩 Setup Instructions
1. Upload contents to `/public_html/`
2. Import `sample_database.sql` via phpMyAdmin
3. Update DB credentials in each PHP file:
   ```php
   $host = 'localhost';
   $db   = 'your_db_name';
   $user = 'your_db_user';
   $pass = 'your_db_pass';
   ```
4. Login via `/login.php` (username: `admin`, password: `12345`)
5. Enjoy managing your custom league!

## 🧠 Notes
- Consider adding authentication salt/hash for production.
- You can customize charts, player stats, and more!

Created with ❤️ by ChatGPT + Fatahillah
