# 🧩 Task #4 — User Management Web Application

> ⚡ **Objective:**  
> Build a secure, user-friendly web app for registration, authentication, and admin‑style user management.  
> Implement a multi‑select toolbar to Block, Unblock, or Delete users, and guarantee email uniqueness via a database‑level unique index.

---

## 📝 Requirements

1. **Unique Index**
    - Create a database‑level **unique index** on `users.email` so MySQL enforces email uniqueness, even under concurrent requests.
2. **Sorted Table**
    - Display users **sorted by Last Login** time (most recent first).
3. **Multi‑Select Checkboxes**
    - Leftmost column has row checkboxes; header has a “select all” checkbox.
4. **Toolbar Actions**
    - Above the table:
        - **Block** (text button)
        - **Unblock** (icon)
        - **Delete** (icon)
    - **No per‑row buttons** allowed.
5. **Access Control**
    - **Unauthenticated** users → only Registration & Login pages.
    - **Authenticated & non‑blocked** users → access the user management panel.
    - **Every** request (except registration/login) must check that the user exists and isn’t blocked; otherwise redirect to Login with a notification.

---

## 🗄️ Database Schema & Unique Index (MySQL)

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  last_login DATETIME,
  status ENUM('active','blocked') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Enforce email uniqueness at the storage level:
CREATE UNIQUE INDEX idx_users_email_unique ON users(email);
```

> **Note:** Run these statements in **phpMyAdmin** or via the MySQL CLI bundled with XAMPP.

---

## 🚀 Features

- **User Registration & Login** with any non‑empty password.
- **Session Management** using PHP sessions.
- **Last Login** timestamp updated on successful authentication.
- **User Management Panel**
    - Columns: [☐], Name, Email, Last Login, Status.
    - Toolbar: Block, Unblock, Delete (applies to selected rows).
    - **Real‑time status messages** (e.g. “User blocked successfully”).
- **Error Handling**
    - Duplicate‑email registration → catch MySQL error → show “Email already in use.”

---

## 🎨 UI & UX

- Built with **Bootstrap 5**.
- Responsive on desktop & mobile—no custom animations.
- Clean table layout—no wallpapers or row‑level buttons.
- Tooltips for toolbar actions; consistent text alignment.

---

## 📦 Tech Stack

- **Frontend:** JavaScript (or TypeScript) + React (or plain PHP templates).
- **Backend:** PHP 8.x running on XAMPP (Apache + PHP).
- **Database:** MySQL (via XAMPP’s phpMyAdmin).

---

## ⚙️ Running Locally with XAMPP

1. **Install & start XAMPP**
    - Launch **XAMPP Control Panel**, start **Apache** and **MySQL**.
2. **Project Setup**
    - Place project folder into `C:\xampp\htdocs\<project>`.
    - Copy `.env.example` → `.env` (or edit `config.php`) to set DB credentials (`root`/no password by default).
3. **Import Database**
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`), create a new database, then import the SQL schema.
4. **Run the app**
    - Visit `http://localhost/<project>/` in your browser.
5. **Login & manage users** via the web interface.

---

## 🌐 Deployment

You can deploy to any PHP‑compatible host:
- Copy files via FTP or Git.
- Import the DB schema and unique index.
- Ensure `config.php` or `.env` matches production credentials.
- Point your domain to the public folder.

---

## 📤 Submission Requirements

Email to `p.lebedev@itransition.com` with:

1. **Full Name**
2. **GitHub Repo URL**
3. **Deployed App URL**
4. **Recorded Video** demonstrating:
    - Registration & Login
    - Viewing the user list sorted by last login
    - Selecting one or all users → Block, Unblock, Delete
    - Redirect when a blocked user attempts any action
    - Proof of the **unique index** in MySQL & code catching the duplicate‑email error with an appropriate message

> 🔈 Video must clearly show phpMyAdmin (or MySQL CLI) with the index and your PHP error‑handling code.

---

## 🗓️ Deadline

**31 May 2025** (inclusive)

---

## ❓ FAQ

**Q: Can blocked users log in?**  
A: No—every request checks status and redirects if blocked.

**Q: May I use per‑row action buttons?**  
A: ❌ No—only toolbar actions on selected rows.

---

## 🧠 Why This Task?

- Enforces **storage‑level constraints** (MySQL unique index).
- Practices **access control** and **session management** in PHP.
- Builds a **responsive admin UI** with multi‑select toolbar operations.
- Teaches handling **concurrent inserts** and **error feedback**.

Good luck! 🚀
