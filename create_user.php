<?php
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Assuming the form sends data via POST

        // Retrieve user input from the form
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // You should add validation and sanitization here

        // Prepare and execute the SQL query to insert the new user
        $stmt = $pdo->prepare("INSERT INTO Users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);

        header("Location: index.php?status=Account Created");
    } catch (PDOException $e) {
        // Handle any database errors
        // Notify user with JavaScript if insertion is successful
        echo '<script>alert("User creation FAILED"' . $e->getMessage() . '");</script>';
        echo 'Error: ' . $e->getMessage();
    }
}
