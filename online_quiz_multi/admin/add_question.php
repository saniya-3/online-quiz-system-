<?php
require '../db.php'; session_start();
if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
if(!$quiz_id) { header('Location: dashboard.php'); exit; }
$err=''; if($_SERVER['REQUEST_METHOD']==='POST') {
  $qtext = trim($_POST['question_text']); $opts = $_POST['options'] ?? []; $correct = isset($_POST['correct']) ? (int)$_POST['correct'] : -1;
  if($qtext==='' || count($opts) < 2) $err='Provide question and at least 2 options'; else {
    $i = $conn->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?,?)'); $i->bind_param('is',$quiz_id,$qtext); $i->execute(); $qid = $i->insert_id;
    $ost = $conn->prepare('INSERT INTO options (question_id, option_text, is_correct) VALUES (?,?,?)');
    foreach($opts as $idx => $op){ $is = ($idx === $correct) ? 1 : 0; $ost->bind_param('isi',$qid,$op,$is); $ost->execute(); }
    header('Location: questions.php?quiz_id=' . $quiz_id); exit;
  }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Add Question</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><h3>Add Question</h3><?php if($err): ?><div class="alert alert-danger"><?php echo $err ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label>Question</label><textarea name="question_text" class="form-control" required></textarea></div>
<div id="opts"><div class="mb-2"><input name="options[]" class="form-control" placeholder="Option 1" required></div><div class="mb-2"><input name="options[]" class="form-control" placeholder="Option 2" required></div></div>
<div class="mb-3"><label>Correct option index (0-based)</label><input name="correct" class="form-control" value="0" required></div>
<button type="button" class="btn btn-secondary mb-3" onclick="addOpt()">Add option</button><br>
<button class="btn btn-success">Save</button> <a href="questions.php?quiz_id=<?php echo $quiz_id ?>" class="btn btn-link">Cancel</a></form></div>
<script>function addOpt(){var d=document.createElement('div');d.className='mb-2';d.innerHTML='<input name="options[]" class="form-control" placeholder="Option" required>';document.getElementById('opts').appendChild(d);}</script>
</body></html>
