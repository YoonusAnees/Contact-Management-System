<?php
declare(strict_types=1);

require __DIR__ . "/../db/db.php";
require __DIR__ . "/../validation/functions.php";
require __DIR__ . "/../config/auth.php";

$errors = [];
$success = "";

$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = clean_string($_POST["username"] ?? "");
    $email = clean_string($_POST["email"] ?? "");
    $password = (string)($_POST["password"] ?? "");
    $confirm = (string)($_POST["confirm_password"] ?? "");

    if ($username === "") $errors["username"] = "Username is required.";
    if ($email === "") $errors["email"] = "Email is required.";
    if ($email !== "" && !is_valid_email($email)) $errors["email"] = "Invalid email format.";

    if ($password === "") $errors["password"] = "Password is required.";
    if (strlen($password) < 6) $errors["password"] = "Password must be at least 6 characters.";
    if ($confirm === "") $errors["confirm_password"] = "Confirm password is required.";
    if ($password !== "" && $confirm !== "" && $password !== $confirm) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    if (!$errors) {
        try {
            // Check existing
            $check = $pdo->prepare("SELECT id FROM admins WHERE username = :u OR email = :e LIMIT 1");
            $check->execute([":u" => $username, ":e" => $email]);
            if ($check->fetch()) {
                $errors["general"] = "Admin with that username or email already exists.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO admins (username, email, password_hash)
                    VALUES (:u, :e, :h)
                ");
                $stmt->execute([":u" => $username, ":e" => $email, ":h" => $hash]);

                header("Location: login.php?status=registered");
                exit;
            }
        } catch (Throwable $e) {
            $errors["general"] = "Registration failed. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Register</title>
  <link rel="stylesheet" href="../assets/css/style.css"/>
    <link rel="icon" type="image/png" href="assets/logo.png">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<main class="container">
  <header class="header">
    <h1>Admin Register</h1>
    <p>Create an admin account (one-time setup).</p>
  </header>

  <section class="card">
    <form method="POST" class="form" novalidate>
      <div class="field">
        <label>Username *</label>
        <input name="username" type="text" value="<?= e($username) ?>" />
        <?php if (!empty($errors["username"])): ?><small class="error-text"><?= e($errors["username"]) ?></small><?php endif; ?>
      </div>

      <div class="field">
        <label>Email *</label>
        <input name="email" type="email" value="<?= e($email) ?>" />
        <?php if (!empty($errors["email"])): ?><small class="error-text"><?= e($errors["email"]) ?></small><?php endif; ?>
      </div>

      <div class="field">
        <label>Password *</label>
        <input name="password" type="password" />
        <?php if (!empty($errors["password"])): ?><small class="error-text"><?= e($errors["password"]) ?></small><?php endif; ?>
      </div>

      <div class="field">
        <label>Confirm Password *</label>
        <input name="confirm_password" type="password" />
        <?php if (!empty($errors["confirm_password"])): ?><small class="error-text"><?= e($errors["confirm_password"]) ?></small><?php endif; ?>
      </div>

      <div class="actions">
        <button type="submit">Create Admin</button>
        <a class="link" href="login.php">Go to Login</a>
      </div>
    </form>
  </section>
</main>

<?php if (!empty($errors["general"])): ?>
<script>
Swal.fire({icon:"error",title:"Error",text:"<?= e($errors["general"]) ?>",confirmButtonColor:"#ff5a5f"});
</script>
<?php endif; ?>
</body>
</html>