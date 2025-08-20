<?php
require '../db.php'; session_start();
if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare('SELECT * FROM quizzes WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $quiz = $stmt->get_result()->fetch_assoc();
if(!$quiz) { echo 'Not found'; exit; }
$err=''; if($_SERVER['REQUEST_METHOD']==='POST'){ $name = trim($_POST['name']); $desc = trim($_POST['description']);
 if($name==='') $err='Name required'; else { $u = $conn->prepare('UPDATE quizzes SET name=?, description=? WHERE id=?'); $u->bind_param('ssi',$name,$desc,$id); $u->execute(); header('Location: dashboard.php'); exit; } }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Edit Quiz</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><h3>Edit Quiz</h3><?php if($err): ?><div class="alert alert-danger"><?php echo $err ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label>Name</label><input name="name" class="form-control" value="<?php echo htmlspecialchars($quiz['name']) ?>" required></div>
<div class="mb-3"><label>Description</label><textarea name="description" class="form-control"><?php echo htmlspecialchars($quiz['description']) ?></textarea></div>
<button class="btn btn-primary">Save</button> <a href="dashboard.php" class="btn btn-link">Cancel</a></form></div></body></html>
