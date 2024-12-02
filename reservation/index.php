<?php
$room_code = isset($_GET['room_code']) ? $_GET['room_code'] : 'list-retret-house';
$layout_opener = true;
$header_content = true;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/layout.php'; ?>

<main class="container">
    <input type="hidden" name="room_code" id="room_code" value="<?php echo $room_code?>" />
    <div class="row my-5">
        <div class='col-12 col-md-7'>
            <div  class="card">
                <div class="card-body">
                    <h5 class="card-title">Kamar</h5>
                </div>
                <img id="room-image">
                <div class="card-body">
                    <h5 id="room-code-content" class="card-title">Kamar</h5>
                    <h6 id="retret-house-content" class="card-subtitle mb-2 text-body-secondary">Retreat House: </h6>
                    <h6 id="number-day-content" class="card-subtitle mb-2 text-body-secondary">jumlah Hari : </h6>
                    <h6 id="price-per-person-content" class="card-subtitle mb-2 text-body-secondary">Harga Per Orang : </h6>
                    <h6 id="capacity-content" class="card-subtitle mb-2 text-body-secondary">Kapasitas: 4 Orang</h6>
                </div>
            </div>
            <div  class="card mt-3">
                <div class="card-body">
                    <form>
                        <div class="row g-2">
                            <div class=" col-md mb-3">
                                <label for="start-date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start-date" name="start_date">
                            </div>
                            <div class="col-md mb-3">
                                <label for="end-date" class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control" id="end-date" name="end_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="number-of-participants" class="form-label">Jumlah Peserta</label>
                            <input type="number" class="form-control" id="number-of-participants" aria-describedby="number_of_participantsHelp" name="number_of_participants">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class='col-12 col-md-5'>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail</h5>
                    <h6 id="start-date-detail" class="card-subtitle mb-2 text-body-secondary">Tanggal Mulai: </h6>
                    <h6 id="end-date-detail" class="card-subtitle mb-2 text-body-secondary">Tanggal Berakhir: </h6>
                    <h6 id="number-day-detail" class="card-subtitle mb-2 text-body-secondary">Junlah Hari: </h6>
                    <h6 id="number-of-partisipant-detail" class="card-subtitle mb-2 text-body-secondary">Jumlah Peserta: </h6>
                    <h6 id="price-total-detail" class="card-subtitle mb-2 text-body-secondary">Total Harga: </h6>
                    <button class="btn btn-primary w-100" type="button" id="make-reservation" >Buat Pesanan</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$layout_closer = true;
$layout_opener = false;
$footer_content = true;
$additional_js = 'assets/js/make-reservation.js';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/layout.php'; ?>
