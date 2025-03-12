<?php

include "connect.php";
include "user_status.php";

//query's for current page data

$query = $db->prepare("SELECT * FROM anime WHERE id=:id");
$query->bindParam(":id", $animeId);
$query->execute();

$animeDataResult = $query->fetchAll(PDO::FETCH_ASSOC);

// query for sidebar

$query = $db->prepare("SELECT * FROM anime");
$query->execute();

$sidebarData = $query->fetchAll(PDO::FETCH_ASSOC);

$date = date('l jS \of F Y h:i:s A');

// query for genres

$query = $db->prepare("SELECT * FROM genre");
$query->execute();

$allGenres = $query->fetchAll(PDO::FETCH_ASSOC);

$selectedGenresId = [];
$animeWithGenres = [];

if (isset($_POST['filter'])) {

    for ($i = 0; $i < count($allGenres); $i++) {
        if (isset($_POST["genre$i"])) {
            $selectedGenresId[] = $allGenres[$i]['genre_id'];
        }
        if (count($selectedGenresId) > 0) {
            $genreCount = count($selectedGenresId);
            $genreIdToString = implode(',', $selectedGenresId);
            $query = $db->prepare("SELECT anime_genre.anime_id, COUNT(anime_genre.genre_id) AS genre_count, anime.*
            FROM anime_genre
            JOIN anime ON anime_genre.anime_id = anime.id
            WHERE genre_id IN ($genreIdToString)
            GROUP BY anime_genre.anime_id, anime.name
            HAVING genre_count = $genreCount;");
            $query->execute();
            $animeWithGenres = $query->fetchAll(PDO::FETCH_ASSOC);

        } 
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="assets/logo.png">
    <title>Netfixed - Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        <div class="container-fluid">
            <div class="row">
                <!-- Genre Section -->
                <div class="col-md-2 sidebar mb-2 p-2 rounded container-fluid">
                    <div class="row">
                        <div class="col-md-12 w-100 mb-1">
                            <div class="input-group">
                                <input class="form-control" type="search" placeholder="Search"
                                    id="example-search-input">
                                <span class="input-group-append">
                                    <div class="input-group-text h-100 border-0 rounded-0"><i class="bi bi-search"></i>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Filter -->
                    <form method="post">
                        <ul class="list-group">
                            <details>
                                <summary class="list-group-item animation bg-dark text-white">Genre</summary>
                                <div class="d-flex flex-wrap gap-3 list-group-item">
                                    <?php
                                    for ($i = 0; $i < count($allGenres); $i++) {
                                        $currentGenre = $allGenres[$i];
                                        $genreName = $currentGenre['genre_name'];
                                        $genreId = $currentGenre['genre_id'];
                                        echo "<div>";
                                        if (in_array($genreId, $selectedGenresId)) {
                                        echo "<input type='checkbox' id='genre$i' name='genre$i' checked>";
                                        } else {
                                            echo "<input type='checkbox' id='genre$i' name='genre$i'>";
                                        }
                                        echo "<label for='genre$i' class='p-1'>$genreName</label>";
                                        echo "</div>";
                                    }
                                    ?>

                                </div>
                            </details>
                            <details>
                                <summary class="list-group-item bg-dark text-white">Most Rated</summary>
                                <li class="list-group-item">1 star</li>
                                <li class="list-group-item">2 star</li>
                                <li class="list-group-item">3 star</li>
                                <li class="list-group-item">4 star</li>
                                <li class="list-group-item">5 star</li>
                            </details>
                            <button name="filter" class="btn btn-primary text-black rounded-0">Filter</button>
                        </ul>
                    </form>

                </div>
                <div class="col-md-10">
                    <div class="video-player container-fluid p-2 rounded" style="background-color: #2c2c2c;">
                        <div class=" w-100 position-relative d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <?php
                            if (!isset($_POST['filter']) && count($selectedGenresId) == 0) {
                                $query = $db->prepare("SELECT * FROM anime");
                                $query->execute();
                                $allAnimes = $query->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($allAnimes as $anime) {
                                    $name = $anime['name'];
                                    $image = $anime['image'];
                                
                                    echo "<div>";
                                    echo "<img src='assets/img/$image' class='img-fluid' alt='Poster' style='width: 200px;' title='$name'>";
                                    echo "</div>";
                                }
                            }
                            // display anime with selected genres
                            if (isset($_POST['filter'])) {
                                if (count($selectedGenresId) == 0) {
                                    $query = $db->prepare("SELECT * FROM anime");
                                    $query->execute();
                                    $allAnimes = $query->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($allAnimes as $anime) {
                                        $name = $anime['name'];
                                        $image = $anime['image'];
                                    
                                        echo "<div>";
                                        echo "<img src='assets/img/$image' class='img-fluid' alt='Poster' style='width: 200px;' title='$name'>";
                                        echo "</div>";
                                    }
                                }
                                foreach ($animeWithGenres as $anime) {
                                    $name = $anime['name'];
                                    $image = $anime['image'];
                                
                                    echo "<div>";
                                    echo "<img src='assets/img/$image' class='img-fluid' alt='Poster' style='width: 200px;' title='$name'>";
                                    echo "</div>";
                                }
                            }
                            ?>
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
                <div class="col-md-3 footer-section footer-banner d-flex align-items-center">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4910.181975184132!2d4.3450431935262195!3d52.02344010051849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b5d771b631e7%3A0xcbe808e083d3d558!2sROC%20Mondriaan%20-%20Brasserskade!5e0!3m2!1snl!2snl!4v1729244290523!5m2!1snl!2snl"
                        width="300" height="200" style="border: 0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" class="rounded-2"></iframe>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>