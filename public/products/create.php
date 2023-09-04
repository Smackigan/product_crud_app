<?php

require_once "../../database.php";
require_once "../../functions.php";

$errors = [];

$title = '';
$description = '';
$price = '';
$product = [
    'image' => '',
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check if the required fields are set and not empty
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])) {

        require_once "../../validate_product.php";

        // If there are no errors, insert data to DB
        if (empty($errors)) {

            // Prepare SQL statement
            $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) VALUES (:title, :image, :description, :price, :date)");
            $statement->bindValue(':title', $title);
            $statement->bindValue(':image', $imagePath);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':date', date('Y-m-d H:i:s'));
            $statement->execute();
            // Redirect
            header('Location: index.php');
        }
    }
}

?>

<?php include_once '../../views/partials/header.php' ?>

<h1>Create new product</h1>

<p> <a href="index.php" class="btn btn-secondary">Go back</a></p>

<?php include_once "../../views/products/form.php" ?>

</body>

</html>