<?php
$layout_opener = true;
$title = 'Rumah Retret | Dashboard Admin';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>

    <?php 
    $sidebar_opener = true;
    include $_SERVER['DOCUMENT_ROOT'] . "/includes/sidebar.php";
    ?>

        <h3 class="mb-3">Akun</h3>
        <div class="container mt-4 mb-4 p-3 d-flex justify-content-center"> 
            <div id="card-profile" class="card p-4 w-50"> 
                <div class=" image d-flex flex-column justify-content-center align-items-center"> 
                    <div class=" avatar-large"> 
                        <img id="data-image" src="/assets/images/icon/user.svg"/>
                    </div> 
                    <span id="data-name" class="name mt-3">Asep Surjaman Pena</span> 
                    <span id="data-role" class="idd">Pengunjung</span> 
                    <div class=" d-flex mt-2"> 
                        <button id='edit-profile' class="btn btn-primary">Edit Profile</button> 
                    </div> 
                    <ol class="list-group  w-100 mt-4">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Email</div>
                                <span id='data-email'>Content for list item</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Telepone</div>
                                <span id='data-phone'>Content for list item</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">alamat</div>
                                <span id='data-address'>Content for list item</span>
                            </div>
                        </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Jenis Kelamin</div>
                                <span id='data-gender'>Content for list item</span>
                            </div>
                        </li>
                    </ol>   

                    <div class=" px-2 rounded mt-4 date "> 
                        <span id="join" class="join">Joined May,2021</span> 
                    </div> 
                </div> 
            </div>
            <div id='card-profile-form' class="card p-4 w-50 hide">
                <div class=" image d-flex flex-column justify-content-center align-items-center"> 
                    <form id="handleSubmitEdit" class="w-100">
                        <div id="alert-container"></div>
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="image_id" name="image_id">
                        <div class="mb-3 d-flex align-items-center flex-column">
                            <div class="avatar-large "> 
                                <img id="image-preview" src="/assets/images/icon/user.svg"/>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <label for="image-upload" class="">
                                    <input type="file" class="form-control" id="image-upload" aria-describedby="nameHelp" name="image" accept="image/*" hidden>
                                    <div id="btn-choose-image" class="btn btn-primary">Pilih Gambar</div>
                                </label>
                                <button type="button" id="btn-remove-image" class="btn btn-danger ms-3">Hapus Gambar</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="name" class="form-control" id="name" aria-describedby="nameHelp" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" autocomplete="additional-name" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telephone</label>
                            <input type="text" class="form-control" id="phone" autocomplete="additional-name" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" autocomplete="additional-name" name="address">
                        </div>
                            <div class="mb-3">
                            <label for="address" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" aria-label="Default select example" id="gender" name="gender">
                                <option selected value="">Pilih Jenis Kelamin</option>
                                <option value="Laki Laki">Laki Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-3" id="cancel-edit">Batal</button>
                            <button id="submitBtn" type="submit" class="btn btn-primary">Ubah</button>
                        </div>
                    </form>
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
$additional_js = 'assets/js/account.js';
include $_SERVER['DOCUMENT_ROOT'] . "/includes/layout.php" ?>
