# ETRE Portal & Mobile Application

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B%20%7C%208.x-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B%20%7C%208.x-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Capacitor](https://img.shields.io/badge/Capacitor-5.x-119EFF?logo=capacitor&logoColor=white)](https://capacitorjs.com/)
[![Android](https://img.shields.io/badge/Android-Gradle-3DDC84?logo=android&logoColor=white)](https://developer.android.com/)

A comprehensive feedback collection portal featuring a modern **PHP/MySQL Web Application** with an admin management dashboard and an **Android Native Application Wrapper** built with Capacitor.

---

## 📁 Repository Structure

```text
ETRE PORTAL/
├── 1web/                      # PHP Web Application (Frontend & Backend)
│   ├── admin/                 # Admin Panel (Dashboard, Login, Admin Registration)
│   │   ├── dashboard.php      # Feedback review, approve/reject/delete interface
│   │   ├── login.php          # Admin authentication page
│   │   └── register.php       # Create new admin accounts
│   ├── assets/                # Stylesheets, images, and upload storage
│   │   ├── css/               # Modular CSS (style.css, admin.css)
│   │   ├── images/            # Static brand assets
│   │   └── uploads/           # User upload directories (photos/ & videos/)
│   ├── includes/              # Shared PHP modules & utility functions
│   │   ├── auth.php           # Session & permission control
│   │   ├── config.php         # Database configuration & environment resolution
│   │   └── functions.php      # File upload handling & sanitization helpers
│   ├── index.php              # Public user feedback submission portal
│   ├── submit-feedback.php    # Form handling script (Upload validation & DB insertion)
│   └── thankyou.php           # Submission confirmation screen
├── etreapp/
│   └── android-wrapper/       # Capacitor Android Project
│       ├── android/           # Native Android Studio Project (Gradle)
│       ├── capacitor.config.ts# Capacitor configuration file
│       └── package.json       # Node package manifest
├── database.sql               # Complete MySQL database schema import
├── .gitignore                 # Preconfigured Git ignore definitions
├── LICENSE                    # MIT Open Source License
└── README.md                  # Project documentation
```

---

## ✨ Features

- 📝 **Public Feedback Portal**: Secure user submission form supporting text comments, photo attachments (JPEG, PNG, GIF up to 5MB), and video attachments (MP4, WebM up to 5MB).
- 🛡️ **Security & Validation**: Built-in XSS protection, input sanitization, file extension checking, and MIME-type verification.
- 🔐 **Admin Management Dashboard**:
  - Secure authentication via BCrypt password hashing.
  - Approve, reject, or delete submitted feedback entries.
  - Automatic media cleanup upon feedback deletion.
  - Admin account registration portal.
- 📱 **Mobile Native Android App**: Capacitor wrapper converting the web portal into an Android app (`com.etrefeedback.app`).

---

## 🚀 Quick Start & Installation

### Prerequisites

- **Web Server**: Apache / Nginx with PHP 7.4+ or 8.x
- **Database**: MySQL 5.7+ or MariaDB
- **PHP Extensions**: `pdo`, `pdo_mysql`, `fileinfo`
- **Mobile Development** *(Optional)*: Node.js (v16+), Android Studio, Android SDK

---

### 1. Database Setup

1. Open your database administration tool (e.g., phpMyAdmin, MySQL Workbench, or CLI).
2. Import the `database.sql` script into your database server:

```bash
mysql -u root -p < database.sql
```

This creates the `b7_39643211_etre_feedback` database alongside the `admins` and `feedback` tables with all necessary foreign keys and indexes.

---

### 2. Web Application Setup (`1web`)

1. Copy or deploy the `1web/` directory to your web server root (e.g., `htdocs`, `www`, or `/var/www/html/`).
2. Configure database credentials. You can set Environment Variables on your server or update `1web/includes/config.php`:

| Parameter | Environment Variable | Default Value |
| :--- | :--- | :--- |
| Database Host | `DB_HOST` | `localhost` |
| Database User | `DB_USER` | `root` |
| Database Password | `DB_PASS` | `""` *(empty)* |
| Database Name | `DB_NAME` | `b7_39643211_etre_feedback` |

3. Ensure write permissions for media uploads:

```bash
chmod -R 755 1web/assets/uploads/photos
chmod -R 755 1web/assets/uploads/videos
```

4. Verify database connection by navigating to:
   `http://localhost/1web/test_connection.php`

---

### 3. Android Mobile Application Setup (`etreapp/android-wrapper`)

1. Navigate to the Android wrapper directory:

```bash
cd etreapp/android-wrapper
```

2. Install Node dependencies:

```bash
npm install
```

3. Configure your hosted backend domain in `capacitor.config.ts`:

```typescript
const config: CapacitorConfig = {
  appId: 'com.etrefeedback.app',
  appName: 'Etre Feedback',
  webDir: 'dist',
  server: {
    url: 'https://your-hosted-domain.com', // Replace with your live web app URL
    cleartext: false
  }
};
```

4. Sync Capacitor changes to the Android project and open in Android Studio:

```bash
npx cap sync android
npx cap open android
```

5. Build or run the project in Android Studio to create the APK / AAB.

---

## 🔒 Security Best Practices

- Always update the default admin password after initial database setup.
- Ensure `config.php` credentials are not publicly exposed.
- Set `display_errors = 0` in production `php.ini`.

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
