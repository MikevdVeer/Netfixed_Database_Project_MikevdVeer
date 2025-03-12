<?php

include "connect.php";

$query = $db->prepare("SELECT * FROM profiles");
$query->execute();

$result = $query->fetchAll(PDO::FETCH_ASSOC);
$currentImage = $result[1];

function imageToDataUri($imageBinary, $mimeType) {
    $base64 = base64_encode($imageBinary);
    return 'data:' . $mimeType . ';base64,' . $base64;
}

$dataUri = imageToDataUri($currentImage['image'], $currentImage['image_mime']);

?>


<img src="<?php echo $dataUri; ?>" alt="Image" />