<?php
declare(strict_types=1);

require __DIR__ . "/../db/db.php";
require __DIR__ . "/../validation/functions.php";
require __DIR__ . "/../config/auth.php";

require_admin_login();

$stmt = $pdo->query("SELECT id, name, email, phone, message, created_at FROM inquiries ORDER BY id DESC");
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin - Inquiries</title>
  <link rel="stylesheet" href="../assets/css/style.css"/>
  <style>
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;}
    .table-wrap{overflow:auto;border-radius:14px;border:1px solid rgba(255,255,255,.12);}
    table{width:100%;border-collapse:collapse;min-width:900px;background: rgba(18,26,44,0.92);}
    th,td{padding:12px;border-bottom:1px solid rgba(255,255,255,.12);vertical-align:top;}
    th{text-align:left;color: rgba(255,255,255,.65);font-weight:800;}
    tr:hover td{background: rgba(255,255,255,0.03);}
    .msg{max-width:520px;white-space:pre-wrap;}
  </style>
</head>
<body>
<main class="container">
  <div class="topbar">
    <div>
      <h1 style="margin:0 0 6px;">Submitted Inquiries</h1>
      <div style="color: rgba(255,255,255,.65);">Total: <?= count($rows) ?></div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="link" href="dashboard.php">Dashboard</a>
      <a class="link" href="logout.php">Logout</a>
    </div>
  </div>

  <section class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Message</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="6">No inquiries yet.</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r["id"] ?></td>
            <td><?= e($r["name"]) ?></td>
            <td><?= e($r["email"]) ?></td>
            <td><?= e((string)($r["phone"] ?? "")) ?></td>
            <td class="msg"><?= e($r["message"]) ?></td>
            <td><?= e($r["created_at"]) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>
</body>
</html>