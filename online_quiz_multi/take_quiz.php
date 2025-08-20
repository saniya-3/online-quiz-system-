<?php
require 'db.php';
$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;
if(!$quiz_id) { header('Location: index.php'); exit; }

// fetch quiz
$stmt = $conn->prepare('SELECT * FROM quizzes WHERE id=? LIMIT 1');
$stmt->bind_param('i', $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
if(!$quiz) { echo 'Quiz not found'; exit; }

// fetch questions and options
$questions = [];
$qstmt = $conn->prepare('SELECT * FROM questions WHERE quiz_id=? ORDER BY id');
$qstmt->bind_param('i', $quiz_id);
$qstmt->execute();
$qres = $qstmt->get_result();
while($q = $qres->fetch_assoc()) {
    $q['options'] = [];
    $ostmt = $conn->prepare('SELECT * FROM options WHERE question_id=? ORDER BY id');
    $ostmt->bind_param('i', $q['id']);
    $ostmt->execute();
    $ores = $ostmt->get_result();
    while($o = $ores->fetch_assoc()) $q['options'][] = $o;
    $questions[] = $q;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($quiz['name']) ?> — Take Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
  <div class="container py-4">
    <div class="card mx-auto" style="max-width:900px">
      <div class="card-body">
        <h3><?php echo htmlspecialchars($quiz['name']) ?></h3>
        <p class="text-muted"><?php echo htmlspecialchars($quiz['description']) ?></p>

        <?php if(count($questions) == 0): ?>
          <div class="alert alert-warning">No questions in this quiz yet. Try another quiz.</div>
        <?php else: ?>
          <form id="quizForm" method="post" action="submit_quiz.php">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>">
            <div id="questionArea">
              <!-- Questions will be injected -->
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
              <div><small id="progressText">Question 1 of <?php echo count($questions) ?></small></div>
              <div>
                <button type="button" id="prevBtn" class="btn btn-secondary me-2" onclick="prevQ()" disabled>Previous</button>
                <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextQ()">Next</button>
                <button type="submit" id="submitBtn" class="btn btn-success" style="display:none">Submit Quiz</button>
              </div>
            </div>
          </form>
        <?php endif; ?>

      </div>
    </div>
  </div>

<script>
const questions = <?php echo json_encode($questions); ?>;
let current = 0;

function renderQuestion(idx) {
  const q = questions[idx];
  let html = '<h5>Q' + (idx+1) + '. ' + escapeHtml(q.question_text) + '</h5>';
  html += '<div class="mt-2">';
  q.options.forEach(o => {
    html += '<div class="form-check">' +
      '<input class="form-check-input" type="radio" name="answer['+q.id+']" id="opt'+o.id+'" value="'+o.id+'">' +
      '<label class="form-check-label" for="opt'+o.id+'">' + escapeHtml(o.option_text) + '</label>' +
      '</div>';
  });
  html += '</div>';
  document.getElementById('questionArea').innerHTML = html;
  document.getElementById('progressText').innerText = 'Question ' + (idx+1) + ' of ' + questions.length;
  document.getElementById('prevBtn').disabled = idx === 0;
  document.getElementById('nextBtn').style.display = (idx === questions.length-1) ? 'none' : 'inline-block';
  document.getElementById('submitBtn').style.display = (idx === questions.length-1) ? 'inline-block' : 'none';
}

function nextQ(){
  current++;
  renderQuestion(current);
}

function prevQ(){
  current--;
  renderQuestion(current);
}

function escapeHtml(unsafe) {
  return unsafe
       .replace(/&/g, '&amp;')
       .replace(/</g, '&lt;')
       .replace(/>/g, '&gt;')
       .replace(/"/g, '&quot;')
       .replace(/'/g, '&#039;');
}

window.onload = function(){
  if(questions.length) renderQuestion(0);
}
</script>
</body>
</html>
