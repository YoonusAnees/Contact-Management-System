<?php
declare(strict_types=1);

require __DIR__ . "/db/db.php";
require __DIR__ . "/validation/functions.php";

$errors = [];
$success = "";
$name = "";
$email = "";
$phone = "";
$message = "";


$status = $_GET["status"] ?? "";
if ($status === "success") {
    $success = "Thanks! Your message has been submitted successfully.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = clean_string($_POST["name"] ?? "");
    $email = clean_string($_POST["email"] ?? "");
    $phone = clean_string($_POST["phone"] ?? "");
    $message = clean_string($_POST["message"] ?? "");

    if ($name === "") $errors["name"] = "Name is required.";
    if ($email === "") $errors["email"] = "Email is required.";
    if ($message === "") $errors["message"] = "Message is required.";

    if ($email !== "" && !is_valid_email($email)) {
        $errors["email"] = "Please enter a valid email address.";
    }

    if ($name !== "" && mb_strlen($name) > 120) $errors["name"] = "Name is too long (max 120 chars).";
    if ($email !== "" && mb_strlen($email) > 180) $errors["email"] = "Email is too long (max 180 chars).";
    if ($phone !== "" && mb_strlen($phone) > 40) $errors["phone"] = "Phone is too long (max 40 chars).";

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO inquiries (name, email, phone, message)
                VALUES (:name, :email, :phone, :message)
            ");
            $stmt->execute([
                ":name" => $name,
                ":email" => $email,
                ":phone" => ($phone === "" ? null : $phone),
                ":message" => $message,
            ]);

            header("Location: index.php?status=success");
            exit;

        } catch (Throwable $e) {
            $errors["general"] = "Something went wrong while saving your message. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Contact Form</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <main class="container">
    <header class="header">
      <h1>Contact Us</h1>
      <p>Send us a message and we’ll get back to you.</p>
    </header>

    <section class="card">
      <form id="contactForm" method="POST" action="index.php" class="form" novalidate>
        <div class="field">
          <label for="name">Name <span class="req">*</span></label>
          <input
            id="name"
            name="name"
            type="text"
            value="<?= e($name) ?>"
            placeholder="Your full name"
            autocomplete="name"
          />
          <?php if (!empty($errors["name"])): ?>
            <small class="error-text"><?= e($errors["name"]) ?></small>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="email">Email <span class="req">*</span></label>
          <input
            id="email"
            name="email"
            type="email"
            value="<?= e($email) ?>"
            placeholder="you@example.com"
            autocomplete="email"
          />
          <?php if (!empty($errors["email"])): ?>
            <small class="error-text"><?= e($errors["email"]) ?></small>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="phone">Phone (optional)</label>
          <input
            id="phone"
            name="phone"
            type="text"
            value="<?= e($phone) ?>"
            placeholder="+94..."
            autocomplete="tel"
          />
          <?php if (!empty($errors["phone"])): ?>
            <small class="error-text"><?= e($errors["phone"]) ?></small>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="message">Message <span class="req">*</span></label>
          <textarea
            id="message"
            name="message"
            rows="6"
            placeholder="Type your message..."
          ><?= e($message) ?></textarea>
          <?php if (!empty($errors["message"])): ?>
            <small class="error-text"><?= e($errors["message"]) ?></small>
          <?php endif; ?>
        </div>

        <div class="actions">
          <button type="submit">Submit</button>
          <a class="link" href="admin/login.php">Admin Login</a>
        </div>
      </form>
    </section>

    <footer class="footer">
      <small>Veloz Marketing – Contact Management System</small>
    </footer>
  </main>

  <script>
    const form = document.getElementById("contactForm");
    form.addEventListener("submit", function () {
      Swal.fire({
        title: "Submitting...",
        text: "Please wait a moment",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
      });
    });
  </script>

  <?php if (!empty($errors)): ?>
    <?php
      $fieldErrors = [];
      foreach ($errors as $k => $msg) {
        if ($k !== "general") $fieldErrors[] = $msg;
      }
    ?>
    <?php if (!empty($fieldErrors)): ?>
      <script>
        Swal.fire({
          icon: "error",
          title: "Please Fill The Required Fields",
          html: `
            <div style="text-align:left">
              <ul style="margin:0; padding-left:18px;">
                <?php foreach ($fieldErrors as $msg): ?>
                  <li><?= e($msg) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          `,
          confirmButtonColor: "#ff5a5f"
        });
      </script>
    <?php endif; ?>
  <?php endif; ?>

  <!-- General DB error modal -->
  <?php if (!empty($errors["general"])): ?>
    <script>
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "<?= e($errors["general"]) ?>",
        confirmButtonColor: "#ff5a5f"
      });
    </script>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <script>
      Swal.fire({
        icon: "success",
        title: "Submitted!",
        text: "<?= e($success) ?>",
        confirmButtonColor: "#4c7dff"
      }).then(() => {
        const url = new URL(window.location);
        url.searchParams.delete("status");
        window.history.replaceState({}, "", url);
      });
    </script>
  <?php endif; ?>

</body>
</html>