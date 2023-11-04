<?php
session_start();
if ($_SESSION['role'] == 'Admin') {
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
        include '../includes/admin_sidebar.php';

        include  'admin_view_books.php';
        ?>

        <div class="content">
            <!-- Admin Dashboard Content Goes Here -->
        </div>
    </body>

    </html>

<?php
} else {
    header('Location: ../index.php?status=Access Denied');
    exit();
}
