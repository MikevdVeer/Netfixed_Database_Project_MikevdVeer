<?php

include "../connect.php";
include "../user_status.php";
if ($currentUserType != 'admin') {
  header("Location: ../userpage.php");
}

$query = $db->prepare("SELECT * FROM anime");
$query->execute();

$result = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $db->prepare("SELECT * FROM users");
$query->execute();

$allUsers = $query->fetchAll(PDO::FETCH_ASSOC);

const NAME_REQUIRED = 'name required';

$errors = [];
$inputs = [];

if (isset($_POST['send'])) {
  $animeName = filter_input(INPUT_POST, 'animeName', FILTER_SANITIZE_SPECIAL_CHARS);
  $animeName = trim($animeName);
  if (empty($animeName)) {
    $errors['animeName'] = NAME_REQUIRED;
  } else {
    $inputs['animeName'] = $animeName;
  }
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
  $description = trim($description);
  $inputs['description'] = $description;

  if (count($errors) === 0) {
    global $db;

    $sth = $db->prepare('INSERT INTO anime (name, description) VALUES (:name, :description)');
    $sth->bindParam(":name", $inputs['animeName']);
    $sth->bindParam(":description", $inputs['description']);
    $result = $sth->execute();

    header("Location: admin.php");
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="assets/logo.png">
  <title>Admin</title>
</head>

<body class="bg-dark">
  <div class="container py-4 text-white">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white rounded-2 p-3 text-black">
      <div class="d-flex align-items-center gap-2">
        <img src="<?php echo $currentProfilePicture ?>" class="rounded-circle" alt="" style="width: 100px; height: 100px;">
        <div class="m">
          <h1 class="h4 mb-0">Admin Dashboard</h1>
          <p class="text-black small mb-0">Welcome back, Admin</p>
        </div>

      </div>
      <div class="d-flex gap-2 text-black">
        <a href="../edit_profile.php">
          <button class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Edit profile
          </button>
        </a>
      </div>
    </div>

    <!-- Users Display -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-12 col-lg-6">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Recent Activity</h5>
              <button class="btn btn-link text-decoration-none p-0">View All</button>
            </div>
          </div>
          <div class="card-body">
            <div class="timeline">
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <div class="bg-primary bg-opacity-10 p-2 rounded">
                    <i class="fas fa-user-plus text-primary"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">New user registered (name)</h6>
                  <p class="text-muted small mb-0">2 minutes ago</p>
                </div>
              </div>
              <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                  <div class="bg-success bg-opacity-10 p-2 rounded">
                    <i class="fas fa-check text-success"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">Add anime complete <p class="text-muted small mb-0">Registered anime(name)</p>
                  </h6>

                  <p class="text-muted small mb-0">1 hour ago</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Users -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center h-100">
              <div>
                <h6 class="text-muted mb-2">Total Users</h6>
                <h3 class="mb-0"><?php echo count($allUsers);?></h3>
                <small class="text-success">
                  <i class="fas fa-arrow-up me-1"></i>12.5% 
                  <!-- math procentages on date -->
                </small>
              </div>
              <div class="bg-primary bg-opacity-10 p-3 rounded">
                <i class="fas fa-users text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center h-100">
              <div>
                <h6 class="text-muted mb-2">Revenue</h6>
                <h3 class="mb-0">$0,00</h3>
                <small class="text-success">
                  <i class="fas fa-arrow-up me-1"></i>0.00%
                </small>
              </div>
              <div class="bg-success bg-opacity-10 p-3 rounded">
                <i class="fas fa-dollar-sign text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Grud -->
    <div class="row g-3">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">GRUD Anime</h5>

            </div>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">id</th>
                  <th scope="col">Name</th>
                  <th scope="col">Image</th>
                  <th scope="col">Update</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($result as $data) {
                  echo "<tr>";
                  echo "<th scope='row'>" . $data['id'] . "</th>";
                  echo "<td>" . $data["name"] . "</td>";
                  echo "<td>" . $data["image"] . "</td>";
                  echo "<td>" . "<a href='update_detail.php?id=" . $data['id'] . "'>" . "update >" . "</a>" . "</td>";
                  echo "<td>" . "<a href='delete.php?id=" . $data['id'] . "'>" . "delete >" . "</a>" . "</td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- New section -->

      </div>

      <!-- Quick Actions -->
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Add anime</h5>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <form method="post" enctype="multipart/form-data" class="d-flex">
                <!-- 2 column grid layout with text inputs for the first and last names -->
                <div class="row">
                  <!-- Username input -->
                  <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="username">Name</label><br>
                    <input type="text" name="animeName" class="form-control">
                  </div>

                  <!-- Password input -->
                  <div class="col-md-12 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="description">Description</label><br>
                      <textarea class="w-100" name="description" id="description"></textarea>
                    </div>
                  </div>

                  
                  <div data-mdb-input-init class="input-group mb-3">
                    <input type="file" name="file_up" class="form-control btn btn-primary">
                  </div>

                  <!-- Submit button -->
                  <div class="d-flex justify-content-center mt-2">
                    <input type="submit" name="submit" class ="btn btn-primary d-flex gap-1">
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>