<?php

include "connect.php";
include "user_status.php";

$query = $db->prepare("SELECT * FROM anime");
$query->execute();

$result = $query->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $image = file_get_contents($_FILES['file_up']['tmp_name']);
    $imgMime = mime_content_type($_FILES['file_up']['tmp_name']);
    
    $query = $db->prepare("UPDATE users SET profile_picture=:image, picture_mime=:mime WHERE id=:userId");
    $query->bindParam(":userId", $currentUserId);
    $query->bindParam(":image", $image);
    $query->bindParam(":mime", $imgMime);
    $query->execute();
    $currentProfilePicture = imageToDataUri($image, $imgMime);
}


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
                <button class="btn rounded-circle p-2 align-items-center text-white d-flex align-items-center justify-content-start" title="exit">
                    <span class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    </span>
                </button>
            </a>
        </div>
        <div class="profile-section mx-auto p-3">
            <div class="container avatar d-flex justify-content-center mx-auto flex-column p-3 align-items-center gap-3 position-relative">
                <!-- default profile  -->
                <img src="<?php echo $currentProfilePicture?>" class="rounded-circle img-fluid" alt="Profile Picture" style="width: 125px; height: 125px;">
                <!-- edit icon -->
                 <div class="position-absolute p-2 rounded-circle" style="background: #5a8cf0; top: 35%; right: 45%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" fill="currentColor" class="bi bi-pencil " style=" " viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                    </svg>
                 </div>
                 <!-- <input type="image" src="img_submit.gif" alt="Submit" width="48" height="48"> https://www.w3schools.com/tags/tryit.asp?filename=tryhtml5_input_type_image -->
                <form method="post" enctype="multipart/form-data">

                    <div class="input-group mb-3">
                    <input type="file" name="file_up" class="form-control">
                    <input type="submit" name="submit" class="input-group-text" value="upload image">
                    </div>
                    
                </form>
                <input type="text" name="title" id="" value="<?php echo $currentUser['username'] ?>"
                    class="text-white shadow-none border-0 rounded p-1 text-center" style="background-color: #2c2c2c;">
                <button class="btn btn-outline-light mt-2 rounded-2">Edit profile</button>
            </div>
            <div class="container">
                <div class="bio-section p-3 mb-2">
                    <label for="bio">
                        <h4 class="font-weight-bold mb-3">Bio</h4>
                    </label>
                    <textarea class="form-control border-0 text-white rounded mb-3" id="bio" name="review" rows="4" placeholder="Description" style="background-color: #2c2c2c; resize: none;"></textarea>
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
                <div class="communities-section mt-5 p-3">
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