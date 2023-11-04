<div class="sidebar">
    <h2><a href="dashboard.php">Viewer Dashboard</a></h2>
    <ul>
        <li><a href="#">Manage Account</a></li>
        <?php
        if ($_SESSION['role'] == 'Writer') {
            echo '<li><a href="writer_view_books.php">View Books</a></li>';
        }
        ?>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>