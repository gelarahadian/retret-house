import {
  getUrlParameter,
  updateUrlParameter,
  generateButtonPagination,
} from "/assets/js/main.js";

function getReservations() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchUser").val(searchQuery);
  $.ajax({
    url: "/api/reservation",
    type: "GET",
    dataType: "json",
    data: {
      page: currentPage || 1, // halaman yang diinginkan
      search: searchQuery || "", // kata kunci pencarian
      by_user: true
    },
    success: function (response) {
        const data = response.data;

        if(data.length === 0) {
            $("#list-reservation-user").html(`
                <div class='col-12'>
                    <p class='text-center'>Anda Belum Memiliki Pesanan Rumah Retreat</p>
                    <div class='d-flex justify-content-center'>
                        <a href='/retret-house' class='btn btn-primary'>Pesan Rumah Retreat</a>
                    </div>
                </div>
            `);
            return;
        }
        console.log(data.length)
        let rows = "";
        data.forEach(reservation => {
            const total_price_in_rupiah = new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 2,
              maximumFractionDigits: 2,
            }).format(reservation.total_price);
            rows += `
                 <div class="col-md-12 col-lg-6 col-xl-4 mb-3 ">
                    <div class="card">
                        <img src="${
                          reservation.room_image_url
                            ? reservation.room_image_url
                            : ""
                        }" class="card-img-top object-fit-cover" height="160" alt="img">
                        <div class="card-body w-100">
                            <div class='d-flex justify-content-between'>
                                <h5 class="card-title">${
                                  reservation.room_code
                                }</h5>
                                ${
                                  reservation.status === "waiting"
                                    ? `<p class='badge text-bg-primary'>Menunggu</p>`
                                    : reservation.status === "checkin"
                                    ? `<p class='badge text-bg-info'>Berlangsung</p> `
                                    : reservation.status === "cancelled"
                                    ? `<p class='badge text-bg-danger'>Dibatalkan</p>`
                                    : `<p class='badge text-bg-success'>Selesai</p>`
                                }
                            </div>
                            <span class="card-text">Tanggal Inap :${
                              reservation.start_date
                            } - ${reservation.end_date}</span>
                            <p class="card-text">Jumlah Peserta :${
                              reservation.number_of_participants
                            }</p>
                            <h6 class="card-subtitle mb-2 text-body-secondary">Total Harga : ${total_price_in_rupiah}</h6>
                            ${
                              reservation.status === "finished"
                                ? `
                                ${
                                  reservation.review_id
                                    ? `<p>Anda sudah memberi ulasan pada pesanan ini</p>`
                                    : `
                                    <div class='d-flex justify-content-end'>
                                        <button class="btn btn-primary reviewBtn" data-id='${reservation.reservation_id}' data-retret-house-id='${reservation.retret_house_id}'>Beri Ulasan</button>
                                    </div>
                                `
                                }
                                `
                                : reservation.status !== "cancelled"
                                ? `
                                <div class='d-flex justify-content-end'>
                                    <button class="btn btn-danger cancelBtn" data-id='${reservation.reservation_id}'>Batalkan</button>
                                </div>
                                `
                                : ""
                            }
                        </div>
                    </div>
                </div>
                `; 
            });
        $("#list-reservation-user").html(rows)
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}


(function ($) {
  "use strict";
  getReservations();

  $("#searchReservation").on("input", function (e) {
    updateUrlParameter("search", e.target.value);
    getReservations();
  });

  $(document).on("click", ".editBtn", function () {
    var retretHouseId = $(this).data("id");
    $.ajax({
      type: "GET",
      url: "/api/user/get-single.php?id=" + retretHouseId,
      dataType: "json",
      success: function (response) {
        if (response.status === "error") {
          console.log(response.message);
        } else if (response.status == "success") {
          const data = response.data;
          $("#modalRetretHouseLabel").text("Edit Retret House");
          $("#id").val(data.retret_house_id);
          $("#name").val(data.name);
          $("#address").val(data.address);
          $("#phone").val(data.phone);
          $("#submitBtn").text("Edit");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
    $("#modalRetretHouse").modal("show");
  });

    $(document).on("click", ".cancelBtn", function () {
        var reservation_id = $(this).data("id");
        $("#confirmCancel").data("id", reservation_id);
        $("#modalReservationCancel").modal("show");
    });

    $(document).on("click", ".reviewBtn", function () {
        var reservation_id = $(this).data("id");
        var retret_house_id = $(this).data("retret-house-id");
        let input_reservation_id = document.createElement("input");
        input_reservation_id.name = "reservation_id";
        input_reservation_id.value = reservation_id;
        input_reservation_id.type = "hidden";
        $("#submitReview").append(input_reservation_id);
        let input_retret_house_id = document.createElement('input');
        input_retret_house_id.type = "hidden";
        input_retret_house_id.name = 'retret_house_id';
        input_retret_house_id.value= retret_house_id;
        $('#submitReview').append(input_retret_house_id);
        $("#modalReview").modal("show");
    });

    $("#confirmCancel").on("click", function () {
        var reservationId = $(this).data("id");

        $.ajax({
            type: "PUT",
            url: "/api/reservation/update.php?id=" + reservationId,
            dataType: "json",
            data: {
                id: reservationId,
                cancel: true,
            },
            success: function (response) {
            if (response.status === "error") {
                $("#alert-container")
                .text(response.message)
                .addClass(`alert alert-danger`)
                .removeClass(`alert-success`);
            } else if (response.status == "success") {
                $("#toast .toast-body").text(response.message);
                getReservations();
                var toast = new bootstrap.Toast($("#toast")[0]);
                toast.show();

                $("#modalReservationCancel").modal("hide");
            }
            },
            error: function (xhr, status, error) {
            console.error("Terjadi kesalahan: " + error);
            },
        });
    });

    $("#modalReview").on("hide.bs.modal", function () {
        $("#submitReview")[0].reset();
        $('input[name="retret_house_id"]').remove();
    });

    $('#rate').on('input', function (e) {
        if(e.target.value < 1){
            e.target.value = 1;
        }if(e.target.value > 5) {
            e.target.value = 5;
        }
    })

    $('#submitReview').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/api/review/create.php",
            dataType: "json",
            data: $(this).serialize(),
            success: function (response) {
                console.log(response);
                if(response.status === 'success') {
                    $("#toast .toast-body").text(response.message);
                    getReservations();
                    var toast = new bootstrap.Toast($("#toast")[0]);
                    toast.show();
                    $("#modalReview").modal("hide");
                }else {
                    $("#alert-container")
                        .text(response.message)
                        .addClass(`alert alert-danger`)
                        .removeClass(`alert-success`);
                }
                $("#submitReview")[0].reset();
            },
            error: function (xhr, status, error) {
            console.error("Terjadi kesalahan: " + error);
            },
        })
    })

    

  $(".pagination").on(
    "click",
    ".page-link[aria-label='Previous']",
    function (e) {
      e.preventDefault();
      var currentPage = $(this).data("current_page");
      if (currentPage > 1) {
        updateUrlParameter("page", currentPage - 1);
        getUsers();
      }
    }
  );

  $(".pagination").on("click", ".page-link[aria-label='Next']", function (e) {
    e.preventDefault();
    var currentPage = $(this).data("current_page");
    var totalPages = $(this).data("total_pages");

    if (currentPage < totalPages) {
      updateUrlParameter("page", currentPage + 1);
      getUsers();
    }
  });

  $(".pagination").on(
    "click",
    ".page-item:not(:first-child):not(:last-child)",
    function (e) {
      e.preventDefault();
      var pageTo = $(this).data("page_to");
      updateUrlParameter("page", pageTo);
      getUsers();
    }
  );
})(jQuery);