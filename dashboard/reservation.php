<?php
$layout_opener = true;
$title = 'Rumah Retret | Dashboard Admin';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Pemesanan</h3>
        <div class="d-flex justify-content-between">
            <a href="/retret-house" class="btn btn-primary mb-3">
                Pesan Kamar
            </a>
            <div class="input-group mb-3" style="width: 300px;">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>    </span>
                <input type="text" class="form-control" placeholder="Cari Pesanan Saya (Kode Kamar)" aria-label="Search" aria-describedby="basic-addon1" id="searchReservation">
            </div>
        </div>
        <div id="list-reservation-user" class="row"></div>

        <div class="modal fade " id="modalReservationCancel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center">Apakah anda yakin untuk membatalkan pesanan ini?</h3>
                        <div class="d-flex justify-content-center ">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-primary me-3">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmCancel">Batalkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade " id="modalReview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRetretHouseLabel">Ulasan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="submitReview">
                            <div id="alert-container"></div>
                            <input type="hidden" id="id" name="id">
                            <div class="mb-3">
                                <label for="rate" class="form-label">Penilaian (1 - 5)</label>
                                <input type="number" class="form-control" id="rate"  autocomplete="additional-name" name="rate" min='1' max='5'>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <input type="text" class="form-control" id="message" aria-describedby="nameHelp" name="message">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Batal</button>
                                <button id="submitBtn" type="submit" class="btn btn-primary">Beri Penilaian</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        


    <?php 
    $sidebar_closer = true;
    $sidebar_opener = false;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>
<?php
$layout_closer = true;
$layout_opener = false;
$additional_js = 'assets/js/reservation.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
