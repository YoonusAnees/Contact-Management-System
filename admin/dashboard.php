<?php
declare(strict_types=1);

require __DIR__ . "/../db/db.php";
require __DIR__ . "/../validation/functions.php";
require __DIR__ . "/../config/auth.php";

require_admin_login();

// Stats
$total = (int)$pdo->query("SELECT COUNT(*) AS c FROM inquiries")->fetch()["c"];

$today = (int)$pdo->query("
  SELECT COUNT(*) AS c
  FROM inquiries
  WHERE DATE(created_at) = CURDATE()
")->fetch()["c"];

$uniqueEmails = (int)$pdo->query("
  SELECT COUNT(DISTINCT email) AS c
  FROM inquiries
")->fetch()["c"];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="assets/logo.png">

  <link rel="stylesheet" href="../assets/css/style.css"/>
  <style>
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;}
    .grid{display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));}
    .stat{background: rgba(18,26,44,0.92); border:1px solid rgba(255,255,255,.12); border-radius:14px; padding:16px;}
    .stat h3{margin:0 0 6px;font-size:14px;color: rgba(255,255,255,.65); font-weight:700;}
    .stat .num{font-size:34px;font-weight:800; margin:0;}
  </style>
</head>
<body>
<main class="container">
  <div class="topbar">
    <div>
      <h1 style="margin:0 0 6px;">Admin Dashboard</h1>
      <div style="color: rgba(255,255,255,.65);">
        Logged in as <?= e((string)($_SESSION["admin_username"] ?? "admin")) ?>
      </div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="link" href="inquiries.php">View Inquiries</a>
      <a class="link" href="logout.php">Logout</a>
    </div>
  </div>

  <div class="grid">
    <div class="stat">
      <h3>Total Inquiries</h3>
      <p class="num"><?= $total ?></p>
    </div>
    <div class="stat">
      <h3>Today’s Inquiries</h3>
      <p class="num"><?= $today ?></p>
    </div>
    <div class="stat">
      <h3>Unique Emails</h3>
      <p class="num"><?= $uniqueEmails ?></p>
    </div>
  </div>
</main>
</body>
</html>