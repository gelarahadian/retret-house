import {
  getUrlParameter,
  updateUrlParameter,
  generateButtonPagination,
} from "/assets/js/main.js";

function getFacilities() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchFacility").val(searchQuery);

  $.ajax({
    url: "/api/facility",
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
        $("#facilityTable tbody").html(
          "<tr><td colspan='6' class='text-center border border-0 mt-3'>Data kosong</td></tr>"
        );
        return;
      }
      response.data.forEach(function (facility, i) {
        rows += `
        <tr>
          <td>${i + 1}</td>
          <td>${facility.name}</td>
          <td>${facility.facility_type}</td>
          <td>
            <button class='btn editBtn' data-id="${
              facility.facility_id
            }">
              <i class='fa fa-pencil' aria-hidden='true'></i>
            </button>
            <button type='button' class='btn deleteBtn' data-id="${
              facility.facility_id
            }">
              <i class='fa fa-trash'></i>
            </button>
          </td>
        </tr>`;
      });
      $("#facilityTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages);
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}


(function ($) {
    'use strict';

    // Get data when the page is loaded
    $(document).ready(function () {
      getFacilities();
    });

    $("#searchFacility").on("input", function (e) {
      updateUrlParameter("search", e.target.value);
      getFacilities();
    });

    $("#createBtn").on("click", function () {
      $("#modalRetretHouseLabel").text("Buat Fasilitas");
      $("#submitBtn").text("Buat");
      $("#modalRetretHouse").modal("show");
    });

    $(document).on("click", ".editBtn", function () {
      var retretHouseId = $(this).data("id");
      $.ajax({
        type: "GET",
        url: "/api/facility/get-single.php?id=" + retretHouseId,
        dataType: "json",
        success: function (response) {
          if (response.status === "error") {
            console.log(response.message);
          } else if (response.status == "success") {
            const data = response.data;
            $("#modalFacilityLable").text("Edit Facility");
            $("#id").val(data.facility_id);
            $("#name").val(data.name);
            $("#select-facility-type").val(data.facility_type);
            $("#submitBtn").text("Edit Facility");
          }
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
      $("#modalFacility").modal("show");
    });

    $("#handleSubmitFacility").on("submit", function (e) {
      e.preventDefault();
      const id = e.target.id.value;

      if (id) {
        $.ajax({
          type: "PUT",
          url: "/api/facility/update.php",
          dataType: "json",
          data: $(this).serialize() + "&action=updateRoom",
          success: function (response) {
            console.log(response)
            if (response.status == "error") {
              $("#alert-container")
                .text(response.message)
                .addClass(`alert alert-danger`)
                .removeClass(`alert-success`);
            } else if (response.status == "success") {
              $("#toast .toast-body").text(response.message);
              getFacilities();
              var toast = new bootstrap.Toast($("#toast")[0]);
              toast.show();
              $("#modalFacility").modal("hide");
            }
            $("#handleSubmitFacility")[0].reset();
          },
          error: function (xhr, status, error) {
            console.error("Terjadi kesalahan: " + error);
          },
        });
      } else {
        $.ajax({
          type: "POST",
          url: "/api/facility/create.php",
          data: $(this).serialize() + "&action=createFacility",
          dataType: "json",
          success: function (response) {
            console.log(response);
            if (response.status == "error") {
              $("#alert-container")
                .text(response.message)
                .addClass(`alert alert-danger`)
                .removeClass(`alert-success`);
            } else if (response.status == "success") {
              $("#toast .toast-body").text(response.message);
              getFacilities();
              var toast = new bootstrap.Toast($("#toast")[0]);
              toast.show();
              $("#modalFacility").modal("hide");
            }
            $("#handleSubmitFacility")[0].reset();
          },
          error: function (xhr, status, error) {
            console.error("Terjadi kesalahan: " + error);
          },
        });
      }
    });

    $(document).on("click", ".deleteBtn", function () {
      var facilityId = $(this).data("id");
      $("#confirmDelete").data("id", facilityId);
      $("#modalFacilityDelete").modal("show");
    });

    $("#confirmDelete").on("click", function () {
      var facilityId = $(this).data("id");

      $.ajax({
        type: "DELETE",
        url: "/api/facility/delete.php?id=" + facilityId,
        dataType: "json",
        success: function (response) {
          if (response.status === "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#toast .toast-body").text(response.message);
            getFacilities();

            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();

            $("#modalFacilityDelete").modal("hide");
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
          getFacilities();
        }
      }
    );

    $(".pagination").on("click", ".page-link[aria-label='Next']", function (e) {
      e.preventDefault();
      var currentPage = $(this).data("current_page");
      var totalPages = $(this).data("total_pages");

      if (currentPage < totalPages) {
        updateUrlParameter("page", currentPage + 1);
        getFacilities();
      }
    });

    $(".pagination").on(
      "click",
      ".page-item:not(:first-child):not(:last-child)",
      function (e) {
        e.preventDefault();
        var pageTo = $(this).data("page_to");
        updateUrlParameter("page", pageTo);
        getFacilities();
      }
    );
})(jQuery);