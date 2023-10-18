<?php
session_start(); // Anda perlu memulai sesi untuk menggunakan $_SESSION

include '../config.php';

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $subjudul = $_POST['subjudul'];
    $status = $_POST['status'];
    $namagambar = $_FILES['gambar']['name'];
    $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
    $x = explode('.', $namagambar);
    $ekstensi = strtolower(end($x));
    $ukuran = $_FILES['gambar']['size'];
    $file_tmp = $_FILES['gambar']['tmp_name']; // Perbaiki penugasan di sini

    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        if ($ukuran < 1044070) {
            move_uploaded_file($file_tmp, '../assets/img/' . $namagambar);
            $koneksi->query("INSERT INTO hero (gambar, judul, subjudul, status) VALUES ('$namagambar','$judul','$subjudul','$status')");
            header("location:index.php");
        } else {
            $_SESSION['gagalposting'] = "Maaf, postingan tidak berhasil disimpan karena ukuran file terlalu besar";
            header("location:index.php?gagal");
        }
    } else {
        $_SESSION['gagalposting'] = "Maaf, postingan tidak berhasil disimpan karena format file tidak sesuai. Hanya file PNG, JPG, dan JPEG yang diperbolehkan.";
        header("location:index.php?gagal");
    }
}

if (isset($_GET['delete'])) {
    $idhero = $_GET['delete'];
    $koneksi->query("DELETE FROM hero WHERE idhero = '$idhero'");
    header("location:index.php");
}

if (isset($_POST['editposting'])) {
    $idhero = $_POST['idhero'];
    $judul = $_POST['judul'];
    $subjudul = $_POST['subjudul'];
    $status = $_POST['status'];
    if (!empty($_FILES['gambar']['name'])) {
        $namagambar = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];

        if (move_uploaded_file($file_tmp, '../assets/img/' . $namagambar)) {
            $koneksi->query("UPDATE hero SET judul = '$judul', subjudul = '$subjudul', gambar = '$namagambar', status = '$status' WHERE idhero = '$idhero'");
        }
    } else {
        $koneksi->query("UPDATE hero SET judul = '$judul', subjudul = '$subjudul', status = '$status' WHERE idhero = '$idhero'");
    }
    header("location:index.php");
}
?>
