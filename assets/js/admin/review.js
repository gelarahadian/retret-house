import {
  getUrlParameter,
  updateUrlParameter,
  generateButtonPagination,
} from "/assets/js/main.js";

function getReviews() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchUser").val(searchQuery);
  $.ajax({
    url: "/api/review",
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
      var rows = ''
      response.data.forEach(function (review, i) {
        rows += `
            <tr>
                <td>${response.limit * (response.currentPage - 1) + i + 1}</td>
                <td>${review.user_name}</td>
                <td>${review.retret_house_name}</td>
                <td>${review.rate}</td>
                <td>${review.message}</td>
                <td>
                    <button type='button' class='btn deleteBtn' data-id="${
                    review.review_id
                    }">
                        <i class='fa fa-trash'></i>
                    </button>
                </td>
            </tr>
        `;
      })

      $("#reviewTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages);
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  "use strict";
  getReviews();

    $("#searchReview").on("input", function (e) {
        updateUrlParameter("search", e.target.value);
        getReviews();
    });

    $(document).on("click", ".deleteBtn", function () {
        console.log("delete");
        var reservation_id = $(this).data("id");
        $("#confirmDelete").data("id", reservation_id);
        $("#modalReviewDelete").modal("show");
    });

    $("#confirmDelete").on("click", function () {
      var roomId = $(this).data("id");

      $.ajax({
        type: "DELETE",
        url: "/api/review/delete.php?id=" + roomId,
        dataType: "json",
        success: function (response) {
          if (response.status === "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#toast .toast-body").text(response.message);
            getReviews();

            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();

            $("#modalReviewDelete").modal("hide");
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
