<?php
session_start(); // Start the session
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve user input from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // You should add validation and sanitization here

        // Check if the username exists in the database
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['password']) {
            // If username and password match, create session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to a logged-in page

            if ($user['role'] == 'Admin') {
                header('Location: pages/admin_dashboard.php');
                exit();
            } elseif ($user['role'] == 'Writer' || $user['role'] == 'Viewer') {
                header('Location: pages/dashboard.php');
                exit();
            } else {
                header('Location: index.php?status=Unknown role');
                exit();
            }
        } else {
            // Invalid username or password, display an error message
            echo '<script>alert("Invalid username or password.");</script>';
            header('Location: index.php?statu=Invalid username or password');
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
