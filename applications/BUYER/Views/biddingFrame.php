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

        /* Circular Timer Styles */
        .circular-timer {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto;
        }

        .circular-timer .circle {
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
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
            font-size: 24px;
            text-anchor: middle;
            dominant-baseline: middle;
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
    <input type="hidden" value="<?php echo @$response_data[0]['min_hour_over']; ?>" id="min_hour_over_session">
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
                <th title="Last Sold Price">LSP</th>
                <th title="Highest Bidding price">HBP</th>
                <th>Status</th>
                <th style="width:5%;">Bidding Quantity</th>
                <th style="width:7%;">Bid</th>
                <th style="width:7%;">Auto</th>
                <th>Delete </th>
                <th>Action </th>

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

                //echo '<pre>';print_r($auction_items);exit;

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
                    $current_auct = 0;
                    if ($response_data[0]['min_hour_over'] == 1) {
                        if ($value['auction_status'] == 'current') {
                            $current_auct = 1;
                        }
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
                        <td style="background-color: #0131e8;"><?php echo @$value['valuation_price']; ?></td>
                        <td style="background-color: #0131e8;"><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                        <td id="highestbid_<?php echo $value['id']; ?>" <?php if ($current_auct == 1 && $value['high_buyer_id'] == session()->get('user_id')) { ?> style="background-color: green;" <?php } ?>><?php echo @$value['bid_price']; ?></td>
                        <td>
                            <?php
                            if ($value['is_withdrawn'])
                                echo "withdrawn";
                            else {
                                if ($response_data[0]['min_hour_over'] == 1) {
                                    if ($value['auction_status'] == 'current') {
                                        echo 'Live';
                                    } elseif (@$value['auction_status'] == 'completed') {
                                        if ($value['bid_price'] < $value['reverse_price'])
                                            echo 'Unsold';
                                        else
                                            echo 'Sold';
                                    } else {
                                        echo 'Pending';
                                    }
                                } else
                                    echo '<span class="btn btn-danger">Minimum</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <input type="text" readonly value="<?php echo @$value['auction_quantity']; ?>" class="form-control" />
                            <?php
                            if ($value['auction_status'] == 'current') {
                            ?>
                                <input type="hidden" readonly value="<?php echo @$value['auctLotSet']; ?>" class="form-control auction_lot_set" />
                            <?php
                            }
                            ?>
                        </td>
                        <td>
                            <input type="hidden" name="center_id" id="center_id_<?php echo $value['id']; ?>" value="<?php echo @$response_data[0]['center_id']; ?>" />
                            <input type="hidden" name="buyer_id" id="buyer" value="<?php echo session()->get('user_id'); ?>" />
                            <input type="hidden" name="auction_item_id" id="auction_item_id_<?php echo $value['id']; ?>" value="<?php echo @$value['id']; ?>" />
                            <input type="hidden" name="auction_id" id="auction_id_<?php echo $value['id']; ?>" value="<?php echo @$value['auction_id']; ?>" />
                            <input type="hidden" id="basebid_<?php echo $value['id']; ?>" value="<?php echo @$value['base_price']; ?>" />
                            <?php
                            if ($response_data[0]['min_hour_over'] == 1) {
                                if ($completed != 'yes') {
                            ?>
                                    <div class="input-groups d-flex" style="width:0%">
                                        <input type="number" name="min_price" <?php if ($completed == 'yes' || $value['is_withdrawn'] == 1) { ?> readonly <?php } ?> id="result_<?php echo $value['id']; ?>" class="form-control bidPriceInput message-receiver min-bid-input" value="<?php echo @$value['min_price']; ?>" />
                                        <span class="input-groups-append">
                                            <i class="fa fa-caret-up plus-btn 
                                    <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                        echo 'disabled';
                                    } ?>" aria-hidden="true">
                                            </i>
                                            <i class="fa fa-caret-down minus-btn 
                                    <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                        echo 'disabled';
                                    } ?>" aria-hidden="true">
                                            </i>
                                        </span>
                                        <span class="input-group-append">
                                            <button class="btn btn-success manual_bid_save" onclick="sendMessage(<?php echo $value['id']; ?>,1)" step="0.01" id="update-reserve-price_<?php echo $value['id']; ?>" <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) { ?> disabled <?php } ?>>
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </span>
                                        <input type="hidden" id="myLastBidPrice<?php echo $value['id']; ?>" value="">
                                        <input type="hidden" id="bidWonBy<?php echo $value['id']; ?>" value="">
                                    </div>
                                <?php
                                } else
                                    echo '-';
                            } else {
                                ?>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control min-bid-input" name="min_price" value="<?php echo $value['min_price']; ?>" id="result_<?php echo $value['id']; ?>" <?php echo ($value['is_withdrawn'] == 1) ? 'readonly' : ''; ?>>
                                    <span class="input-group-append">
                                        <button type="button" id="min_bid_id_<?php echo $value['id']; ?>" class="btn btn-success btn-flat save_min_bid" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                            <i class="fa fa-save" title="Bid<?php echo $value['id']; ?>"></i>
                                        </button>
                                    </span>
                                </div>
                            <?php
                            }
                            ?>
                        </td>

                        <td>
                            <?php
                            if ($response_data[0]['min_hour_over'] == 1) {
                            ?>
                                <div class="input-groups d-flex" style="width:0%">
                                    <input type="hidden" name="max_bid1" id="max_bid_val1_<?php echo $value['id']; ?>" class="form-control" value="<?php echo @$value['max_price']; ?>" />
                                    <input type="number" name="max_bid" <?php if ($completed == 'yes'  || $value['is_withdrawn'] == 1) { ?> readonly <?php } ?> id="max_bid_val_<?php echo $value['id']; ?>" class="form-control max-bid-input" value="<?php echo @$value['max_price']; ?>" />
                                    <span class="input-groups-append">
                                        <i class="fa fa-caret-up plus-btn <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                                                                echo 'disabled';
                                                                            } ?>" aria-hidden="true">
                                        </i>
                                        <i class="fa fa-caret-down minus-btn 
                                    <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) {
                                        echo 'disabled';
                                    } ?>" aria-hidden="true">
                                        </i>
                                    </span>
                                    <span class="input-group-append">
                                        <button type="button" id="max_bid_id_<?php echo $value['id']; ?>" id="autobidMax_<?php echo $value['id']; ?>" class="btn btn-success btn-flat save_max_bid" <?php if ((@$completed == 'yes') || $value['is_withdrawn'] == 1) { ?> disabled <?php } ?>>
                                            <i class="fa fa-save" title="Autobid"></i>
                                        </button>
                                    </span>
                                </div>

                            <?php } else {
                            ?>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control max-bid-input" name="max_bid" value="<?php echo $value['max_price']; ?>" id="max_bid_val_<?php echo $value['id']; ?>" <?php echo ($value['is_withdrawn'] == 1) ? 'readonly' : ''; ?>>
                                    <span class="input-group-append">
                                        <button type="button" id="max_bid_id_<?php echo $value['id']; ?>" class="btn btn-danger btn-flat save_max_bid" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                            <i class="fa fa-save" title="Autobid"></i>
                                        </button>
                                    </span>
                                </div>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if ($response_data[0]['min_hour_over'] != 1) {
                            ?>
                                <button type="button" title="Delete" id="delete_<?php echo $value['id']; ?>" class="btn btn-sm btn-danger delete-biddata" onclick="parent.deleteBidData(<?php echo $value['id']; ?>)" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td id="is_catalog_<?php echo @$value['id']; ?>">
                            <input type="checkbox" id="heart-checkbox-<?php echo @$value['id']; ?>" class="checkbox" style="display: none" onchange="Catalogchecked(<?php echo @$value['id']; ?>);">
                            <label id="heart-<?php echo @$value['id']; ?>" for="heart-checkbox-<?php echo @$value['id']; ?>" <?php if ($value['buyer_catalog_count'] == 1) { ?> style="color: blue" <?php } else { ?> style="color: white" <?php } ?>>
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            </label> &nbsp;
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

        $('.min-bid-input, .max-bid-input').on('keyup', function() {
            var value = $(this).val();

            // Remove any characters other than numbers and decimals
            value = value.replace(/[^0-9]/g, '');

            // Prevent more than one decimal point
            // value = value.replace(/(\..*)\./g, '$1');

            // Limit to two decimal places
            // if (value.indexOf('.') !== -1) {
            //     var parts = value.split('.');
            //     parts[1] = parts[1].substring(0, 2); // Allow only 2 decimal places
            //     value = parts.join('.');
            // }

            if (value.length > 6) {
                value = value.substring(0, 6);
            }

            $(this).val(value); // Set the cleaned-up value back to the input field
        });

        $('.plus-btn').on('click', function() {
            if ($(this).hasClass('disabled')) return;
            var inputField = $(this).closest('.input-groups').find('input[type="number"]');
            var currentValue = parseFloat(inputField.val()) || 0;
            inputField.val(currentValue + <?php echo $response_data[0]['auction_items'][0]['settings']; ?>);

        });


        $('.minus-btn').on('click', function() {
            if ($(this).hasClass('disabled')) return;
            var inputField = $(this).closest('.input-groups').find('input[type="number"]');
            var currentValue = parseFloat(inputField.val()) || 0;
            if (currentValue > 0) {
                inputField.val(currentValue - <?php echo $response_data[0]['auction_items'][0]['settings']; ?>);
            }
        });


        $('.save_max_bid').on('click', function() {
            var min_hour_over_session = $('#min_hour_over_session').val();
            var mainInputValue = $(parent.document).find('#active_session').val();

            // alert(manual_bid_over);
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            var id = $(this).attr('id');
            let parts = id.split('_');
            var auction_item_id = parts[3];
            var auction_id = $('#auction_id_' + parts[3]).val();
            var buyer_id = document.getElementById("buyer").value;
            var max_amt = $('#max_bid_val_' + parts[3]).val();
            var max_amt_existing = $('#max_bid_val1_' + parts[3]).val();
            var url = '<?= @basePath ?>BUYER/addMaxBid';
            var formmethod = 'post';
            var high_bid = $("#highestbid_" + auction_item_id).text();

            var max_bid_amount = parseFloat(max_amt);
            var max_bid_amount_existing = parseFloat(max_amt_existing);
            var highest_bid = parseFloat(high_bid);
            if (max_bid_amount <= highest_bid) {
                Toast.fire({
                    icon: 'error',
                    title: 'Auto bid value must be greater than Highest Bid price',
                    timer: 1000,
                    timerProgressBar: true
                });
                return false;
            }

            // if (max_bid_amount <= max_bid_amount_existing) {
            //     Toast.fire({
            //         icon: 'error',
            //         title: 'Auto bid value must be greater than Existing price',
            //         timer: 1000,
            //         timerProgressBar: true
            //     });
            //     return false;
            // }
            var base_price = $("#basebid_" + parts[3]).val();
            if (parseFloat(max_bid_amount) < parseFloat(base_price)) {
                Toast.fire({
                    icon: 'error',
                    title: 'Value must be greater than base price',
                    timer: 1000,
                    timerProgressBar: true
                });
                return false;
            }

            if (min_hour_over_session == 0) {
                check_highest_bid = (highest_bid > 0) ? parseFloat(highest_bid) : parseFloat(base_price);
                check_price = parseFloat(check_highest_bid + (check_highest_bid / 2));
            } else {
                if (highest_bid > 0) {
                    check_price = parseFloat(highest_bid) + parseFloat(highest_bid / 2);
                } else {
                    check_price = parseFloat(base_price) + parseFloat(base_price / 2);
                }
            }

            if (max_bid_amount > check_price) {
                parent.Swal.fire({
                    title: 'Max Bid Warning',
                    text: 'The Autobid value is more than 50% greater than the highest bid or base price. Do you want to proceed?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitAutoBid();
                    } else {
                        $('#max_bid_val_' + parts[3]).val('');
                    }
                });
            } else {
                submitAutoBid(); // No need for warning, proceed directly
            }

            function submitAutoBid() {
                if (mainInputValue == 2 || mainInputValue == 3) {
                    sendMessage(auction_item_id, 2);
                } else {
                    $.ajax({
                        url: url,
                        type: formmethod,
                        data: {
                            'auction_id': auction_id,
                            'auction_item_id': auction_item_id,
                            'buyer_id': buyer_id,
                            'max_amt': max_amt,
                        },
                        dataType: 'JSON',
                        success: function(_response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Auto Bid Added Successfully.',
                                timer: 300, // Display for 0.3 seconds
                                timerProgressBar: true
                            });

                            $('#max_bid_val1_' + parts[3]).val(max_amt); // Reset and update max bid value
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError(xhr);
                        }
                    });
                }
            }

            function handleAjaxError(xhr) {
                var data = $.parseJSON(xhr.responseText);
                $('.error').remove();

                if (xhr.status === 422) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Value must be higher',
                        timer: 1000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Internal Server Error',
                    });
                }
            }


        });

        $('.save_min_bid').on('click', function() {
            var min_hour_over_session = $('#min_hour_over_session').val();
            var mainInputValue = $(parent.document).find('#active_session').val();

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            var id = $(this).attr('id');
            let parts = id.split('_');
            var auction_item_id = parts[3];
            var auction_id = $('#auction_id_' + parts[3]).val();
            var buyer_id = $('input[name="buyer_id"]').val();
            var min_amt = $('#result_' + parts[3]).val();
            var url = '<?= @basePath ?>BUYER/addMinBid';
            var formmethod = 'post';
            var base_price = $("#basebid_" + parts[3]).val();
            // console.log(min_amt);
            // console.log(base_price);
            var high_bid = $("#highestbid_" + auction_item_id).text();
            var highest_bid = parseFloat(high_bid);

            if (parseFloat(min_amt) < parseFloat(base_price)) {
                Toast.fire({
                    icon: 'error',
                    title: 'Value must be greater than base price',
                    timer: 1000,
                    timerProgressBar: true
                });
                return false;
            }


            if (min_hour_over_session == 0) {
                check_highest_bid = (highest_bid > 0) ? parseFloat(highest_bid) : parseFloat(base_price);
                check_price = parseFloat(check_highest_bid + (check_highest_bid / 2));
            } else {
                if (highest_bid > 0) {
                    check_price = parseFloat(highest_bid) + parseFloat(highest_bid / 2);
                } else {
                    check_price = parseFloat(base_price) + parseFloat(base_price / 2);
                }
            }

            if (parseFloat(min_amt) > check_price) {
                parent.Swal.fire({
                    title: 'Max Bid Warning',
                    text: 'The Bid value is more than 50% greater than the highest bid or base price. Do you want to proceed?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitBid();
                    } else {
                        $('#result_' + parts[3]).val('');
                    }
                });
            } else {
                submitBid(); // No need for warning, proceed directly
            }

            function submitBid() {
                if (mainInputValue == 2 || mainInputValue == 3) {
                    sendMessage(auction_item_id, 2);
                } else {
                    $.ajax({
                        url: url,
                        type: formmethod,
                        data: {
                            'auction_id': auction_id,
                            'auction_item_id': auction_item_id,
                            'buyer_id': buyer_id,
                            'min_amt': min_amt,
                        },
                        dataType: 'JSON',
                        success: function(_response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Bid Added Successfully.',
                                timer: 1000, // Display for 1 second
                                timerProgressBar: true // Optional: Show a progress bar for the timer
                            });
                            console.log(_response);
                        },
                        error: function(xhr, status, error) {
                            handleAjaxError1(xhr);
                        }
                    });
                }
            }

            function handleAjaxError1(xhr) {
                var data = $.parseJSON(xhr.responseText);
                $('.error').remove();

                if (xhr.status === 422) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Value must be higher',
                        timer: 1000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Internal Server Error',
                    });
                }
            }

        });
    });

    function Catalogchecked(id) {
        var checkbox = document.getElementById('heart-checkbox-' + id);
        var row = $(checkbox).closest('tr');

        var formdata = {};
        formdata['is_checked'] = checkbox.checked ? 1 : 0;
        formdata['auction_item_id'] = row.find('input[name="auction_item_id"]').val();
        formdata['auction_id'] = row.find('input[name="auction_id"]').val();
        formdata['buyer_id'] = row.find('input[name="buyer_id"]').val();

        var url = '<?= @basePath ?>BUYER/addtoCatalog';
        var formmethod = 'post';

        $.ajax({
            url: url,
            type: formmethod,
            data: formdata,
            dataType: 'JSON',
            success: function(_response) {
                console.log(_response)
                if (_response.message == 'Inserted') {
                    $('#is_catalog_' + id).empty();

                    $('#is_catalog_' + id).append('<input type="checkbox" id="heart-checkbox-' + id + '" class="checkbox" style="display: none" onchange="Catalogchecked(' + id + ');">' +
                        '<label id="heart-' + id + '" for="heart-checkbox-' + id + '" style="color: blue">' +
                        '<i class="fa fa-heart" aria-hidden="true"></i>' +
                        '</label>');
                } else if (_response.message == 'Deleted') {
                    $('#is_catalog_' + id).empty();

                    $('#is_catalog_' + id).append('<input type="checkbox" id="heart-checkbox-' + id + '" class="checkbox" style="display: none" onchange="Catalogchecked(' + id + ');">' +
                        '<label id="heart-' + id + '" for="heart-checkbox-' + id + '">' +
                        '<i class="fa fa-heart" aria-hidden="true"></i>' +
                        '</label>');
                }
                parent.reloadFirstIframe();

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#myIframe').attr('src', function(i, val) {
                    return val;
                });
                var iframe = $('#myIframe');
                iframe.attr('src', iframe.attr('src'));
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'error',
                    title: 'An error occurred while adding to catalog',
                    timer: 1000,
                    timerProgressBar: true
                });
            }
        });
    }
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
        console.log("Message received: ", parsedMessage);
        var buyerId = document.getElementById("buyer").value;
        var highestBuyerId = parsedMessage.buyer_id;
        var bidValue = parsedMessage.bid_value;
        var item_id = parsedMessage.auction_item_value;
        var updated_time = parsedMessage.updated_time;

        var current_time = parsedMessage.current_time;
        $(parent.document).find("#last_log_value").val(current_time);

        // Check if parts is defined before using it
        // if (!parts) {
        //     console.error("Parts is not defined.");
        //     return;
        // }


        $("#highestbid_" + item_id).text(bidValue);
        $("#auction_bid_price_" + item_id).text(bidValue);
        $("#bidWonBy" + item_id).val(highestBuyerId);
        document.getElementById("result_" + item_id).value = bidValue;
        document.getElementById("highestbid_" + item_id).value = bidValue;

        $(parent.document).find("#last_log").text(updated_time);
        $(parent.document).find("#last_log_time").val(updated_time);
        var myLastBidPrice = parseFloat(document.getElementById("myLastBidPrice" + item_id).value);
        // alert(highestBuyerId);
        var BidWonBy = parseFloat(document.getElementById("bidWonBy" + item_id).value);

        // if (parseFloat(myLastBidPrice) >= parseFloat(bidValue) && ($("#buyer").val() == BidWonBy)) {
        if (($("#buyer").val() == BidWonBy)) {
            $("#highestbid_" + item_id).css("background-color", "green"); // Change background color to Green
        } else {
            $("#highestbid_" + item_id).css("background-color", ""); // No Color
        }
        //if(parseFloat(buyerId) == parseFloat(highestBuyerId)) {
        //if(parseFloat(myLastBidPrice) >= parseFloat(bidValue)) {
        //$("#highestbid_" + item_id).css("background-color", "green"); // Change background color to Green
        //} else {
        //$("#highestbid_" + item_id).css("background-color", ""); // No Color
        //}
        //} else {
        //$("#highestbid_" + item_id).css("background-color", ""); // No Color
        //}

        parent.reloadFirstIframe();
        parent.reloadmybookIframe();
        // var mainInputValue = $(parent.document).find('#active_session').val();
        // if (mainInputValue == 2)
        //     parent.resetSession1Timer();
        // if (typeof parent.startActivityTimer === 'function') {
        //     if (mainInputValue == 2)
        //         parent.startActivityTimer();
        // } else {
        //     console.error("resetActivityTimer is not a function in the parent context.");
        // }
    };

    ws.onerror = function(event) {
        console.error("WebSocket error:", event);
    };

    ws.onclose = function() {
        console.log("WebSocket connection closed.");
    };

    function sendMessage(id, bid_type) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        console.log(id);
        global_id = id;

        var buyer_id = document.getElementById("buyer").value;
        var value2 = (bid_type == 1) ? document.getElementById("result_" + id).value : document.getElementById("max_bid_val_" + id).value;
        var max_value = $("#highestbid_" + id).text();
        var base_price = $("#basebid_" + id).val();
        var value3 = document.getElementById("auction_item_id_" + id).value;
        document.getElementById("myLastBidPrice" + id).value = value2;

        var bidValue = parseFloat(value2);
        var maxBidValue = parseFloat(max_value);

        if (bidValue <= maxBidValue) {
            Toast.fire({
                icon: 'error',
                title: 'Value must be higher',
                timer: 1000,
                timerProgressBar: true
            });
            return false;
        }
        if (value2 == '' || value2 == 0) {
            Toast.fire({
                icon: 'error',
                title: 'Please enter amount',
                timer: 1000,
                timerProgressBar: true
            });
            return false;
        }
        if (bidValue < parseFloat(base_price)) {
            Toast.fire({
                icon: 'error',
                title: 'Value must be greater than base price',
                timer: 1000,
                timerProgressBar: true
            });
            return false;
        }

        var high_bid = $("#highestbid_" + id).text();
        var highest_bid = parseFloat(high_bid);
        var check_price;

        if (min_hour_over_session == 0) {
            check_highest_bid = (highest_bid > 0) ? parseFloat(highest_bid) : parseFloat(base_price);
            check_price = parseFloat(check_highest_bid + (check_highest_bid / 2));
        } else {
            check_price = (highest_bid > 0) ? parseFloat(highest_bid) + parseFloat(highest_bid / 2) : parseFloat(base_price) + parseFloat(base_price / 2);
        }

        // Use min_amt or max_amt based on bid_type
        var amtToCheck = bidValue;

        if (parseFloat(amtToCheck) > check_price) {
            parent.Swal.fire({
                title: 'Max Bid Warning',
                text: 'The Autobid value is more than 50% greater than the highest bid or base price. Do you want to proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    placeBid(bid_type, buyer_id, value2, value3, id);
                } else {
                    (bid_type == 1) ? $('#result_' + id).val('') : $('#max_bid_val_' + id).val('');
                }
            });
        } else {
            placeBid(bid_type, buyer_id, value2, value3, id);
        }
    }

    function placeBid(bid_type, buyer_id, value2, value3, id) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var message = {
            buyer_id: buyer_id,
            bid_type: bid_type,
            bid_value: parseFloat(value2).toFixed(2),
            auction_item_value: value3,
            parts: parts,
        };

        if (bid_type == 2) {
            $('#max_bid_val1_' + id).val('');
            $('#max_bid_val1_' + id).val(parseFloat(value2));
        }
        $("#highestbid_" + id).text(parseFloat(value2).toFixed(2));

        // Set the message title based on bid_type
        var title = (bid_type == 1) ? 'Bid Placed Successfully.' : 'Auto Bid Placed Successfully.';
        Toast.fire({
            icon: 'success',
            title: title,
            timer: 300,
            timerProgressBar: true
        });

        ws.send(JSON.stringify(message));
        parent.reloadFirstIframe();
        parent.reloadmybookIframe();
        console.log("Message sent: ", message); // Debugging log
    }


    function reloadIframe(iframeId, src, interval) {

        setInterval(function() {
            var iframe = document.getElementById('myIframe');
            iframe.src = src;
            Toast.fire({
                icon: 'error',
                title: src,
                timer: 1000,
                timerProgressBar: true
            });
        }, 1000);
    }
    $(".bidPriceInput").on('change', function() {
        let value = $(this).val();

        value = value.replace(/[^0-9.]/g, '');
        let parts = value.split('.');

        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
            parts = value.split('.');
        }
        if (parts.length === 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        } else if (parts.length === 1) {
            value = parts[0] + '.00';
        }

        $(this).val(value);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {


        const productTableBody = document.getElementById("buyers-data").getElementsByTagName("tbody")[0];
        const dateTimeElement = document.getElementById("datetime");
        const circle = document.querySelector('.circle');

        let batchIndex = 0;
        let products = [];
        let activityTimer = 10;
        let activityInterval;

        function updateProductStatuses() {
            products.forEach((product, index) => {
                if (index >= batchIndex * 5 && index < (batchIndex + 1) * 5) {
                    product.status = "Live";
                } else if (index < batchIndex * 5) {
                    product.status = "Completed";
                } else {
                    product.status = "Pending";
                }
            });

            // Sort products: Live first, then Pending, then Completed
            products.sort((a, b) => {
                const statusOrder = {
                    "Live": 1,
                    "Pending": 2,
                    "Completed": 3
                };
                return statusOrder[a.status] - statusOrder[b.status];
            });
        }

        function renderProducts() {
            // Calling for the idle activity for 10 sec
            console.log("timeup");
        }

        // function updateDateTime() {
        //     const now = new Date();
        //     dateTimeElement.textContent = now.toLocaleString();
        // }



        // function resetActivityTimer() {

        //     startActivityTimer();
        // }

        function startActivityTimer() {
            clearInterval(activityInterval);
            activityInterval = setInterval(() => {
                activityTimer--;
                if (activityTimer < 0) {
                    clearInterval(activityInterval);
                    completeLiveProducts();
                    batchIndex++;
                    if (batchIndex < Math.ceil(products.length / 5)) {
                        updateProductStatuses();
                        resetActivityTimer();
                    }
                    //checkForEntries();
                }
            }, 1000);
        }



        function completeLiveProducts() {

            renderProducts();
        }

        window.sendMessage = sendMessage;


        //updateDateTime();

        // Start activity timer
        // resetActivityTimer();

        // Update date and time every second
        //setInterval(updateDateTime, 1000);

    });
</script>