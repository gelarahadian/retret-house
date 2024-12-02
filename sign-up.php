<?php
$title = "Daftar | Retret";
$layout_opener = true;
$header_content = true;
include './includes/layout.php'  ?>
<main>
    <div class="container col-xl-4 col-md-6 border rounded-2 py-4 px-4 my-5">
        <h2>Daftar</h2>
        <h3 class="mb-5">Rumah Retret</h3>
        <div id="responseMessage" class="form-text"></div>
        <form id="registerForm">
            <div class="row g-2">
                <div class=" col-md mb-3">
                    <label for="firstName" class="form-label">Nama Awal</label>
                    <input type="text" class="form-control" id="firstName" name="firstName">
                </div>
                <div class="col-md mb-3">
                    <label for="lastName" class="form-label">Nama Akhir</label>
                    <input type="text" class="form-control" id="lastName" name="lastName">
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                <div id="emailHelp" class="form-text">Kami tidak akan pernah membagikan email Anda kepada orang lain.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" autocomplete="additional-name" name="password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
            <p class="text-center">Sudah Punya akun? <a href="/sign-in.php">Masuk</a></p>
        </form>
    </div>
</main>

<?php
$layout_closer = true;
$layout_opener = false;
$footer_content = false;
$additional_js = '../assets/js/auth.js';
include './includes/layout.php'?>