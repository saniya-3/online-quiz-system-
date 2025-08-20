<?php
require '../db.php'; session_start();
if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$err=''; if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']); $desc = trim($_POST['description']);
  if($name==='') $err='Name required'; else {
    $stmt = $conn->prepare('INSERT INTO quizzes (name, description) VALUES (?,?)');
    $stmt->bind_param('ss',$name,$desc); $stmt->execute(); header('Location: dashboard.php'); exit;
  }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Add Quiz</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><h3>Add Quiz</h3><?php if($err): ?><div class="alert alert-danger"><?php echo $err ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label>Name</label><input name="name" class="form-control" required></div>
<div class="mb-3"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
<button class="btn btn-success">Create Quiz</button> <a href="dashboard.php" class="btn btn-link">Cancel</a></form></div></body></html>
