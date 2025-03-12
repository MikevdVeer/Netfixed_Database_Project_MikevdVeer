<?php
include "connect.php";

$query = $db->prepare('SELECT * FROM users');
$query->execute();

$users = $query->fetchAll(PDO::FETCH_ASSOC);

const USERNAME_REQUIRED = 'Username required';
const USERNAME_EXISTS = 'Username already exists';
const EMAIL_REQUIRED = 'Email required';
const EMAIL_EXISTS = 'Email already exists';
const EMAIL_VALID = 'Email is not valid';
const PASSWORD_REQUIRED = 'Password required';
const AGREE_REQUIRED = 'Please accept our terms and conditions';
const REPEAT_PASSWORD_REQUIRED = 'Please repeat your password';
const NO_PASSWORD_MATCH = 'Passwords do not match';

$inputs = [];
$errors = [];

if (isset($_POST['signup'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $username = trim($username);
    if (empty($username)) {
        $errors['username'] = USERNAME_REQUIRED;
    }  else {
        $existingUsers = 0;
        foreach ($users as $user) {
            if ($username === $user['username']) {
                $existingUsers++;
            }
        }
        if ($existingUsers === 0) {
            $inputs['username'] = $username;
        } else {
            $errors['username'] = USERNAME_EXISTS;
        }
        
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $email = trim($email);
    if (empty($email)) {
        $errors['email'] = EMAIL_REQUIRED;
    } else {
        $existingEmails = 0;
        foreach ($users as $user) {
            if ($email === $user['email']) {
                $existingEmails++;
            }
        }
        if ($existingEmails === 0) {
            $inputs['email'] = $email;
        } else {
            $errors['email'] = EMAIL_EXISTS;
        }
        
    }

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = trim($password);
    if (empty($password)) {
        $errors['password'] = PASSWORD_REQUIRED;
    } else {
        $inputs['password'] = $password;
    }

    $rePassword = filter_input(INPUT_POST, 'rePassword', FILTER_SANITIZE_SPECIAL_CHARS);
    $rePassword = trim($rePassword);
    if (empty($rePassword)) {
        $errors['rePassword'] = REPEAT_PASSWORD_REQUIRED;
    } elseif ($rePassword !== $password) { 
        $errors['rePassword'] = NO_PASSWORD_MATCH;
    } else {
        $inputs['rePassword'] = $rePassword;
    }

    $agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($agree === null) {
        $errors['agree'] = AGREE_REQUIRED;
    }

    if (count($errors) === 0) {
        global $db;
        $userType = 'user';
        $sth = $db->prepare('INSERT INTO users (username, email, password, type) VALUES (:username, :email, :password, :type)');
        $sth->bindParam(':username', $inputs['username']);
        $sth->bindParam(':email', $inputs['email']);
        $sth->bindParam(':password', $inputs['password']);
        $sth->bindParam(':type', $userType);
        $result = $sth->execute();

        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Netfixed - Signup</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="./assets/logo.png">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>

<!-- Hero Section -->
<main class="bg-dark text-white container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative">
<video class="position-absolute top-0 left-0" width="100%" height="100%" autoplay muted loop preload="none">
  <source src="./assets/background_vid/flowers.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
</video>
  <section>
    <div class="card mx-4 mx-md-5 shadow-5-strong bg-login">
      <div class="card-body py-5 px-md-5">
        <div class="row d-flex justify-content-center">
          <div class="col-lg-8">
            <h2 class="fw-bold mb-5 text-center">Sign up</h2>
            <!-- 2 column grid layout with text inputs for the first and last names -->
            <form method="post">
              <!-- 2 column grid layout with text inputs for the first and last names -->
              <div class="row">
                <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="username">Username</label><br>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $inputs['username'] ?? '' ?>">
                    <div class="form-text text-danger">
                        <?php echo $errors['username'] ?? '' ?>
                    </div>
                </div>
  
              <!-- Email input -->
              <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="email">Email address</label><br> 
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $inputs['email'] ?? '' ?>">
                    <div class="form-text text-danger">
                        <?php echo $errors['email'] ?? '' ?>
                    </div>
              </div>
  
              <!-- Password input -->
              <div class="col-md-6 mb-4">
                <div data-mdb-input-init class="form-outline">
                    <label class="form-label" for="password">Password</label><br>
                    <input type="password" id="password" name="password" class="form-control" value="<?php echo $inputs['password'] ?? '' ?>">
                    <div class="form-text text-danger">
                        <?php echo $errors['password'] ?? '' ?>
                    </div>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div data-mdb-input-init class="form-outline">
                    <label class="form-label" for="password_confirmation">Repeat Password</label><br>
                    <input type="password" id="rePassword" name="rePassword" class="form-control" value="<?php echo $inputs['rePassword'] ?? '' ?>">
                    <div class="form-text text-danger">
                        <?php echo $errors['rePassword'] ?? '' ?>
                    </div>
                </div>
              </div>
            </div>
              <!-- Checkbox -->
              <div class="form-check d-flex justify-content-center mb-4">
                    <input type="checkbox" class="form-check-input me-2" name="agree" value="" id="agree">
                    <label class="form-check-label" for="agree">
                    Agree terms and conditions
                    </label>
              </div>
                <div class="form-text text-danger w-100 d-flex justify-content-center">
                    <?php echo $errors['agree'] ?? '' ?>
                </div>
  
              <!-- Submit button -->
              <div class="d-flex justify-content-center">
                <input type="submit" name="signup" class="btn btn-primary">
              <!-- <button type="submit" name="signup" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">
                <span class="">Sign up</span>
              </button> -->
            </div>
            </form>
          </div>
          </div>
          <div class="d-flex justify-content-between">
            <a class="text-decoration-none" href="#">&#8592; Back</a>
            <a class="text-decoration-none" href="login.php">Login &#8594;</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
</body>
</html>