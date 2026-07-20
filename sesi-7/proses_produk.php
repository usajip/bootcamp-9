<?php
session_start();

$errors = [];
$old = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Simpan nilai lama untuk dipasang kembali jika validasi gagal
    $old = [
        'name'        => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price'       => $_POST['price'] ?? '',
        'stock'       => $_POST['stock'] ?? '',
        'category'    => $_POST['category'] ?? ''
    ];

    // 1. Validasi Nama Produk
    if (empty($_POST["name"])) {
        $errors['name'] = "Nama produk wajib diisi.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
        if (strlen($name) < 3) {
            $errors['name'] = "Nama produk minimal 3 karakter.";
        }
    }

    // 2. Validasi Deskripsi (Sanitasi)
    $description = htmlspecialchars(trim($_POST["description"] ?? ''));

    // 3. Validasi Harga
    if (empty($_POST["price"]) && $_POST["price"] !== '0') {
        $errors['price'] = "Harga produk wajib diisi.";
    } else {
        $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0) {
            $errors['price'] = "Harga produk harus berupa angka positif.";
        }
    }

    // 4. Validasi Stok
    if (empty($_POST["stock"]) && $_POST["stock"] !== '0') {
        $errors['stock'] = "Stok produk wajib diisi.";
    } else {
        $stock = filter_var($_POST["stock"], FILTER_VALIDATE_INT);
        if ($stock === false || $stock < 0) {
            $errors['stock'] = "Stok harus berupa angka bulat positif.";
        }
    }

    // 5. Validasi Kategori
    $allowed_categories = ['Elektronik', 'Pakaian', 'Makanan', 'Aksesoris'];
    if (empty($_POST["category"])) {
        $errors['category'] = "Kategori wajib dipilih.";
    } else {
        $category = $_POST["category"];
        if (!in_array($category, $allowed_categories)) {
            $errors['category'] = "Kategori tidak valid.";
        }
    }

    // 6. Validasi Gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName    = $_FILES['image']['name'];
        $fileSize    = $_FILES['image']['size'];

        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors['image'] = "Format gambar harus JPG, JPEG, PNG, atau WEBP.";
        } elseif ($fileSize > $maxFileSize) {
            $errors['image'] = "Ukuran gambar maksimal 2MB.";
        }
    } else {
        $errors['image'] = "Gambar produk wajib diunggah.";
    }

    // Jika ada error, simpan data ke session dan kembalikan ke index.php
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old']    = $old;
        header("Location: tambah_produk.php");
        exit;
    }

    // Jika validasi lolos: unggah gambar & simpan data
    $uploadDir = './uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $destPath    = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
    } else {
        $_SESSION['errors'] = ['image' => "Gagal mengunggah gambar ke server."];
        $_SESSION['old']    = $old;
    }

    header("Location: tambah_produk.php");
    exit;
}