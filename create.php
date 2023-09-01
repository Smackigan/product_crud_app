<?php
$errors = [];

$title = '';
$description = '';
$price = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check if the required fields are set and not empty
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])) {

        // Create a connection to the database
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $date = date('Y-m-d H:i:s');

        if (!$title) {
            $errors[] = 'Title is required';
        }
        if (!$price) {
            $errors[] = 'Price is required';
        }

        if (!is_dir('images')) {
            mkdir('images');
        }

        // If there are no errors, insert data to DB
        if (empty($errors)) {

            // Image upload
            $image = $_FILES['image'] ?? null;
            $imagePath = '';

            if ($image && $image['tmp_name']) {
                $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
                mkdir(dirname($imagePath));

                move_uploaded_file($image['tmp_name'], $imagePath);
            }

            // Prepare SQL statement
            $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) VALUES (:title, :image, :description, :price, :date)");
            $statement->bindValue(':title', $title);
            $statement->bindValue(':image', $imagePath);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':date', $date);

            // Redirect
            header('Location: index.php');

            // Execute the SQL statement
            if ($statement->execute()) {
                // Data was successful
                echo "Product added successfully!";
            } else {
                // Data failed
                echo "Error: Unable to add product.";
            }
        } else {
            // Required fields not provided
            echo "Error: Please fill in all required fields.";
        }
    }
}

// Random string generator for image names
function randomString($n)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $str = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css" />
</head>

<body>
    <h1>Create new product</h1>

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Product image</label>
            <br>
            <input type="file" name="image">
        </div>
        <div class="mb-3">
            <label>Product title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
        </div>
        <div class="mb-3">
            <label>Product description</label>
            <textarea class="form-control" name="description" value="<?php echo $description ?>"></textarea>
        </div>
        <div class="mb-3">
            <label>Product price</label>
            <input type="number" step="0.01" name="price" value="<?php echo $price ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</body>

</html>