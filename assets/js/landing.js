function getRetretHouses() {

  $.ajax({
    url: "/api/retret-house",
    type: "GET",
    dataType: "json",
    data: {
        limit : 4
    },
    success: function (response) {
        const data = response.data;
        console.log(data);

        data.forEach(retret_house => {
            $("#list-retret-houses").append(`
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-3 ">
                    <div class="card">
                        <img src="${retret_house.images.length > 0 ? retret_house.images[0].url : ''}" class="card-img-top object-fit-cover" height="160" alt="img">
                        <div class="card-body">
                            <h5 class="card-title">${retret_house.name}</h5>
                            <span class="card-text">Alamat :${retret_house.address}</span>
                            <p class="card-text">Telepon :${retret_house.phone}</p>
                            <a href="/retret-house/${retret_house.slug}" class="btn btn-primary">Lihat Detil</a>
                        </div>
                    </div>
                </div>
            `);
        });


    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

function getRooms() {
  $.ajax({
    url: "/api/room",
    type: "GET",
    dataType: "json",
    data: {
        limit : 8
    },
    success: function (response) {
        const data = response.data;
        console.log(data);

        data.forEach(room => {
            $("#list-rooms").append(`
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-3 ">
                    <div class="card">
                        <img src="${room.image_url}" class="card-img-top object-fit-cover" height="160" alt="img">
                        <div class="card-body">
                            <h5 class="card-title">${room.room_code}</h5>
                            <p class="card-text">Rumah Retret : ${room.retret_house_name}</p>
                            <a href="/retret-house/${room.retret_house_slug}#${room.room_code}" class="btn btn-primary">Lihat Detil</a>
                        </div>
                    </div>
                </div>
            `);
        })
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  getRetretHouses()
  getRooms();
})(jQuery);
