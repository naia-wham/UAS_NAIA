<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$target_dir = "uploads/";
$uploadOk = 1;

// Include koneksi
require 'koneksi.php';

// Ambil ID dari POST
$id = $_POST['id'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$author = $_POST['author'];
$kategori = $_POST['kategori'];
$current_image = $_POST['image']; // Current image name

$imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

// Cek jika file baru diupload
if (isset($_POST["submit"]) && !empty($_FILES["image"]["name"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["image"]["size"] > 5000000) { // Limit to 5 MB
        echo "Ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Hanya izinkan file gambar tertentu
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Tentukan target file
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Cek apakah file sudah ada 
    if (file_exists($target_file)) {
        echo "File sudah ada. Mengganti file yang ada.";
        // Optional: You can rename the file here if you want
        // $image = uniqid() . '.' . $imageFileType;
        // $target_file = $target_dir . $image;
    }

    // Jika semua cek lolos, lanjutkan dengan upload
    if ($uploadOk == 1) {
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Terjadi kesalahan saat mengupload gambar.";
            $uploadOk = 0;
        }
    }
}

// Update SQL
if ($uploadOk == 1 && !empty($_FILES["image"]["name"])) {
    // Use the new image name if uploaded
    $image = basename($_FILES["image"]["name"]);
} else {
    // Keep the current image if no new upload
    $image = $current_image;
}

// Eksekusi query
$sql = "UPDATE post SET judul='$judul', deskripsi='$deskripsi', author='$author', kategori='$kategori', image='$image' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header("Location: ../admin/index.php");
    exit; // Ensure script stops executing after redirect
} else {
    echo "Error: {$sql} <br> {$conn->error}";
}

$conn->close();
?>
