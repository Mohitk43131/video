<?php require __DIR__.'/config.php'; require __DIR__.'/inc/helpers.php'; $conn=db();
$res = $conn->query('SELECT id, title, filename, size, views, created_at FROM videos ORDER BY created_at DESC');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YouTube-MVP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark"><div class="container"><a class="navbar-brand" href="./">YouTube-MVP</a><a class="btn btn-outline-light" href="upload.php">Upload</a></div></nav>
<main class="container py-4">
  <h1 class="h4 mb-3">Latest uploads</h1>
  <div class="row g-3">
  <?php while($v=$res->fetch_assoc()): ?>
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <a href="watch.php?id=<?= (int)$v['id'] ?>" class="ratio ratio-16x9 bg-dark d-block">
          <video preload="metadata" muted>
            <source src="stream.php?id=<?= (int)$v['id'] ?>#t=0.1" type="video/mp4">
          </video>
        </a>
        <div class="card-body">
          <h2 class="h6 mb-1"><a class="text-decoration-none" href="watch.php?id=<?= (int)$v['id'] ?>"><?= e($v['title']) ?></a></h2>
          <div class="text-muted small"><?= human_size($v['size']) ?> · <?= (int)$v['views'] ?> views · <?= e($v['created_at']) ?></div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>
</main>
</body>
</html>