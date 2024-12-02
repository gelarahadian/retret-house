$(function () {
  // Register
  $("#registerForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "/api/auth/sign-up.php",
      data: $(this).serialize() + "&action=register",
      success: function (response) {
        console.log(response);
        const data = JSON.parse(response) || {};
        $("#responseMessage").text(data.message).addClass(`alert `);

        if (data.status === "success") {
          $("#responseMessage")
            .addClass(`alert-success`)
            .removeClass(`alert-danger`);

          // Redirect to the dashboard
          setTimeout(function () {
            window.location.href = "sign-in.php";
          }, 1000);
        } else {
          $("#responseMessage")
            .addClass(`alert-danger`)
            .removeClass(`alert-success`);
        }
      },
    });
  });

  // Login
  $("#loginForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "/api/auth/sign-in.php",
      data: $(this).serialize() + "&action=logout",
      success: function (response) {
        const data = JSON.parse(response) || {};
        $("#responseMessage").text(data.message).addClass(`alert `);

        if (data.status === "success") {
          $("#responseMessage")
            .addClass(`alert-success`)
            .removeClass(`alert-danger`);
            
            sessionStorage.setItem('user', JSON.stringify(data.data));

          // redirect to the dashboard page
          setTimeout(function () {
            window.location.href = "dashboard";
          }, 1000);
        } else {
          $("#responseMessage")
            .addClass(`alert-danger`)
            .removeClass(`alert-success`);
        }
      },
      error: function (xhr, status, error) {
        console.log("Error: " + error);
      },
    });
  });
});
