<?php

include 'connect.php';

$image = file_get_contents($_FILES['file_up']['tmp_name']);
$imgMime = mime_content_type($_FILES['file_up']['tmp_name']);
echo $imgMime;

$dataUri = imageToDataUri($image, $imgMime);

$query = $db->prepare("INSERT INTO profiles (image, image_mime) VALUES (:image, :mime)");
$query->bindParam(":image", $image);
$query->bindParam(":mime", $imgMime);
$query->execute();

function imageToDataUri($imageBinary, $mimeType) {
    $base64 = base64_encode($imageBinary);
    return 'data:' . $mimeType . ';base64,' . $base64;
}

?>

<img src="<?php echo $dataUri; ?>" alt="Image" />