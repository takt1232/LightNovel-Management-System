<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $bookId = $_POST['book-id'];
        $userId = $_POST['user-id'];
        $selectedRating = $_POST['star'];
        $comment = $_POST['comment'];

        // Prepare the SQL statement for insertion
        $stmt = $pdo->prepare("INSERT INTO reviews (book_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$bookId, $userId, $selectedRating, $comment]);

        echo '<script>';
        echo 'if(confirm("Book submitted successfully. Press OK to continue.")){';
        echo 'window.history.back();';
        echo '}';
        echo '</script>';
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
