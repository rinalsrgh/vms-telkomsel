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

if (isset($_POST['add'])) {
    $date = $_POST['date'];
    $name = $_POST['name'];
    $building = $_POST['building'];
    $purpose = $_POST['purpose'];
    $phone = $_POST['phone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    $sql = "INSERT INTO visitors (date, name, building, purpose, phone, checkin, checkout, created_at) 
            VALUES ('$date', '$name', '$building', '$purpose', '$phone', '$checkin', '$checkout', NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Visitor</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
    <script>
        function validateForm() {
            var phone = document.getElementById("phone").value;
            var phonePattern = /^[0-9]+$/;
            var errorMessage = document.getElementById("phone-error");

            if (!phonePattern.test(phone)) {
                errorMessage.textContent = "Please enter a valid phone number with digits only.";
                return false; // Prevent form submission
            } else {
                errorMessage.textContent = ""; // Clear error message
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>
    <footer class="top-footer">
        <img src="../logo4.png" alt="logo" class="logo">
    </footer>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../logo4.png" alt="logo" class="logo">
                </div>
            </div>
           
            <!-- sidebar menu: : style can be found in sidebar.less -->
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
        <!-- /.sidebar -->
    </aside>

    <div class="container">
        <h2>Tambah Data Pengunjung</h2>
        <form method="post" action="add.php" onsubmit="return validateForm()">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="building">Building:</label>
            <select id="building" name="building" required>
                <option value="" disabled selected>Select Building:</option>
                <option value="SCS">SCS</option>
                <option value="Office">Office</option>
            </select>
            <label for="purpose">Purpose:</label>
            <input type="text" id="purpose" name="purpose" required>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
            <div id="phone-error" class="error-message"></div>
            <label for="checkin">Check-in Time:</label>
            <input type="time" id="checkin" name="checkin" required>
            <label for="checkout">Check-out Time:</label>
            <input type="time" id="checkout" name="checkout" required>
            <input type="submit" name="add" value="Add">
        </form>
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
$conn->close();
?>
