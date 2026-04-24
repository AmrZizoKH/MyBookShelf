    <?php 
   require_once '../vendor/autoload.php';
  use App\Authenticate;
   

    $alert = new \App\Warnings();
    $auth = new Authenticate(); 
    $auth->redirectIfAuth();
     ?>
  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyBookShelf — Sign In</title>
  <link rel="stylesheet" href="../resources/css/auth.css">
  <link rel="stylesheet" href="../resources/css/nav.css?v=20260424">
</head>
<body class="auth-page">
<?php require_once '../layout/nav.php'; ?>
<div class="card auth-card">
  <span class="page-title">sign in</span>

  <form method="POST" action="login.php">

    <div class="field">
      <label for="email">email</label>
      <input
        type="email"
        id="email"
        name="email"
        autocomplete="email"
        placeholder="your@email.com"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
      >
    </div>

    <div class="field">
      <label for="password">password</label>
      <input
        type="password"
        id="password"
        name="password"
        autocomplete="current-password"
        placeholder="••••••••"
      >
    </div>

    <button class="btn-submit" type="submit" name="logInBtn">Sign in →</button>


  </form>

  <span class="switch-link">No account? <a href="signup.php">Sign up</a></span>
        <?php $auth->login(); 
        
        ?>
</div>

</body>
</html>