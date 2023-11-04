<?php
include '../includes/functions.php';

session_start();

if ((isset($_GET['bookId']))) {
    try {
        $bookId = $_GET['bookId'];

        $sql = "SELECT *, users.username FROM books INNER JOIN users ON books.author_id = users.user_id WHERE book_id = ?";
        $params = [$bookId];
        $book = executeQuery($sql, $params);

        // Calculate Average Rating and Retrieve Comments
        $stmt = $pdo->prepare("SELECT ROUND(AVG(rating), 1) as avg_rating FROM reviews WHERE book_id = ?");
        $stmt->execute([$bookId]);
        $avg_rating = $stmt->fetchColumn();

        if (!$avg_rating) {
            $avg_rating = 0;
        }

        $stmt = $pdo->prepare("SELECT *, users.username FROM reviews INNER JOIN users ON reviews.user_id = users.user_id WHERE book_id = ?");
        $stmt->execute([$bookId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewer Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css?v=p<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
    <?php
    if ($_SESSION['role'] == 'Admin') {
        include '../includes/admin_sidebar.php';
    } elseif ($_SESSION['role'] == 'Writer' || $_SESSION['role'] == 'Viewer') {
        include '../includes/sidebar.php';
    }
    ?>

    <div class="content">
        <div class="box">
            <div class="card-one">
                <?php if (!empty($book['book_cover']) && file_exists($book['book_cover'])) : ?>
                    <img class="card-img" src="<?php echo $book['book_cover']; ?>" alt="Book Cover">
                <?php else : ?>
                    <img class="card-img" src="../images/no-cover-art.jpg" alt="Placeholder Image">
                <?php endif; ?>
            </div>
            <div class="title-page">
                <h1><?php echo $book[0]['book_title']; ?></h1>

                <p><?php echo $book[0]['synopsis']; ?></p>
                <div class="divider"></div>

                <!-- Display Average Rating -->
                <div class="rating-wrapper">
                    <div class="average-rating">
                        <?php
                        function displayStars($rating)
                        {
                            $fullStars = floor($rating);
                            $halfStar = $rating - $fullStars >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star" style="color:yellow;"></i>';
                            }

                            if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt" style="color:yellow;"></i>';
                            }

                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="fas fa-star" style="color:white"></i>';
                            }
                        }

                        displayStars($avg_rating);
                        ?>
                    </div>

                    <p><?php echo $avg_rating; ?></p>
                </div>

                <!-- Display Comments -->
                <div class="comments">
                    <p class="review-title">POPULAR REVIEWS</p>
                    <div class="divider"></div>
                    <?php if (count($comments) == 0) {
                        echo '<p>No review yet</p>';
                    } else {
                        foreach ($comments as $comment) : ?>
                            <div class="comment">
                                <p>Review by <?php echo $comment['username']; ?></p>
                                <p>Comment: <?php echo $comment['comment']; ?></p>
                            </div>
                    <?php endforeach;
                    } ?>
                </div>
            </div>
        </div>
        <!-- Add Review Form -->
        <div class="add-review-form">
            <h3>Add Your Review</h3>
            <div class="divider"></div>
            <form id="reviewForm" action="add_review.php" method="POST">
                <input type="hidden" name="book-id" value="<?php echo $bookId; ?>">
                <input type="hidden" name="user-id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="star-rating" id="starRating">
                    <input type="radio" name="star" id="star1" value="5"><label for="star1"></label>
                    <input type="radio" name="star" id="star2" value="4"><label for="star2"></label>
                    <input type="radio" name="star" id="star3" value="3"><label for="star3"></label>
                    <input type="radio" name="star" id="star4" value="2"><label for="star4"></label>
                    <input type="radio" name="star" id="star5" value="1"><label for="star5"></label>
                </div>
                <textarea name="comment" placeholder="Add your comment..." required></textarea>
                <button type="submit">Submit Review</button>
            </form>
        </div>
    </div>
</body>

</html>