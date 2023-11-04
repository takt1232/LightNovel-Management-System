<?php
session_start(); // Start the session

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page (or any other desired page)
header('Location: ../index.php'); // Change 'login.html' to the actual login page
exit();
