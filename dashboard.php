<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$countries = $conn->query("SELECT groups.group_name, countries.* FROM countries JOIN groups ON countries.group_id = groups.id");
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 80%;
        margin: auto;
        padding: 20px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .links {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .links a {
        text-decoration: none;
        color: #007bff;
        margin-right: 10px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f0f0f0;
    }
    td a {
        text-decoration: none;
        color: #007bff;
    }
</style>

<div class="container">
    <div class="links">
        <a href="add_group.php">Tambah Group</a>
        <a href="add_country.php">Tambah Negara</a>
        <a href="report.php">Cetak Laporan</a>
        <a href="logout.php">Logout</a>
    </div>
    <table>
        <tr>
            <th>Group</th>
            <th>Nama Negara</th>
            <th>Menang</th>
            <th>Seri</th>
            <th>Kalah</th>
            <th>Poin</th>
            <th>Ubah</th>
            <th>Hapus</th>
        </tr>
        <?php while ($row = $countries->fetch_assoc()): ?>
            <tr>
                <td><?= $row['group_name'] ?></td>
                <td><?= $row['country_name'] ?></td>
                <td><?= $row['wins'] ?></td>
                <td><?= $row['draws'] ?></td>
                <td><?= $row['losses'] ?></td>
                <td><?= $row['points'] ?></td>
                <td><a href="edit_country.php?id=<?= $row['id'] ?>">Ubah</a></td>
                <td><a href="delete_country.php?id=<?= $row['id'] ?>">Hapus</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

