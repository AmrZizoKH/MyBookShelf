<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Authenticate;
$auth = new Authenticate();
$auth->redirectIfNotAuth();
$auth->logout();

$booksResult = (new \App\Operations())->ViewBooks();

?>

<!doctype html>
<html lang="en">
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="../resources/css/auth.css?v=20260424">
    <link rel="stylesheet" href="../resources/css/nav.css?v=20260424">
</head>
<body>
<?php require_once '../layout/nav.php'; ?>

    <main class="home-page-wrap">
        <section class="home_PB">
            <h1>Welcome Back, <?php echo htmlspecialchars((string) ($_SESSION['userName'] ?? 'Reader')); ?></h1>
            <p>Your books are waiting for you!</p>
        </section>

        <section class="home-books-section container">
            <h2 class="home-books-title">Your Book Cards</h2>

            <div class="home-cards-wrapper">
                <?php if ($booksResult && $booksResult->num_rows > 0): ?>
                    <div class="home-books-grid">
                        <?php foreach ($booksResult as $book): ?>
                            <article class="home-book-card">
                                <h3 class="home-book-heading">
                                    <?php echo htmlspecialchars((string) ($book['title'] ?? 'Untitled')); ?>
                                    <span>by <?php echo htmlspecialchars((string) ($book['author'] ?? 'Unknown')); ?></span>
                                </h3>

                                <div class="home-book-cover-wrap">
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <img
                                            class="home-book-cover"
                                            src="../<?php echo htmlspecialchars((string) $book['cover_image']); ?>"
                                            alt="Cover for <?php echo htmlspecialchars((string) ($book['title'] ?? 'book')); ?>"
                                        >
                                    <?php else: ?>
                                        <div class="home-book-cover home-book-cover-placeholder">No cover</div>
                                    <?php endif; ?>
                                </div>

                                <p class="home-book-note">
                                    <?php echo nl2br(htmlspecialchars((string) ($book['note'] ?? 'No note added yet.'))); ?>
                                </p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="home-no-books">You have not added any books yet. Add one and a card will appear here.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>