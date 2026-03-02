<?php
declare(strict_types=1);

require __DIR__ . "/../config/auth.php";
admin_logout();
header("Location: login.php");
exit;