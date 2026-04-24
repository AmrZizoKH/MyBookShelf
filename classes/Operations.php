<?php

namespace App;
class Operations
{

    private function removeCoverImageFile(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }

        $prefix = 'Book_Covers/';
        if (strpos($relativePath, $prefix) !== 0) {
            return;
        }

        $coversDir = realpath(dirname(__DIR__) . '/Book_Covers');
        if ($coversDir === false) {
            return;
        }

        $targetPath = realpath(dirname(__DIR__) . '/' . $relativePath);
        if ($targetPath === false) {
            return;
        }

        if (strpos($targetPath, $coversDir . DIRECTORY_SEPARATOR) !== 0) {
            return;
        }

        if (is_file($targetPath)) {
            unlink($targetPath);
        }
    }

    private function uploadCoverImage(array $file): ?string
    {
    
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        
        $tmpPath = $file['tmp_name'] ?? '';
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!isset($allowedMimeTypes[$mimeType])) {
            return null;
        }

        $coversDir = dirname(__DIR__) . '/Book_Covers';
        if (!is_dir($coversDir) && !mkdir($coversDir, 0755, true) && !is_dir($coversDir)) {
            return null;
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $allowedMimeTypes[$mimeType];
        $targetPath = $coversDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $targetPath)) {
            return null;
        }

        return 'Book_Covers/' . $fileName;
    }

    public function handleBookViewRequest()
        {
            $bookToEdit = null;
            $bookToView = null;
            $action = $_GET['action'] ?? null;
            $bookId = isset($_GET['book_id']) ? (int) $_GET['book_id'] : null;

            if ($action === 'delete' && $bookId !== null) {
                $this->deleteBook($bookId);
            }

            if ($action === 'update' && $bookId !== null) {
                if (isset($_POST['updateTaskBtn'])) {
                    $this->update($bookId);
                }
                $bookToEdit = $this->getBookById($bookId);
            }

            if ($action === 'view' && $bookId !== null) {
                $bookToView = $this->getBookById($bookId);
            }

            return [
                'action' => $action,
                'bookToEdit' => $bookToEdit,
                'bookToView' => $bookToView,
                'allBooks' => $this->ViewBooks(),
            ];
        }

    public function store()
    {
        if (isset($_POST['addNewBookBtn'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $release_date = $_POST['release_date'];
            $genre = $_POST['genre'];
            $note = $_POST['note'] ?? '';
            $coverImagePath = $this->uploadCoverImage($_FILES['cover_image'] ?? []);

            if ($coverImagePath === null) {
                Warnings::printMessage("Please upload a valid cover image (jpg, jpeg, png, webp, gif).", "danger");
                return;
            }

            $db = new Database();
            $book_user = $_SESSION['userID'];
            $insertQuery = "INSERT INTO `book` (title, author, genre, note, release_date, cover_image) VALUES (?,?,?,?,?,?)";
            $prepareStmt = $db->connectdb->prepare($insertQuery);
            $prepareStmt->bind_param('ssssss', $title, $author, $genre, $note, $release_date, $coverImagePath);
            $prepareStmt->execute();
            $book_id = $db->connectdb->insert_id;
            $linkQuery = "INSERT INTO `user_books` (user_id, book_id) VALUES (?,?)";
            $linkStmt = $db->connectdb->prepare($linkQuery);
            $linkStmt->bind_param('ii', $book_user, $book_id);
            $checkQuery = $linkStmt->execute();
            if ($checkQuery) {
                Warnings::printMessage("Book added successfully", "success");
                header("Location: BookView.php");
            } else
                Warnings::printMessage("Task not added", "danger");
        }
    }

        public function ViewBooks()
    {
        $bookuser_id = $_SESSION['userID'];
        $selectQuery = "SELECT book.* FROM `book` INNER JOIN user_books ON book.book_id = user_books.book_id WHERE user_books.user_id = ?";
        $db = new Database();
        $prepareStmt = $db->connectdb->prepare($selectQuery);
        $prepareStmt->bind_param('i', $bookuser_id);
        $prepareStmt->execute();
        return $prepareStmt->get_result();
    }
    
    public function update($book_id)
    {
        if (isset($_POST['updateTaskBtn'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $genre = $_POST['genre'];
            $note = $_POST['note'] ?? '';
            $bookuser_id = $_SESSION['userID'];
            $db = new Database();
            $insertQuery = "UPDATE `book` 
                            INNER JOIN `user_books` ON book.book_id = user_books.book_id 
                            SET title = ?, author = ?, genre = ?, note = ? 
                            WHERE book.book_id = ? AND user_books.user_id = ?";
            $prepareStmt = $db->connectdb->prepare($insertQuery);
            $prepareStmt->bind_param('ssssii', $title, $author, $genre, $note, $book_id, $bookuser_id);
            $checkQuery = $prepareStmt->execute();
            if ($checkQuery) {
                header("Location: BookView.php");
                exit;
            } else
                Warnings::printMessage("Task is not updated, please try again", "danger");
        }
    }

    public function getBookById($book_id)
    {
        $bookuser_id = $_SESSION['userID'];
        $selectQuery = "SELECT book.* 
                        FROM `book` 
                        INNER JOIN `user_books` ON book.book_id = user_books.book_id 
                        WHERE book.book_id = ? AND user_books.user_id = ? 
                        LIMIT 1";
        $db = new Database();
        $prepareStmt = $db->connectdb->prepare($selectQuery);
        $prepareStmt->bind_param('ii', $book_id, $bookuser_id);
        $prepareStmt->execute();
        return $prepareStmt->get_result()->fetch_assoc();
    }

    public function deleteBook($book_id)
    {
        $userID = $_SESSION['userID'];
        $db = new Database();

        $coverImagePath = null;
        $coverQuery = "SELECT cover_image FROM `book` WHERE book_id = ? LIMIT 1";
        $coverStmt = $db->connectdb->prepare($coverQuery);
        $coverStmt->bind_param('i', $book_id);
        $coverStmt->execute();
        $coverResult = $coverStmt->get_result()->fetch_assoc();
        if ($coverResult) {
            $coverImagePath = (string) ($coverResult['cover_image'] ?? '');
        }

        $deleteLinkQuery = "DELETE FROM `user_books` WHERE book_id = ? AND user_id = ?";
        $deleteLinkStmt = $db->connectdb->prepare($deleteLinkQuery);
        $deleteLinkStmt->bind_param('ii', $book_id, $userID);

        if (!$deleteLinkStmt->execute() || $deleteLinkStmt->affected_rows === 0) {
            Warnings::printMessage("You are not authorized to delete this book.", "danger");
            return;
        }

        $deleteOrphanBookQuery = "DELETE FROM `book` WHERE book_id = ? AND NOT EXISTS (SELECT 1 FROM `user_books` WHERE book_id = ?)";
        $deleteOrphanStmt = $db->connectdb->prepare($deleteOrphanBookQuery);
        $deleteOrphanStmt->bind_param('ii', $book_id, $book_id);
        $deleteOrphanStmt->execute();

        if ($deleteOrphanStmt->affected_rows > 0) {
            $this->removeCoverImageFile($coverImagePath);
        }

        header("Location: BookView.php");
        exit;
    }
}