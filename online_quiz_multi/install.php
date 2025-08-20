<?php
// install.php - run once to create DB and tables, and default admin
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'online_quiz';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

$sql = file_get_contents('database.sql');
if(!$sql) die('database.sql not found');

// split statements and run
foreach (explode(";\n", $sql) as $stmt) {
    $stmt = trim($stmt);
    if ($stmt) {
        if (!$conn->query($stmt)) {
            echo 'Error running statement: ' . $conn->error . '<br>';
        }
    }
}

// create default admin with hashed password if not exists
$conn->select_db($DB_NAME);
$user = 'admin';
$pass = 'admin123';
$stmt = $conn->prepare('SELECT id FROM admins WHERE username=? LIMIT 1');
$stmt->bind_param('s', $user);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $i = $conn->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
    $i->bind_param('ss', $user, $hash);
    if ($i->execute()) {
        echo 'Default admin created (username: admin, password: admin123)<br>';
    } else echo 'Error creating admin: ' . $conn->error . '<br>';
} else echo 'Admin already exists, skipping creating default admin.<br>';

echo '<br>Install complete. For security delete install.php after confirming the DB is created.';
?>