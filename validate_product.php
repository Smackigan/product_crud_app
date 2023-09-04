<?php

// Check if the required fields are set and not empty
if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $imagePath = '';

    if (!$title) {
        $errors[] = 'Title is required';
    }
    if (!$price) {
        $errors[] = 'Price is required';
    }

    if (!is_dir(__DIR__ . '/public/images')) {
        mkdir(__DIR__ . '/public/images');
    }

    // If there are no errors, insert data to DB
    if (empty($errors)) {

        // Image upload
        $image = $_FILES['image'] ?? null;
        $imagePath = $product['image'];

        if ($image && $image['tmp_name']) {

            if ($product['image']) {
                unlink(__DIR__ . '/public/' . $product['image']);
            }

            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
            mkdir(dirname(__DIR__ . '/public/' . $imagePath));

            move_uploaded_file($image['tmp_name'], __DIR__ . '/public/' . $imagePath);
        }

        // Prepare SQL statement
        $statement = $pdo->prepare("UPDATE products SET title = :title, image = :image, description = :description, price = :price WHERE id = :id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}