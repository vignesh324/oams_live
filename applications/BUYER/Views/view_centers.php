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
            stroke-width: 10px;
            stroke: grey;
        }

        .base-timer__path-remaining {
            stroke-width: 10px;
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

        /* sajahan added style  */

        #last_log_value,
        #min_hour_over,
        #last_log_value_plus_10 {
            border: none;
            background-color: transparent;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <?php
    $lastLogValue = @$response_data[0]['last_log_value'] ?? 0;
    $updatedtime = $response_data[0]['updated_time'] ?? 0;
    ?>

    <div class="wrapper">
        <?= @$header ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php
            $lots = $response_data[0]['auction_items'];
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
                $lotCount = $response_data[0]['lot_count']; //count($lots);

                $sessions[] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'lot_count' => $lotCount
                ];
            }
            usort($sessions, function ($a, $b) {
                return strtotime($a['start_time']) - strtotime($b['start_time']);
            });

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

                                                <span> <i class="far fa-clock"></i> Active Lots : <lable class=""> 2<?php //echo @$response_data[0]['lot_count']; 
                                                                                                                    ?></label></span>
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
                                                <p class="current_bid_section" style="color:yellow;font-weight:bold;text-align:right">PreBidding Session</span>

                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" value="" id="activity-count">
                                                <input type="hidden" name="auction_id_complete_state" class="auction_id_complete_state" value="" />
                                                <input type="text" name="min_hour_over" id="min_hour_over" value="<?php echo @$response_data[0]['min_hour_over']; ?>" />
                                                <input type="hidden" value="0" id="saj_test">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="our-session-info">
                                        <div class="row">
                                            <div class="col-sm-3 data-itmes-list">
                                                <?php
                                                $each_session = @$response_data[0]['session_time'];
                                                ?>
                                                <h3>Session Time:<span><?php echo @$response_data[0]['start_time']; ?> - <?php echo @$response_data[0]['end_time']; ?></span></h3>
                                                <input type="hidden" value="<?php echo @$response_data[0]['session_time']; ?>" id="lot_time">
                                                <input type="hidden" value="<?php echo @$response_data[0]['id']; ?>" id="auction_id">
                                                <input type="text" value="<?php echo $lastLogValue; ?>" id="last_log_value">
                                                <!-- <input type="text" value="<?php //echo $lastLogValue + 10; 
                                                                                ?>" id="last_log_value_plus_10"> -->
                                                <input type="text" value="<?php echo $updatedtime; ?>" id="last_log_time">
                                                <input type="text" id="log_10_sec">

                                            </div>
                                            <div class="col-sm-3  data-itmes-list">
                                                <h3>Sale no : <span><?php echo @$response_data[0]['sale_no']; ?></span></h3>
                                            </div>
                                            <div class="col-sm-3  data-itmes-list">
                                                <h3>Auction Date : <span><?php echo @$response_data[0]['date']; ?></span>
                                            </div>
                                            <div class="col-sm-3  data-itmes-list">
                                                <?php
                                                @$start_time = $response_data[0]['start_time'];
                                                ?>

                                                <span id="mainTimer" class="timer"></span>
                                                <span id="session1Timer" class="timer"></span>
                                                <span id="session2Timer" class="timer"></span>
                                                <span id="user_type" style="display: none;">buyer</span>
                                                <input id="sessuser_type" type="hidden" value="buyer">
                                                <span id="countdown"></span>
                                                <!-- <div class="circular-timer" id="circular-timer">
                                                    <svg width="50" height="50">
                                                        <circle class="circle-bg" cx="25" cy="25" r="20"></circle>
                                                        <circle class="circle" cx="25" cy="25" r="20"></circle>
                                                        <text x="25" y="25" id="timer-text">10</text>
                                                    </svg>
                                                </div> -->
                                            </div>
                                        </div>

                                    </div>

                                    <!-- <table class="table table-striped">
                                        <tr>
                                            <td><h3 class="card-title">Auction Catalog</h3></td>
                                            <td style="text-align: right;">
                                                <button class="btn btn-success">Add to catalog</button>
                                            </td>
                                        </tr>
                                    </table> -->
                                    <div class="our-session-info-data">
                                        <?php
                                        if (!empty($response_data[0]['auction_items'])) {
                                            $auction_items = @$response_data[0]['auction_items'];

                                            $distinct_start_times = [];

                                            // Loop through the array to fetch distinct start times
                                            foreach ($auction_items as $item) {
                                                $start_time = $item['start_time'];
                                                if (!in_array($start_time, $distinct_start_times)) {
                                                    $distinct_start_times[] = "'" . $start_time . "'";
                                                }
                                            }
                                        }
                                        //array_push($distinct_start_times,"'" .$response_data[0]['end_time']."'" );
                                        //$distinct_start_times = Array ('01:01:30','01:01:40','01:01:50');
                                        $distinct_start_times = implode(', ', $distinct_start_times);
                                        //echo '<pre>';print_r($distinct_start_times);

                                        if (!empty($response_data[0]['auction_items'])) {
                                        ?>
                                            <input type="hidden" value="" id="active_session">
                                            <iframe height="300px" width="100%" src="<?= @basePath ?>BUYER/auctionLots/<?php echo base64_encode($response_data[0]['id']); ?>" frameborder="yes" name="biddingIframe" id="biddingIframe"></iframe>
                                        <?php } ?>

                                    </div>
                                </div>

                                <!-- /.card-body -->
                            </div>

                            <div class="row">
                                <div class="col col-lg-6">
                                    <div class="card" id="my-catalog-card">
                                        <?php if (!empty($response_data[0]['auction_items'])) { ?>
                                            <iframe height="300px" width="100%" src="<?= @basePath ?>BUYER/myCatalog/table/<?php echo @$response_data[0]['id']; ?>" frameborder="yes" name="myIframe" id="myIframe"></iframe>
                                        <?php } ?>
                                    </div>
                                </div>


                                <div class="col col-lg-6">
                                    <div class="card">
                                        <?php if (!empty($response_data[0]['auction_items'])) { ?>
                                            <iframe height="300px" width="100%" src="<?= @basePath ?>BUYER/mybidBook/<?php echo base64_encode(@$response_data[0]['id']); ?>" frameborder="yes" name="myIframe" id="mybidBook"></iframe>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?= @$data['footer']; ?>

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script> -->

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

                        $.ajax({
                            url: url,
                            type: formmethod,
                            data: {
                                'auction_id': $("#auction_id").val()
                            },
                            dataType: 'JSON',
                            success: function(_response) {
                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Session Ended.',
                                    timer: 1000,
                                    timerProgressBar: true
                                });
                                var iframe = document.getElementById('biddingIframe');
                                setTimeout(function() {
                                    iframe.src = iframe.src;
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                $('#myIframe').attr('src', function(i, val) {
                                    return val;
                                });
                                var iframe = $('#myIframe');
                                iframe.attr('src', iframe.attr('src'));
                                // Show error message using native alert
                                alert('An error occurred while adding to catalog');
                            }
                        });

                    }
                }

                // Capture the initial client time when the script loads
                var initialClientTime = new Date().getTime();

                // Update the countdown every second
                var timer = setInterval(countdown, 1000);

                // Initial call to display the countdown immediately
                countdown();



            }

            // Call the function to start the countdown sajahan
            //updateCountdown();

            function reloadFirstIframe() {
                var firstIframe = document.getElementById('myIframe');
                document.getElementById('saj_test').value = 1; //sajahan added for 10 sec close for no activity
                firstIframe.contentWindow.location.reload();
            }

            function reloadmybookIframe() {
                var bidIframe = document.getElementById('mybidBook');
                bidIframe.contentWindow.location.reload();
            }


            function deleteBidData(auction_item_id) {
                var auction_id = <?php echo $response_data[0]['id']; ?>;
                var buyer_id = <?php echo session()->get('user_id'); ?>;
                var url = '<?= @basePath ?>BUYER/deleteBidData';
                var formmethod = 'post';
                console.log(auction_id);
                console.log(buyer_id);
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(id);
                        $.ajax({
                            url: url,
                            type: formmethod,
                            data: {
                                'auction_id': auction_id,
                                'auction_item_id': auction_item_id,
                                'buyer_id': buyer_id,
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Deleted successfully',
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        var bidIframe = document.getElementById('biddingIframe');
                                        bidIframe.contentWindow.location.reload();
                                    }
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'An error occurred while deleting',
                                });
                            }
                        });
                    }
                });
            };
        </script>
</body>

</html>