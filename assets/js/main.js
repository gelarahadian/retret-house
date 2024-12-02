export function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

export function updateUrlParameter(key, value) {
  const url = new URL(window.location.href);
  url.searchParams.set(key, value);
  window.history.pushState({}, "", url); // Update URL tanpa reload halaman
}

$( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );

export function generateButtonPagination(currentPage, totalPages) {
  var page_items = "";
  const page_prev = `
    <li class="page-item">
      <a class="page-link" href="#" data-current_page="${currentPage}" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
  `;
  page_items += page_prev;

  if (currentPage - 1 >= 1) {
    page_items += `
      <li class="page-item" data-page_to="${currentPage - 1}">
        <a class="page-link" href="#" >${currentPage - 1}</a>
      </li>
    `;
  }

  const current_page = `
  <li class="page-item" data-page_to="${currentPage}">
    <a class="page-link active" href="#" >${currentPage}</a>
  </li>
  `;
  page_items += current_page;

  if (currentPage + 1 <= totalPages) {
    page_items += `
    <li class="page-item" data-page_to="${currentPage + 1}">
      <a class="page-link" href="#" >${currentPage + 1}</a>
    </li>
  `;
  }

  const page_next = `
    <li class="page-item">
      <a class="page-link" href="#" data-current_page="${currentPage}" data-total_pages="${totalPages}" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  `;
  page_items += page_next;

  $(".pagination").html(page_items);
}

function getUser() {
  $.ajax({
    url: "/api/auth/me.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      const data = response.data;

      sessionStorage.setItem("user", JSON.stringify(data));
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
    },
  });
}

export function setAvatar () {
  const user = JSON.parse(sessionStorage.getItem('user'));
  if (user) {
    $('#avatar-name').text(`${user.name} (${user.role === 'admin' ? 'Admin' : 'Pengunjung'})`)
    if(user.image){
      $("#avatar-img").attr("src", user.image.url,).attr("alt", user.name);
    }else{
      $("#avatar-img").attr("src", "/assets/images/icon/user.svg").attr("alt", user.name);
    }
  }
}

(function ($) {
  ("use strict");

  var fullHeight = function () {
    $(".js-fullheight").css("height", $(window).height());
    $(window).resize(function () {
      $(".js-fullheight").css("height", $(window).height());
    });
  };
  fullHeight();
  getUser();
  setAvatar();

  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("active");
  });

  $(".navbar-nav li").on("click", function () {
    // Hapus class active dari semua item menu
    $(".navbar-nav li").removeClass("active");

    // Tambahkan class active ke item yang diklik
    $(this).addClass("active");
  });

  // Logout
  $("#logoutButton").on("click", function () {
    $.ajax({
      type: "POST",
      url: "/api/auth/sign-out.php",
      success: function (response) {
        const data = JSON.parse(response) || {};
        if (data.status === "success") {
          sessionStorage.removeItem("user"); 
          window.location.href = "/sign-in.php";
        }
      },
    });
  });
})(jQuery);
