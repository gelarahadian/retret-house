<?php
$layout_opener = true;
$title = 'Dashboard | Retreat';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

<main>
    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Kamar</h3>
        <div class="d-flex justify-content-between">
            <button type="button" id="createBtn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalRoom">
                Tambahkan Kamar
            </button>
            <div class="input-group mb-3" style="width: 300px;">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>    </span>
                <input type="text" class="form-control" placeholder="Cari Kamar" aria-label="Search" aria-describedby="basic-addon1" id="searchRooms">
            </div>

        </div>
        <table class="table" id="roomsTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Kode Kamar</th>
                    <th scope="col">kapasitas</th>
                    <th scope="col">Harga Per Orang</th>
                    <th scope="col">Rumah Retret</th>
                    <th scope="col">Fasilitas</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
                                  
        <nav class="d-flex justify-content-center" aria-label="Page navigation example">
            <ul class="pagination">
            </ul>   
        </nav>

        <!-- Create Modal -->
        <div class="modal fade " id="modalRoom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRoomLable">Buat Kamar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="handleSubmitRoom">
                            <div id="alert-container"></div>
                            <input type="hidden" id="id" name="id">
                            <div id="image-previews" class="row">
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <label for="image-upload" class="">
                                    <input type="file" class="form-control" id="image-upload" aria-describedby="nameHelp" name="image" accept="image/*" hidden>
                                    <div id="btn-choose-image" class="btn btn-primary">Tambahkan Gambar</div>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Kapasitas</label>
                                <input type="text" class="form-control" id="capacity" autocomplete="additional-name" name="capacity">
                            </div>
                                <div class="mb-3">
                                <label for="price-per-person" class="form-label">Harga /Orang</label>
                                <input type="text" class="form-control" id="price-per-person" autocomplete="additional-name" name="price-per-person">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepone</label>
                                <input type="text" class="form-control" id="phone" autocomplete="additional-name" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="retret-house-select" class="form-label">Rumah Retret</label>
                                <select class="form-select" id="retret-house-select" data-placeholder="Tambahkan Fasilitas" name="retret-house-id">
                                </select>
                            </div>
                                 <div class="mb-3">
                                <label for="facility-select" class="form-label">Fasilitas</label>
                                <select class="form-select" id="facility-select" data-placeholder="Tambahkan Fasilitas" multiple name="facility_ids[]">

                                </select>
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

        <div class="modal fade " id="modalRoomDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-center">Apakah anda yakin untuk menghapus Rumah Retret?</h3>
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
</main>
<?php
$layout_closer = true;
$layout_opener = false;
$additional_js = 'assets/js/admin/room.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
