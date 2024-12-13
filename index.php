<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Registration Form</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
   .suggestions {
       border: 1px solid #ccc;
       border-radius: 5px;
       background: #fff;
       max-height: 150px;
       overflow-y: auto;
       position: absolute;
       z-index: 1000;
   }

   .suggestions ul {
       list-style-type: none;
       padding: 0;
       margin: 0;
   }

   .suggestions li {
       padding: 8px;
       cursor: pointer;
   }

   .suggestions li:hover {
       background-color: #f0f0f0;
   }
</style>

</head>
<body>
    <footer class = "top-footer">
        <img src = "logo4.png" alt="logo" class="logo">
        <div class="dropdown">
            <button class="dropbtn">Login</button>
            <div class="dropdown-content">
                <a href="admin/login.php">Admin Login</a>
                <a href="security/login.php">Security Login</a>
            </div>
        </div>
    </footer>
    <div class="container">
        <h2>Visitor Registration Form</h2>
        <form id="visitorForm" action="submit.php" method="post">
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" onkeyup="searchVisitor(this.value)" autocomplete="off" required>
            <div id="suggestions" class="suggestions"></div>

            <label for="building">Building:</label>
            <select id="building" name="building" required>
                <option value="" disabled selected>Select Building:</option>
                <option value="SCS">SCS</option>
                <option value="Office">Office</option>
            </select>

            <label for="purpose">Purpose:</label>
            <input type="text" id="purpose" name="purpose" required>

            <label for="vehicle_number">Vehicle Number:</label>
            <input type="text" id="vehicle_number" name="vehicle_number" required>
            
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
            
            <input type="submit" value="Submit">
        </form>
    </div>
    
    <script>
    document.getElementById('visitorForm').addEventListener('submit', function(event) {
    var date = document.getElementById('date').value;
    var name = document.getElementById('name').value;
    var building = document.getElementById('building').value;
    var purpose = document.getElementById('purpose').value;
    var phone = document.getElementById('phone').value;
    var checkin = document.getElementById('checkin').value;
    var checkout = document.getElementById('checkout').value;

    if (!date || !name || !building || !purpose || !phone || !checkin || !checkout) {
        alert('Please fill out all fields');
        event.preventDefault();
    }
});

function searchVisitor(query) {
    if (query.length < 2) {
        document.getElementById('suggestions').innerHTML = '';
        return;
    }
    
    fetch('search_visitor.php?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            let suggestions = '';
            if (data.length > 0) {
                suggestions = '<ul>';
                data.forEach(visitor => {
                    suggestions += `<li onclick="selectVisitor('${visitor.name}', '${visitor.vehicle_number}', '${visitor.contact_number}')">${visitor.name}</li>`;
                });
                suggestions += '</ul>';
            }
            document.getElementById('suggestions').innerHTML = suggestions;
        });
}

function selectVisitor(name, vehicle_number, contact_number) {
    document.getElementById('name').value = name;
    document.getElementById('vehicle_number').value = vehicle_number;
    document.getElementById('phone').value = contact_number;
    document.getElementById('suggestions').innerHTML = '';
}


    </script>
</body>
</html>
