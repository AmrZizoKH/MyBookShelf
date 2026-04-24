<?php
require_once '../vendor/autoload.php';

use App\Authenticate;
$auth = new Authenticate();
$auth->redirectIfAuth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyBookShelf — Sign Up</title>
  <link rel="stylesheet" href="../resources/css/auth.css">
  <link rel="stylesheet" href="../resources/css/nav.css?v=20260424">
</head>
<body class="auth-page">
<?php require_once '../layout/nav.php'; ?>

<div class="card auth-card">

  <span class="page-title">sign up</span>

  <form method="POST" action="signup.php">

    <div class="field">
      <label for="username">Name</label>
      <input
        type="text"
        id="username"
        name="username"
        autocomplete="username"
        placeholder="something quiet"
        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
        required
      >
    </div>

    <div class="field">
      <label for="email">email</label>
      <input
        type="email"
        id="email"
        name="email"
        autocomplete="email"
        placeholder="your@email.com"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        required
      >
    </div>

    <div class="field">
      <label for="password">password</label>
      <input
        type="password"
        id="password"
        name="password"
        autocomplete="new-password"
        placeholder="at least 6 characters"
        required
      >
    </div>

    <div class="field">
      <label for="confirm_password">confirm password</label>
      <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        autocomplete="new-password"
        placeholder="repeat your password"
        required
      >
    </div>

    <button class="btn-submit" type="submit" name="signUpBtn">create account →</button>

  </form>

  <span class="switch-link">Already have an account? <a href="login.php">Sign in</a></span>
  <?php $auth->signup() ?>
</div>

</body>
</html>