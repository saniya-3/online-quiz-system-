<?php
require '../db.php'; session_start();
if(!isset($_SESSION['admin_logged'])) { header('Location: login.php'); exit; }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare('SELECT * FROM questions WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $q = $stmt->get_result()->fetch_assoc();
if(!$q) { echo 'Not found'; exit; }
$opts = []; $ost = $conn->prepare('SELECT * FROM options WHERE question_id=? ORDER BY id'); $ost->bind_param('i',$id); $ost->execute(); $ores = $ost->get_result(); while($o = $ores->fetch_assoc()) $opts[] = $o;
$err=''; if($_SERVER['REQUEST_METHOD']==='POST') {
  $qtext = trim($_POST['question_text']); $newopts = $_POST['options'] ?? []; $correct = isset($_POST['correct']) ? (int)$_POST['correct'] : -1;
  if($qtext==='' || count($newopts) < 2) $err='Provide question and at least 2 options'; else {
    $u = $conn->prepare('UPDATE questions SET question_text=? WHERE id=?'); $u->bind_param('si',$qtext,$id); $u->execute();
    $d = $conn->prepare('DELETE FROM options WHERE question_id=?'); $d->bind_param('i',$id); $d->execute();
    $ins = $conn->prepare('INSERT INTO options (question_id, option_text, is_correct) VALUES (?,?,?)');
    foreach($newopts as $idx => $op){ $is = ($idx === $correct) ? 1 : 0; $ins->bind_param('isi',$id,$op,$is); $ins->execute(); }
    header('Location: questions.php?quiz_id=' . $q['quiz_id']); exit;
  }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Edit Question</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container py-4"><h3>Edit Question</h3><?php if($err): ?><div class="alert alert-danger"><?php echo $err ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label>Question</label><textarea name="question_text" class="form-control" required><?php echo htmlspecialchars($q['question_text']) ?></textarea></div>
<div id="opts"><?php foreach($opts as $o): ?><div class="mb-2"><input name="options[]" class="form-control" value="<?php echo htmlspecialchars($o['option_text']) ?>" required></div><?php endforeach; ?></div>
<div class="mb-3"><label>Correct option index (0-based)</label><input name="correct" class="form-control" value="0" required></div>
<button type="button" class="btn btn-secondary mb-3" onclick="addOpt()">Add option</button><br>
<button class="btn btn-primary">Update</button> <a href="questions.php?quiz_id=<?php echo $q['quiz_id'] ?>" class="btn btn-link">Cancel</a></form></div>
<script>function addOpt(){var d=document.createElement('div');d.className='mb-2';d.innerHTML='<input name="options[]" class="form-control" placeholder="Option" required>';document.getElementById('opts').appendChild(d);}</script>
</body></html>
