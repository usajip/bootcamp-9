<?php
require_once '../../connect.php';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Validate the form data
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Nama produk harus diisi.';
    }
    if (empty($category)) {
        $errors['category'] = 'Kategori produk harus dipilih.';
    }
    if (empty($price) || !is_numeric($price) || $price < 0) {
        $errors['price'] = 'Harga produk harus berupa angka positif.';
    }
    if (empty($stock) || !is_numeric($stock) || $stock < 0) {
        $errors['stock'] = 'Stok produk harus berupa angka positif.';
    }
    if (empty($image)) {
        $errors['image'] = 'Gambar produk harus diunggah.';
    }

    // If there are no validation errors, proceed to insert the data into the database
    if (empty($errors)) {
        // Move the uploaded image to the desired directory
        $upload_dir = '../../uploads/';
        //remove space from the image name and replace it with underscore
        $image = str_replace(' ', '_', $image);
        $image = time() . '_' . basename($image); // Rename the image to avoid conflicts
        $image_path = $upload_dir . $image;
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Prepare the SQL statement to insert the product data into the database
            $sql = "INSERT INTO products (name, category, price, stock, description, image) VALUES (:name, :category, :price, :stock, :description, :image)";
            $stmt = $pdo->prepare($sql);
            // Bind the parameters and execute the statement
            $stmt->execute([
                ':name' => $name,
                ':category' => $category,
                ':price' => $price,
                ':stock' => $stock,
                ':description' => $description,
                ':image' => $image
            ]);
            // Redirect to the product list page after successful insertion
            header('Location: ../index.php');
            exit();
        } else {
            $errors['image'] = 'Gagal mengunggah gambar produk.';
        }
    }
}