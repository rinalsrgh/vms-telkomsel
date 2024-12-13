<?php
require_once 'D:/Data Software Kuliah/Xampp/htdocs/VisitorManagementSystem/vendor/autoload.php';


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "if0_36995947_visitors";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan rentang tanggal dari query parameter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : "";
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : "";

// Mengambil data dari tabel visits dan visitors1 berdasarkan rentang tanggal
$sql = "
    SELECT 
        visits.id_visit, 
        visits.visit_purpose, 
        visits.visit_number, 
        visits.building, 
        visits.status, 
        visitors1.name, 
        visitors1.contact_number, 
        visits.checkin_time as checkin, 
        visits.checkout_time as checkout, 
        visits.created_at, 
        DATE(visits.created_at) as date
    FROM visits 
    JOIN visitors1 ON visits.id_visitor = visitors1.id_visitor
    WHERE DATE(visits.created_at) BETWEEN '$start_date' AND '$end_date'
    ORDER BY visits.created_at DESC
";

$result = $conn->query($sql);

// Inisialisasi mPDF
$mpdf = new \Mpdf\Mpdf();

// Membuat konten HTML untuk PDF
$html = '<h2>Visitor Report</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Name</th>
            <th>Building</th>
            <th>Purpose</th>
            <th>Contact</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>' . $row["id_visit"] . '</td>
            <td>' . $row["date"] . '</td>
            <td>' . $row["name"] . '</td>
            <td>' . $row["building"] . '</td>
            <td>' . $row["visit_purpose"] . '</td>
            <td>' . $row["contact_number"] . '</td>
            <td>' . $row["checkin"] . '</td>
            <td>' . $row["checkout"] . '</td>
            <td>' . $row["created_at"] . '</td>
            <td>' . $row["status"] . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="10">No visitors found</td></tr>';
}

$html .= '</tbody></table>';

// Menulis HTML ke dalam file PDF
$mpdf->WriteHTML($html);

// Output file PDF ke browser
$filename = 'Visitor_Report_' . date('Ymd') . '.pdf';
$mpdf->Output($filename, 'D');

// Menutup koneksi database
$conn->close();
?>
