<?php
include 'includes/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css?v=p<?php echo time(); ?>">
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($_GET['status'])) {
            if ($_GET['status'] == 'Account Created') {
                echo '<p style="color:green;">' . $_GET['status'] . '<p>';
            } else {
                echo '<p style="color:red;">' . $_GET['status'] . '<p>';
            }
        } ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>

</html>