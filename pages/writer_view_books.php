<?php
session_start();
$userId = $_SESSION['user_id'];
$userName = $_SESSION['username'];
$role = $_SESSION['role'];
if ($role == 'Writer') {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="dashboard.css?v=p<?php echo time(); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    </head>

    <body>
        <?php
        include 'view_books.php';
        ?>

        <!-- Modal -->
        <div id="uploadModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeUploadModal()">&times;</span>
                <form action="add_book.php" method="POST" enctype="multipart/form-data">
                    <h2>Upload Instructions</h2>
                    <p>Upload a book in PDF format only.</p>
                    <p>You can upload pictures but only accept .jpeg/jpg file type.</p>

                    <label>Book Title:</label>
                    <input type="text" name="book-title" id="book-title" placeholder="Book Title" required>

                    <label>Book Synopsis:</label>
                    <textarea name="synopsis" id="synopsis" placeholder="Book Synopsis" required></textarea>

                    <label>Book File:</label>
                    <input type="file" name="myfile" id="fileInput" accept=".pdf" required />

                    <label>Book Cover (Optional):</label>
                    <input type="file" name="book-cover" id="book-cover" accept=".jpeg, .jpg" />

                    <input type="number" name="user-id" id="user-id" value="<?php echo $userId; ?>" hidden>
                    <div class="btn-div">
                        <button class="modal-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        </div>

        <script>
            function openUploadModal() {
                document.getElementById("uploadModal").style.display = "block";
            }

            function closeUploadModal() {
                document.getElementById("uploadModal").style.display = "none";
            }
        </script>

    </body>

    </html>

<?php
} else {
    header('Location: ../index.php?status=Access Denied');
    exit();
}
?>