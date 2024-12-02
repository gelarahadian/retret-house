<?php
$layout_opener = true;
$header_content = true;
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php"  ?>

<main class="container">
    <input type="hidden" name="slug" id="slug" value="<?php echo $slug?>" />
    <div class="card mt-5">
        <div class="card-body d-flex">
            <div class="w-50">
                <h5 id="retret-house-name" class="card-title">Card title</h5>
                <p  id="retret-house-address" class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
            <div class="w-50 d-flex align-items-center flex-wrap justify-content-end">
                <div>
                    <h6 id="price-room" class="card-subtitle mb-2 text-body-secondary">Rp.</h6>
                    <p class="card-text"><small class="text-body-secondary">Harga Per Orang</small></p>
                </div>
                <a href="#rooms" class="btn btn-primary ms-4">Pilih Kamar</a>
            </div>
        </div>
        <div id="carouselRetretHouse" class="carousel slide">
            <div id="retret-house-carousel-inner" class="carousel-inner">
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselRetretHouse" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselRetretHouse" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div id="all-rooms" class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Semua Kamar</h5>
            <div id="rooms">
        </div>
        </div>
    </div>
    <div  class="card mt-3 mb-5">
        <div id="reviews" class="card-body">
            <h5 class="card-title">Ulasan</h5>
        </div>
    </div>
</main>
    
<?php
$layout_closer = true;
$layout_opener = false;
$footer_content = true;
$additional_js = 'assets/js/detail-retret-house.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php"  ?>
