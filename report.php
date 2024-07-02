<?php
session_start();
if (!isset($_SESSION['nim'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dompdf = new Dompdf();
    $current_time = date("d M Y H:i:s");
    $nim = $_SESSION['nim'];

    $html = '<h1 style="text-align: center;">Data Negara UEFA 2024</h1>';
    $html .= '<p style="text-align: center;">Per ' . $current_time . ' (Waktu dan Jam Sekarang)</p>';
    $html .= '<p style="text-align: center;">NIM: ' . $nim . '</p>';

    // Get groups
    $groups = $conn->query("SELECT * FROM groups");

    while ($group = $groups->fetch_assoc()) {
        $group_id = $group['id'];
        $group_name = $group['group_name'];

        $html .= '<h2 style="text-align: center;">Data Group ' . $group_name . '</h2>';
        $html .= '<table border="1" width="100%" style="border-collapse: collapse; margin: auto;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Tim</th>';
        $html .= '<th>Menang</th>';
        $html .= '<th>Seri</th>';
        $html .= '<th>Kalah</th>';
        $html .= '<th>Poin</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        // Get countries for the current group
        $countries = $conn->query("SELECT * FROM countries WHERE group_id = $group_id ORDER BY points DESC, wins DESC");

        while ($country = $countries->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . $country['country_name'] . '</td>';
            $html .= '<td>' . $country['wins'] . '</td>';
            $html .= '<td>' . $country['draws'] . '</td>';
            $html .= '<td>' . $country['losses'] . '</td>';
            $html .= '<td>' . $country['points'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
    }

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("report.pdf");
    exit();
}
?>

<form method="POST" style="text-align: center;">
    <input type="submit" value="Cetak PDF" style="margin-top: 20px; padding: 10px 20px; border: none; border-radius: 20px; background-color: #007bff; color: white; cursor: pointer;">
</form>

<?php
// Display the same HTML for the webpage
$current_time = date("d M Y H:i:s");
$nim = $_SESSION['nim'];

echo '<h1 style="text-align: center;">Data Negara UEFA 2024</h1>';
echo '<p style="text-align: center;">Per ' . $current_time . ' (Waktu dan Jam Sekarang)</p>';
echo '<p style="text-align: center;">NIM: ' . $nim . '</p>';

// Get groups
$groups = $conn->query("SELECT * FROM groups");

while ($group = $groups->fetch_assoc()) {
    $group_id = $group['id'];
    $group_name = $group['group_name'];

    echo '<h2 style="text-align: center;">Data Group ' . $group_name . '</h2>';
    echo '<table border="1" width="100%" style="border-collapse: collapse; margin: auto;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Tim</th>';
    echo '<th>Menang</th>';
    echo '<th>Seri</th>';
    echo '<th>Kalah</th>';
    echo '<th>Poin</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Get countries for the current group
    $countries = $conn->query("SELECT * FROM countries WHERE group_id = $group_id ORDER BY points DESC, wins DESC");

    while ($country = $countries->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $country['country_name'] . '</td>';
        echo '<td>' . $country['wins'] . '</td>';
        echo '<td>' . $country['draws'] . '</td>';
        echo '<td>' . $country['losses'] . '</td>';
        echo '<td>' . $country['points'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}
?>
