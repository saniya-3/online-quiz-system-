<?php
require 'db.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
if($user_name === '') $user_name = 'Guest';

$answers = isset($_POST['answer']) ? $_POST['answer'] : [];
$total = 0; $score = 0;
foreach($answers as $q_id => $opt_id) {
    $total++;
    $opt_id = (int)$opt_id;
    $stmt = $conn->prepare('SELECT is_correct FROM options WHERE id=? LIMIT 1');
    $stmt->bind_param('i', $opt_id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    if($r && $r['is_correct']) $score++;
}

// store result
$stmt = $conn->prepare('INSERT INTO results (user_name, quiz_id, score, total) VALUES (?, ?, ?, ?)');
$stmt->bind_param('siii', $user_name, $quiz_id, $score, $total);
$stmt->execute();
$rid = $stmt->insert_id;
header('Location: result.php?id=' . $rid);
exit;
?>