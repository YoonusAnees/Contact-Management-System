# 📌 Veloz Marketing -- PHP Contact Management System

Built using **PHP, MySQL, HTML, CSS, JavaScript, SweetAlert2**

------------------------------------------------------------------------

# 📖 Project Overview

This is a simple Contact Management System developed 

The system allows users to:

-   Submit inquiries through a responsive contact form
-   Validate form inputs using PHP
-   Store records securely in a MySQL database
-   Allow admin users to login and view submitted inquiries
-   View dashboard statistics such as total inquiries, today's
    inquiries, and unique emails

The system follows secure coding practices including:

-   PDO prepared statements
-   Password hashing
-   Session-based authentication
-   POST → Redirect → GET pattern (prevents form resubmission)

------------------------------------------------------------------------

# ⚙️ Technologies Used

-   PHP 8+
-   MySQL
-   HTML5
-   CSS3 (Responsive Design)
-   JavaScript
-   SweetAlert2
-   PDO (Database Access)
-   PHP Sessions (Authentication)

------------------------------------------------------------------------

# 📂 Project Structure

    project-root/
    │
    ├── index.php
    ├── db/
    │   └── db.php
    ├── validation/
    │   └── functions.php
    ├── config/
    │   └── auth.php
    ├── admin/
    │   ├── register.php
    │   ├── login.php
    │   ├── logout.php
    │   ├── dashboard.php
    │   └── inquiries.php
    ├── assets/
    │   └── css/
    │       └── style.css
    └── README.md

------------------------------------------------------------------------

# 🛠 Installation & Setup Guide

## Step 1 -- Requirements

-   XAMPP or WAMP installed
-   Apache running
-   MySQL running
-   PHP 8+ recommended

## Step 2 -- Move Project Folder

Place the project folder inside:

XAMPP: C:`\xampp`{=tex}`\htdocs`{=tex}\

WAMP: C:`\wamp64`{=tex}`\www`{=tex}\

## Step 3 -- Create Database

Open: http://localhost/phpmyadmin

Create database: veloz_contact

## Step 4 -- Create Tables

### Inquiries Table

``` sql
CREATE TABLE inquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL,
  phone VARCHAR(40),
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Admins Table

``` sql
CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

------------------------------------------------------------------------

# 🚀 How to Use the System

### Contact Form

http://localhost/your-project-folder/index.php

### Admin Registration (First Time Only)

http://localhost/your-project-folder/admin/register.php

### Admin Login

http://localhost/your-project-folder/admin/login.php

### Admin Dashboard

http://localhost/your-project-folder/admin/dashboard.php

------------------------------------------------------------------------

# 🔐 Security Features

-   PDO prepared statements (SQL injection protection)
-   Password hashing (password_hash)
-   Password verification (password_verify)
-   Session authentication
-   Protected admin routes
-   Input sanitization
-   PRG pattern implementation

------------------------------------------------------------------------

# 🎯 Extra Enhancements

-   SweetAlert2 popups
-   Loading spinner during submission
-   Dashboard statistics
-   Clean project structure
-   Reusable validation functions

------------------------------------------------------------------------

# 📎 Final Notes

This project demonstrates secure PHP development practices and full
integration between frontend, backend, and database systems.
