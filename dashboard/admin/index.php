<?php
$layout_opener = true;
$title = 'Dashboard | Retreat';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

<main>
    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h1 class="text-center">Dashboard Admin Rumah Retreat </h1>
        <div class="d-flex justify-content-center">
            <a href="retret-house.php" class=" text-center btn btn-primary">Lihat Semua Rumah Retreat</a>
        </div>

    <?php 
    $sidebar_closer = true;
    $sidebar_opener = false;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>
</main>
<?php
$layout_closer = true;
$layout_opener = false;
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
