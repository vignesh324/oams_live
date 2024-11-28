let basePath = window.location.origin;
// Event listener for the state select element
$(document).on("change", "#state_id", function (event) {
  var stateId = $(this).val(); // Get the selected state ID
  // console.log('sid:'+ stateId);
  // alert('hiii');
  if (stateId == "") {
    $("#city_id").empty();
    $("#area_id").empty();
    $("#area_id").append('<option value="">Select Area</option>');
    $("#city_id").append('<option value="">Select City</option>');
  } else {
    $.ajax({
      url: basePath + "/USER/City/StateCity",
      type: "POST",
      data: {
        state_id: stateId,
      },
      dataType: "json",
      success: function (response) {
        $("#city_id").empty();
        $("#area_id").empty();
        $("#area_id").append('<option value="">Select Area</option>');

        if (response.status == 200) {
          $("#city_id").append('<option value="">Select City</option>');
          $.each(response.data.city, function (key, city) {
            $("#city_id").append(
              '<option value="' + city.id + '">' + city.name + "</option>"
            );
          });
        } else if (response.status == 404) {
          $("#city_id").append('<option value="">No data found</option>');
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
      },
    });
  }
});

// Event listener for the city select element
$(document).on("change", "#city_id", function (event) {
  var cityId = $(this).val();
  // console.log(cityId);

  if (cityId == "") {
    // alert('hii');
    $("#city_id").empty();
    $("#area_id").empty();
    $("#area_id").append('<option value="">Select Area</option>');
    $("#city_id").append('<option value="">Select City</option>');
  } else {
    $.ajax({
      url: basePath + "/USER/Area/CityArea",
      type: "POST",
      data: {
        city_id: cityId,
      },
      dataType: "json",
      success: function (response) {
        $("#area_id").empty();

        if (response.status == 200) {
          $("#area_id").append('<option value="">Select Area</option>');
          $.each(response.data.area, function (key, area) {
            $("#area_id").append(
              '<option value="' + area.id + '">' + area.name + "</option>"
            );
          });
        } else if (response.status == 404) {
          $("#area_id").append('<option value="">No data found</option>');
        }
      },
      error: function (xhr, status, error) {
        console.error(error); // Log any errors to the console
      },
    });
  }
});

$(document).on("click", "#submit-buyer", function (event) {
  event.preventDefault();
  $("#submit-buyer").attr("disabled", true);

  var url = $("#user-form").attr("action");
  var formmethod = "post";
  var formdata = $("form").serialize();
  console.log(formdata);
  $.ajax({
    url: url,
    type: formmethod,
    data: formdata,
    success: function (_response) {
      Swal.fire({
        icon: "success",
        title: "Success!",
        text: "Form submitted successfully",
      }).then((result) => {
        if (result.isConfirmed || result.isDismissed) {
          window.location.reload(); // Reload the page on success
        }
      });
    },
    error: function (_response) {
      var data = $.parseJSON(_response.responseText);

      $(".error").remove();
      if (_response.status === 422) {
        var errors = $.parseJSON(_response.responseText);
        error = errors.errors;
        $.each(data.errors, function (key, value) {
          if ($("input[name=" + key + "]").length != 0)
            $("input[name=" + key + "]").after(
              '<span class="error ">' + value + "</span>"
            );
          else if ($("select[name=" + key + "]").length != 0)
            $("select[name=" + key + "]").after(
              '<span class="error">' + value + "</span>"
            );
          else $("#" + key).after('<span class="error">' + value + "</span>");
        });
      } else if (_response.status === 500) {
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Internal Server Error",
        });
      }
    },
    complete: function () {
      // Re-enable the submit button after the request is complete
      $("#submit-buyer").attr("disabled", false);
    },
  });
});
function ShowBidPrice() {
  $("#auction_bid_price_1").text("hh");
}
