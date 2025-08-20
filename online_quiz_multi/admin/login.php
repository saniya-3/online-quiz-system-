<?php
require '../db.php';
session_start();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id, password FROM admins WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if($res && password_verify($pass, $res['password'])) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_id'] = $res['id'];
        $_SESSION['admin_user'] = $user;
        header('Location: dashboard.php'); exit;
    } else $err = 'Invalid credentials';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card mx-auto" style="max-width:420px">
      <div class="card-body">
        <h4>Admin Login</h4>
        <?php if($err): ?><div class="alert alert-danger"><?php echo $err ?></div><?php endif; ?>
        <form method="post">
          <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
          <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control" required></div>
          <button class="btn btn-primary">Login</button>
        </form>
        <a href="../index.php" class="btn btn-link mt-2">Back to site</a>
      </div>
    </div>
  </div>
</body>
</html>
