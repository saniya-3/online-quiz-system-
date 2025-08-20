<?php
require '../db.php'; session_start(); if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$res = $conn->query('SELECT r.*, q.name as quiz_name FROM results r LEFT JOIN quizzes q ON r.quiz_id=q.id ORDER BY r.taken_at DESC');
$results = []; while($row = $res->fetch_assoc()) $results[] = $row;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Results</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-white"><div class="container py-4"><h3>Results</h3>
<table class="table table-striped"><thead><tr><th>ID</th><th>User</th><th>Quiz</th><th>Score</th><th>Date</th></tr></thead><tbody>
<?php foreach($results as $r): ?><tr><td><?php echo $r['id'] ?></td><td><?php echo htmlspecialchars($r['user_name']) ?></td><td><?php echo htmlspecialchars($r['quiz_name']) ?></td><td><?php echo $r['score'].'/'.$r['total'] ?></td><td><?php echo $r['taken_at'] ?></td></tr><?php endforeach; ?>
</tbody></table><a href="dashboard.php" class="btn btn-link">Back</a></div></body></html>
