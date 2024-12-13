<?php
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

// Menyiapkan variabel pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Menyusun query SQL dengan kondisi pencarian
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
    WHERE visitors1.name LIKE ?
    ORDER BY visits.created_at DESC
";

// Menyiapkan statement
$stmt = $conn->prepare($sql);

// Mengikat parameter
$searchTerm = '%' . $search . '%';
$stmt->bind_param('s', $searchTerm);

// Menjalankan statement
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../logo4.png" alt="logo" class="logo">
                </div>
            </div>
           
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="<?php if($page=='dashboard') { echo 'active'; }?>">
                    <a href="admin_dashboard.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="<?php if($page=='newvisitor') { echo 'active'; }?>">
                    <a href="new_visitor.php">
                        <i class="fa fa-user-plus"></i> <span>New Visitor</span>
                    </a>
                </li>
                <li class="<?php if($page=='manage_visitor') { echo 'active'; }?>">
                    <a href="manage_visitor.php">
                        <i class="fa fa-users"></i> <span>Manage Visitor</span>
                    </a>
                </li>
                <li class="<?php if($page=='report') { echo 'active'; }?>">
                    <a href="report.php">
                        <i class="fa fa-file-text"></i> <span>Report</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="security-login-button">
                        <i class="fa fa-sign-out"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>

    <!-- Tabel Data Pengunjung -->
    <div class="table-container">
        <h2>Data Pengunjung</h2>
        <!-- Formulir Pencarian -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Bangunan</th>
                    <th>Tujuan</th>
                    <th>Telepon</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Dibuat Pada</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                    <th>Set Checkout</th>
                </tr>
            </thead>
            <tbody>
    <?php
    // Menampilkan data pengunjung
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Menentukan kelas berdasarkan status
            $statusClass = '';
            if ($row["status"] == 'Waiting') {
                $statusClass = 'status-waiting';
            } elseif ($row["status"] == 'Confirmed') {
                $statusClass = 'status-confirmed';
            }
            
            echo "<tr>
                    <td>" . $row["id_visit"] . "</td>
                    <td>" . $row["date"] . "</td>
                    <td>" . $row["name"] . "</td>
                    <td>" . $row["building"] . "</td>
                    <td>" . $row["visit_purpose"] . "</td>
                    <td>" . $row["contact_number"] . "</td>
                    <td>" . $row["checkin"] . "</td>
                    <td>" . $row["checkout"] . "</td>
                    <td>" . $row["created_at"] . "</td>
                    <td class='" . $statusClass . "'>" . $row["status"] . "</td>
                    <td>
                        <form action='edit.php' method='GET' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row["id_visit"] . "'>
                            <button type='submit' class='edit-button'>Edit</button>
                        </form>
                    </td>
                    <td>
                        <form action='delete.php' method='GET' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row["id_visit"] . "'>
                            <button type='submit' class='delete-button' onclick='return confirm(\"Are you sure?\")'>Delete</button>
                        </form>

                    </td>
                    <td>
                        <form action='set_checkout.php' method='GET' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row["id_visit"] . "'>
                            <button type='submit' class='set-button'>Checkout</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='13'>Tidak ada data pengunjung</td></tr>";
    }
    ?>
</tbody>

        </table>
    </div>

    <footer class="footer">
        <div class="right">
            <p>&copy; PT TELEKOMUNIKASI SELULAR, 2024.</p>
        </div>
        <div class="right">
            <a href="https://www.telkomsel.com/privacy-policy">Privacy Policy</a> 
            <a href="https://www.telkomsel.com/terms-and-conditions">Terms of Service</a>
            <a href="https://www.telkomsel.com/support/contact-us">Contact Us</a>
        </div>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
