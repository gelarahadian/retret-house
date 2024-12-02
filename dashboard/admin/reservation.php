<?php
$layout_opener = true;
$title = 'Rumah Retret | Dashboard Admin';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Pemesanan</h3>
        <div class="d-flex justify-content-end">
            <div class="input-group mb-3" style="width: 300px;">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>    </span>
                <input type="text" class="form-control" placeholder="Cari Rumah Retret" aria-label="Search" aria-describedby="basic-addon1" id="searchReservation">
            </div>

        </div>
        <div class=" overflow-x-auto">
            <table class="table" id="reservationTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Pemesan</th>
                        <th scope="col">Rumah Retret</th>
                        <th scope="col">Kamar</th>
                        <th scope="col">Jumlah Peserta</th>
                        <th scope="col">Tanggal Order</th>
                        <th scope="col">Tanggal Inap</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        
        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
            </ul>   
        </nav>

        <!-- Create Modal -->
        <div class="modal fade " id="modalReservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRetretHouseLabel">Buat Retret House</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="handleSubmitRetretHouse">
                            <div id="alert-container"></div>
                            <input type="hidden" id="id" name="id">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="name" class="form-control" id="name" aria-describedby="nameHelp" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" autocomplete="additional-name" name="address">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telephone</label>
                                <input type="text" class="form-control" id="phone" autocomplete="additional-name" name="phone">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">Batal</button>
                                <button id="submitBtn" type="submit" class="btn btn-primary">Buat</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade " id="modalReservationCheck" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center">Apakah anda yangkin untuk mengkonfirmasi pengunjung telah memasuki kamar?</h3>
                        <div class="d-flex justify-content-center ">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-primary me-3">Batal</button>
                            <button type="button" class="btn btn-success" id="confirmCheck">Konfirmasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="modal fade " id="modalReservationFinish" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center">Apakah anda yakin untuk mengkonfirmasi Pengunjung telah menginap dan menyelesaikan pesanan ini?</h3>
                        <div class="d-flex justify-content-center ">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-primary me-3">Batal</button>
                            <button type="button" class="btn btn-success" id="confirmFinish">Konfirmasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade " id="modalReservationDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center">Apakah anda yakin untuk menghapus Pesanan ini?</h3>
                        <div class="d-flex justify-content-center ">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-primary me-3">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                        </div>
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
$additional_js = 'assets/js/admin/reservation.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
