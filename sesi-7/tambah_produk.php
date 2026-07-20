<?php
session_start();

// Ambil error, data lama, dan pesan sukses dari session (jika ada)
$errors  = $_SESSION['errors'] ?? [];
$old     = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? '';

// Hapus session setelah diambil agar tidak muncul terus saat di-refresh
unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Produk</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Tambah Produk Baru</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Pesan Sukses -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="proses_produk.php" method="POST" enctype="multipart/form-data" novalidate>
                        
                        <!-- Nama Produk -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                   placeholder="Contoh: Sepatu Lari">
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>" 
                                    id="category" 
                                    name="category">
                                <option value="">-- Pilih Kategori --</option>
                                <?php 
                                $categories = ['Elektronik', 'Pakaian', 'Makanan', 'Aksesoris'];
                                $selectedCategory = $old['category'] ?? '';
                                foreach ($categories as $cat): 
                                ?>
                                    <option value="<?= $cat ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category'])): ?>
                                <div class="invalid-feedback"><?= $errors['category'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Harga & Stok -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                       id="price" 
                                       name="price" 
                                       value="<?= htmlspecialchars($old['price'] ?? '') ?>" 
                                       placeholder="150000">
                                <?php if (isset($errors['price'])): ?>
                                    <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control <?= isset($errors['stock']) ? 'is-invalid' : '' ?>" 
                                       id="stock" 
                                       name="stock" 
                                       value="<?= htmlspecialchars($old['stock'] ?? '') ?>" 
                                       placeholder="25">
                                <?php if (isset($errors['stock'])): ?>
                                    <div class="invalid-feedback"><?= $errors['stock'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Tuliskan spesifikasi atau deskripsi produk..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?= $errors['description'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Gambar Produk -->
                        <div class="mb-4">
                            <label for="image" class="form-label">Gambar Produk <span class="text-danger">*</span></label>
                            <input class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" 
                                   type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/png, image/jpeg, image/jpg, image/webp">
                            <div class="form-text">Format: JPG, JPEG, PNG, WEBP (Max 2MB)</div>
                            <?php if (isset($errors['image'])): ?>
                                <div class="invalid-feedback"><?= $errors['image'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Simpan Produk</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>