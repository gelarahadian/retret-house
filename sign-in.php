<?php
$title = "Masuk | Retret";
$layout_opener = true;
$header_content = true;
include './includes/layout.php'  ?>
<main>
    <div class="container col-xl-4 col-md-6 border rounded-2 py-4 px-4 my-5">
        <h2>Masuk</h2>
        <h3 class="mb-5">Rumah Retret</h3>
        <div id="responseMessage" class="form-text"></div>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                <div id="emailHelp" class="form-text">Kami tidak akan pernah membagikan email Anda kepada orang lain.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" autocomplete="additional-name" name="password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
            <p class="text-center">Belum Punya akun? <a href="/sign-up.php">Buat Akun</a></p>
        </form>
    </div>
</main>

<?php 
$layout_closer = true;
$layout_opener = false;
$footer_content = true;
$additional_js = '../assets/js/auth.js';
include './includes/layout.php'?>