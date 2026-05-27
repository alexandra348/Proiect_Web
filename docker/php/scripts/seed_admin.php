<?php

$pdo = new PDO(
    "pgsql:host=postgres;dbname=" . $_ENV['POSTGRES_DB'],
    $_ENV['POSTGRES_USER'],
    $_ENV['POSTGRES_PASSWORD']
);

$hash = password_hash($_ENV['ADMIN_PASSWORD'], PASSWORD_BCRYPT);

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (?, ?, ?, 'admin')
    ON CONFLICT (email) DO NOTHING
");

$stmt->execute([
    $_ENV['ADMIN_USERNAME'],
    $_ENV['ADMIN_EMAIL'],
    $hash
]);