# Kedia Builders & Colonizers — cPanel Production Deployment Guide

This guide provides step-by-step instructions to deploy the Kedia Builders Laravel 11 application to any cPanel shared hosting or VPS environment.

---

## 📋 Prerequisites
- **cPanel Account** with File Manager and phpMyAdmin access.
- **PHP Version**: `8.2` or `8.3` (Set via cPanel **Select PHP Version** or **MultiPHP Manager**).
- **Required PHP Extensions**: `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl` (standard on all cPanel hosts).

---

## 🚀 Deployment Options

You can deploy using either of two methods:

### Method A: Single-Folder Upload into `public_html` (Easiest)
Upload the entire repository directly inside `public_html/`. The included root `.htaccess` will automatically rewrite traffic to `public/` while securing `.env` and internal system directories.

### Method B: Split Architecture (Best Security Practice)
1. Upload all project files into a folder outside the webroot: `~/kedia/` (e.g. `/home/username/kedia/`).
2. Move the contents of `public/*` into `~/public_html/`.
3. The modified `public/index.php` will automatically detect the parent `../kedia/` folder without any manual edits.

---

## 🛠️ Step-by-Step Deployment Instructions

### Step 1: Create MySQL Database & User in cPanel
1. In cPanel, navigate to **MySQL Databases** (or **MySQL Database Wizard**).
2. Create a new database, e.g.: `cpusername_kediadb`.
3. Create a new user with a strong password, e.g.: `cpusername_kediausr`.
4. Add the user to the database and check **ALL PRIVILEGES**.
5. Save the database name, username, and password.

---

### Step 2: Import the Database Schema
1. In cPanel, open **phpMyAdmin**.
2. Select your newly created database (`cpusername_kediadb`) from the left sidebar.
3. Click on the **Import** tab at the top.
4. Choose the file `database/cpanel_import.sql` from this project.
5. Click **Go** / **Import** at the bottom.
   > **Note:** The SQL dump contains all 16 signature projects, 120 cadastral units, migrations, and seed data. It does not contain any hardcoded database names, so it will import smoothly without permission errors.

---

### Step 3: Configure the `.env` File
1. In cPanel **File Manager**, ensure **Show Hidden Files (dotfiles)** is enabled in Settings (top right).
2. Copy `.env.cpanel.example` to `.env` in the project root.
3. Edit `.env` and configure:
   ```env
   APP_NAME="Kedia Builders & Colonizers"
   APP_ENV=production
   APP_KEY=base64:uH5h+j6B9lH8kK5rT3pQ2wE4yU7iO0pA1sD3fG5hJ7k=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=cpusername_kediadb
   DB_USERNAME=cpusername_kediausr
   DB_PASSWORD="your_password_here"
   ```

---

### Step 4: Upload the Project Files
1. Create a `.zip` file of this repository (excluding `node_modules` and `.git`).
2. In cPanel **File Manager**, upload the zip file to your server.
3. Right-click and **Extract** the zip file.
4. Set folder permissions (standard cPanel permissions):
   - Directories: `755`
   - Files: `644`
   - `storage/` and `bootstrap/cache/`: `775` (writable)

---

### Step 5: Run Diagnostics & One-Click Setup
1. In your web browser, navigate to:
   ```
   https://yourdomain.com/cpanel-setup?key=kedia2026_cpanel_setup
   ```
2. Verify all diagnostics:
   - **Database Status**: CONNECTED (16 projects, 120 units detected).
   - **Storage Writable**: WRITABLE for all paths.
   - **PHP Extensions**: All required extensions verified.
3. Click the one-click action buttons:
   - **🚀 Compile Caches**: Caches configuration, routes, and views for blazing-fast shared hosting performance.
   - **🔗 Storage Symlink**: Links `storage/app/public` to `public/storage` (or creates a fallback mirror if your host disables the PHP `symlink` function).

---

## 🔒 Security Best Practices on cPanel
- **Root `.htaccess`**: Automatically blocks public access to `.env`, `.git`, `composer.json`, `artisan`, and internal folders.
- **Admin Command Center**: Access at `https://yourdomain.com/admin` (Protected by HTTP Basic Auth: `admin` / `kedia2026`).
- **Setup Key**: You can change `CPANEL_SETUP_KEY` in `.env` or delete `app/Http/Controllers/CpanelSetupController.php` once your initial deployment is finished.

---

## ❓ Frequently Asked Questions & Troubleshooting

| Issue | Cause | Solution |
| :--- | :--- | :--- |
| **404 Not Found on inner routes** | Apache `mod_rewrite` missing | Ensure `public/.htaccess` is present and uploaded. |
| **500 Server Error** | Missing `.env` or PHP < 8.2 | Switch to PHP 8.2/8.3 in cPanel **MultiPHP Manager** and check `.env` syntax. |
| **Permission Denied in storage** | Permissions on `storage/` | Set `chmod -R 775 storage bootstrap/cache` in cPanel File Manager or Terminal. |
| **Database Connection Error** | Wrong DB credentials | On cPanel, `DB_HOST` is almost always `localhost` (not `127.0.0.1`). Double-check database prefix. |
