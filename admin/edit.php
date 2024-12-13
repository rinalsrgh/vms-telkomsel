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

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $name = $_POST['name'];
    $building = $_POST['building'];
    $purpose = $_POST['purpose'];
    $phone = $_POST['phone'];
    $checkin = $date . ' ' . $_POST['checkin'] . ':00'; // Format datetime
    $checkout = $date . ' ' . $_POST['checkout'] . ':00'; // Format datetime

    // Mengupdate data pada tabel visits
    $sql = "UPDATE visits 
    JOIN visitors1 ON visits.id_visitor = visitors1.id_visitor
    SET visits.checkin_time='$checkin', 
        visits.checkout_time='$checkout', 
        visits.visit_purpose='$purpose', 
        visits.building='$building', 
        visitors1.name='$name', 
        visitors1.contact_number='$phone'
    WHERE visits.id_visit='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_visitor.php");
        exit; // Pastikan untuk keluar setelah header redirect
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
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
    WHERE visits.id_visit='$id'
";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Visitor</title>
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
    <div class="container">
        <h2>Edit Visitor</h2>
        <form method="post" action="edit.php" onsubmit="return validateForm()">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_visit']); ?>">
            <table>
                <tr>
                    <td><label for="date">Date:</label></td>
                    <td><input type="date" id="date" name="date" value="<?php echo htmlspecialchars($row['date']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="name">Name:</label></td>
                    <td><input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="building">Building:</label></td>
                    <td><input type="text" id="building" name="building" value="<?php echo htmlspecialchars($row['building']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="purpose">Purpose:</label></td>
                    <td><input type="text" id="purpose" name="purpose" value="<?php echo htmlspecialchars($row['visit_purpose']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="phone">Phone:</label></td>
                    <td>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required>
                        <div id="phone-error" class="error-message"></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="checkin">Check-in Time:</label></td>
                    <td><input type="time" id="checkin" name="checkin" value="<?php echo htmlspecialchars(substr($row['checkin'], 11, 5)); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="checkout">Check-out Time:</label></td>
                    <td><input type="time" id="checkout" name="checkout" value="<?php echo htmlspecialchars(substr($row['checkout'], 11, 5)); ?>" required></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="update" value="Update" class="button"></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
