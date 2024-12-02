function scrollToHash(hash) {
  const checkExist = setInterval(() => {
    const targetElement = document.querySelector(hash); // Cari elemen berdasarkan hash
    if (targetElement) {
      targetElement.scrollIntoView({ behavior: "smooth" }); // Scroll ke elemen
      clearInterval(checkExist); // Hentikan interval setelah elemen ditemukan
    }
  }, 100); // Periksa setiap 100ms
}

function getRetretHouse(slug) {
  $.ajax({
    url: "/api/retret-house/get-single.php",
    type: "GET",
    dataType: "json",
    data: {
      slug,
    },
    success: function (response) {
      if (response.status === "success") {
        console.log(response);
        const data = response.data;
        const rooms = data.rooms;
        const reviews = data.reviews;

        // Tampilkan data retreat house
        $("#retret-house-name").text(data.name);
        $("#retret-house-address").text(data.address);

        if(data.images.length > 0) {
          data.images.forEach((image, i) => {
            $("#retret-house-carousel-inner").append(`
              <div class="carousel-item ${i === 0 ? 'active' : ''}">
                <img src="${image.url}" class="d-block w-100" alt="..." > 
              </div>
              `);
          });
        }

        if (rooms && rooms.length > 0) {
          // Ambil harga terendah
          const lowestPrice = rooms.reduce((min, room) => {
            return room.price_per_person < min ? room.price_per_person : min;
          }, rooms[0].price_per_person);

          const price_in_rupiah_lowest_price = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }).format(lowestPrice);
          $("#price-room").text(price_in_rupiah_lowest_price);
          rooms.forEach((room) => {
            const price_in_rupiah = new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 2,
              maximumFractionDigits: 2,
            }).format(room.price_per_person);
            $("#rooms").append(`
              <div id="${room.room_code}" class="card mb-3">
                  <div class="row g-0">
                      <div class="col-md-4">
                      <img src="${room.image_url}" class="card-img-top object-fit-cover" height="160" alt="img">
                      </div>
                      <div class="col-md-8">
                          <div class="card-body">
                              <h6 class="card-subtitle mb-2 text-body-secondary">Kode Kamar : ${room.room_code}</h6>
                              <p class="card-text">Kapasitas : ${room.capacity}</p>
                              <h6 class="card-subtitle mb-2 text-body-secondary">Harga Per Orang : ${price_in_rupiah}</h6>
                              <div class='d-flex justify-content-end'>
                                  <a href="/reservation/${room.room_code}" class="btn btn-primary">Pemesanan</a>
                              </div>

                          </div>
                      </div>
                  </div>
              </div>
            `);

            const hash = window.location.hash;
            if (hash) {
                scrollToHash(hash);
            }
            // window.location.href(location.hash)
          });
        } else {
          $("#rooms").html(`
            <div class='card-body'>
              <p class='card-text text-center'>Rumah Retreat Belum Memiliki Kamar!</p>
            </div>
          `)
        }

        reviews.forEach(function (review) {
          $("#reviews").append(`
            <div class="card mb-3">
              <div class="card-body">
                  <h6 class="card-subtitle text-body-secondary">${review.user}</h6>
                  <p class="card-text">Ulasan : ${review.rate}</p>
                  <p class="card-text">Pesan : ${review.message}</p>
              </div>
            </div>
          `);
        });
      } else if (response.status === "error") {
        console.error("Data tidak ditemukan: " + response.message);
        // Redirect ke halaman list.php
        window.location.href = "/retret-house/not-found.php";
      }
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  getRetretHouse($("#slug").val());
})(jQuery);
