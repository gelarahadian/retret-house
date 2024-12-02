import {
  getUrlParameter,
  updateUrlParameter,
  generateButtonPagination,
} from "/assets/js/main.js";

function getUsers() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchUser").val(searchQuery);
  $.ajax({
    url: "/api/user",
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
        $("#userTable tbody").html(
          "<tr><td colspan='6' class='text-center border border-0 mt-3'>Data kosong</td></tr>"
        );
        return;
      }
      response.data.forEach(function (user, i) {
        rows += `
        <tr>
          <td>${i + 1}</td>
          <td>${user.name}</td>
          <td>${user.email}</td>
          <td>${user.phone}</td>
          <td>${user.address}</td>
          <td>${user.gender}</td>
          <td>${user.role}</td>
          <td>
            <button class='btn editBtn' data-id="${user.user_id}">
              <i class='fa fa-pencil' aria-hidden='true'></i>
            </button>
            <button type='button' class='btn deleteBtn' data-id="${
              user.user_id
            }">
              <i class='fa fa-trash'></i>
            </button>
          </td>
        </tr>`;
      });
      $("#userTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages);
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  "use strict";
  getUsers();

  $("#searchUser").on("input", function (e) {
    updateUrlParameter("search", e.target.value);
    getUsers();
  });

  $("#createBtn").on("click", function () {
    $("#modalUserLable").text("Tambahkan Pengguna");
    $("#submitBtn").text("Tambahkan");
    $("#modalUser").modal("show");
  });

  $(document).on("click", ".editBtn", function () {
    var userId = $(this).data("id");
    $.ajax({
      type: "GET",
      url: "/api/user/get-single.php?id=" + userId,
      dataType: "json",
      success: function (response) {
        if (response.status === "error") {
          console.log(response.message);
        } else if (response.status == "success") {
          const data = response.data;
          $("#modalUserLable").text("Ubah Data Pengguna");
          $("#id").val(data.user_id);
          $("#name").val(data.name);
          $("#email").val(data.email);
          $("#address").val(data.address);
          $("#phone").val(data.phone);
          $('#gender').val(data.gender);
          $("#select-role").val(data.role);
          $("#submitBtn").text("Edit");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
    $("#modalUser").modal("show");
  });

  $("#handleSubmitUser").on("submit", function (e) {
    e.preventDefault();
    const id = e.target.id.value;

    if (id) {
      $.ajax({
        type: "PUT",
        url: "/api/user/update.php",
        dataType: "json",
        data: $(this).serialize() + "&action=updateRoom",
        success: function (response) {
          if (response.status == "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#toast .toast-body").text(response.message);
            getUsers();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalUser").modal("hide");
          }
          $("#handleSubmitUser")[0].reset();
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    }else {
      $.ajax({
        type: "POST",
        url: "/api/user/create.php",
        data: $(this).serialize() + "&action=createUser",
        success: function (response) {
          const data = JSON.parse(response) || {};
          console.log(data);
          if (data.status == "error") {
            $("#alert-container")
              .text(data.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (data.status == "success") {
            $("#toast .toast-body").text(data.message);
            getUsers();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalUser").modal("hide");
          }
          $("#handleSubmitUser")[0].reset();
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    }
  });

    $(document).on("click", ".deleteBtn", function () {
        var userId = $(this).data("id");
        $("#confirmDelete").data("id", userId);
        $("#modalUserDelete").modal("show");
    });

    $("#confirmDelete").on("click", function () {
      var userId = $(this).data("id");

      $.ajax({
        type: "DELETE",
        url: "/api/user/delete.php?id=" + userId,
        dataType: "json",
        success: function (response) {
          if (response.status === "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#toast .toast-body").text(response.message);
            getUsers();

            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();

            $("#modalUserDelete").modal("hide");
          }
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    });

  $("#modalUser").on("hide.bs.modal", function () {
    // $("#image-previews").empty();
    $("#handleSubmitUser")[0].reset();
    $('#id').val('');
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
