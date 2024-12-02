<?php
$layout_opener = true;
$title = 'Rumah Retret | Dashboard Admin';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Retret House</h3>
        <div class="d-flex justify-content-between">
            <button type="button" id="createBtn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalRetretHouse">
                tambahkan Retret House
            </button>
            <div class="input-group mb-3" style="width: 300px;">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>    </span>
                <input type="text" class="form-control" placeholder="Cari Rumah Retret" aria-label="Search" aria-describedby="basic-addon1" id="searchRetretHouse">
            </div>
        </div>
        <table class="table" id="retretHouseTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Telepon</th>
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

        <!-- Create / Update Modal -->
        <div class="modal fade " id="modalRetretHouse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <div id="image-previews" class="row">
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <label for="image-upload" class="">
                                    <input type="file" class="form-control" id="image-upload" aria-describedby="nameHelp" name="image" accept="image/*" hidden>
                                    <div id="btn-choose-image" class="btn btn-primary">Tambahkan Gambar</div>
                                </label>
                            </div>
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

        <!-- Delete Modal -->
        <div class="modal fade " id="modalReservationDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
<?php
$layout_closer = true;
$layout_opener = false;
$additional_js = 'assets/js/admin/retret-house.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
