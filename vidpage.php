<?php

include "connect.php";
include "user_status.php";

$animeId = $_GET['id'];
$animeEp = $_GET['ep'];

//query's for current page data

$query = $db->prepare("SELECT * FROM anime WHERE id=:id");
$query->bindParam(":id", $animeId);
$query->execute();

$animeDataResult = $query->fetchAll(PDO::FETCH_ASSOC);
$showAnimeData = $animeDataResult[0];

// episode query

$query = $db->prepare("SELECT * FROM episodes WHERE anime_id=:id");
$query->bindParam(":id", $animeId);
$query->execute();

$episodeResult = $query->fetchAll(PDO::FETCH_ASSOC);

// iframe query

$query = $db->prepare("SELECT * FROM episodes WHERE anime_id=:id AND episode_nr=:ep");
$query->bindParam(":id", $animeId);
$query->bindParam(":ep", $animeEp);
$query->execute();

$episodeIframe = $query->fetchAll(PDO::FETCH_ASSOC);

// review query

$query = $db->prepare("SELECT * FROM review WHERE anime_id=:id AND episode_nr=:ep");
$query->bindParam(":id", $animeId);
$query->bindParam(":ep", $animeEp);
$query->execute();

$reviewResults = $query->fetchAll(PDO::FETCH_ASSOC);

// query for sidebar

$query = $db->prepare("SELECT * FROM anime");
$query->execute();

$sidebarData = $query->fetchAll(PDO::FETCH_ASSOC);

$date = date('l jS \of F Y h:i:s A');

// review form code

const TITLE_REQUIRED = "Needs a title";
const REVIEW_REQUIRED = "Review can't be empty";
const REVIEW_TOO_LONG = "Max 500 characters";

$inputs = [];
$errors = [];

if (isset($_POST['send'])) {

  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
  $title = trim($title);
  if (empty($title)) {
    $errors['title'] = TITLE_REQUIRED;
  } else {
    $inputs['title'] = $title;
  }

  $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_SPECIAL_CHARS);
  $review = trim($review);
  if (empty($review)) {
    $errors['review'] = REVIEW_REQUIRED;
  } elseif (strlen($review) > 500) {
    $errors['review'] = REVIEW_TOO_LONG;
  } else {
    $inputs['review'] = $review;
  }

  if (count($errors) === 0) {
    global $db;

    $sth = $db->prepare('INSERT INTO review (title, review, anime_id, user_id, episode_nr, username) VALUES (:title, :review, :animeId, :userId, :animeEp, :username)');
    $sth->bindParam(":title", $inputs['title']);
    $sth->bindParam(":review", $inputs['review']);
    $sth->bindParam(":animeId", $animeId);
    $sth->bindParam(":userId", $currentUserId);
    $sth->bindParam(":animeEp", $animeEp);
    $sth->bindParam(":username", $currentUsername);
    $result = $sth->execute();

    header("Location: vidpage.php?id=$animeId&ep=$animeEp");
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="assets/logo.png">
  <title>Netfixed<?php echo '  -  ' . $showAnimeData['name'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./css/style.css" />
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg mb-3 p-2">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand mt-2 mt-lg-0" href="userpage.php">
          <h4 class="m-0">Netfixed</h4>
        </a>

        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

        </div>
        <!-- Avatar -->
        <div class="dropdown">
          <a class="dropdown d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo $currentProfilePicture ?>" class="rounded-circle" height="55" width="55"
              alt="Black and White Portrait of a Man" loading="lazy" />
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
            <li>
              <a class="dropdown-item" href="./profile.php">My profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="./edit_profile.php">Settings</a>
            </li>
            <li>
              <a class="dropdown-item" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main class="mb-3">
    <div class="container-fluid p-5 pt-0 pb-0">
      <div class="row">
        <!-- Video Player Section -->
          <div class="video-player container-fluid p-2 rounded" style="background-color: #2c2c2c;">
            <?php
            if (empty($episodeIframe)) {
              echo '<p>No video available.</p>';
            }
            ?>
            <div class="responsive-iframe w-100 position-relative">
              <iframe class="position-absolute top-0 start-0" id="videoIframe"
                src="<?php echo $episodeIframe[0]['iframe']; ?>"
                frameborder="0" allowfullscreen="true" marginwidth="0" marginheight="0" scrolling="no" width="100%"
                height="100%">
              </iframe>
            </div>
          </div>
          <div class="container-fluid d-flex mt-3 p-3 rounded" style="background-color: #2c2c2c;">
            <div class="col-md-3 p-1">
              <h4 class="title"><?php echo $showAnimeData['name']; ?></h4>
              <div class="p-3">
                <img src="./assets/img/<?php echo $showAnimeData['image'] ?>" alt="Anime poster" class="img-fluid rounded mt-3" />
              </div>
            </div>
            <!-- episodes -->
            <div class="col-md-6 container-fluid bio-section">
              <div class="row">
                <div class="col-md-4 mt-2">
                  <ul class="list-group">
                    <details class="mb-2">
                      <summary class="list-group-item animation bg-dark text-white">Seasons</summary>
                      <div cl>
                        <a href="" class="text-decoration-none">
                          <li class="list-group-item">Season 1</li>
                        </a>
                        <li class="list-group-item">No seasons available</li>
                      </div>
                    </details>
                  </ul>
                </div>
                <div class="col-md-8 p-1 mt-2 mb-2">
                  <h4 class="title">Episode: <?php echo $animeEp ?></h4>
                </div>
                <div class="episode-list d-flex flex-wrap gap-1 pt-2 pb-2 rounded" style="max-height:100%">
                    <?php
                    if (!empty($episodeResult)) {
                      foreach ($episodeResult as $episode) {
                        $epi = $episode['episode_nr'];
                        $activeEpi = "";
                        if ($epi == $animeEp) {
                          $activeEpi = 'active';
                        }
                        echo "<a href='vidpage.php?id=$animeId&ep=$epi'>";
                        echo "<button class='btn btn-dark text-white m-1 $activeEpi'>$epi</button>";
                        echo "</a>";
                      }
                    } else {
                      echo "<p class='text-white'>No episodes available.</p>";
                    }
                    ?>
                  </div>
              </div>

            </div>

          </div>
          <!-- video recommendations Section -->
        <div class="tag-sidebar container mt-3 p-3 rounded h-100">
        <div class="row row-cols-5 mx-auto justify-content-center gap-4">
          <?php
          foreach ($sidebarData as $anime) {
              $id = $anime['id'];
              $name = $anime['name'];
              $image = $anime['image'];
              echo "<div class='mb-3 position-relative' style='width: 200px; height:300px'>";
              echo "<a href='vidpage.php?id=$id&ep=1' class=''>";
              echo "<img class='rounded-2' src='assets/img/$image' alt='$name' title='$name' style='position-absolute; top-0 left-0 width 200px; height:300px'>";
              echo "</a>";
              echo "</div>";
          }
          ?>
          </div>
        </div>

        <!-- Review section -->
          <div class="container-fluid mb-5 mt-5">
            <div class="row">
              <div class="col-md-12">
                <!-- Review Cards -->
                <div class="review-card mb-3 d-flex align-items-center w-100 rounded">
                  <div class="user-avatar align-items-center justify-content-center p-3">
                    <img class="rounded-circle" src="<?php echo $currentProfilePicture ?>"
                      alt="Avatar" style="width: 100px; height: 100px;">
                  </div>
                  <div class="review-content p-3 rounded d-flex justify-content-between w-100">
                    <div class="inner-review w-100 m-2">
                      <!-- review form -->
                      <form method="post">
                        <div class="mb-3">
                          <input type="text" name="title" id="" placeholder="Title"
                            class="text-white shadow-none border-0 rounded p-1" style="background-color: #2c2c2c;" value="<?php echo $inputs['title'] ?? '' ?>">
                          <div class="form-text text-danger">
                            <?php echo $errors['title'] ?? '' ?>
                          </div>
                        </div>
                        <textarea class="border-0 text-white rounded w-100" name="review" rows="4" cols="50"
                          placeholder="Discription" style="background-color: #2c2c2c; resize:none;" value="<?php echo $inputs['review'] ?? '' ?>"></textarea>
                        <div class="form-text text-danger">
                          <?php echo $errors['review'] ?? '' ?>
                        </div>
                    </div>
                    <button type="submit" name="send" value="Submit" class="rounded bg-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                      </svg></button>
                    </form>
                  </div>
                </div>
                <!-- card_review when posted -->
                <?php
                foreach ($reviewResults as $review) {
                  $query = $db->prepare("SELECT * FROM users WHERE id=:userId");
                  $query->bindParam(":userId", $review['user_id']);
                  $query->execute();

                  $reviewUserResult = $query->fetchAll(PDO::FETCH_ASSOC);
                  $reviewPpBinary = $reviewUserResult[0]['profile_picture'];
                  $reviewPpMime = $reviewUserResult[0]['picture_mime'];

                  $reviewPp = imageToDataUri($reviewPpBinary, $reviewPpMime);

                  $currentDate = $review['date'];
                  $reviewUsername = $review['username'];
                  $reviewTitle = $review['title'];
                  $reviewMessage = $review['review'];
                  echo "<div class='review-card mb-3 d-flex align-items-center w-100 rounded'>";
                  echo "<div class='user-avatar m-2 d-flex'>";
                  echo "<img class='rounded-circle' src='$reviewPp' alt='avatar' style='width: 100px; height: 100px;'>";
                  echo "</div>";
                  echo "<div class='review-content p-3 rounded d-flex justify-content-between w-100'>";
                  echo "<div class='inner-review w-100 m-2'>";
                  echo "<div class='mb-3'>";
                  echo "<p class=''><button class='btn btn-primary mx-1'>Posted</button>  $currentDate by <b>$reviewUsername</b></p>";
                  echo "<h3 class='text-white mx-1'>$reviewTitle</h3>";
                  echo "</div>";
                  echo "<p class='text-white mx-2 w-100'>$reviewMessage</p>";
                  echo "</div>";
                  echo "<div class='tag'>";
                  echo "<div class='btn tag-question mx-2'>#Question</div>";
                  echo "</div>";
                  echo "</div>";
                  echo "</div>";
                }

                ?>
                <!-- End of Review -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
  <footer class="footer-container p-2">
    <div class="container p-2 w-100">
      <div class="row">
        <!-- Logo Section -->
        <div class="col-md-2 footer-section d-flex align-items-center p-2">
          <div class="footer-logo fs-4 fw-semibold">Netfixed</div>
        </div>

        <!-- Contact Us Section -->
        <div
          class="col-md-3 footer-section d-flex justify-content-center flex-column text-white text-decoration-none">
          <h5 class="">Contact Us</h5>
          <a href="#" class="text-white text-decoration-none">Made by: Mike & Sibren</a>
          <a href="#" class="text-white text-decoration-none">Student.nr: 302815715</a>
          <a href="#" class="text-white text-decoration-none">Number: 0640358050</a>
          <a href="https://maps.app.goo.gl/5aDVzH94kJFBqJyWA" class="text-white">Place: rocmondriaan Delft</a>
        </div>

        <!-- Policy Section -->
        <div
          class="col-md-3 footer-section d-flex justify-content-center flex-column text-white text-decoration-none">
          <h5 class="">Policy</h5>
          <a href="#" class="text-white">Cookievoorkeuren</a>
          <a href="#" class="text-white">Gebruiksvoorwaarden</a>
          <a href="#" class="text-white">Privacy</a>
          <a href="#" class="text-white">Reclameopties</a>
          <a href="#" class="text-white">Wettelijke bepalingen</a>
        </div>

        <!-- Banner Image Section -->
        <div
          class="col-md-3 footer-section footer-banner d-flex align-items-center">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4910.181975184132!2d4.3450431935262195!3d52.02344010051849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b5d771b631e7%3A0xcbe808e083d3d558!2sROC%20Mondriaan%20-%20Brasserskade!5e0!3m2!1snl!2snl!4v1729244290523!5m2!1snl!2snl"
            width="300"
            height="200"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            class="rounded-2"></iframe>
        </div>
      </div>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>