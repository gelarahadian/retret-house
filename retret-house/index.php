<?php
// echo 'detail retret-house';
// Ambil slug dari URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'list-retret-house';

// Mapping slug ke file atau database
switch ($slug) {
    case 'list-retret-house':
        include 'list.php';
        break;
    default:
        // Jika slug tidak ditemukan, arahkan ke halaman detail
        include 'detail.php';
        break;
}
// ?>
