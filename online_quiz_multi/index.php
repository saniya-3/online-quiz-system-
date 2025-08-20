<?php
require 'db.php';
$quizzes = [];
$res = $conn->query('SELECT * FROM quizzes ORDER BY id DESC');
while($row = $res->fetch_assoc()) $quizzes[] = $row;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Quizzes — Online Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="index.php">Online Quiz</a>
      <div class="ms-auto">
        <a href="admin/login.php" class="btn btn-outline-light">Admin</a>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h1 class="mb-4">Available Quizzes</h1>
    <div class="row g-4">
      <?php foreach($quizzes as $q): ?>
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($q['name']) ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($q['description']) ?></p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <a href="take_quiz.php?quiz_id=<?php echo $q['id'] ?>" class="btn btn-primary">Take Quiz</a>
              <small class="text-muted">ID: <?php echo $q['id'] ?></small>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(count($quizzes) == 0): ?>
        <div class="col-12"><div class="alert alert-info">No quizzes available. Admin can add quizzes.</div></div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
