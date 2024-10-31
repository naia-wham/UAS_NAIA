<?php
require 'koneksi.php';

$id = $_GET['id'];


$sql = "DELETE FROM post WHERE id=$id";


if ($conn->query($sql) === TRUE) {
  header("location:../admin/index.php");
} else {
  echo "Error: {$sql} <br> {$conn->error}";
}


$conn->close();