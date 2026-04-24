<nav>
    <div class="nav-name">
        <a href="index.php">MyBookShelf</a>
        <span>a quiet place to write</span>
    </div>

    <div class="nav-links">
        <?php if (isset($_SESSION['userID'])): ?>
            <a href="index.php">Home</a>
            <a href="BookView.php">My books</a>
            <a href="AddBook.php">Add book</a>
            <span><?php echo htmlspecialchars((string) $_SESSION['userName']); ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Sign In</a>
            <a href="SignUp.php">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>