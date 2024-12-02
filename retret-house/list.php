<?php
$layout_opener = true;
$header_content = true;
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php"  ?>

<main class="container">
    <h1 class="text-center">Daftar Rumah Retret</h1>
    <div id="list-retret-houses" class="row">
    </div>
</main>

<?php
$layout_closer = true;
$layout_opener = false;
$footer_content = true;
$additional_js = 'assets/js/list-retret-house.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php"  ?>
