<?php
$layout_opener = true;
$title = 'Rumah Retret | Dashboard Admin';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Retret House</h3>
        <div class="d-flex justify-content-end">
            <div class="input-group mb-3" style="width: 300px;">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i>    </span>
                <input type="text" class="form-control" placeholder="Cari Ulasan" aria-label="Search" aria-describedby="basic-addon1" id="searchReview">
            </div>
        </div>
        <table class="table" id="reviewTable">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Pengguna</th>
                    <th scope="col">Rumah Retreat</th>
                    <th scope="col">Bintang</th>
                    <th scope="col">Komen</th>
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

        <!-- Delete Modal -->
        <div class="modal fade " id="modalReviewDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
$additional_js = 'assets/js/admin/review.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
