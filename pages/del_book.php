<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve the book ID from the POST data
        $bookId = $_POST['del-book-id'];

        // Prepare and execute the SQL query to delete the book
        $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
        $stmt->execute([$bookId]);

        // Redirect back to the page where the deletion was initiated
        echo '<script>';
        echo 'if(confirm("Book deleted successfully. Press OK to continue.")){';
        echo '  window.location.href = "writer_view_books.php";';
        echo '}';
        echo '</script>';
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
