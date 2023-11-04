<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $bookId = $_POST['edit-book-id'];
        $bookTitle = $_POST['edit-book-title'];
        $synopsis = $_POST['edit-synopsis'];
        $userId = $_POST['edit-user-id'];
        $prevFilePath = $_POST['edit-file-path'];

        // Check if a file was uploaded
        if (isset($_FILES['edit-myfile']) && $_FILES['edit-myfile']['error'] === UPLOAD_ERR_OK) {

            // Save the PDF as a file on your server
            $pdfFilePath = '../uploads/' . uniqid() . '.pdf'; // Generate a unique file name
            move_uploaded_file($_FILES['edit-myfile']['tmp_name'], $pdfFilePath);

            // Delete the previous file
            if (file_exists($prevFilePath)) {
                unlink($prevFilePath);
            }

            // Prepare and execute the SQL query to update book details
            $stmt = $pdo->prepare("UPDATE books SET book_title = ?, synopsis = ?, file_path = ? WHERE book_id = ?");
            $stmt->execute([$bookTitle, $synopsis, $pdfFilePath, $bookId]);

            echo '<script>';
            echo 'if(confirm("Book Edited successfully. Press OK to continue.")){';
            echo '  window.location.href = "window.history.back().php";';
            echo '}';
            echo '</script>';
        } else {
            // If no file was uploaded, update book details without changing file data
            $stmt = $pdo->prepare("UPDATE books SET book_title = ?, synopsis = ? WHERE book_id = ?");
            $stmt->execute([$bookTitle, $synopsis, $bookId]);

            echo '<script>';
            echo 'if(confirm("Book Edited successfully. Press OK to continue.")){';
            echo '  window.location.href = "upload_book.php";';
            echo '}';
            echo '</script>';
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
