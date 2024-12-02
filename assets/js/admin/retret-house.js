import { getUrlParameter, updateUrlParameter, generateButtonPagination } from "/assets/js/main.js";


function getRetretHouse() {
  const searchQuery = getUrlParameter("search") || ""; // Jika tidak ada, default ke string kosong
  const currentPage = parseInt(getUrlParameter("page")) || 1;

  $("#searchRetretHouse").val(searchQuery);
  $.ajax({
    url: "/api/retret-house",
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
        $("#roomsTable tbody").html(
          "<tr><td colspan='6' class='text-center border border-0 mt-3'>Data kosong</td></tr>"
        );
        return;
      } 
      response.data.forEach(function (retret_house, i) {
        rows += `
        <tr>
          <td>${response.limit * (response.currentPage - 1) + i + 1}</td>
          <td>
            <div class='image-preview' style='width: 120px;'>
            ${retret_house.images.length > 0 ? `
              <img 
                src="${
                  retret_house.images[0].url
                }" 
                alt="${retret_house.name}"
                width="100"
                height="80"
              />
              ` : `
              <p>Belum Ada Gambar</p>
              `}

            </div>
          </td>
          <td>${retret_house.name}</td>
          <td>${retret_house.address}</td>
          <td>${retret_house.phone}</td>
          <td>
            <button class='btn editBtn' data-id="${
              retret_house.retret_house_id
            }">
              <i class='fa fa-pencil' aria-hidden='true'></i>
            </button>
            <button type='button' class='btn deleteBtn' data-id="${
              retret_house.retret_house_id
            }">
              <i class='fa fa-trash'></i>
            </button>
          </td>
        </tr>`;
      });
      $("#retretHouseTable tbody").html(rows);
      generateButtonPagination(response.currentPage, response.totalPages)
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  "use strict";
  getRetretHouse();

  $("#searchRetretHouse").on("input", function (e) {
    updateUrlParameter("search", e.target.value);
    getRetretHouse()
  });

  $("#createBtn").on("click", function () {
    $("#modalRetretHouseLabel").text("Buat Retret House");
    $("#submitBtn").text("Buat");
    $("#modalRetretHouse").modal("show");
  });



  $(document).on("click", ".editBtn", function () {
    var retretHouseId = $(this).data("id");
    console.log(retretHouseId)
    $.ajax({
      type: "GET",
      url: "/api/retret-house/get-single.php?id=" + retretHouseId,
      dataType: "json",
      success: function (response) {
          console.log(response);
        if (response.status === "error") {
          console.log(response.message);
        } else if (response.status == "success") {
          const data = response.data;
          $("#modalRetretHouseLabel").text("Edit Retret House");
          if(data.images.length > 0) {
            data.images.forEach(function(image) {
            $("#image-previews").append(`
              <div data-image-id='${image.id}' class="col-4 image-preview position-relative" style="height: 120px;">
                <img src="${image.url}" alt="test">
                <button type="button" class="delete-img btn btn-danger position-absolute" data-image-id='${image.id}' >Hapus</button>
              </div> 
              `);
            })
          }
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
            <div data-image-id='${response.image_id}' class="col-4 image-preview position-relative" style="height: 120px;">
                <img src="${response.url}" alt="test">
                <button type="button" class="delete-img btn btn-danger position-absolute" data-image-id='${response.image_id}' >Hapus</button>
            </div> 
            `);

            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "image_ids[]"; 
            input.value = response.image_id;
            $("#handleSubmitRetretHouse").append(input); 
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert("Gagal mengupload gambar.");
      },
    });
  });

  $(document).on("click", '.delete-img', function () {
    var imageId = $(this).data("image-id");
    console.log('image_id', imageId)

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

          let imageIds = $("#image_ids").val()
            ? $("#image_ids").val().split(",")
            : [];

          const index = imageIds.indexOf(imageId.toString());

          if (index !== -1) {
            imageIds.splice(index, 1);
          }

          $("#image_ids").val(imageIds.join(","));
          
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  });

  $("#handleSubmitRetretHouse").on("submit", function (e) {
    e.preventDefault();
    const id = e.target.id.value;

    if (id) {
      $.ajax({
        type: "PUT",
        url: "/api/retret-house/update.php",
        dataType: "json",
        data: $(this).serialize() + "&action=updateRoom",
        success: function (response) {
          if (response.status == "error") {
            $("#alert-container")
              .text(response.message)
              .addClass(`alert alert-danger`)
              .removeClass(`alert-success`);
          } else if (response.status == "success") {
            console.log(response)
            $("#toast .toast-body").text(response.message);
            getRetretHouse();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalRetretHouse").modal("hide");
          }
          $("#handleSubmitRetretHouse")[0].reset();
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: "/api/retret-house/create.php",
        data: $(this).serialize() + "&action=createReteretHouse",
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
            getRetretHouse();
            var toast = new bootstrap.Toast($("#toast")[0]);
            toast.show();
            $("#modalRetretHouse").modal("hide");
          }
          $("#handleSubmitRetretHouse")[0].reset();
        },
        error: function (xhr, status, error) {
          console.error("Terjadi kesalahan: " + error);
        },
      });
    }

    $("#image-previews").empty();
    $('input[name="image_ids[]"]').remove();
  });

  $(document).on("click", ".deleteBtn", function () {
    var retretHouseId = $(this).data("id");
    $("#confirmDelete").data("id", retretHouseId);
    $("#modalRetretHouseDelete").modal("show");
  });

  $("#confirmDelete").on("click", function () {
    var retretHouseId = $(this).data("id");

    $.ajax({
      type: "DELETE",
      url: "/api/retret-house/delete.php?id=" + retretHouseId,
      dataType: "json",
      success: function (response) {
        if (response.status === "error") {
          $("#alert-container")
            .text(response.message)
            .addClass(`alert alert-danger`)
            .removeClass(`alert-success`);
        } else if (response.status == "success") {
          $("#toast .toast-body").text(response.message);
          getRetretHouse();

          var toast = new bootstrap.Toast($("#toast")[0]);
          toast.show();

          $("#modalRetretHouseDelete").modal("hide");
        }
      },
      error: function (xhr, status, error) {
        console.error("Terjadi kesalahan: " + error);
      },
    });
  });

  $("#modalRetretHouse").on("hide.bs.modal", function() {
    $("#image-previews").empty();
    $("#handleSubmitRetretHouse")[0].reset();
  });

  $('.pagination').on(
    "click",".page-link[aria-label='Previous']",
    function (e) {
      e.preventDefault();
      var currentPage = $(this).data("current_page");
      if (currentPage > 1) {
        updateUrlParameter('page', currentPage - 1);
        getRetretHouse();
      }
    }
  );

  $('.pagination').on(
    "click",".page-link[aria-label='Next']",
    function (e) {
      e.preventDefault();
      var currentPage = $(this).data("current_page");
      var totalPages = $(this).data("total_pages");

      if (currentPage < totalPages) {
        updateUrlParameter("page", currentPage + 1);
        getRetretHouse();
      }
    }
  );

  $(".pagination").on(
    "click",
    ".page-item:not(:first-child):not(:last-child)",
    function (e) {
      e.preventDefault();
      var pageTo = $(this).data("page_to")
      updateUrlParameter("page", pageTo);
      getRetretHouse();
    }
  );

})(jQuery);
