<?php

declare(strict_types=1);

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_string(?string $value): string {
    $value = (string)$value;
    $value = trim($value);
    // remove NULL bytes
    $value = str_replace("\0", "", $value);
    return $value;
}

function is_valid_email(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}