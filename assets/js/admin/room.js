import { getUrlParameter, updateUrlParameter, generateButtonPagination } from "/assets/js/main.js";

function getRooms() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchRooms").val(searchQuery);

  $.ajax({
    url: "/api/room",
    type: "GET",
    dataType: "json",
    data: {
      page: currentPage || 1, // halaman yang diinginkan
      search: searchQuery || "", // kata kunci pencarian
    },
    success: function (response) {
      console.log(response);
      var rows = "";
      if(response.data.length < 1) {
        $("#roomsTable tbody").html(
          "<tr><td colspan='6' class='text-center border border-0 mt-3'>Data kosong</td></tr>"
        );
        return;
      } 
      response.data.forEach(function (room, i) {
        rows += `
        <tr>
          <td>${i + 1}</td>
          <td>
            <div class='image-preview' style='width: 120px;'>
              ${
                room.image_url
                  ? `
              <img src='${room.image_url}' 
              alt='${room.room_code}' 
              width='100' 
              height='80'/>
              `
              : `
              <p>Belum Ada Gambar</p>
              `
                          }
            </div>
          </td>
          <td>${room.room_code}</td>
          <td>${room.capacity}</td>
          <td>${room.price_per_person}</td>
          <td>${room.retret_house_name}</td>
          <td>
            <ul class="list-group">
              ${room.facility_names
                .map(function (facility_name) {
                  return `<li class="list-group-item">${facility_name}</li>`;
                })
                .join("")}
            </ul>
          </td>
          <td>
            <button class='btn editBtn' data-id="${room.room_id}">
              <i class='fa fa-pencil' aria-hidden='true'></i>
            </button>
            <button type='button' class='btn deleteBtn' data-id="${
              room.room_id
            }">
              <i class='fa fa-trash'></i>
            </button>
          </td>
        </tr>`;
      });
      $("#roomsTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages);
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
    "use strict";

  getRooms();

  $("#facility-select").select2({
    theme: "bootstrap-5",
    width: "100%",
    placeholder: $(this).data("placeholder"),
    closeOnSelect: false,
  });
    $("#retret-house-select").select2({
      theme: "bootstrap-5",
      width: "100%",
      placeholder: $(this).data("placeholder"),
      closeOnSelect: true,
    });

  $("#searchRooms").on("input", function (e) {
    updateUrlParameter("search", e.target.value);
    getRooms();
  });

  $("#createBtn").on("click", function () {
    $("#modalRoomLable").text("Buat Kamar");
    $("#submitBtn").text("Buat");
    $("#modalRoom").modal("show");
    $.ajax({
        type: "GET",
        url: "/api/retret-house",
        dataType: 'json',
        data: {
            all: true
        },
        success: function (response) {
            console.log(response)
            $("#retret-house-select").empty();
            $("#retret-house-select").append(
              `<option selected value=''>Pilih Retret House</option>`
            );
            response.data.forEach(function (retret_house) {
                $("#retret-house-select").append(
                  `<option value="${retret_house.retret_house_id}">${retret_house.name}</option>`
                );
            });
        },
    })
    $.ajax({
      type: "GET",
      url: "/api/facility",
      dataType: "json",
      data: {
        all: true,
      },
      success: function (response) {
        console.log(response);
        $("#facility-select").empty();
        response.data.forEach(function (facility) {
          $("#facility-select").append(
            `<option value="${facility.facility_id}">${facility.name}</option>`
          );
        });
      },
    });
  });

  $(document).on("click", ".editBtn", function () {
    var roomId = $(this).data("id");
    console.log(roomId);
    $.ajax({
      type: "GET",
      url: "/api/room/get-single.php?id=" + roomId,
      dataType: "json",
      success: function (response) {
        console.log(response);
        if (response.status === "error") {
          console.log(response.message);
        } else if (response.status == "success") {
          const data = response.data;
          $.ajax({
            type: "GET",
            url: "/api/retret-house",
            dataType: "json",
            data: {
              all: true,
            },
            success: function (response) {
              console.log(response);
              $("#retret-house-select").empty();
              $("#retret-house-select").append(
                `<option selected value=''>Pilih Retret House</option>`
              );
              response.data.forEach(function (retret_house) {
                $("#retret-house-select").append(
                  `<option value="${retret_house.retret_house_id}">${retret_house.name}</option>`
                );
              });

              $("#retret-house-select").val(data.retret_house_id);

            },
          });
          $.ajax({
            type: "GET",
            url: "/api/facility",
            dataType: "json",
            data: {
              all: true,
            },
            success: function (response) {
              console.log(response);
              $("#facility-select").empty();
              response.data.forEach(function (facility) {
                $("#facility-select").append(
                  `<option value="${facility.facility_id}">${facility.name}</option>`
                );
              });

              $("#facility-select").val(data.facility_ids);

            },
          });

          $("#modalRoomLable").text("Ubah Data Kamar");
          $("#id").val(data.room_id);
          if(data.image_url) {
            $("#image-previews").append(`
              <div data-image-id='${data.image_id}' class="col-12 image-preview position-relative" style="height: 120px;">
                <img src="${data.image_url}" alt="test">
                <button type="button" class="delete-img btn btn-danger position-absolute" data-image-id='${data.image_id}' >Hapus</button>
              </div> 
              `);
          }
          $("#capacity").val(data.capacity);
          $("#price-per-person").val(data.price_per_person);
          $("#phone").val(data.phone);
          $("#submitBtn").text("Ubah Kamar");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
    $("#modalRoom").modal("show");
  });

  $("#image-upload").on("input", function (e) {
    e.preventDefault();

    $("#alert-container").text("").removeClass("alert alert-warning");

    let formData = new FormData();
    let fileInput = $("#image-upload")[0].files[0];
    const maxSize = 1 * 1024 * 1024;

    if (!fileInput) {
      alert("Pilih file sebelum mengupload!");
      return;
    }

    if (fileInput && fileInput.size > maxSize) {
      $("#alert-container")
        .text("Ukuran file tidak boleh lebih dari 1 Mb")
        .addClass("alert alert-warning")
        .removeClass("alert-success");
      $("#image-upload").val("");
      return;
    }

    formData.append("image", fileInput);

    $.ajax({
      url: "/api/image/upload.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        console.log(response);
        $("#image_id").val(response.image_id);
        if (response.status === "success") {
          $("#image-previews").append(`
            <div data-image-id='${response.image_id}' class="col-12 image-preview position-relative" style="height: 120px;">
                <img src="${response.url}" alt="test">
                <button type="button" class="delete-img btn btn-danger position-absolute" data-image-id='${response.image_id}' >Hapus</button>
            </div> 
            `);

          let input = document.createElement("input");
          input.type = "hidden";
          input.name = "image_id";
          input.value = response.image_id;
          $("#handleSubmitRoom").append(input);
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert("Gagal mengupload gambar.");
      },
    });
  });

  $(document).on("click", ".delete-img", function () {
    var imageId = $(this).data("image-id");
    console.log("image_id", imageId);

    $("#alert-container").text("").removeClass("alert alert-warning");

    $.ajax({
      url: "/api/image/delete.php",
      type: "DELETE",
      data: { id: imageId },
      dataType: "json",
      success: function (response) {
        console.log(response);
        if (response.status === "success") {
          $(`.image-preview[data-image-id='${imageId}']`).remove();

          $('input[name="image_id"]').remove();

        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  });

  $("#handleSubmitRoom").on("submit", function (e) {
    e.preventDefault();
    const id = e.target.id.value;
    console.log($('#facility-select').val());

    if (id) {
      $.ajax({
        type: "PUT",
        url: "/api/room/update.php",
        dataType: "json",
        data: $(this).serialize() + "&action=updateRetretHouse",
        success: function (response) {
          console.log(response);
          if (response.status == "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#toast .toast-body").text(response.message);
            getRooms();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalRoom").modal("hide");
          }
          $("#handleSubmitRoom")[0].reset();
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: "/api/room/create.php",
        data: $(this).serialize() + "&action=createRoom",
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response.status == "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            $("#alert-container").removeClass(`alert alert-danger`);
            $("#toast .toast-body").text(response.message);
            getRooms();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalRoom").modal("hide");
            $("#handleSubmitRoom")[0].reset();
          }
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    }
  });

  $(document).on("click", ".deleteBtn", function () {
    var retretHouseId = $(this).data("id");
    $("#confirmDelete").data("id", retretHouseId);
    $("#modalRoomDelete").modal("show");
  });

  $("#confirmDelete").on("click", function () {
    var roomId = $(this).data("id");

    $.ajax({
      type: "DELETE",
      url: "/api/room/delete.php?id=" + roomId,
      dataType: "json",
      success: function (response) {
        if (response.status === "error") {
          $("#alert-container")
            .text(response.message)
            .addClass(`alert alert-danger`)
            .removeClass(`alert-success`);
        } else if (response.status == "success") {
          $("#toast .toast-body").text(response.message);
          getRooms();

          var toast = new bootstrap.Toast($("#toast")[0]);
          toast.show();

          $("#modalRoomDelete").modal("hide");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
  });

    $("#modalRoom").on("hide.bs.modal", function () {
      $("#image-previews").empty();
      $("#handleSubmitRoom")[0].reset();
    });

  $(".pagination").on(
    "click",
    ".page-link[aria-label='Previous']",
    function (e) {
      e.preventDefault();
      var currentPage = $(this).data("current_page");
      if (currentPage > 1) {
        updateUrlParameter("page", currentPage - 1);
        getRooms();
      }
    }
  );

  $(".pagination").on("click", ".page-link[aria-label='Next']", function (e) {
    e.preventDefault();
    var currentPage = $(this).data("current_page");
    var totalPages = $(this).data("total_pages");

    if (currentPage < totalPages) {
      updateUrlParameter("page", currentPage + 1);
      getRooms();
    }
  });

  $(".pagination").on(
    "click",
    ".page-item:not(:first-child):not(:last-child)",
    function (e) {
      e.preventDefault();
      var pageTo = $(this).data("page_to");
      updateUrlParameter("page", pageTo);
      getRooms();
    }
  );
})(jQuery);
