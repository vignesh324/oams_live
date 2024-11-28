let startTimeInterval; // to be used by startTimer();
document.addEventListener("DOMContentLoaded", () => {
  startAuctionTimers();
});

function startAuctionTimers() {
  let mainTimer = document.getElementById("mainTimer");
  let session1Timer = document.getElementById("session1Timer");
  let session2Timer = document.getElementById("session2Timer");
  let auction_id = document.getElementById("auction_id").value;
  let lastFunctionCallTime = 0;
  //alert(auction_id);return false;
  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
  });

  startTimer("min_bid_timer.txt", 300, mainTimer, () => {
    completeManual(auction_id);
    Toast.fire({
      icon: "error",
      title: "Bid setup time over",
      timer: 3000,
      timerProgressBar: true,
    });
    mainTimer.classList.add("hidden");
    $(".current_bid_section").text("Manual Bidding Running");
    startTimer("session1_timer.txt", 120, session1Timer, () => {
      autoBidLog(auction_id);
      Toast.fire({
        icon: "error",
        title: "Manual bidding time over",
        timer: 5000,
        timerProgressBar: true,
      });
      session1Timer.classList.add("hidden");
      $(".current_bid_section").text("Auto Bidding Running");
      setTimeout(function () {
        disableIframeButtons();
      }, 600);
      startTimer("session2_timer.txt", 40, session2Timer, async () => {
        disableIframeButtons();
        session2Timer.classList.add("hidden");
        const currentTime = Date.now();
        //if (currentTime - lastFunctionCallTime > 120000) {
        //}
          $.when(clearAllTimersAndFiles())
        .then(function() {
            return recallTimers();
        })
        .then(function() {
            return closeEachSession();
        });
        // clearAllTimersAndFiles();
        // recallTimers();
        // closeEachSession();
      });
    });
  });
}
function recallTimers() {
  // Reinitialize necessary elements or states here if needed
  // For example, you may need to show the timers again if they were hidden
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
  activityTimer = timersec;
  timerText.textContent = activityTimer;
  circle.style.transition = "none";
  circle.style.strokeDashoffset = 130;
  setTimeout(() => {
    circle.style.transition = "stroke-dashoffset 10s linear";
    circle.style.strokeDashoffset = 0;
  }, 0);
  startActivityTimer();
}

function resetSession1Timer() {
  let session1Timer = document.getElementById("session1Timer");
  session1Timer.textContent = "";

  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
  });

  startTimer(
    "session1_timer.txt",
    120,
    session1Timer,
    () => {
      Toast.fire({
        icon: "error",
        title: "Manual bidding time over",
        timer: 5000,
        timerProgressBar: true,
      });
      session1Timer.classList.add("hidden");
      $(".current_bid_section").text("Auto Bidding Running");
      disableIframeButtons();
      startTimer("session2_timer.txt", 40, session2Timer, async () => {
        disableIframeButtons();
        session2Timer.classList.add("hidden");
        const currentTime = Date.now();
        //if (currentTime - lastFunctionCallTime > 120000) {
        //}
        $.when(clearAllTimersAndFiles())
        .then(function() {
            return recallTimers();
        })
        .then(function() {
            return closeEachSession();
        });
        // clearAllTimersAndFiles();
        // recallTimers();
        // closeEachSession();
      });
    },
    true
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
        renderProducts();
        resetActivityTimer();
      } else {
        timerText.textContent = "0";
      }
    }
  }, 1000);
}

function closeEachSession(params) {
  var iframe = document.getElementById("biddingIframe");
  var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  var input = iframeDoc.querySelector("input.auction_lot_set");
  var auction_lot_set = input.value;

  var url = BASE_URL + "BUYER/movetoclosed";
  var formmethod = "post";

  var iframe = $("#myIframe");
  iframe.attr("src", iframe.attr("src"));
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

  }
  renderProducts();
}

function disableIframeButtons() {
  console.log('buttons-disabled')
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
