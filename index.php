<?php
$layout_opener = true;
$header_content = true;
include './includes/layout.php'; ?>

<main>
    <!-- Hero Section -->
    <section id="hero" class="hero section accent-background">

      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5 justify-content-between">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h2><span>Selamat Datang Di </span><span class="accent">Rumah Retreat</span></h2>
            <p>Website Untuk memudahkan anda Memesan kamar rumah retreat secara online</p>
            <div class="d-flex">
              <a href="#about" class="btn-get-started">Mulai</a>
            </div>
          </div>
          <div class="col-lg-5 order-1 order-lg-2">
            <img src="assets/img/hero-img.svg" class="img-fluid" alt="">
          </div>
        </div>
      </div>

      <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
        <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-end">
                <h4 class="mb-3 mt-5">Rumah Retret Populer</h4>
                <p>

                    <a href="/retret-house" style="color: white;">Tampilkan Semua</a>
                </p>
            </div>
            <div id="list-retret-houses" class="row"></div>
        </div>
      </div>

    </section>
    <div class="container">
            <h2 class="mb-3 mt-5">Daftar Kamar Populer</h2>
            <div id="list-rooms" class="row">
        
        </div>
    </div>
    
</main>
    
<?php
$layout_closer = true;
$layout_opener = false;
$footer_content = true;
$additional_js = 'assets/js/landing.js';
include './includes/layout.php'; ?>
