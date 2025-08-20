<?php
require '../db.php';
session_start();
if(!isset($_SESSION['admin_logged']) || !$_SESSION['admin_logged']) { header('Location: login.php'); exit; }

// stats
$stats = [];
$r = $conn->query('SELECT COUNT(*) as c FROM quizzes')->fetch_assoc(); $stats['quizzes'] = $r['c'];
$r = $conn->query('SELECT COUNT(*) as c FROM questions')->fetch_assoc(); $stats['questions'] = $r['c'];
$r = $conn->query('SELECT COUNT(*) as c FROM results')->fetch_assoc(); $stats['results'] = $r['c'];

$quizzes = []; $res = $conn->query('SELECT * FROM quizzes ORDER BY id DESC'); while($q=$res->fetch_assoc()) $quizzes[]=$q;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">Admin</a>
      <div class="ms-auto">
        <span class="navbar-text me-3">Hi, <?php echo htmlspecialchars($_SESSION['admin_user']) ?></span>
        <a class="btn btn-outline-light" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <div class="row mb-3">
      <div class="col-md-4"><div class="card p-3"><h5>Quizzes</h5><p class="h2"><?php echo $stats['quizzes'] ?></p></div></div>
      <div class="col-md-4"><div class="card p-3"><h5>Questions</h5><p class="h2"><?php echo $stats['questions'] ?></p></div></div>
      <div class="col-md-4"><div class="card p-3"><h5>Results</h5><p class="h2"><?php echo $stats['results'] ?></p></div></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
      <h4>Quizzes</h4>
      <div>
        <a href="add_quiz.php" class="btn btn-success">Add Quiz</a>
        <a href="results.php" class="btn btn-outline-secondary ms-2">View Results</a>
      </div>
    </div>

    <?php if(count($quizzes) == 0): ?>
      <div class="alert alert-info">No quizzes. Add one.</div>
    <?php else: ?>
      <table class="table table-striped">
        <thead><tr><th>ID</th><th>Name</th><th>Questions</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($quizzes as $q): 
            $cnt = $conn->query('SELECT COUNT(*) as c FROM questions WHERE quiz_id=' . (int)$q['id'])->fetch_assoc()['c'];
        ?>
          <tr>
            <td><?php echo $q['id'] ?></td>
            <td><?php echo htmlspecialchars($q['name']) ?></td>
            <td><?php echo $cnt ?></td>
            <td>
              <a href="edit_quiz.php?id=<?php echo $q['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="questions.php?quiz_id=<?php echo $q['id'] ?>" class="btn btn-sm btn-info">Questions</a>
              <a href="dashboard.php?delete=<?php echo $q['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete quiz? This will remove its questions too.')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>
</body>
</html>
