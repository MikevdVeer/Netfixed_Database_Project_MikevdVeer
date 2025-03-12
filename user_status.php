<?php 
session_start();
$currentUsername = $_SESSION['username'];
$currentUserId = $_SESSION['userId'];
$currentUserType = $_SESSION['userType'];

if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.php");
}

// process to get user profile picture

$query = $db->prepare("SELECT * FROM users WHERE id=:userId");
$query->bindParam(":userId", $currentUserId);
$query->execute();

$userResult = $query->fetchAll(PDO::FETCH_ASSOC);
$currentUser = $userResult[0];

function imageToDataUri($imageBinary, $mimeType) {
    $base64 = base64_encode($imageBinary);
    return 'data:' . $mimeType . ';base64,' . $base64;
}

$user_pp = imageToDataUri($currentUser['profile_picture'], $currentUser['picture_mime']);
// checks if the profile picture is not empty
$currentProfilePicture = $currentUser['profile_picture'] == '' ? './assets/img/default-profile.jpg' : $user_pp;
?>