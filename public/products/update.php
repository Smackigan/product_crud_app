<?php

require_once "../../database.php";
require_once "../../functions.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];

$title = $product['title'];
$description = $product['description'];
$price = $product['price'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check if the required fields are set and not empty
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])) {

        require_once '../../validate_product.php';

        // If there are no errors, insert data to DB
        if (empty($errors)) {
        }

        // Prepare SQL statement
        $statement = $pdo->prepare("UPDATE products SET title = :title, image = :image, description = :description, price = :price WHERE id = :id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();

        // Redirect
        header('Location: index.php');
    }
}

?>

<?php include_once '../../views/partials/header.php' ?>

<p>
    <a href="index.php" class="btn btn-secondary">Go back</a>
</p>

<h1>Update product <b><?php echo $product['title'] ?></b></h1>

<?php include_once "../../views/products/form.php" ?>

</body>

</html>