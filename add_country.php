<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle group addition if necessary
    $group_name = $_POST['group_name'];
    if (!empty($_POST['new_group_name'])) {
        $group_name = $_POST['new_group_name'];
        $existing_group = $conn->query("SELECT * FROM groups WHERE group_name = '$group_name'")->fetch_assoc();

        if (!$existing_group) {
            $stmt = $conn->prepare("INSERT INTO groups (group_name) VALUES (?)");
            $stmt->bind_param("s", $group_name);
            $stmt->execute();
            $group_id = $stmt->insert_id;
        } else {
            $group_id = $existing_group['id'];
        }
    } else {
        $existing_group = $conn->query("SELECT * FROM groups WHERE group_name = '$group_name'")->fetch_assoc();
        $group_id = $existing_group['id'];
    }

    // Handle country addition
    $country_name = $_POST['country_name'];
    $wins = $_POST['wins'];
    $draws = $_POST['draws'];
    $losses = $_POST['losses'];
    $points = ($wins * 3) + $draws;

    $stmt = $conn->prepare("INSERT INTO countries (group_id, country_name, wins, draws, losses, points) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiii", $group_id, $country_name, $wins, $draws, $losses, $points);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

$groups = $conn->query("SELECT * FROM groups");
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    .container {
        width: 80%;
        margin: auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 10px;
    }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-group input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
    }
</style>

<div class="container">
    <form method="POST">
        <div class="form-group">
            <label for="group_name">Nama Group:</label>
            <select name="group_name" id="group_name">
                <?php while ($group = $groups->fetch_assoc()): ?>
                    <option value="<?= $group['group_name'] ?>"><?= $group['group_name'] ?></option>
                <?php endwhile; ?>
                <option value="" disabled>--Tambah Group Baru--</option>
            </select>
        </div>
        <div class="form-group">
            <label for="new_group_name">Nama Group Baru (opsional):</label>
            <input type="text" name="new_group_name" id="new_group_name">
        </div>
        <div class="form-group">
            <label for="country_name">Nama Negara:</label>
            <input type="text" name="country_name" id="country_name">
        </div>
        <div class="form-group">
            <label for="wins">Jumlah Menang:</label>
            <input type="number" name="wins" id="wins">
        </div>
        <div class="form-group">
            <label for="draws">Jumlah Seri:</label>
            <input type="number" name="draws" id="draws">
        </div>
        <div class="form-group">
            <label for="losses">Jumlah Kalah:</label>
            <input type="number" name="losses" id="losses">
        </div>
        <div class="form-group">
            <input type="submit" value="Tambah Negara">
        </div>
    </form>
</div>

