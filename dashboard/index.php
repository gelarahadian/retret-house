<?php
$layout_opener = true;
$title = 'Dashboard | Retreat';
include "../includes/layout.php" ?>

<main>
    <?php 
    $sidebar_opener = true;
    include "../includes/sidebar.php";
    ?>

        <h1 class="text-center">Dashboard Pengunjung Rumah Retreat </h1>
        <div class="d-flex justify-content-center">
            <a href="reservation.php" class=" text-center btn btn-primary">Lihat Pemesanan Rumah Retreat Saya</a>
        </div>

    <?php 
    $sidebar_closer = true;
    $sidebar_opener = false;
    include "../includes/sidebar.php";
    ?>
</main>
<?php
$layout_closer = true;
$layout_opener = false;
include "../includes/layout.php" ?>
