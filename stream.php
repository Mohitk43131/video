<?php
require __DIR__.'/config.php';
$conn = db();
$id = (int)($_GET['id'] ?? 0);
$res = $conn->prepare('SELECT filename, mime, size FROM videos WHERE id=?');
$res->bind_param('i',$id);
$res->execute();
$res = $res->get_result();
if(!$v = $res->fetch_assoc()) { http_response_code(404); exit('Not found'); }
$path = __DIR__.'/storage/videos/'.$v['filename'];
if(!is_file($path)) { http_response_code(410); exit('Gone'); }
$size = (int)$v['size'];
$mime = $v['mime'];
$start = 0; $length = $size; $end = $size - 1;
header('Content-Type: '.$mime);
header('Accept-Ranges: bytes');

if (isset($_SERVER['HTTP_RANGE'])) {
  if (preg_match('/bytes=([0-9]*)-([0-9]*)/', $_SERVER['HTTP_RANGE'], $matches)) {
    if ($matches[1] !== '') $start = (int)$matches[1];
    if ($matches[2] !== '') $end = (int)$matches[2];
    $end = min($end, $size-1);
    if ($start > $end) { http_response_code(416); header("Content-Range: bytes */$size"); exit; }
    $length = $end - $start + 1;
    http_response_code(206);
    header("Content-Range: bytes $start-$end/$size");
    header("Content-Length: $length");
  }
} else {
  header("Content-Length: $size");
}
$fp = fopen($path, 'rb');
if ($start) fseek($fp, $start);
$chunk = 8192;
while(!feof($fp) && $length > 0){
  $read = ($length > $chunk) ? $chunk : $length;
  $buffer = fread($fp, $read);
  echo $buffer;
  flush();
  $length -= strlen($buffer);
}
fclose($fp);