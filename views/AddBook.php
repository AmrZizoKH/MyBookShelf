<?php
require_once '../vendor/autoload.php';

(new App\Authenticate())->redirectIfNotAuth();

(new \App\Operations())->store();

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="\viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/auth.css">
    <link rel="stylesheet" href="../resources/css/nav.css?v=20260424">
    
    <title>Add Book</title>
    <style>
        input,
        textarea {
            unicode-bidi: plaintext;
        }
    </style>

</head>

<body>
<?php require_once '../layout/nav.php'; ?>

    <br>

    <div class="add-book-container">
        <h1 style="font-style: italic;margin:15px auto">Add New Book </h1>
        <div class="container col-4">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="col">
                    <div class="col">
                        <div class="card">


                            <form method="POST" action="signup.php">

                                <div class="field">
                                    <label for="title">Book Title</label>
                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        autocomplete="title"
                                        placeholder="Book Title"
                                        value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                                        required
                                        >
                                </div>

                                <div class="field">
                                    <label for="author">Author</label>
                                    <input
                                        type="text"
                                        id="author"
                                        name="author"
                                        autocomplete="author"
                                        placeholder="Book Author"
                                        value="<?= htmlspecialchars($_POST['author'] ?? '') ?>"
                                        required
                                        >
                                </div>

                                <div class="field">
                                    <label for="genre">Genre</label>
                                    <input
                                        type="text"
                                        id="genre"
                                        name="genre"
                                        autocomplete="genre"
                                        placeholder="Book Genre"
                                        value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>"
                                        required
                                        >
                                </div>

                                <div class="field">
                                    <label for="text">Note</label>
                                    <input
                                        type="text"
                                        id="note"
                                        name="note"
                                        autocomplete="text"
                                        placeholder="What do you think about this book?"
                                        value="<?= htmlspecialchars($_POST['note'] ?? '') ?>"
                                        required
                                        >
                                </div>

                                <div class="field">
                                    <label for="release_date">Release Date</label>
                                    <input
                                        type="date"
                                        id="release_date"
                                        name="release_date"
                                        autocomplete="release_date"
                                        placeholder="4/12/1984"
                                        value="<?= htmlspecialchars($_POST['release_date'] ?? '') ?>"
                                        required
                                        >
                                    </div>

                                <div class="field field-upload">
                                    <label for="cover_image">Upload Cover Image</label>
                                    <input
                                        type="file"
                                        id="cover_image"
                                        name="cover_image"
                                        class="upload-input"
                                        accept="image/*"
                                        >
                                </div>

                                <div class="text-center">
                                    <button style="margin-top: 20px;" type="submit" name="addNewBookBtn"
                                        class="btn btn-outline-primary text-center">Add Book
                                    </button>
                                </div>

                        </div>


            </form>
        </div>
    </div>
    </div>
    <div class="col">

    </div>
    </form>
    </div>
    </div>

</body>

</html>