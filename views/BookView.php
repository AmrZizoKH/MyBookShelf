<?php
require_once('../vendor/autoload.php');
use \App\Warnings;
(new \App\Authenticate())->redirectIfNotAuth();
$BookObj = new \App\Operations();
$bookViewData = $BookObj->handleBookViewRequest();
$action = $bookViewData['action'];
$bookToEdit = $bookViewData['bookToEdit'];
$bookToView = $bookViewData['bookToView'];
$allBooks = $bookViewData['allBooks'];
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/auth.css">
    <link rel="stylesheet" href="../resources/css/nav.css?v=20260424">
    <title>My Books</title>
</head>
<body>
<?php require_once '../layout/nav.php'; ?>


<div class="To-Do-List">
    <?php if ($action === 'view' && !$bookToView): ?>
        <div class="container" style="margin-top: 20px; max-width: 980px;">
            <?php Warnings::printMessage("Book not found or you are not allowed to view it.", "danger"); ?>
        </div>
    <?php endif; ?>

    <?php if ($bookToEdit): ?>
        <div class="container col-4 bookview-update-container" style="margin-top: 20px;">
            <h2 style="font-style: italic;">Update Book</h2>
            <form action="BookView.php?action=update&book_id=<?php echo (int) $bookToEdit['book_id']; ?>" method="post">
                <div class="field">
                    <label for="title">Name</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?php echo htmlspecialchars($_POST['title'] ?? $bookToEdit['title']); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="author">Author</label>
                    <input
                        type="text"
                        id="author"
                        name="author"
                        value="<?php echo htmlspecialchars($_POST['author'] ?? $bookToEdit['author']); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="genre">Genre</label>
                    <input
                        type="text"
                        id="genre"
                        name="genre"
                        value="<?php echo htmlspecialchars($_POST['genre'] ?? $bookToEdit['genre']); ?>"
                        required
                    >
                </div>

                <div class="field">
                    <label for="note">Note</label>
                    <textarea
                        id="note"
                        name="note"
                        placeholder="Update your note about this book"
                        required
                    ><?php echo htmlspecialchars($_POST['note'] ?? (string) ($bookToEdit['note'] ?? '')); ?></textarea>
                </div>

                <div class="text-center" style="margin-top: 20px;">
                    <button type="submit" name="updateTaskBtn" class="btn btn-warning text-center">Save Changes</button>
                    <a href="BookView.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <hr>
        </div>
    <?php elseif ($action === 'update'): ?>
        <div class="container" style="margin-top: 20px;">
            <?php Warnings::printMessage("Book not found or you are not allowed to update it.", "danger"); ?>
        </div>
    <?php endif; ?>

    <div class="bookview-hero">
        <h1 class="bookview-title">my books</h1>
        <p class="bookview-subtitle">your personal reading list</p>
    </div>

    <table class="table table-dark container bookview-table">
        <tr>
            <th>Name</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($allBooks as $book): ?>
             <tr> 
                <td>
                    <?php echo htmlspecialchars($book['title']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($book['author']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($book['genre']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string) ($book['release_date'] ?? 'N/A')); ?>
                </td>
                <td class="action-buttons">
                    <a href="BookView.php?action=view&book_id=<?php echo (int) $book['book_id']; ?>#book-<?php echo (int) $book['book_id']; ?>">
                        <button class="btn btn-info text-center" type="button">View</button>
                    </a>
                    <a href="BookView.php?action=update&book_id=<?php echo (int) $book['book_id']; ?>">
                        <button class="btn btn-warning text-center" type="button">Update</button>
                    </a>
                    <a href="BookView.php?action=delete&book_id=<?php echo (int) $book['book_id']; ?>" onclick="return confirm('Delete this book?');">
                        <button class="btn btn-danger text-center" type="button">Delete</button>
                    </a>
                </td>
            </tr> 

            <?php if ($action === 'view' && $bookToView && (int) $bookToView['book_id'] === (int) $book['book_id']): ?>
                <tr class="book-note-row" id="book-<?php echo (int) $book['book_id']; ?>">
                    <td colspan="5" class="book-note-cell">
                        <div class="book-note-inline-layout" style="display:flex; width:100%; align-items:center; gap:14px;">
                            <div class="book-note-inline-left">
                                <div class="book-note-inline-book"><strong>Book:</strong> <?php echo htmlspecialchars((string) ($bookToView['title'] ?? 'Unknown')); ?></div>
                                <?php if (!empty($bookToView['cover_image'])): ?>
                                    <div style="margin-bottom:10px;">
                                        <img
                                            src="../<?php echo htmlspecialchars((string) $bookToView['cover_image']); ?>"
                                            alt="Cover image for <?php echo htmlspecialchars((string) ($bookToView['title'] ?? 'book')); ?>"
                                            style="max-width:140px; max-height:210px; object-fit:cover; border-radius:6px; border:1px solid #c4a77a;"
                                        >
                                    </div>
                                <?php endif; ?>
                                <div class="book-note-inline-note"><?php echo nl2br(htmlspecialchars((string) ($bookToView['note'] ?? 'No note added.'))); ?></div>
                            </div>
                            <div class="book-note-inline-right" style="margin-left:auto; display:flex; justify-content:flex-end; align-items:center;">
                                <a href="BookView.php" class="btn btn-note-inline-close">Close</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>


</div>
<hr>

</body>
</html> 
