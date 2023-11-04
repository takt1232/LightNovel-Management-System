<?php
include '../includes/sidebar.php';
include '../includes/functions.php';

// Get the logged-in user's user_id
$user_id = $_SESSION['user_id'];

$sql = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE author_id = ? AND status = 'Submitted'";
$params = [$user_id];
$pendingBooks = executeQuery($sql, $params);

$sql1 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE author_id = ? AND status = 'Approved'";
$params1 = [$user_id];
$approvedBooks = executeQuery($sql1, $params1);

$sql2 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE author_id = ? AND status = 'Approved' AND approval_token = 'Approved by writer' ";
$params2 = [$user_id];
$completeApprovalBooks = executeQuery($sql2, $params2);

$sql3 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE author_id = ? AND status = 'Published'";
$params3 = [$user_id];
$publishedBooks = executeQuery($sql3, $params3);

?>

<div class="content">
    <div class="box">
        <!-- Upload a Book Section -->
        <div class="upload-section">
            <h3><i class="fas fa-upload" onclick="openUploadModal()"></i> Upload a Book</h3>
        </div>
        <div class="divider"></div>

        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter" onchange="filterBooks()">
            <option value="Published">Published</option>
            <option value="Submitted">Submitted</option>
            <option value="Approved">Approved</option>
            <option value="Complete Approval">Complete Approval</option>
        </select>
        <div class="divider"></div>

        <div class="table-container">
            <table id="booksTable">
                <!-- Table content will be dynamically generated here -->
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <form action="edit_book.php" method="POST" enctype="multipart/form-data">
            <h2>Edit Book</h2>
            <div class="divider"></div>
            <p>Upload a book in PDF format only.</p>
            <input type="text" name="edit-book-title" id="edit-book-title" placeholder="Book Title" required>
            <textarea name="edit-synopsis" id="edit-synopsis" placeholder="Book Synopsis" required></textarea>

            <!-- Checkbox to toggle file upload -->
            <div class="toggle-wrapper">
                <input type="checkbox" id="update-pdf" onchange="toggleFileUpload()">
                <label for="update-pdf">Update PDF</label>
            </div>

            <!-- File input field -->
            <input type="file" name="edit-myfile" id="edit-myfile" accept=".pdf" style="display: none;">

            <input type="number" name="edit-user-id" id="edit-user-id" hidden>
            <input type="number" name="edit-book-id" id="edit-book-id" hidden>
            <input type="text" name="edit-file-path" id="edit-file-path" hidden>

            <div class="btn-div">
                <button type="submit" class="modal-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <form action="del_book.php" method="POST">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Delete Confirmation</h2>
            <div class="divider"></div>
            <p>Are you sure you want to delete this book?</p>
            <input type="number" name="del-book-id" id="del-book-id" hidden>
            <button type="submit" class="modal-btn" onclick="deleteBook()">Delete</button>
        </form>
    </div>
</div>

<!-- Publish Modal -->
<div id="publishModal" class="modal">
    <div class="modal-content">
        <form action="writer_approved_book.php" method="POST">
            <span class="close" onclick="closePublishModal()">&times;</span>
            <h2>Approval Confirmation</h2>
            <div class="divider"></div>
            <p>Are you sure you want to approved the publishing of this book?</p>
            <input type="number" name="publish-book-id" id="publish-book-id" hidden>
            <button type="submit" class="modal-btn">Approved</button>
        </form>
    </div>
</div>

<script>
    function generateTable(data) {
        var tableContent = '';
        if (data.length > 0) {
            tableContent += `
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Synopsis</th>
                    <th>Status</th>
                    <th>Author's Approval</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            `;

            data.forEach(function(book) {
                tableContent += `
                    <tr>
                        <td>${book['book_title']}</td>
                        <td>${book['username']}</td>
                        <td>${book['synopsis']}</td>
                        <td>${book['status']}</td>
                        <td>${book['approval_token']}</td>
                        <td>
                            <a href="${book['file_path']}" target="_blank">Open PDF</a>
                        </td>
                        <td>
                            <a href="#" class="action-icon">
                                <i class="fas fa-edit" onclick="openEditModal(${JSON.stringify(book)})"></i>
                            </a>
                            <a href="#" class="action-icon">
                                <i class="fas fa-upload" onclick="openPublishModal(${JSON.stringify(book)})"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
        } else {
            tableContent += '<tr><td colspan="7">No books available.</td></tr>';
        }

        document.getElementById('booksTable').innerHTML = tableContent;
    }

    function filterBooks() {
        var selectedStatus = document.getElementById('statusFilter').value;
        var booksData = <?php echo json_encode([$publishedBooks, $pendingBooks, $approvedBooks, $completeApprovalBooks]); ?>;
        var filteredData = [];

        switch (selectedStatus) {
            case 'Published':
                filteredData = booksData[0]; // Index 3 corresponds to publishedBooks
                break;
            case 'Submitted':
                filteredData = booksData[1]; // Index 1 corresponds to submittedBooks
                break;
            case 'Approved':
                filteredData = booksData[2]; // Index 2 corresponds to adminApprovedBooks
                break;
            case 'Complete Approval':
                filteredData = booksData[3]; // Index 4 corresponds to completeApprovalBooks
                break;
        }

        generateTable(filteredData);
    }

    // Initially, load the table with published books
    generateTable(<?php echo json_encode($publishedBooks); ?>);
</script>

<script>
    function openDeleteModal(bookId) {
        document.getElementById('deleteModal').style.display = 'block';

        document.getElementById('del-book-id').value = bookId;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function openEditModal(bookData) {
        document.getElementById('editModal').style.display = 'block';

        document.getElementById('edit-book-title').value = bookData.book_title;
        document.getElementById('edit-synopsis').value = bookData.synopsis;
        document.getElementById('edit-book-id').value = bookData.book_id;
        document.getElementById('edit-user-id').value = bookData.author_id;
        document.getElementById('edit-file-path').value = bookData.file_path;
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function openPublishModal(bookId) {
        document.getElementById('publishModal').style.display = 'block';

        document.getElementById('publish-book-id').value = bookId;
    }

    function closePublishModal() {
        document.getElementById('publishModal').style.display = 'none';
    }

    function toggleFileUpload() {
        var fileInput = document.getElementById('edit-myfile');
        if (fileInput.style.display === 'none') {
            fileInput.style.display = 'block';
        } else {
            fileInput.style.display = 'none';
        }
    }
</script>