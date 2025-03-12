<?php

include_once "connect.php";

$errors = [];
$inputs = [];

const USERNAME_REQUIRED = 'Username required';
const PASSWORD_REQUIRED = 'Password required';
const USERNAME_PASSWORD_INVALID = 'Wrong username or password';

// users query

$query = $db->prepare('SELECT * FROM users');
$query->execute();

$users = $query->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['login'])) {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
  $username = trim($username);
  if (empty($username)) {
    $errors['username'] = USERNAME_REQUIRED;
  } else {
    $inputs['username'] = $username;
  }
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
  $password = trim($password);
  if (empty($password)) {
    $errors['password'] = PASSWORD_REQUIRED;
  } else {
    $inputs['password'] = $password;
  }
  if (count($errors) === 0) {
    foreach ($users as $user) {
      if ($username === $user['username'] && $password === $user['password']) {
        // logged in
        session_start(); 
        $_SESSION['username'] = $user['username'];
        $_SESSION['userId'] = $user['id'];
        $_SESSION['userType'] = $user['type'];
        $_SESSION['loggedIn'] = true;
        header("Location: userpage.php");
      }
    }
    $errors['incorrect'] = USERNAME_PASSWORD_INVALID;
}
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
          <link rel="icon" type="image/x-icon" href="./assets/logo.png">
          <link rel="stylesheet" href="./css/style.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Netfixed - Login</title>
</head>
<body>
<main class="bg-dark text-white container-fluid min-vh-100 p d-flex align-items-center justify-content-center position-relative">
<video class="position-absolute top-0 left-0" width="100%" height="100%" autoplay muted loop preload="none">
  <source src="./assets/background_vid/flowers.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
</video>
  <section>
    <div class="card container mx-4 mx-md-5 shadow-5-strong bg-login">
      <div class="card-body py-5 px-md-5">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-8 text-black">
            <h2 class="fw-bold mb-5 text-center">Login</h2>
            <form method="post">
              <div class="row">
              <!-- Username input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="username">Username</label><br> 
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $inputs['username'] ?? '' ?>">
                <div class="form-text text-danger">
                  <?php echo $errors['username'] ?? '' ?>
                </div>
              </div>
  
              <!-- Password input -->
              <div class="col-md-12 mb-4">
                <div data-mdb-input-init class="form-outline">
                  <label class="form-label" for="password">Password</label><br>
                  <input type="password" id="password" name="password" class="form-control" value="<?php echo $inputs['password'] ?? '' ?>">
                  <div class="form-text text-danger">
                  <?php echo $errors['password'] ?? '' ?>
                  </div>
                </div>
              </div>
            </div>
  
              <!-- Submit button -->
              <div class="form-text text-danger d-flex justify-content-center">
                <?php echo $errors['incorrect'] ?? '' ?>
              </div>
              <div class="d-flex justify-content-center mt-2">
                <button type="submit" name="login" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">
                  <span class="">Login</span>
                </button>
              </div>
            </form>
          </div>
          </div>
          <div class="d-flex justify-content-between">
            <a class="text-decoration-none" href="signup.php">&#8592; back</a>
            <a class="text-decoration-none" href="login.php">Login &#8594;</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
</body>
</html>

</body>
</html>