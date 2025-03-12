<?php
if(isset($_POST["submit"])) {
    $query = $pdo->prepare("DELETE FROM anime WHERE id = :id");
    $query->bindParam(":id", $_GET["id"]);
    $query->execute();
    header("location:admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link>
    <title>Document</title>
</head>
<body class="container mt-5">
    <form method="post">
        <h1>Verwijder rij met nummer
        <?=$_GET['id']?></h1>
        <?php
    try {
        $db = new PDO("mysql:host=localhost;dbname=dbp_data", "root", "");
        $query = $db->prepare("SELECT * FROM anime WHERE id = " . $_GET['id']);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $data) {
            echo "id: " . $data['id'] . "<br>";
            echo "Name: " . $data['name'] . "<br>";
            echo "Image: " . $data['image'] . "<br><br>";
            echo "Description: " . $data['description'] . "<br><br>";
        }
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage());
    }
?>
        <input type="submit" name="submit" value="delete">
    </form><br>
    <a href="admin.php">Terug</a>
</body>
</html>