<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= @CompanyName ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/style.css">

  <style>
    .table-bordered th {
      font-size: smaller !important;
    }

    .highlight {
      border: 2px solid #007BFF !important;
      /* You can change the color as per your preference */
      outline: none !important;
      /* Remove default outline */
      box-shadow: 0 0 5px #007BFF !important;
      /* Optional: Add a shadow for a more pronounced effect */
    }

    .table td,
    .table th {
      padding: .5rem;
    }

    /* .table td .form-control {
      max-width: 100px;
      height: 30px;
      border: 0px;
      border-radius: 3px;
      padding: 6px 5px;
      font-size: 13px;
    } */

    input[type=number]::-webkit-inner-spin-button {
            opacity: 1
        }

        #buyers-data {
            height: 300px !important;
            overflow-y: auto;
        }

        .table td,
        .table th {
            padding: 0.20rem !important;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .status-live {
            color: red;
        }

        .status-pending {
            color: grey;
        }

        .status-completed {
            color: green;
        }

        /* Circular Timer Styles */
        .circular-timer {
            position: relative;
            margin: 0 auto;
        }

        .circular-timer .circle {
            stroke-dasharray: 130;
            stroke-dashoffset: 130;
            stroke-width: 10;
            stroke: #00aaff;
            fill: none;
            transition: stroke-dashoffset 10s linear;
        }

        .circular-timer .circle-bg {
            stroke-width: 10;
            stroke: #ddd;
            fill: none;
        }

        .circular-timer text {
            font-size: 16px;
            font-weight: bold;
            text-anchor: middle;
            dominant-baseline: middle;
        }

        .hidden {
            display: none;
        }

        .timer {
            font-size: 20px !important;
            font-weight: bold;
        }

        .base-timer {
            display: grid;
            margin: -40px;
            position: static;
            width: 49px;
            align-content: center;
            justify-items: center;
        }
        
        .base-timer__svg {
          transform: scaleX(-1);
        }
        
        .base-timer__circle {
          fill: none;
          stroke: none;
        }
        
        .base-timer__path-elapsed {
          stroke-width: 3px;
          stroke: grey;
        }
        
        .base-timer__path-remaining {
          stroke-width: 3px;
          stroke-linecap: round;
          transform: rotate(90deg);
          transform-origin: center;
          transition: 1s linear all;
          fill-rule: nonzero;
          stroke: currentColor;
        }
        
        .base-timer__path-remaining.green {
          color: rgb(8, 235, 197);
        }
        
        .base-timer__path-remaining.orange {
          color: orange;
        }
        
        .base-timer__path-remaining.red {
          color: red;
        }
        
        .base-timer__label {
          position: absolute;
          width: 25px;
          height: 25px;
          top: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 20px;
        }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?= @$header ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?= @$sidebar ?>

    <div class="content-wrapper">
      <?php
      $lots = $auction_data['auctionItems'];
      $groupedLots = [];
      foreach ($lots as $lot) {
        $lotSet = $lot['lot_set'];
        if (!isset($groupedLots[$lotSet])) {
          $groupedLots[$lotSet] = [];
        }
        $groupedLots[$lotSet][] = $lot;
      }

      // Prepare the sessions array
      $sessions = [];
      foreach ($groupedLots as $lotSet => $lots) {
        $startTime = $lots[0]['start_time'];
        $endTime = end($lots)['end_time'];
        $lotCount = $auction_data['lot_count']; //count($lots);

        $sessions[] = [
          'start_time' => $startTime,
          'end_time' => $endTime,
          'lot_count' => $lotCount
        ];
      }
      usort($sessions, function ($a, $b) {
        return strtotime($a['start_time']) - strtotime($b['start_time']);
      });
      //echo '<pre>';print_r($sessions);    
      ?>
      <!-- Main content -->
      <section class="content user-data-info-view">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card mt-3">
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="top-user-info">
                    <div class="row">
                      <div class="col-sm-3">
                        <h3 class="title-name">
                          Welcome <?php echo ucfirst(session()->get('buyer_name')) ?>
                        </h3>
                      </div>
                      <div class="col-sm-3">

                        <span> <i class="far fa-clock"></i> Active Lots : <lable class=""><?php echo @$auction_data['lot_count']; ?></label></span>
                      </div>
                      <div class="col-sm-3">
                        <!-- <span><i class="far fa-clock"></i>
                                                    Auction Time Remaning : <label id="countdown"></label>
                                                </span> -->
                      </div>
                      <div class="col-sm-3">
                        &nbsp;

                        <div id="timers">

                        </div>
                        <p class="current_bid_section" style="color:yellow;font-weight:bold;text-align:right">Setup Bidding</span>
                      </div>
                      <div class="col-sm-3">
                        &nbsp;
                        <input type="hidden" value="" id="activity-count">
                        <input type="hidden" name="min_hour_over" id="min_hour_over" value="<?php echo @$auction_data['min_hour_over']; ?>" />

                      </div>

                    </div>
                  </div>

                  <div class="our-session-info">
                    <div class="row">
                      <div class="col-sm-3 data-itmes-list">
                        <?php
                        $each_session = @$auction_data['session_time'];
                        ?>
                        <h3>Session Time:<span><?php echo @$auction_data['start_time']; ?> - <?php echo @$auction_data['end_time']; ?></span></h3>
                        <input type="hidden" value="<?php echo @$auction_data['session_time']; ?>" id="lot_time">
                        <input type="hidden" value="<?php echo @$auction_data['id']; ?>" id="auction_id">

                      </div>
                      <div class="col-sm-3  data-itmes-list">
                        <h3>Sale no : <span><?php echo @$auction_data['sale_no']; ?></span></h3>
                      </div>
                      <div class="col-sm-3  data-itmes-list">
                        <h3>Auction Date : <span><?php echo @$auction_data['date']; ?></span>
                      </div>
                      <div class="col-sm-3  data-itmes-list">
                        <?php
                        @$start_time = $auction_data['start_time'];
                        ?>

                        <span id="mainTimer" class="timer"></span>
                        <span id="session1Timer" class="timer"></span>
                        <span id="session2Timer" class="timer"></span>
                        <span id="user_type" style="display: none;">user</span>
                        <input id="sessuser_type" type="hidden" value="user">

                      </div>
                    </div>

                  </div>

                  <div class="our-session-info-data">
                    <?php
                    if (!empty($auction_data['auctionItems'])) {
                      $auction_items = @$auction_data['auctionItems'];

                      $distinct_start_times = [];

                      // Loop through the array to fetch distinct start times
                      foreach ($auction_items as $item) {
                        $start_time = $item['start_time'];
                        if (!in_array($start_time, $distinct_start_times)) {
                          $distinct_start_times[] = "'" . $start_time . "'";
                        }
                      }
                    }
                    //array_push($distinct_start_times,"'" .$auction_data['end_time']."'" );
                    //$distinct_start_times = Array ('01:01:30','01:01:40','01:01:50');
                    $distinct_start_times = implode(', ', $distinct_start_times);
                    //echo '<pre>';print_r($distinct_start_times);

                    if (!empty($auction_data['auctionItems'])) {
                    ?>
                      <iframe height="438.5px" width="100%" src="<?= @basePath ?>USER/BiddingSession/EditReserveBidframe/<?php echo base64_encode($auction_data['id']); ?>" frameborder="yes" name="biddingIframe" id="biddingIframe"></iframe>
                    <?php } ?>

                  </div>
                </div>

                <!-- /.card-body -->
              </div>

              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.row -->
    </div>
    <!-- /.content-wrapper -->
    <?= @$data['footer']; ?>


    <script src="<?= @basePath ?>admin_assets/plugins/toastr/toastr.min.js"></script>

    <script src="<?= @basePath ?>admin_assets/dist/js/scripts.js?v=<?= time(); ?>"></script>
    <?php $serverTime = microtime(true); ?>
    <script>
      function updateCountdown() {
        // Server end time
        var endTimeStr = "<?php echo @$response_data[0]['end_time']; ?>";
        var endTimeParts = endTimeStr.split(":");
        var endTime = new Date();
        endTime.setHours(parseInt(endTimeParts[0], 10));
        endTime.setMinutes(parseInt(endTimeParts[1], 10));
        endTime.setSeconds(parseInt(endTimeParts[2], 10));
        console.log("end Time:", endTime);

        function padZero(num) {
          return num < 10 ? '0' + num : num;
        }

        function countdown() {
          // Server current time passed from PHP
          var serverTimeStr = "<?php echo date('H:i:s'); ?>";
          var serverTimeParts = serverTimeStr.split(":");
          var serverTime = new Date();
          serverTime.setHours(parseInt(serverTimeParts[0], 10));
          serverTime.setMinutes(parseInt(serverTimeParts[1], 10));
          serverTime.setSeconds(parseInt(serverTimeParts[2], 10));

          // Calculate the difference between the end time and server time
          var distance = endTime - serverTime;

          // Update server time by adding elapsed time
          var now = new Date().getTime();
          var elapsed = now - initialClientTime;
          var currentServerTime = new Date(serverTime.getTime() + elapsed);

          // Calculate the remaining time
          var remainingTime = endTime - currentServerTime;

          // Calculate remaining hours, minutes, and seconds
          var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

          // Output the result in the element with id="countdown"
          document.getElementById("countdown").innerHTML = hours + "h " + minutes + "m " + seconds + "s ";



          // Update the HTML elements with the calculated values
          //document.getElementById('remainingTime').textContent = ` ${remainingTime1}`;
          // console.log($("#auction_id").val());
          // If the countdown is over, display a message
          if (remainingTime <= 0) {
            clearInterval(timer);
            document.getElementById("countdown").innerHTML = "Session Ended";
            var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });
            Toast.fire({
              icon: 'error',
              title: 'Auction Closed.',
              timer: 1000,
              timerProgressBar: true
            });
            var url = '<?= @basePath ?>BUYER/movetoclosed';
            var formmethod = 'post';


          }
        }

        // Capture the initial client time when the script loads
        var initialClientTime = new Date().getTime();

        // Update the countdown every second
        var timer = setInterval(countdown, 1000);

        // Initial call to display the countdown immediately
        countdown();



      }

      // Call the function to start the countdown
      //updateCountdown();

      function reloadFirstIframe() {
        var firstIframe = document.getElementById('myIframe');
        firstIframe.contentWindow.location.reload();
      }

      function reloadmybookIframe() {
        var bidIframe = document.getElementById('mybidBook');
        bidIframe.contentWindow.location.reload();
      }
    </script>
    <?php $serverTime = microtime(true); ?>
</body>

</html>