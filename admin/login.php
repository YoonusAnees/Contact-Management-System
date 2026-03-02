<?php
declare(strict_types=1);

require __DIR__ . "/../db/db.php";
require __DIR__ . "/../validation/functions.php";
require __DIR__ . "/../config/auth.php";

$errors = [];
$emailOrUsername = "";

$status = $_GET["status"] ?? "";
$registeredMsg = ($status === "registered") ? "Admin created. Please login." : "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $emailOrUsername = clean_string($_POST["login"] ?? "");
    $password = (string)($_POST["password"] ?? "");

    if ($emailOrUsername === "") $errors["login"] = "Username or email is required.";
    if ($password === "") $errors["password"] = "Password is required.";

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("
                SELECT id, username, email, password_hash
                FROM admins
                WHERE username = :x OR email = :x
                LIMIT 1
            ");
            $stmt->execute([":x" => $emailOrUsername]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($password, $admin["password_hash"])) {
                $errors["general"] = "Invalid login credentials.";
            } else {
                // session
                $_SESSION["admin_id"] = (int)$admin["id"];
                $_SESSION["admin_username"] = $admin["username"];
                $_SESSION["admin_email"] = $admin["email"];

                header("Location: dashboard.php");
                exit;
            }
        } catch (Throwable $e) {
            $errors["general"] = "Login failed. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/css/style.css"/>
    <link rel="icon" type="image/png" href="assets/logo.png">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<main class="container">
  <header class="header">
    <h1>Admin Login</h1>
    <p>Login to view inquiries.</p>
  </header>

  <section class="card">
    <form method="POST" class="form" novalidate>
      <div class="field">
        <label>Username or Email *</label>
        <input name="login" type="text" value="<?= e($emailOrUsername) ?>" />
        <?php if (!empty($errors["login"])): ?><small class="error-text"><?= e($errors["login"]) ?></small><?php endif; ?>
      </div>

      <div class="field">
        <label>Password *</label>
        <input name="password" type="password" />
        <?php if (!empty($errors["password"])): ?><small class="error-text"><?= e($errors["password"]) ?></small><?php endif; ?>
      </div>

      <div class="actions">
        <button type="submit">Login</button>
        <a class="link" href="../index.php">Back to Form</a>
      </div>
    </form>
  </section>
</main>

<?php if ($registeredMsg): ?>
<script>
Swal.fire({icon:"success",title:"Success",text:"<?= e($registeredMsg) ?>",confirmButtonColor:"#4c7dff"});
</script>
<?php endif; ?>

<?php if (!empty($errors["general"])): ?>
<script>
Swal.fire({icon:"error",title:"Oops...",text:"<?= e($errors["general"]) ?>",confirmButtonColor:"#ff5a5f"});
</script>
<?php endif; ?>
</body>
</html>