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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./assets/logo.png">
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="./css/style.css"/>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <div class="profile-header p-2">
            <a href="./userpage.php">
                <button class="btn rounded-circle align-items-center p-2 text-white" title="Exit">
                    <span class="align-items-center text-center d-flex justify-content-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                        </svg>
                    </span>
                </button>
            </a>
        </div>
        <div class="profile-section mx-auto p-3">
            <div class="container w-50 avatar d-flex justify-content-center mx-auto flex-column p-3 align-items-center gap-3 position-relative">
                <img src="./assets/img/default-profile.jpg" class="rounded-circle img-fluid" alt="Profile Picture" style="width: 125px;">
                <h5 class="text-white shadow-none border-0 rounded p-1 text-center" style="background-color: #2c2c2c;"><?php  echo $currentUsername ?></h5>
                    <a href="./edit_profile.php">
                <button class="btn btn-outline-light mt-2 rounded-2">Edit profile</button>
                </a>
            </div>
            <div class="container">
                <div class="bio-section p-3 mb-2">
                    <label for="bio">
                        <h4 class="font-weight-bold mb-3">Bio</h4>
                    </label>
                    <div class="form-control border-0 text-white rounded mb-3 h-100" style="background-color: #2c2c2c;">
                        <p>This user has no bio</p>
                    </div>
                </div>

                <div class="carousel-container p-3 mt-5">
                    <h2 class="text-white">Favorite anime</h2>
                    <div class="slick-carousel w-100 h-100">
                        <?php
                        foreach ($result as $anime) {
                            if ($anime['is_top'] == 1) {
                                $id = $anime['id'];
                                $name = $anime['name'];
                                $image = $anime['image'];
                                echo "<div>";
                                echo "<a href='vidpage.php?id=$id&ep=1'>";
                                echo "<img class='w-100 p-3 image-box rounded-2' src='assets/img/$image' alt='' title='$name'>";
                                echo "</a>";
                                echo "<h5 class='text-center'>$name</h5>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div><br>

                <h2 class="text-white">Community</h2>
                <div class="communities-section mt-3 p-3">
                    <h5>Server Name</h5>
                    <div class="d-flex align-items-center">
                        <div class="status-circle rounded-circle me-2"></div>
                        <span>182</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
        type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS\scroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>