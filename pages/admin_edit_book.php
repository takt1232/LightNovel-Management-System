<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $bookId = $_POST['edit-book-id'];
        $bookStatus = $_POST['edit-status'];

        // Prepare and execute the SQL query to update book details
        $stmt = $pdo->prepare("UPDATE books SET status = ? WHERE book_id = ?");
        $stmt->execute([$bookStatus, $bookId]);

        echo '<script>';
        echo 'if(confirm("Book status edited successfully. Press OK to continue.")){';
        echo '  window.location.href = "admin_manage_books.php";';
        echo '}';
        echo '</script>';
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
