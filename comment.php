<?php
require __DIR__.'/../config.php';
$conn = db();
$video_id = (int)($_POST['video_id'] ?? 0);
$author = trim($_POST['author'] ?? '');
$body = trim($_POST['body'] ?? '');
if(!$video_id || !$author || !$body){ http_response_code(422); exit('Invalid'); }
$stmt = $conn->prepare('INSERT INTO comments(video_id,author,body) VALUES(?,?,?)');
$stmt->bind_param('iss',$video_id,$author,$body);
$stmt->execute();
header('Location: ../watch.php?id='.$video_id);