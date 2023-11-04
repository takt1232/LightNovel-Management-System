<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $adminApproval = $_POST['admin-approval'];
        $writerApproval = $_POST['writer-approval'];

        if ($adminApproval == 'Approved' && $writerApproval == 'Approved by writer') {
            // Retrieve form data
            $bookId = $_POST['publish-book-id'];
            $bookStatus = 'Published';

            // Prepare and execute the SQL query to update book details
            $stmt = $pdo->prepare("UPDATE books SET status = ? WHERE book_id = ?");
            $stmt->execute([$bookStatus, $bookId]);

            echo '<script>';
            echo 'if(confirm("Book publish successfully. Press OK to continue.")){';
            echo '  window.location.href = "admin_manage_books.php";';
            echo '}';
            echo '</script>';
        } else {
            echo '<script>';
            echo 'if(confirm("Publish failed please seek proper approval. Press OK to continue.")){';
            echo '  window.location.href = "admin_manage_books.php";';
            echo '}';
            echo '</script>';
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
