<?php
require '../db.php'; session_start();
if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
if(!$quiz_id) { header('Location: dashboard.php'); exit; }
// handle delete question
if(isset($_GET['delete'])) { $id=(int)$_GET['delete']; $d=$conn->prepare('DELETE FROM questions WHERE id=?'); $d->bind_param('i',$id); $d->execute(); header('Location: questions.php?quiz_id='.$quiz_id); exit; }
$qstmt = $conn->prepare('SELECT * FROM quizzes WHERE id=?'); $qstmt->bind_param('i',$quiz_id); $qstmt->execute(); $quiz = $qstmt->get_result()->fetch_assoc();
$questions = []; $res = $conn->prepare('SELECT * FROM questions WHERE quiz_id=? ORDER BY id DESC'); $res->bind_param('i',$quiz_id); $res->execute(); $qres=$res->get_result(); while($r=$qres->fetch_assoc()) $questions[]=$r;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Questions - <?php echo htmlspecialchars($quiz['name']) ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-white"><div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3"><h3>Questions for: <?php echo htmlspecialchars($quiz['name']) ?></h3><div><a href="add_question.php?quiz_id=<?php echo $quiz_id ?>" class="btn btn-success">Add Question</a> <a href="dashboard.php" class="btn btn-link">Back</a></div></div>
  <?php if(count($questions)==0): ?><div class="alert alert-info">No questions yet.</div><?php else: ?>
    <table class="table"><thead><tr><th>ID</th><th>Question</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($questions as $qq): ?>
      <tr><td><?php echo $qq['id'] ?></td><td><?php echo htmlspecialchars($qq['question_text']) ?></td>
      <td><a class="btn btn-sm btn-primary" href="edit_question.php?id=<?php echo $qq['id'] ?>">Edit</a>
      <a class="btn btn-sm btn-danger" href="questions.php?quiz_id=<?php echo $quiz_id ?>&delete=<?php echo $qq['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td></tr>
    <?php endforeach; ?>
    </tbody></table>
  <?php endif; ?>
</div></body></html>
