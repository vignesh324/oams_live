let startTimeInterval; // to be used by startTimer();
let isAutoBiddingActive = false; // flag to prevent restarting auto-bidding timer

document.addEventListener("DOMContentLoaded", () => {
  startAuctionTimers();
});

function startAuctionTimers() {
  let mainTimer = document.getElementById("mainTimer");
  let session1Timer = document.getElementById("session1Timer");
  let session2Timer = document.getElementById("session2Timer");
  let auction_id = document.getElementById("auction_id").value;
  let lastFunctionCallTime = 0;
  
  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
  });

  // Start the bid setup timer
  startTimer("min_bid_timer.txt", 60, mainTimer, () => {
    completeManual(auction_id);
    Toast.fire({
      icon: "error",
      title: "Bid setup time over",
      timer: 3000,
      timerProgressBar: true,
    });
    mainTimer.classList.add("hidden");
    $(".current_bid_section").text("Manual Bidding Running");
    localStorage.setItem('manual_bid_over', 1);

    // Start the manual bidding timer (Session 1)
    startTimer("session1_timer.txt", 90, session1Timer, () => {
      if (!isAutoBiddingActive) { // Ensure this block doesn't run multiple times
        autoBidLog(auction_id);
        isAutoBiddingActive = true; // Set flag to prevent resetting auto-bidding
        Toast.fire({
          icon: "error",
          title: "Manual bidding time over",
          timer: 5000,
          timerProgressBar: true,
        });
        session1Timer.classList.add("hidden");
        $(".current_bid_section").text("Auto Bidding Running");

        // Start auto-bid session (Session 2)
        startTimer("session2_timer.txt", 60, session2Timer, async () => {
          session2Timer.classList.add("hidden");

          $.when(clearAllTimersAndFiles())
            .then(function() {
              return recallTimers();
            })
            .then(function() {
              return closeEachSession();
            });
        });
      }
    });
  });
}

// Function to recall timers (resets the interface and restarts)
function recallTimers() {
  mainTimer.classList.remove("hidden");
  session1Timer.classList.remove("hidden");
  session2Timer.classList.remove("hidden");

  // Restart the timers
  startAuctionTimers();
}

function saveBid(id, minBid, maxBid) {
  fetch("save_bid.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id, minBid, maxBid }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Bid saved successfully");
      } else {
        alert("Error saving bid");
      }
    });
}

function autoBidLog(id) {
  var iframe = document.getElementById("biddingIframe");
  var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  var input = iframeDoc.querySelector("input.auction_lot_set");
  var auction_lot_set = input.value;

  $.ajax({
    url: BASE_URL + "/BUYER/AutoBidLog",
    type: "POST",
    dataType: "JSON",
    data: {
      id: id,
      lot_set: auction_lot_set,
    },
    success: function (response) {
      console.log(response);
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function completeManual(id) {
  $.ajax({
    url: BASE_URL + "/BUYER/completeManual",
    type: "POST",
    dataType: "JSON",
    data: {
      id: id,
    },
    success: function (response) {
      $("#min_hour_over").val("");
      $("#min_hour_over").val(1);

      var iframe = document.getElementById("biddingIframe");
      setTimeout(function () {
        iframe.src = iframe.src;
      }, 1000);
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function startTimer(file, duration, display, callback, isManualSave = false) {
  const fetchStartTime = new Date().getTime();
  fetch(`${BASE_URL}timer/get_server_time.php`)
    .then((response) => response.json())
    .then((serverTimeData) => {
      const serverTime = new Date(serverTimeData.serverTime).getTime();
      const fetchEndTime = new Date().getTime();
      const fetchDelay = (fetchEndTime - fetchStartTime) / 2;
      return fetch(
        `${BASE_URL}timer/timer.php?file=${file}&isManualSave=${isManualSave}`
      )
        .then((response) => response.json())
        .then((data) => {
          if (file === "session1_timer.txt") {
            clearInterval(startTimeInterval);
          }
          let startTime = new Date(data.startTime).getTime();

          let endTime = startTime + duration * 1000;
          const offset = serverTime - new Date().getTime() + fetchDelay;
          startTimeInterval = setInterval(() => {
            let now = new Date().getTime() + offset;
            let remaining = Math.max((endTime - now) / 1000, 0);
            let minutes = Math.floor(remaining / 60);
            let seconds = Math.floor(remaining % 60);
            display.textContent = `${minutes < 10 ? "0" : ""}${minutes}:${
              seconds < 10 ? "0" : ""
            }${seconds}`;
            if (remaining <= 0) {
              clearInterval(startTimeInterval);
              callback();
            }
          }, 1000);
        });
    });
}

function clearAllTimersAndFiles() {
  console.log("Clearing timers and files...");
  document.getElementById("mainTimer").textContent = "";
  document.getElementById("session1Timer").textContent = "";
  document.getElementById("session2Timer").textContent = "";

  var min_hour_over = $("#min_hour_over").val();
  console.log(min_hour_over);

  if (min_hour_over == 1) {
    var _url = `${BASE_URL}timer/clear_timers.php?flag=1`;
  } else {
    var _url = `${BASE_URL}timer/clear_timers.php`;
  }
  fetch(_url)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log("Timers cleared successfully");
      } else {
        console.error("Error clearing timer files: ", data.errors);
        alert("Error clearing timer files: " + data.errors.join(", "));
      }
    })
    .catch((error) => {
      console.error("Fetch error: ", error);
      alert("Fetch error: " + error.message);
    });
}

const dateTimeElement = document.getElementById("datetime");
const timerText = document.getElementById("timer-text");
const circle = document.querySelector(".circle");

let batchIndex = 0;
let products = [];
let activityTimer = 40;
let activityInterval;

function resetActivityTimer(timersec) {
  
  startActivityTimer();
}

function resetSession1Timer() {
  let session1Timer = document.getElementById("session1Timer");
  let session2Timer = document.getElementById("session2Timer"); // Ensure session2Timer is defined
  session1Timer.textContent = ""; // Clear the timer display

  // Initialize Toast notifications
  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
  });

  // Clear any existing intervals (if necessary)
  if (startTimeInterval) {
    clearInterval(startTimeInterval);
  }

  // Restart the manual bidding timer (Session 1)
  startTimer(
    "session1_timer.txt",
    90, // Duration for session 1
    session1Timer,
    () => {
      // Callback when session 1 timer is over
      Toast.fire({
        icon: "error",
        title: "Manual bidding time over",
        timer: 5000,
        timerProgressBar: true,
      });
      session1Timer.classList.add("hidden");
      $(".current_bid_section").text("Auto Bidding Running");

      // Start auto bidding session (Session 2)
      startTimer("session2_timer.txt", 30, session2Timer, async () => {
        session2Timer.classList.add("hidden");
        
        // Chaining promises to ensure timers and files are cleared and session closes correctly
        $.when(clearAllTimersAndFiles())
          .then(function() {
            return recallTimers();
          })
          .then(function() {
            return closeEachSession();
          });
      });
    },
    true // Pass true if this indicates manual save logic
  );
}

function startActivityTimer(timersec) {
  clearInterval(activityInterval);
  activityInterval = setInterval(() => {
    activityTimer--;
    if (activityTimer < 0) {
      clearInterval(activityInterval);
      //completeLiveProducts();
      batchIndex++;
      if (batchIndex < Math.ceil(products.length / 5)) {
        updateProductStatuses();
        resetActivityTimer();
      } else {
        timerText.textContent = "0";
      }
    }
  }, 1000);
}

function closeEachSession(params) {
  var iframe = document.getElementById("biddingIframe");
  var userss_type = document.getElementById("sessuser_type").value;
  
  var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  var input = iframeDoc.querySelector("input.auction_lot_set");
  var auction_lot_set = input.value;

  var url = BASE_URL + "BUYER/movetoclosed";
  var formmethod = "post";

  if(userss_type=='user')
  {
    var Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
    });
    Toast.fire({
      icon: "error",
      title: "Session Ended.",
      timer: 3000,
      timerProgressBar: true,
    });
    var iframe = document.getElementById("biddingIframe");
    
      setTimeout(function () {
        iframe.src = iframe.src;
      }, 1000);
    return false;
  }

  $.ajax({
    url: url,
    type: formmethod,
    data: {
      auction_id: $("#auction_id").val(),
      lot_no: auction_lot_set,
    },
    dataType: "JSON",
    success: function (_response) {
      var Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
      });
      Toast.fire({
        icon: "error",
        title: "Session Ended.",
        timer: 3000,
        timerProgressBar: true,
      });
      var iframe = document.getElementById("biddingIframe");
      setTimeout(function () {
        iframe.src = iframe.src;
      }, 1000);

      if (_response.upcomingdata == 0) {
        var user_type = document.getElementById("user_type").textContent;
        if (user_type == "user") {
          console.log("hii");
          window.location.href  = BASE_URL + "/USER/BiddingSession";
        } else {
          // console.log(user_type);
          window.location.href = BASE_URL + "/BUYER/completed-auctions";
        }
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
      $("#myIframe").attr("src", function (i, val) {
        return val;
      });
      var iframe = $("#myIframe");
      iframe.attr("src", iframe.attr("src"));
      // Show error message using native alert
      alert("An error occurred while adding to catalog");
    },
  });
}

function completeLiveProducts() {
  var run_timer = $("#activity-count").val();
  var user_type = document.getElementById("user_type");
  if (user_type == "user") {
    console.log("hii");
    var r_url = BASE_URL + "/USER/BiddingSession";
  } else {
    // console.log(user_type);
    var r_url = BASE_URL + "/BUYER/completed-auctions";
  }

  
  if (run_timer === "" || run_timer === "0") {
    $("#activity-count").val(1);
    resetActivityTimer();
  } else {
    run_timer = parseInt(run_timer) + 1;
  }

  if (run_timer > 1) {
    var Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
    });

    var url = BASE_URL + "/BUYER/movetoclosed";
    var formmethod = "post";

    $.ajax({
      url: url,
      type: formmethod,
      data: {
        auction_id: $("#auction_id").val(),
      },
      dataType: "JSON",
      success: function (_response) {
        // alert(_response.upcomingdata);

        if (_response.upcomingdata == 0) {
          var url = BASE_URL + "/BUYER/movetoreview";
          var formmethod = "post";
          $.ajax({
            url: url,
            type: formmethod,
            data: {
              auction_id: $("#auction_id").val(),
            },
            dataType: "JSON",
            success: function (_response) {
              Toast.fire({
                icon: "error",
                title: "Session Ended.",
              });
              setTimeout(function () {
                window.location.href = r_url;
              }, 1000);
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
              $("#myIframe").attr("src", function (i, val) {
                return val;
              });
              var iframe = $("#myIframe");
              iframe.attr("src", iframe.attr("src"));
              // Show error message using native alert
              alert("An error occurred while adding to catalog");
            },
          });
        }
        $("#biddingIframe").attr("src", function (i, val) {
          return val;
        });
        var iframe = $("#biddingIframe");
        iframe.attr("src", iframe.attr("src"));
        $("#activity-count").val(0);
        resetActivityTimer();
      },
      error: function (xhr, status, error) {
        $("#myIframe").attr("src", function (i, val) {
          return val;
        });
        var iframe = $("#myIframe");
        iframe.attr("src", iframe.attr("src"));
        alert("An error occurred while adding to catalog");
      },
    });
  }
}

function disableIframeButtons() {
  // console.log('buttons-disabled')
  var iframe = document.getElementById("biddingIframe");
  if (iframe) {
    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    if (iframeDoc) {
      var buttons = iframeDoc.querySelectorAll("button.manual_bid_save");
      for (var i = 0; i < buttons.length; i++) {
        buttons[i].setAttribute("disabled", true);
      }
    }
  }
}
