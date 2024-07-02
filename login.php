<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['nim'] = $user['nim'];
        header("Location: dashboard.php");
    } else {
        echo "Login failed.";
    }
}
?>

<form method="POST">
    NIM: <input type="text" name="nim">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
</form>
