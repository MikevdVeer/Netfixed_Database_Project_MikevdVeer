<?php
try {
    // fetch Data
    $db = new PDO("mysql:host=localhost;dbname=dbp_data", "root", "");
    $query = $db->prepare("SELECT * FROM anime WHERE id = " . $_GET['id']);

    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    // update
    $db = new PDO("mysql:host=localhost;dbname=dbp_data", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        $merk = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);


        $query = $db->prepare("UPDATE anime SET name = :name,
        description = :descripion
        WHERE id = :id");

        $query->bindParam(":name", $name);
        $query->bindParam(":description", $description);
        $query->bindParam("id", $_GET[ 'id']);

        // Execute the query
        if ($query->execute()) {
            header("location:admin.php");
        } else {
            echo "Er is een fout opgetreden!";
        }
        echo "<br>";
    }
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Update</title>
</head>

<body class="bg-dark">
    <form method="post" action="" class="card vh-100 container d-flex justify-content-center">
    <h2>Update</h2>
        <label>Name</label>
        <input type="text" name="name" value="<?= $result["name"]?>" required><br>


        <label>Description</label>
        <input type="text" name="description" value="<?= $result["description"]?>" required><br>

        <input type="submit" name="submit" value="opslaan" class="btn btn-primary"><br>
        <a href="./admin.php">Back</a>
    </form>

</body>

</html>