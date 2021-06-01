<?php

require_once "function.php";

$pdo = new PDO('mysql:host=localhost; port=3306; dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = []; 

$title = '';
$description = '';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $image = $_FILES['image'] ?? null;
  $imagePath = '';

  if (!$title) {
    $errors[] = 'Title is required';
  }

  if (!$price) {
    $errors[] = 'Price is required';
  }

  if (!is_dir('images')) {
    mkdir('images');
  }

  if (empty($errors)) {

    if ($image && $image['tmp_name']) {
      $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
      mkdir(dirname($imagePath));
      move_uploaded_file($image['tmp_name'], $imagePath);

    }

    $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
    VALUES (:title, :image, :description, :price, :date)");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':image', $imagePath);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':date', date('Y-m-d H:i:s'));
    $statement->execute();

    header('Location: index.php');
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

    <title>Add Product</title>
  </head>

  <body>

    <h1>Add Product</h1>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
          <?php foreach ($errors as $error): ?>
            <div><?php echo $error ?></div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action=""  method="post" enctype="multipart/form-data">  
      <div class="mb-3">
              <label class="form-label">Image</label>
              <br>
              <input type="file" name="image">
          </div>
          <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
          </div>
          <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea  class="form-control" name="description"><?php echo $description ?></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Price</label>
              <input type="number" class="form-control" name="price" value="<?php echo $price ?>">
          </div>

          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="index.php" type="submit" class="btn btn-primary">Back</a>
      </div>
    </form>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->

  </body>
</html>