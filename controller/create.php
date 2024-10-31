<?php

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah file benar-benar gambar 
if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if ($check !== false) {
    echo "File adalah gambar - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File bukan gambar.";
    $uploadOk = 0;
  }
}

// Cek apakah file sudah ada 
if (file_exists($target_file)) {
  echo "File sudah ada.";
  $uploadOk = 0;
}

// Cek ukuran file 
if ($_FILES["image"]["size"] > 500000) {
  echo "Ukuran file terlalu besar.";
  $uploadOk = 0;
}

// Hanya izinkan file gambar tertentu 
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
  echo "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
  $uploadOk = 0;
}

// Jika semua cek lolos, lanjutkan dengan upload
if ($uploadOk == 1) {
  // Pindahkan file ke direktori uploads
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    require 'koneksi.php';

    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $author = $_POST['author'];
    $kategori = $_POST['kategori'];
    $image = basename($_FILES["image"]["name"]);

    $sql = "INSERT INTO post (judul, deskripsi, author, kategori, image) VALUES ('$judul', '$deskripsi', '$author', '$kategori', '$image')";
    if ($conn->query($sql) === TRUE) {
      header("Location: ../admin/index.php");
      exit; // Tambahkan exit setelah header redirect
    } else {
      echo "Error: {$sql} <br> {$conn->error}";
    }
  } else {
    echo "Terjadi kesalahan saat mengupload gambar.";
  }
} else {
  echo "File tidak diupload.";
}

$conn->close();
?>
