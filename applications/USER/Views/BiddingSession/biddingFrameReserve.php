<head>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/style.css">

    <style>
        input[type=number]::-webkit-inner-spin-button {
            opacity: 1
        }

        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0% !important;
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
    </style>
</head>

<div class="table-responsive">
    <?php
    $auc_item_array = @$response_data[0]['auction_items'];
    //echo '<pre>';print_r($sessions);    
    $completedcount = 0;
    foreach ($auc_item_array as $item) {
        if ($item["status"] == 2) {
            $completedcount++;
        }
    }
    ?>
    <input type="hidden" value="<?php echo count(@$response_data[0]['auction_items']) / 2; ?>" id="total_items_cnt">
    <input type="hidden" value="<?php echo $completedcount / 2; ?>" id="completed_items_cnt">
    <table class="table table-bordered table-striped" id="buyers-data">
        <thead>
            <tr style="background-color: #010d23;color:#FFFFFF">
                <th>LotNo</th>
                <th width="10%">Mark</th>
                <th>Grade</th>
                <th>No.of Bags</th>
                <th>Each Net</th>
                <th>Total Net</th>
                <th>Sample Qty</th>
                <th title="Base Price">BP</th>
                <th title="Valuation Price">VP</th>
                <th title="Last Sold Price">LP</th>
                <th title="Reserve Price">RP</th>
                <th title="Highest Bidding price">HBP</th>
                <?php if ($response_data[0]['auction_items'][0]['settings_buyer_show'] == 1) { ?>
                    <th>Buyer Name</th>
                <?php } ?>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($response_data[0]['auction_items'])) :
                $m = 1;
                $current_time = date('H:i');
                $lot_count = $response_data[0]['lot_count'];
                $lot_session = $response_data[0]['session_time'];
                $auction_items = $response_data[0]['auction_items'];
                $current_time = date("H:i:s");

                foreach ($auction_items as $key => $value) :

                    $start_time = strtotime($value['start_time']);
                    $end_time = strtotime($value['end_time']);
                    $current_time_str = strtotime($current_time);

                    if ($value['is_withdrawn'] == 1) {
                        $completed = "no";
                        $completed1 = "no";
                        $set_bg_color = 'style="background-color: #ffc107;color:#FFFFFF"';
                    } else {
                        if ($value['auction_status'] == 'current') {
                            $completed = "no";
                            $completed1 = "no";
                            $set_bg_color = 'style="background-color: #f44336;color:#ffffff"';
                        } else if ($value['auction_status'] == 'pending') {
                            $completed = "yes";
                            $completed1 = "pending";
                            $set_bg_color = 'style="background-color: #918989;color:#FFFFFF"';
                        } else {
                            $completed =  "yes";
                            $completed1 = "completed";
                            $set_bg_color = 'style="background-color: #918989;color:#FFFFFF"';
                        }
                    }

                    if ($value['auction_status'] == 'current') {
            ?>
                        <input type="hidden" readonly value="<?php echo @$value['auctLotSet']; ?>" class="form-control auction_lot_set" />
                    <?php
                    }
                    ?>
                    <tr <?php if ($response_data[0]['min_hour_over'] == 1) echo $set_bg_color;
                        else {
                            echo 'style="background-color: #918989;color:#FFFFFF"';
                        } ?>>
                        <td><?php echo @$value['lot_no']; ?></td>
                        <td><?php echo @$value['gardenname']; ?></td>
                        <td><?php echo @$value['gradename']; ?></td>
                        <td><?php echo @$value['auction_quantity']; ?></td>
                        <td><?php echo @$value['weight_net']; ?></td>
                        <td><?php echo number_format(@$value['weight_net'] * @$value['auction_quantity'], 2, '.', ','); ?></td>
                        <td><?php echo @$value['sample_quantity']; ?></td>
                        <td><?php echo @$value['base_price']; ?></td>
                        <td style="background-color: #0131e8;">
                            <?php echo @$value['valuation_price']; ?>
                            <input type="hidden" class="form-control valuation_price" name="valuation_price" id="valuation_price.<?php echo $key; ?>" placeholder="Valuation Price" value="<?php echo !empty($value['valuation_price']) ? $value['valuation_price'] : '0'; ?>" <?php if ($value['is_withdrawn'] == 1) echo 'readonly' ?>>
                            <input type="hidden" class="form-control auctionitem_id " name="auctionitem_id" id="auctionitem_id.<?php echo $key; ?>" value="<?php echo @$value['id']; ?>">
                            <input type="hidden" class="form-control auction_id" name="auction_id" value="<?php echo @$value['auction_id']; ?>">
                            <input type="hidden" class="form-control base_price" name="base_price" id="base_price.<?php echo $key; ?>" placeholder="Base Price" value="<?php echo !empty($value['base_price']) ? $value['base_price'] : '0'; ?>" <?php if ($value['is_withdrawn'] == 1) echo 'readonly' ?>>
                        </td>
                        <td><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                        <td>
                            <div class="input-group">
                                <input type="text" <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                                        echo 'disabled';
                                                    } ?> class="form-control reserve_price highlight" name="reserve_price" id="reserve_price.<?php echo $key; ?>" placeholder="Reserve Price" value="<?php echo !empty($value['reverse_price']) ? $value['reverse_price'] : '0'; ?>" <?php if ($value['is_withdrawn'] == 1) echo 'readonly' ?> fdprocessedid="vqvk2o">
                                <span class="input-group-append">
                                    <button class="btn btn-success" href="#" <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                                                                    echo 'disabled';
                                                                                } ?> id="update-reserve-price">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div id="highestbid_<?php echo $value['id']; ?>" data-auctionitem="<?php echo $value['id']; ?> "><?php echo $value['bid_price']; ?></div>
                        </td>
                        <?php if ($value['settings_buyer_show'] == 1) { ?>
                            <td>
                                <div id="highestbidder_<?php echo $value['id']; ?>" data-auctionitem="<?php echo $value['id']; ?> " style="font-weight: bold;"><?php echo @$value['highest_bidder_name']; ?></div>
                            </td>
                        <?php } ?>
                        <td>
                            <?php
                            if ($value['is_withdrawn'])
                                echo "withdrawn";
                            else {
                                if ($completed1 == 'no') {
                                    echo 'Live';
                                } elseif (@$completed1 == 'completed') {
                                    if ($value['bid_price'] < $value['reverse_price'])
                                        echo 'Unsold';
                                    else
                                        echo 'Sold';
                                } else {
                                    echo 'Pending';
                                }
                            }
                            ?>
                        </td>

                    </tr>
                <?php
                    $m++;
                endforeach; ?>
            <?php endif; ?>
        </tbody>

    </table>

</div>

<script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>

<script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?= @basePath ?>admin_assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= @basePath ?>admin_assets/dist/js/common.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/toastr/toastr.min.js"></script>
<script>
    $(document).ready(function() {

        $('.reserve_price').on('keyup', function() {
            var value = $(this).val();

            // Remove any characters other than numbers and decimals
            value = value.replace(/[^0-9.]/g, '');

            // Prevent more than one decimal point
            value = value.replace(/(\..*)\./g, '$1');

            // Limit to two decimal places
            if (value.indexOf('.') !== -1) {
                var parts = value.split('.');
                parts[1] = parts[1].substring(0, 2); // Allow only 2 decimal places
                value = parts.join('.');
            }

            $(this).val(value); // Set the cleaned-up value back to the input field
        });
    });
</script>
<script>
    var global_id;
    let parts;
    var ws = new WebSocket("ws://localhost:8081");

    ws.onopen = function() {
        console.log("WebSocket connected.");
    };

    ws.onmessage = function(event) {
        var message = event.data;
        console.log("Message received: ", event); // Debugging log

        var jsonString = message.replace('Message received: ', '');
        var parsedMessage = JSON.parse(jsonString);
        var bidValue = parsedMessage.bid_value;
        var item_id = parsedMessage.auction_item_value;
        var updated_time = parsedMessage.updated_time;

        // Check if parts is defined before using it
        // if (!parts) {
        //     console.error("Parts is not defined.");
        //     return;
        // }


        $("#highestbid_" + item_id).text(bidValue);
        $("#auction_bid_price_" + item_id).text(bidValue);
        document.getElementById("highestbid_" + item_id).value = bidValue;
        $(parent.document).find("#last_log").text(updated_time);

        parent.resetSession1Timer();
        if (typeof parent.startActivityTimer === 'function') {
            parent.startActivityTimer();
        } else {
            console.error("resetActivityTimer is not a function in the parent context.");
        }
    };

    ws.onerror = function(event) {
        console.error("WebSocket error:", event);
    };

    ws.onclose = function() {
        console.log("WebSocket connection closed.");
    };

    $(document).on("click", "#update-reserve-price", function(event) {
        event.preventDefault();
        $("#update-reserve-price").attr("disabled", true);

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        var row = $(this).closest('tr');

        var url = '<?= @basePath ?>USER/BiddingSession/UpdateReservePrice';
        var formmethod = 'post';
        var formdata = {
            auction_id: row.find('[name="auction_id"]').val(),
            reserve_price: row.find('[name="reserve_price"]').val(),
            auctionitem_id: row.find('[name="auctionitem_id"]').val(),
            base_price: row.find('[name="base_price"]').val() ?? 0,
            valuation_price: row.find('[name="valuation_price"]').val(),

        };

        var allFieldsFilled = true; // Variable to track if all fields are filled

        var reserve_price_input = row.find('[name="reserve_price"]').val();
        // Regular expression to match numeric values
        var numericPattern = /^\d*\.?\d+$/;

        // Validate reserve price
        if (!numericPattern.test(reserve_price_input)) {
            row.find('[name="reserve_price"]').closest('td').append('<span class="error">Reserve Price must be a valid number</span>');
            allFieldsFilled = false;
        }

        console.log(parseFloat(reserve_price_input));
        console.log(parseFloat(row.find('[name="base_price"]').val()));
        if (parseFloat(row.find('[name="base_price"]').val()) >= parseFloat(reserve_price_input)) {
            Toast.fire({
                icon: 'error',
                title: 'Reserve Price must be greater than base price (' + parseFloat(row.find('[name="base_price"]').val()) + ')',
                timer: 4000,
                timerProgressBar: true
            });
            allFieldsFilled = false;
        }
        var reserve_price = parseFloat(reserve_price_input);

        // Check if all fields are filled
        if (!allFieldsFilled) {
            $("#update-reserve-price").attr("disabled", false);
            return;
        }

        $.ajax({
            url: url,
            type: formmethod,
            data: formdata,
            success: function(_response) {
                Toast.fire({
                    icon: 'success',
                    title: 'Reserve Price Updated',
                    timer: 3000, // Display for 1 second
                    timerProgressBar: true // Optional: Show a progress bar for the timer
                });
            },
            error: function(_response) {
                var data = $.parseJSON(_response.responseText);

                $('.error').remove();
                if (_response.status === 422) {
                    var errors = $.parseJSON(_response.responseText);
                    error = errors.errors;
                    $.each(data.errors, function(key, value) {
                        var modifiedKey = key.includes('.') ? key.split('.').join('\\.') : key;
                        if ($("input#" + modifiedKey).length != 0)
                            $("input#" + modifiedKey).after('<span class="error ">' + value + "</span>");
                        else if ($("select#" + modifiedKey).length != 0)
                            $("select#" + modifiedKey).after('<span class="error">' + value + "</span>");
                        else
                            $("#" + modifiedKey).after('<span class="error">' + value + "</span>");
                    });
                } else if (_response.status === 500) {
                    Toast.fire({
                        icon: 'error',
                        title: 'An Error Occoured',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            },
            complete: function() {
                // Re-enable the submit button after the request is complete
                $("#update-reserve-price").attr("disabled", false);
            }
        });
    });
</script>