<?php
include '../includes/functions.php';

// Get the logged-in user's user_id
$user_id = $_SESSION['user_id'];

$sql1 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE status = 'Submitted'";
$params1 = [];
$submittedBooks = executeQuery($sql1, $params1);

$sql2 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE status = 'Approved'";
$params2 = [];
$adminApprovedBooks = executeQuery($sql2, $params2);


$sql3 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE status = 'Approved' AND approval_token= 'Approved by writer'";
$params3 = [];
$completeApprovalBooks = executeQuery($sql3, $params3);

$sql4 = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE status = 'Published'";
$params4 = [];
$publishedBooks = executeQuery($sql4, $params4);

?>

<div class="content">
    <div class="box">
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
        var booksData = <?php echo json_encode([$publishedBooks, $submittedBooks, $adminApprovedBooks, $completeApprovalBooks]); ?>;
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
    function openEditModal(bookData) {
        document.getElementById('editModal').style.display = 'block';

        document.getElementById('edit-book-title').value = bookData.book_title;
        document.getElementById('edit-synopsis').value = bookData.synopsis;
        document.getElementById('edit-author').value = bookData.username;
        document.getElementById('edit-book-id').value = bookData.book_id;
        document.getElementById('edit-user-id').value = bookData.author_id;
        document.getElementById('edit-file-path').value = bookData.file_path;
        document.getElementById('edit-status').value = bookData.status;
        document.getElementById('edit-approval').value = bookData.approval_token;
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function openPublishModal(bookData) {
        document.getElementById('publishModal').style.display = 'block';

        document.getElementById('admin-approval').value = bookData.status;
        document.getElementById('writer-approval').value = bookData.approval_token;
        document.getElementById('publish-book-id').value = bookData.book_id;
    }

    function closePublishModal() {
        document.getElementById('publishModal').style.display = 'none';
    }
</script>