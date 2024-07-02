<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $country = $conn->query("SELECT * FROM countries WHERE id = $id")->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $group_id = $_POST['group_id'];
    $country_name = $_POST['country_name'];
    $wins = $_POST['wins'];
    $draws = $_POST['draws'];
    $losses = $_POST['losses'];
    $points = ($wins * 3) + $draws;

    $stmt = $conn->prepare("UPDATE countries SET group_id = ?, country_name = ?, wins = ?, draws = ?, losses = ?, points = ? WHERE id = ?");
    $stmt->bind_param("isiiiii", $group_id, $country_name, $wins, $draws, $losses, $points, $id);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $country['id'] ?>">
    Nama Group:
    <select name="group_id">
        <?php
        $groups = $conn->query("SELECT * FROM groups");
        while ($row = $groups->fetch_assoc()):
        ?>
            <option value="<?= $row['id'] ?>" <?= ($row['id'] == $country['group_id']) ? 'selected' : '' ?>><?= $row['group_name'] ?></option>
        <?php endwhile; ?>
    </select>
    Nama Negara: <input type="text" name="country_name" value="<?= $country['country_name'] ?>">
    Jumlah Menang: <input type="number" name="wins" value="<?= $country['wins'] ?>">
    Jumlah Seri: <input type="number" name="draws" value="<?= $country['draws'] ?>">
    Jumlah Kalah: <input type="number" name="losses" value="<?= $country['losses'] ?>">
    <input type="submit" value="Ubah">
</form>
