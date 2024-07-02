<?php
require 'db.php';

$nim = 'admin';
$password = password_hash('admin', PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (nim, password) VALUES (?, ?)");
$stmt->bind_param("ss", $nim, $password);
$stmt->execute();

echo "User created successfully";
?>
