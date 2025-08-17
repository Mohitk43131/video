<?php require __DIR__.'/config.php'; require __DIR__.'/inc/helpers.php'; $conn=db();
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!isset($_FILES['video']) || $_FILES['video']['error']!==UPLOAD_ERR_OK){
    $err='Upload failed.';
  } else {
    $title = trim($_POST['title'] ?? 'Untitled');
    $f = $_FILES['video'];
    $allowed = ['video/mp4'=>'mp4','video/webm'=>'webm','video/ogg'=>'ogv'];
    $mime = mime_content_type($f['tmp_name']);
    if(!isset($allowed[$mime])){$err='Only MP4/WEBM/OGG allowed.';}
    if(!$err){
      $ext = $allowed[$mime];
      $safe = bin2hex(random_bytes(8)).'.'.$ext;
      $dest = __DIR__.'/storage/videos/'.$safe;
      if(!is_dir(__DIR__.'/storage/videos')) mkdir(__DIR__.'/storage/videos',0775,true);
      if(!move_uploaded_file($f['tmp_name'],$dest)){$err='Failed to save file.';}
      if(!$err){
        $size = filesize($dest);
        $stmt = $conn->prepare('INSERT INTO videos(title,filename,mime,size) VALUES(?,?,?,?)');
        $stmt->bind_param('sssi',$title,$safe,$mime,$size);
        $stmt->execute();
        redirect('watch.php?id='.$stmt->insert_id);
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upload · YouTube-MVP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark"><div class="container"><a class="navbar-brand" href="./">YouTube-MVP</a></div></nav>
<main class="container py-4" style="max-width:720px;">
  <h1 class="h4 mb-3">Upload a video</h1>
  <?php if($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="vstack gap-3">
    <div>
      <label class="form-label">Title</label>
      <input name="title" class="form-control" required>
    </div>
    <div>
      <label class="form-label">Video (MP4/WEBM/OGG)</label>
      <input type="file" name="video" accept="video/mp4,video/webm,video/ogg" class="form-control" required>
    </div>
    <button class="btn btn-primary">Upload</button>
  </form>
</main>
</body>
</html>