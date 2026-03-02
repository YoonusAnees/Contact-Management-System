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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1) Read + sanitize
    $name = clean_string($_POST["name"] ?? "");
    $email = clean_string($_POST["email"] ?? "");
    $phone = clean_string($_POST["phone"] ?? "");
    $message = clean_string($_POST["message"] ?? "");

    // 2) Validate required fields
    if ($name === "") $errors["name"] = "Name is required.";
    if ($email === "") $errors["email"] = "Email is required.";
    if ($message === "") $errors["message"] = "Message is required.";

    // 3) Validate email format
    if ($email !== "" && !is_valid_email($email)) {
        $errors["email"] = "Please enter a valid email address.";
    }

    // 4) Optional: basic length limits (good practice)
    if ($name !== "" && mb_strlen($name) > 120) $errors["name"] = "Name is too long (max 120 chars).";
    if ($email !== "" && mb_strlen($email) > 180) $errors["email"] = "Email is too long (max 180 chars).";
    if ($phone !== "" && mb_strlen($phone) > 40) $errors["phone"] = "Phone is too long (max 40 chars).";

    // 5) Insert if valid
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

            $success = "Thanks! Your message has been submitted successfully.";

            // Reset form values after success
            $name = $email = $phone = $message = "";
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
</head>
<body>
  <main class="container">
    <header class="header">
      <h1>Contact Us</h1>
      <p>Send us a message and we’ll get back to you.</p>
    </header>

    <?php if (!empty($success)): ?>
      <div class="alert success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors["general"])): ?>
      <div class="alert error"><?= e($errors["general"]) ?></div>
    <?php endif; ?>

    <section class="card">
      <form method="POST" action="index.php" class="form" novalidate>
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
          <a class="link" href="admin.php">View Admin Records</a>
        </div>
      </form>
    </section>

    <footer class="footer">
      <small>Veloz Marketing – Contact Management System</small>
    </footer>
  </main>
</body>
</html>