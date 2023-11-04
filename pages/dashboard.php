<?php
include '../includes/functions.php';

session_start();

$sql = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE status='Published'";
$params = [];
$books = executeQuery($sql, $params);

if ($_SESSION['role'] == 'Writer' || $_SESSION['role'] == 'Viewer') {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Viewer Dashboard</title>
        <link rel="stylesheet" href="dashboard_style.css?v=p<?php echo time(); ?>">
    </head>

    <body>
        <?php
        include '../includes/sidebar.php';
        ?>

        <div class="content">
            <div class="title-page">
                <h1>Book Library</h1>
            </div>
            <div class="box">
                <?php foreach ($books as $book) : ?>
                    <a href="book.php?bookId=<?php echo $book['book_id']; ?>" class="card-link">
                        <div class="card">
                            <div class="cover-wrapper">
                                <?php if (!empty($book['book_cover']) && file_exists($book['book_cover'])) : ?>
                                    <img src="<?php echo $book['book_cover']; ?>" alt="Book Cover" style="max-width: 100%; height: auto;">
                                <?php else : ?>
                                    <img src="../images/no-cover-art.jpg" alt="Placeholder Image" style="max-width: 100%; height: auto;">
                                <?php endif; ?>
                            </div>
                            <h2><?php echo $book['book_title']; ?></h2>
                            <p>Author: <?php echo $book['username']; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </body>

    </html>

<?php
} else {
    header('Location: ../index.php?status=Access Denied');
    exit();
}
