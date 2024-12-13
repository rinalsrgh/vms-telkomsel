<!DOCTYPE html>
<html>
<head>
    <title>Security Login</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Security Login</h2>
        <form method="post" action="authenticate.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
