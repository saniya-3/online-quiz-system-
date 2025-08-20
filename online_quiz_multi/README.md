# Online Quiz System (PHP + MySQL + Bootstrap) — Multiple Quizzes

A beginner-friendly, attractive quiz system built with **PHP (mysqli)**, **MySQL**, and **Bootstrap 5**.
Supports **multiple quizzes/categories**, admin panel (with hashed admin password), question management, and results tracking.

---

## Features
- Multiple quizzes (categories) — users choose which quiz to take.
- Admin panel to manage quizzes and questions.
- Secure admin login using `password_hash()` / `password_verify()`.
- Quiz flow with a progress indicator (one question shown at a time).
- Results stored with quiz reference and timestamp.
- Mobile responsive using Bootstrap 5.
- GitHub-ready with a clear structure and setup instructions.

---

## Quick Setup (Windows / XAMPP, similar for others)

1. Copy the project to your web server folder (e.g., `C:\xampp\htdocs\online_quiz_multi`).
2. Make sure MySQL is running (e.g., start MySQL in XAMPP).
3. Open the install script in browser to create the database and default admin:
   - Visit `http://localhost/online_quiz_multi/install.php`
   - This creates the `online_quiz` database and tables, and creates default admin:
     - username: `admin`
     - password: `admin123`
   - After install, **delete** `install.php` or keep it and remove write permissions for security.
4. Edit `db.php` if your DB credentials are different (default `root` with no password for XAMPP).
5. Open `http://localhost/online_quiz_multi/` to view the homepage.

---

## Files of interest
- `install.php` — creates database/tables and default admin with a hashed password.
- `db.php` — database connection (edit if needed).
- `index.php` — homepage listing quizzes.
- `take_quiz.php` — quiz-taking UI (one question at a time with progress).
- `submit_quiz.php` — server-side scoring & results storage.
- `result.php` — shows score, percentage, and pass/fail badge.
- `admin/` — admin panel (login, dashboard, quizzes, questions, results).
- `database.sql` — SQL dump of the tables (same structure created by install.php).

---

## Security notes (learning project)
- Admin password is hashed. In production, use HTTPS and stronger session handling.
- Inputs are validated and prepared statements are used. Still add CSRF protection for production.
- Remove `install.php` after setup.

---

## Screenshots (placeholders)
Include screenshots in the `screenshots/` folder on GitHub for a nicer README preview:
- `screenshots/home.png` — quizzes grid
- `screenshots/take_quiz.png` — in-quiz UI
- `screenshots/result.png` — result summary
- `screenshots/admin_dashboard.png` — admin dashboard
You can add these images later to the repo.

---

If you want, I can push this to a GitHub repo for you (I will provide the files and a Git-ready structure). Tell me if you want additional features: timers, user accounts, CSV export, or convert to a framework (Laravel).
