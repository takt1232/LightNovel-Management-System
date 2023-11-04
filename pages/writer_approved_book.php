<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $bookId = $_POST['publish-book-id'];
        $writerApproval = 'Approved by writer';

        // Prepare and execute the SQL query to update book details
        $stmt = $pdo->prepare("UPDATE books SET approval_token = ? WHERE book_id = ?");
        $stmt->execute([$writerApproval, $bookId]);

        echo '<script>';
        echo 'if(confirm("Book Approved successfully. Press OK to continue.")){';
        echo '  window.location.href = "writer_view_books.php";';
        echo '}';
        echo '</script>';
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
