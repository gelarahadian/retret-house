import { setAvatar } from "/assets/js/main.js";

function getUser() {
    $.ajax({
        url: '/api/auth/me.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            const data = response.data;
            sessionStorage.setItem("user", JSON.stringify(data));
            console.log(data.image)
            if(data.image) {
              $('#image-upload').attr('disabled', true);
              $('#btn-choose-image').addClass('disabled');
              $("#image_id").val(data.image.id);
              $("#data-image").attr('src', data.image.url).attr('alt', data.name);
              $("#image-preview").attr('src', data.image.url).attr('alt', data.name);
            }else {
              $('#btn-remove-image').addClass('disabled');
            }
            $('#data-name').text(data.name);
            $('#data-role').text(data.role === 'admin' ? 'Admin' : 'Pengunjung');
            $("#data-email").text(data.email);
            $('#data-phone').text(data.phone || '-');
            $('#data-address').text(data.address || '-');
            $('#data-gender').text(data.gender || '-');
            const options = { day: "2-digit", month: "long", year: "numeric" };
            const date = new Date(data.created_at);
            $("#join").text(
              `Bergabung pada ${date.toLocaleDateString('id-ID', options)}`
            );

            $("#name").val(data.name);
            $("#role").val(
              data.role === "admin" ? "Admin" : "Pengunjung"
            );
            $("#email").val(data.email);
            $("#phone").val(data.phone);
            $("#address").val(data.address);
            $("#gender").val(data.gender || '');

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    })
}

(function ($) {
    $("#edit-profile").on("click", function() {
        $('#card-profile').addClass('hide');
        $('#card-profile-form').removeClass('hide');
    });

    $("#cancel-edit").on("click", function() {
        $('#card-profile-form').addClass('hide');
        $('#card-profile').removeClass('hide');
    });

    getUser()

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
        $('#alert-container')
          .text("Ukuran file tidak boleh lebih dari 1 Mb")
          .addClass('alert alert-warning')
          .removeClass('alert-success')
        $('#image-upload').val('');
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
            $("#image-preview")
              .attr("src", response.url)
              .attr("alt", "image-preview");
            $("#btn-remove-image").removeClass("disabled");
            $("#image-upload").attr("disabled", true);
            $("#btn-choose-image").addClass("disabled");
          } else {
            alert(response.message);
          }
        },
        error: function () {
          alert("Gagal mengupload gambar.");
        },
      });
    });

    $("#btn-remove-image").on("click", function() {

      $("#alert-container")
        .text("")
        .removeClass("alert alert-warning")

      $.ajax({
        url: "/api/image/delete.php",
        type: "DELETE",
        data: { id: $("#image_id").val() },
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response.status === "success") {
            $("#image_id").val("");
            $('#image-upload').attr('disabled', false);
            $('#btn-choose-image').removeClass('disabled');
            $('#btn-remove-image').addClass('disabled');
            $("#data-image")
              .attr("src", "/assets/images/icon/user.svg")
              .attr("alt", "icon-user");
            $("#image-preview")
              .attr("src", "/assets/images/icon/user.svg")
              .attr("alt", "icon-user");
          } else {
            alert(response.message);
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    });


    $("#handleSubmitEdit").on("submit", function (e) {
      e.preventDefault();

      $.ajax({
        url: "/api/auth/update-profile.php",
        type: "PUT",
        data: $(this).serialize() + "&action=update-profile",
        dataType: "json",
        success: function (response) {
          console.log(response)
          console.log()
            if (response.status === 'success') {
              $("#toast .toast-body").text(response.message);
              var toast = new bootstrap.Toast($("#toast")[0]);
              toast.show();
              $("#card-profile-form").addClass("hide");
              $("#card-profile").removeClass("hide");
              getUser();
              setAvatar();
            } else {
              console.error("Failed to update profile");
            }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    });
})(jQuery)