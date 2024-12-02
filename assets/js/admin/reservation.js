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
    },
    success: function (response) {
      console.log(response);
      var rows = "";
      if (response.data.length < 1) {
        $("#reservationTable tbody").html(
          "<tr><td colspan='6' class='text-center border border-0 mt-3'>Data kosong</td></tr>"
        );
        return;
      }
      response.data.forEach(function (reservation, i) {
          const total_price_in_rupiah = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }).format(reservation.total_price);
        rows += `
        <tr>
          <td>${response.limit * (response.currentPage - 1) + i + 1}</td>
          <td>${reservation.user_name}</td>
          <td>${reservation.retret_house_name}</td>
          <td>${reservation.room_code}</td>
          <td>${reservation.number_of_participants}</td>
          <td>${reservation.order_date}</td>
          <td>${reservation.start_date} - ${reservation.end_date}</td>
          <td>${total_price_in_rupiah}</td>
          <td>
          ${reservation.status === 'waiting' 
            ? `<p class='badge text-bg-primary'>Menunggu</p>` 
            : reservation.status === 'checkin' 
            ? `<p class='badge text-bg-info'>Berlangsung</p>` 
            : reservation.status === 'cancelled' 
            ? `<p class='badge text-bg-danger'>Dibatalkan</p>` 
            : `<p class='badge text-bg-success'>Selesai</p>`}
          </td>
          <td>
          ${
            reservation.status === "waiting"
              ? `
              <button class='btn checkBtn' data-id="${reservation.reservation_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <button class='btn cancelBtn' data-id="${reservation.reservation_id}">
                <i class="fa fa-ban" aria-hidden="true"></i>
              </button>
            `
              : reservation.status === 'checkin' ? `
              <button class='btn finishBtn' data-id="${reservation.reservation_id}">
                <i class="fa fa-hourglass-end" aria-hidden="true"></i>
              </button>
            ` : ''
          }
            
            <button type='button' class='btn deleteBtn' data-id="${
              reservation.reservation_id
            }">
              <i class='fa fa-trash'></i>
            </button>
          </td>
        </tr>`;
      });
      $("#reservationTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages);
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

    $(document).on("click", ".checkBtn", function () {
      console.log("delete");
      var reservation_id = $(this).data("id");
      $("#confirmCheck").data("id", reservation_id);
      $("#modalReservationCheck").modal("show");
    });

    $(document).on("click", ".cancelBtn", function () {
      console.log("delete");
      var reservation_id = $(this).data("id");
      $("#confirmCancel").data("id", reservation_id);
      $("#modalReservationCancel").modal("show");
    });

      $(document).on("click", ".finishBtn", function () {
        console.log("delete");
        var reservation_id = $(this).data("id");
        $("#confirmFinish").data("id", reservation_id);
        $("#modalReservationFinish").modal("show");
      });

    $(document).on("click", ".deleteBtn", function () {
      console.log('delete')
      var reservation_id = $(this).data("id");
      $("#confirmDelete").data("id", reservation_id);
      $("#modalReservationDelete").modal("show");
    });

    $("#confirmCheck").on("click", function () {
      var reservationId = $(this).data("id");

      $.ajax({
        type: "PUT",
        url: "/api/reservation/update.php",
        dataType: "json",
        data: {
          id: reservationId,
          checkin: true,
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

            $("#modalReservationCheck").modal("hide");
          }
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    });


    $("#confirmCancel").on("click", function () {
      var reservationId = $(this).data("id");

      $.ajax({
        type: "PUT",
        url: "/api/reservation/update.php?id=" + reservationId,
        dataType: "json",
        data: {
          id: reservationId,
          cancel: true
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

  $("#confirmFinish").on("click", function () {
    var reservationId = $(this).data("id");

    $.ajax({
      type: "PUT",
      url: "/api/reservation/update.php?id=" + reservationId,
      dataType: "json",
      data: {
        id: reservationId,
        finish: true,
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

          $("#modalReservationFinish").modal("hide");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
  });


    $("#confirmDelete").on("click", function () {
      var reservationId = $(this).data("id");

      $.ajax({
        type: "DELETE",
        url: "/api/reservation/delete.php?id=" + reservationId,
        dataType: "json",
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

            $("#modalReservationDelete").modal("hide");
          }
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    });


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