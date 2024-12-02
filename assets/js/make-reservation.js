let dataReservation = {
    room_id: '',
    start_date: '',
    end_date: '',
    number_of_participants: '',
    total_price: '',
}

function getDaysDifference(startDate, endDate) {
  const start = new Date(startDate);
  const end = new Date(endDate);

  // Hitung selisih waktu dalam milidetik
  const differenceInTime = end - start;

  // Konversi milidetik ke hari (1 hari = 24 * 60 * 60 * 1000 ms)
  const differenceInDays = differenceInTime / (1000 * 60 * 60 * 24);

  return differenceInDays;
}

function calculateTotalPrice(pricePerPeson, totalDays, numberOfParticipants) {
    console.log(pricePerPeson, totalDays, numberOfParticipants);
    if (!pricePerPeson || !totalDays || !numberOfParticipants) {
        return 0;
    }
    const totalPrice = pricePerPeson * totalDays * parseInt(numberOfParticipants);

    dataReservation.total_price = totalPrice;
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(totalPrice);
}

function getRoom(room_code) {
  console.log(room_code);
  $.ajax({
    url: "/api/room/get-single.php",
    type: "GET",
    dataType: "json",
    data: {
      room_code,
    },
    success: function (response) {
      const data = response.data;
      console.log(data);
      if (response.status === "success") {
        dataReservation.room_id = data.room_id;
        $("#room-image")
          .attr("src", data.image_url)
          .attr("alt", data.room_code);
        $("#room-code-content").text(`Kode Kamar : ${data.room_code}`);
        $("#retret-house-content").text(
          `Rumah Retreat : ${data.retret_house_name}`
        );
        const price_in_rupiah = new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        }).format(data.price_per_person);
        $("#price-per-person-content").text(
          `Harga Per Orang : ${price_in_rupiah}`
        );
        $("#capacity-content").text(`Kapasitas : ${data.capacity} Orang`);
        $("#start-date").attr('data-price-per-person', data.price_per_person)
        $("#end-date").attr('data-price-per-person', data.price_per_person)
        $('#number-of-participants').attr('data-capacity', data.capacity).attr('data-price-per-person', data.price_per_person)
      } else if (response.status === "error") {
      }
    },
    error: function (xhr, status, error) {
      console.error("Terjadi kesalahan: " + error);
    },
  });
}

(function ($) {
  getRoom($("#room_code").val());

  $("#start-date").on("input", function (e) {
    $("#start-date-detail").text(`Tanggal Mulai : ${e.target.value}`);
    const price_per_person = $(this).data("price-per-person");
    const number_of_participants = $(this).attr("data-number-of-participants");
    console.log(number_of_participants);

    dataReservation.start_date = $("#start-date").val();

    const differenceInDays = getDaysDifference(
        $("#start-date").val(),
        $("#end-date").val()
    );
    $("#number-day-detail").text(`Jumlah Hari : ${differenceInDays}`);
    $("#number-of-participants").attr(
      "data-difference-in-day",
      differenceInDays
    );

    const totalPrice = calculateTotalPrice(
        price_per_person,
        differenceInDays,
        number_of_participants
    );
    console.log(totalPrice);
    $("#price-total-detail").text(`Total Harga : ${totalPrice}`);

  });

  $("#end-date").on("input", function (e) {
    const price_per_person = $(this).data("price-per-person");
    const number_of_participants = $(this).attr("data-number-of-participants");
    console.log(number_of_participants)

    $("#end-date-detail").text(`Tanggal Berakhir : ${e.target.value}`);
    dataReservation.end_date = $("#end-date").val();

    const differenceInDays = getDaysDifference($("#start-date").val(), $("#end-date").val());
    $("#number-day-detail").text(`Jumlah Hari : ${differenceInDays}`);

    $('#number-of-participants').attr('data-difference-in-day', differenceInDays )

        const totalPrice = calculateTotalPrice(
          price_per_person,
          differenceInDays,
          number_of_participants
        );

    $("#price-total-detail").text(`Total Harga : ${totalPrice}`);

  });

  $("#number-of-participants").on("input", function (e) {
    const capacity = $(this).data('capacity');
    const price_per_person = $(this).data("price-per-person");
    const differenceInDays = $(this).attr("data-difference-in-day");

    if (e.target.value > capacity) {
        e.target.value = capacity;
    }else if (e.target.value < 1) {
        e.target.value = 1;
    }

    dataReservation.number_of_participants = parseInt(e.target.value)
    
    $("#number-of-partisipant-detail").text(
      `Jumlah Peserta : ${e.target.value}`
    );
    $("#start-date").attr("data-number-of-participants", e.target.value);
    $('#end-date').attr('data-number-of-participants', e.target.value)

    const totalPrice = calculateTotalPrice(
        price_per_person,
        differenceInDays,
        e.target.value
    );

    $("#price-total-detail").text(`Total Harga : ${totalPrice}`);

  });

  $('#make-reservation').on('click', () => {
    if (!dataReservation.start_date || !dataReservation.end_date ) {
        alert('Silahkan pilih tanggal mulai dan berakhir')
        return
    }
    if (!dataReservation.number_of_participants ) {
        alert('Silahkan pilih jumlah peserta')
        return
    }

    const formData = new FormData();
    
    for (const key in dataReservation) {
      formData.append(key, dataReservation[key]);
    }
    $.ajax({
        url: '/api/reservation/create.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: (response) => {
            if(response.status === 'success') {
                window.location.replace('/dashboard/reservation.php')
            }
        }
    })
  })
})(jQuery);
