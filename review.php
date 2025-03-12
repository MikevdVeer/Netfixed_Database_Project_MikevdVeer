<?php
include "connect.php";

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
    
        $sth = $db->prepare('INSERT INTO review (title, review) VALUES (:title, :review)');
        $sth->bindParam(':title', $inputs['title']);
        $sth->bindParam(':review', $inputs['review']);
        $result = $sth->execute();
    
        echo "review send";
        header("Location: userpage.php");
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <form method="post" action="">

        <div class="mb-3">
            <label for="u" class="form-label">Title</label>
            <input type="text" class="form-control" id="u" name="title" value="<?php echo $inputs['title'] ?? '' ?>">
            <div class="form-text text-danger">
                <?php echo $errors['title'] ?? '' ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="p" class="form-label">Review</label>
            <input type="review" class="form-control" id="p" name="review" value="<?php echo $inputs['review'] ?? '' ?>">
            <div class="form-text text-danger">
                <?php echo $errors['review'] ?? '' ?>
            </div>
        </div>

        <input type="submit" class="btn btn-primary" name="send" value="verzenden">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>