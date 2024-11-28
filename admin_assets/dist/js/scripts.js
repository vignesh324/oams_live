let startTimeInterval; // to be used by startTimer();
let isAutoBiddingActive = false; // flag to prevent restarting auto-bidding timer
var timer3activate=0;



const FULL_DASH_ARRAY = 283;
const WARNING_THRESHOLD = 10;
const ALERT_THRESHOLD = 5;

const COLOR_CODES = {
  info: {
    color: "green"
  },
  warning: {
    color: "orange",
    threshold: WARNING_THRESHOLD
  },
  alert: {
    color: "red",
    threshold: ALERT_THRESHOLD
  }
};

document.addEventListener("DOMContentLoaded", () => {
  
  startAuctionTimers();
});

function startAuctionTimers() {
  timer3activate=0;
  let mainTimer = document.getElementById("mainTimer");
  let session1Timer = document.getElementById("session1Timer");
  let session2Timer = document.getElementById("session2Timer");
  let auction_id = document.getElementById("auction_id").value;
  
  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000,
  });


  // Start the bid setup timer
  startTimer("min_bid_timer.txt", 60, mainTimer, () => {
    console.log('1st timer over');
    completeManual(auction_id);
    Toast.fire({
      icon: "error",
      title: "Bid setup time over",
      timer: 300,
      timerProgressBar: true,
    });
    mainTimer.classList.add("hidden");
    $(".current_bid_section").text("Session 1");
    $("#active_session").val(2);
    // Start the manual bidding timer (Session 1)
    console.log('1st timer over2');
    startTimer("session1_timer.txt", 40, session1Timer, () => {
      $("#active_session").val(3);
      console.log('2nd timer over');
       //if (!isAutoBiddingActive) { // Ensure this block doesn't run multiple times


      autoBidLog(auction_id);
      isAutoBiddingActive = true; 
      
      // Set flag to prevent resetting auto-bidding
      Toast.fire({
        icon: "error",
        title: "Manual bidding time over",
        timer: 500,
        timerProgressBar: true,
      });
      session1Timer.classList.add("hidden");
      $(".current_bid_section").text("Session 2");

      // Start auto-bid session (Session 2)
      console.log('2nd timer over2');
       
      //Sajahan Code Change
               
                  const hittime1=document.getElementById("last_log_value").value;
                  if(hittime1==0 || hittime1=='' || hittime1==' ' || hittime1==null || hittime1==undefined){
                    var differenttime1=0;
                  }else{
                  const myarray1=hittime1.split(" ");
                  const timestring1=myarray1[1];
              
                  
                  const[hours1,min1,sec1]=timestring1.split(":").map(Number);
                  const datenow1=new Date();
                  datenow1.setHours(hours1);
                  datenow1.setMinutes(min1);
                  datenow1.setSeconds(sec1);
                  let curtime1=datenow1.getTime();
                  timecutendtime1=new Date(curtime1 + 10000);   
                  let nowtime1=new Date();
                  var differenttime1=Math.round((timecutendtime1-nowtime1)/1000)
                  document.getElementById('log_10_sec').value=timecutendtime1;
                }
           
       //sajahan code end for extra 20 sec           
         
      if(differenttime1>0){
        
      startTimer("session2_timer.txt", 20, session2Timer, async () => {
        $("#active_session").val(1);
        console.log('3rd timer over');
        session2Timer.classList.add("hidden");
        localStorage.removeItem('manual_bid_over');

        $.when(clearAllTimersAndFiles())
          .then(function () {
            return recallTimers();
          })
          .then(function () {
            return closeEachSession();
          });
        console.log('3rd timer over2');
      }, false, true);

      timer3activate=0;

    }else{

      //sajahan added new if condition for skip the 3rd timer 
     

      $("#active_session").val(1);
      
      session2Timer.classList.add("hidden");
      localStorage.removeItem('manual_bid_over');

      $.when(clearAllTimersAndFiles())
        .then(function () {
          return recallTimers();
        })
        .then(function () {
          return closeEachSession();
        });
      

    }


      //}
    }, false, true);
  }, false, true);



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
        console.log("sajahan: Save button pressed")
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
      var iframe1 = document.getElementById("mybidBook");
      setTimeout(function () {
        iframe.src = iframe.src;
        if (iframe1) {
          iframe1.src = iframe1.src;
        }
      }, 1000);
      
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}


function startTimer(file, duration, display, callback, isManualSave = false, isLoaderAnimationEnabled = false) {
  
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
          let sajstarttime=new Date(data.startTime).getTime();
          
          var timecutendtime=new Date(sajstarttime + 10000);
          let endTime = startTime + duration * 1000;
          let sajendtime=new Date(endTime);
          console.log("sajahan: start time :" +timecutendtime);
         
          
          const offset = serverTime - new Date().getTime() + fetchDelay;
        
         
          if (isLoaderAnimationEnabled) {
            invokeTimerLoader(display.id, duration);
          }
          startTimeInterval = setInterval(() => {

            //sajahan changed remining time calculation
            let now = new Date().getTime() + offset;
            let remaining = Math.max((endTime - now) / 1000, 0);

           
            let minutes = Math.floor(remaining / 60);
            let seconds = Math.floor(remaining % 60);
           
            if (isLoaderAnimationEnabled) {

              const doc = display.getElementsByClassName('base-timer')[0].getElementsByTagName('span')[0];
              // const doc = document.getElementById("base-timer-label");
              doc.innerHTML = `${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""
                }${seconds}`;

             
              let currentTimeLogForAjax = parseInt(now.toString().substring(0, 10));
              let lastLogValue = parseInt($('#last_log_value_plus_10').val());

              

              // console.log('sajahan:'+lastLogValue);

              if (currentTimeLogForAjax >= lastLogValue) {
                // closeEachSession();

                lastLogValue += 10; // Increment by 10

                // Update the input field value
                $('#last_log_value_plus_10').val(lastLogValue);

                // $.ajax({
                //   url: `${BASE_URL}timer/update_file.php`,
                //   method: 'POST',
                //   data: { last_log_value: currentTimeLogForAjax },
                //   success: function (response) {
                //     const responseData = JSON.parse(response);
                //     if (responseData.status === 'success') {
                //       console.log('File updated successfully:', responseData.updated_value);
                //     } else {
                //       console.error('Error in response:', responseData.message);
                //     }
                //   },
                //   error: function (error) {
                //     console.error('Error updating file:', error);
                //   }
                // });
              }

              setCircleDasharray(remaining, duration, display);
              setRemainingPathColor(remaining, display, duration);

              //Sajahan Code   
           

            let bittime=parseInt(document.getElementById('last_log_value').value);

            if(bittime!==0){
                      
                  //saj time convert code
                  const hittime=document.getElementById("last_log_value").value;

                  const myarray=hittime.split(" ");
                  const timestring=myarray[1];
                  console.log(timestring);
                  const[hours,min,sec]=timestring.split(":").map(Number);
                  const datenow=new Date();
                  datenow.setHours(hours);
                  datenow.setMinutes(min);
                  datenow.setSeconds(sec);
                  
                 
                  // let curtime=parseInt(document.getElementById('last_log_value').value);
                  
                  let curtime=datenow.getTime();

                  timecutendtime=new Date(curtime + 10000);   
                   
             }
          
           let nowtime=new Date();

           var differenttime=Math.round((timecutendtime-nowtime)/1000)
           document.getElementById('log_10_sec').value=timecutendtime;
           
           if(differenttime<=0 && file!=="min_bid_timer.txt"){
              remaining=0;         
             }

             

           //sajahan code end

           
            } else {
              display.textContent = `${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""
                }${seconds}`;
            }

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
  console.log("MIN_HOUR _ : " + min_hour_over);

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
      $("#active_session").val(3);
      // Start auto bidding session (Session 2)
      startTimer("session2_timer.txt", 30, session2Timer, async () => {
        session2Timer.classList.add("hidden");

        // Chaining promises to ensure timers and files are cleared and session closes correctly
        $.when(clearAllTimersAndFiles())
          .then(function () {
            return recallTimers();
          })
          .then(function () {
            return closeEachSession();
          });
      }, false);
    },
    true // Pass true if this indicates manual save logic
  );
}

function startActivityTimer(timersec) {
  clearInterval(activityInterval);
}

function closeEachSession(params) {
  var iframe = document.getElementById("biddingIframe");
  var userss_type = document.getElementById("sessuser_type").value;

  var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  var input = iframeDoc.querySelector("input.auction_lot_set");
  var auction_lot_set = input.value;

  var url = BASE_URL + "BUYER/movetoclosed";
  var formmethod = "post";
  var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
  });
  var upcomingdatacount = 0;

  // if (userss_type == 'buyer') {

  $.ajax({
    url: url,
    type: formmethod,
    data: {
      auction_id: $("#auction_id").val(),
      lot_no: auction_lot_set,
    },
    dataType: "JSON",
    success: function (_response) {

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
      console.log(_response.upcomingdata);
      upcomingdatacount = _response.upcomingdata;

      document.getElementById("last_log_value").value=0;

      if (_response.upcomingdata == 0) {
        var user_type = document.getElementById("user_type").textContent;
        if (user_type == "user") {
          console.log("hii");
          window.location.href = BASE_URL + "/USER/BiddingSession";
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

  // } else {
  //   Toast.fire({
  //     icon: "error",
  //     title: "Session Ended.",
  //     timer: 3000,
  //     timerProgressBar: true,
  //   });
  //   var iframe = document.getElementById("biddingIframe");

  //   setTimeout(function () {
  //     iframe.src = iframe.src;
  //   }, 1000);


  // }

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
    // resetActivityTimer();
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
        // resetActivityTimer(); 
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

function invokeTimerLoader(timerName, timeLimit) {
  console.log('Invoked the timer loader')
  const TIME_LIMIT = timeLimit;
  let timePassed = 0;
  let timeLeft = TIME_LIMIT;
  let timerInterval = null;
  let remainingPathColor = "green";
  console.log("TimeLIT :" + timeLimit);

  document.getElementById(timerName).innerHTML = `
  <div class="base-timer">
    <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <g class="base-timer__circle">
        <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
        <path
          id="base-timer-path-remaining"
          stroke-dasharray="283"
          class="base-timer__path-remaining ${remainingPathColor}"
          d="
            M 50, 50
            m -45, 0
            a 45,45 0 1,0 90,0
            a 45,45 0 1,0 -90,0
          "
        ></path>
      </g>
    </svg>
    <span id="base-timer-label" class="base-timer__label"></span>
  </div>
  `;
  /**
   * ${formatTime(
       timeLeft
     )}
   */
}

function onTimesUp() {
  clearInterval(timerInterval);
}

function startTimerV1() {
  timerInterval = setInterval(() => {
    setCircleDasharray();
    setRemainingPathColor(timeLeft);


    if (timeLeft === 0) {
      onTimesUp();
    }
  }, 1000);
}

function formatTime(time) {
  const minutes = Math.floor(time / 60);
  let seconds = time % 60;

  if (seconds < 10) {
    seconds = `0${seconds}`;
  }
  return `${minutes}:${seconds}`;
}

function setRemainingPathColor(timeLeft, htmlElement, duration) {
  COLOR_CODES.warning.threshold = duration / 2;
  COLOR_CODES.alert.threshold = duration / 4;
  const { alert, warning, info } = COLOR_CODES;
  if (timeLeft <= alert.threshold) {
    htmlElement.getElementsByTagName('path')[0].classList.remove(warning.color);
    htmlElement.getElementsByTagName('path')[0].classList.add(alert.color);
  } else if (timeLeft <= warning.threshold) {
    htmlElement.getElementsByTagName('path')[0].classList.remove(info.color);
    htmlElement.getElementsByTagName('path')[0].classList.add(warning.color);
  }
}

function calculateTimeFraction(remainingTime, totalTimeLimit) {
  const rawTimeFraction = remainingTime / totalTimeLimit;
  return rawTimeFraction - (1 / totalTimeLimit) * (1 - rawTimeFraction);
}

function setCircleDasharray(remainingTime, totalTimeLimit, htmlElement) {
  const circleDasharray = `${(
    calculateTimeFraction(remainingTime, totalTimeLimit) * 283
  ).toFixed(0)} 283`;
  htmlElement.getElementsByTagName('path')[0].setAttribute("stroke-dasharray", circleDasharray);
  // document
  //   .getElementById("base-timer-path-remaining")
  //   .setAttribute("stroke-dasharray", circleDasharray);
}