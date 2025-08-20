<?php
require 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare('SELECT r.*, q.name as quiz_name FROM results r LEFT JOIN quizzes q ON r.quiz_id=q.id WHERE r.id=? LIMIT 1');
$stmt->bind_param('i', $id); $stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if(!$row) { echo 'Result not found'; exit; }

$percent = ($row['total'] > 0) ? round(($row['score'] / $row['total']) * 100) : 0;
$passed = $percent >= 50; // pass threshold
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Result — <?php echo htmlspecialchars($row['quiz_name']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card mx-auto text-center" style="max-width:720px">
      <div class="card-body">
        <h3><?php echo htmlspecialchars($row['quiz_name']) ?> — Result</h3>
        <h4 class="mt-3"><?php echo htmlspecialchars($row['user_name']) ?></h4>
        <p class="lead">Score: <strong><?php echo $row['score'] ?>/<?php echo $row['total'] ?></strong></p>
        <p class="display-6"><?php echo $percent ?>%</p>
        <?php if($passed): ?>
          <span class="badge bg-success fs-5">Passed</span>
        <?php else: ?>
          <span class="badge bg-danger fs-5">Failed</span>
        <?php endif; ?>
        <div class="mt-4">
          <a href="index.php" class="btn btn-primary">Back to Quizzes</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
