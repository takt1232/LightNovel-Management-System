<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve form data
        $bookTitle = $_POST['book-title'];
        $synopsis = $_POST['synopsis'];
        $userId = $_POST['user-id'];
        $approvalToken = 'Not Approved by writer';
        $status = 'Submitted';

        // Check if a file was uploaded
        if (isset($_FILES['myfile']) && $_FILES['myfile']['error'] === UPLOAD_ERR_OK) {

            // Save the PDF as a file on your server
            $pdfFilePath = '../uploads/' . uniqid() . '.pdf'; // Generate a unique file name
            move_uploaded_file($_FILES['myfile']['tmp_name'], $pdfFilePath);

            if (isset($_FILES['book-cover']) && $_FILES['book-cover']['error'] === UPLOAD_ERR_OK) {
                // Save the book cover as a file on your server
                $coverFilePath = '../images/' . uniqid() . '.' . pathinfo($_FILES['book-cover']['name'], PATHINFO_EXTENSION); // Generate a unique file name with the correct extension
                move_uploaded_file($_FILES['book-cover']['tmp_name'], $coverFilePath);
            } else {
                // If no book cover was uploaded, set $coverFilePath to null or an appropriate default value
                $coverFilePath = null; // Modify this based on your specific requirements
            }

            // Insert the book into the database with the file paths
            $stmt = $pdo->prepare("INSERT INTO books (book_title, synopsis, author_id, status, approval_token, file_path, book_cover) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $bookTitle, $synopsis, $userId, $status, $approvalToken, $pdfFilePath, $coverFilePath
            ]);

            // Notify user of successful upload using JavaScript confirm dialog
            echo '<script>';
            echo 'if(confirm("Book submitted successfully. Press OK to continue.")){';
            echo '  window.location.href = "writer_view_books.php";';
            echo '}';
            echo '</script>';
        } else {
            // Handle file upload error (you can customize this message)
            echo 'Error uploading file.';
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo 'Error: ' . $e->getMessage();
    }
}
