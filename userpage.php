<?php
include "connect.php";
include "user_status.php";

$query = $db->prepare("SELECT * FROM anime");
$query->execute();

$result = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Netfixed</title>
  <link rel="icon" type="/image/x-icon" href="./assets/logo.png">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="stylesheet" href="sweetalert2.min.css">
</head>

<body>

  <!-- stackflow slide and header demo -->
  <header>
    <nav class="navbar navbar-expand-lg mb-3 p-2 fixed-top transparent">
      <!-- Container wrapper -->
      <div class="container-fluid">
        <!-- Toggle button -->
        <a class="navbar-brand mt-2 mt-lg-0" href="#">
          <h4 class="m-0">Netfixed</h4>
        </a>
        <!-- Collapsible wrapper Left side -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <a href="./genre.php" class="text-decoration-none text-white">
            <h4 class="m-0 fs-5">Genre</h4>
          </a>
        </div>
        <!-- Avatar -->
        <div class="dropdown">
          <a class="dropdown d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo $currentProfilePicture ?>" class="rounded-circle" height="55" width="55" alt="Black and White Portrait of a Man" loading="lazy" />
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
            <li>
              <a class="dropdown-item" href="profile.php">My profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="edit_profile.php">Settings</a>
            </li>
            <?php if ($currentUserType == 'admin') {
              echo "<li><a class='dropdown-item' href='admin/admin.php'>Admin</a></li>";
            } ?>
            <li>
              <a class="dropdown-item" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main class="bg-dark h-100">
    <!-- Slideshow -->
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
      <div class="carousel-inner vh-100">
        <?php
        foreach ($result as $anime) {
          if ($anime['recommendations'] == 1) {
            $id = $anime['id'];
            $name = $anime['name'];
            $description = $anime['description'];
            $image = $anime['wallpaper'];
            echo "<div class='carousel-item active h-100' data-bs-interval='10000'>";
            echo "<img class='w-100 h-100' src='assets/img/$image' alt='' title='$name'>";
            echo "<div class='carousel-caption content-none lh-1 w-50 text-break position-absolute bottom-1 start-0 m-5 p-3 text-white text-start'>";
            echo "<h1 class='fs-1'>$name</h1>";
            echo "<p class='fs-5'>$description</p>";
            echo "<a href='vidpage.php?id=$id&ep=1' class='btn btn-warning btn-lg'>Watch Here</a>";
            echo "</div>";
            echo "</div>";
          }
        }
        ?>

      </div>
      <button class="carousel-control-prev p-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next p-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
    <!-- Carousel Section -->
    <div class="carousel-container w-75 mx-auto mt-5">
      <h2 class="text-white">Our top anime's</h2>
      <div class="slick-carousel w-100 h-100">
        <?php
        foreach ($result as $anime) {
          if ($anime['is_top'] == 1) {
            $id = $anime['id'];
            $name = $anime['name'];
            $image = $anime['image'];
            echo "<div class='position-relative'>";
            echo "<i class='bi bi-suit-heart position-absolute top-0 end-0 m-4 fs-4 favorite-icon' data-anime-id='$id'></i>";
            echo "<a href='vidpage.php?id=$id&ep=1'>";
            echo "<img class='w-100 p-3 image-box' src='assets/img/$image' alt='' title='$name'>";
            echo "</a>";
            echo "</div>";
          }
        }
        ?>
      </div>
    </div><br><br>
    <div class="carousel-container w-75 mx-auto">
      <h2 class="text-white">Other anime's</h2>
      <div class="slick-carousel w-100 h-100">
        <?php
        foreach ($result as $anime) {
          if ($anime['is_other'] == 1) {
            $id = $anime['id'];
            $name = $anime['name'];
            $image = $anime['image'];
            echo "<div class='position-relative'>";
            echo "<i class='bi bi-suit-heart position-absolute top-0 end-0 m-4 fs-4 favorite-icon' data-anime-id='$id'></i>";
            echo "<a href='vidpage.php?id=$id&ep=1'>";
            echo "<img class='w-100 p-3' src='assets/img/$image' alt='' title='$name'>";
            echo "</a>";
            echo "</div>";
          }
        }
        ?>
      </div>
    </div><br><br>
  </main>
  <footer class="footer-container p-2">
    <div class="container p-2 w-100">
      <div class="row">
        <!-- Logo Section -->
        <div class="col-md-2 footer-section d-flex align-items-center p-2">
          <div class="footer-logo fs-4 fw-semibold">Netfixed</div>
        </div>

        <!-- Contact Us Section -->
        <div class="col-md-3 footer-section d-flex justify-content-center flex-column text-white text-decoration-none">
          <h5 class="">Contact Us</h5>
          <a href="#" class="text-white text-decoration-none">Made by: Mike & Sibren</a>
          <a href="#" class="text-white text-decoration-none">Student.nr: 302815715</a>
          <a href="#" class="text-white text-decoration-none">Number: 0640358050</a>
          <a href="https://maps.app.goo.gl/5aDVzH94kJFBqJyWA" class="text-white">Place: rocmondriaan Delft</a>
        </div>

        <!-- Policy Section -->
        <div class="col-md-3 footer-section d-flex justify-content-center flex-column text-white text-decoration-none">
          <h5 class="">Policy</h5>
          <a href="#" class="text-white">Cookievoorkeuren</a>
          <a href="#" class="text-white">Gebruiksvoorwaarden</a>
          <a href="#" class="text-white">Privacy</a>
          <a href="#" class="text-white">Reclameopties</a>
          <a href="#" class="text-white">Wettelijke bepalingen</a>
        </div>

        <!-- Banner Image Section -->
        <div class="col-md-3 footer-section footer-banner d-flex align-items-center">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4910.181975184132!2d4.3450431935262195!3d52.02344010051849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b5d771b631e7%3A0xcbe808e083d3d558!2sROC%20Mondriaan%20-%20Brasserskade!5e0!3m2!1snl!2snl!4v1729244290523!5m2!1snl!2snl" width="300" height="200" style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-2"></iframe>
        </div>
      </div>
    </div>
  </footer>
  <!-- Bootstrap JS and dependencies -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const favoriteIcons = document.querySelectorAll('.favorite-icon');

      favoriteIcons.forEach(icon => {
        icon.addEventListener('click', function() {
          // Toggle between empty and filled heart
          if (this.classList.contains('bi-suit-heart')) {
            this.classList.remove('bi-suit-heart');
            this.classList.add('bi-suit-heart-fill');
          } else {
            this.classList.remove('bi-suit-heart-fill');
            this.classList.add('bi-suit-heart');
          }
        });
      });
    });
</script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="JS\scroll.js"></script>
  <script src="sweetalert2.all.min.js"></script>
</body>

</html>