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

        .error {
            color: red !important;
        }

        #buyers-data {
            height: 300px !important;
            overflow-y: auto;
        }

        .favorited {
            color: red !important;
        }

        .heart-checkbox {
            cursor: pointer;
        }

        input {
            border: 1px solid #000 !important;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?= @$header ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="modal fade" id="modal-sm-auto">
                <div class="modal-dialog modal-l">
                    <div class="modal-content" id="autobid_form">
                        <!-- user_form -->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!-- Main content -->
            <section class="content user-data-info-view">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mt-3">
                                <!-- /.card-header -->
                                <div class="card-body">



                                    <!-- <table class="table table-striped">
                                        <tr>
                                            <td><h3 class="card-title">Auction Catalog</h3></td>
                                            <td style="text-align: right;">
                                                <button class="btn btn-success">Add to catalog</button>
                                            </td>
                                        </tr>
                                    </table> -->
                                    <div class="our-session-info-data">


                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="buyers-data">
                                                <thead>
                                                    <tr style="background-color: #010d23;color:#FFFFFF">
                                                        <th>LotNo</th>
                                                        <th>Mark</th>
                                                        <th>Grade</th>
                                                        <th>No.of Bags</th>
                                                        <th>Each Net</th>
                                                        <th>Total Net</th>
                                                        <th>Base Price</th>
                                                        <th>Valuation Price</th>
                                                        <th>Last Sold Price</th>
                                                        <?php
                                                        if ($flag == 1) {
                                                        ?>
                                                            <th>Highest Bidding price</th>
                                                        <?php } ?>

                                                        <?php
                                                        if (@$flag != 1) { ?>
                                                            <th>Bid</th>
                                                            <th>Auto</th>
                                                            <th>Delete</th>
                                                        <?php } ?>
                                                        <th>Status</th>
                                                        <th class="action-column"><?php if (@$flag == 1) echo "Buyer";
                                                                                    else echo "Action"; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($response_data)) :
                                                        $m = 1;
                                                        // echo '<pre>';print_r($response_data);exit;
                                                        foreach ($response_data as $key => $value) :
                                                            // echo '<pre>';print_r($response_data);exit;
                                                    ?>
                                                            <tr>
                                                                <td><?php echo @$value['lot_no']; ?></td>
                                                                <td><?php echo @$value['gardenname']; ?></td>
                                                                <td><?php echo @$value['gradename']; ?></td>
                                                                <td><?php echo @$value['auction_quantity']; ?></td>
                                                                <td><?php echo @$value['weight_net']; ?></td>
                                                                <td><?php echo number_format(@$value['weight_net'] * @$value['auction_quantity'], 2, '.', ','); ?></td>
                                                                <td><?php echo @$value['base_price']; ?></td>
                                                                <td><?php echo @$value['valuation_price']; ?></td>
                                                                <td><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                                                                <?php
                                                                $bid_price = isset($value['bid_price']) ? $value['bid_price'] : '0';
                                                                $status = '';

                                                                if ($flag == 1) {

                                                                    if ($bid_price >= @$value['reverse_price'] && $bid_price != 0) {
                                                                        $highest_bidder_name = isset($value['highest_bidder_name']) ? $value['highest_bidder_name'] : '-';
                                                                        $bid_price1 = isset($value['bid_price']) ? $value['bid_price'] : '-';
                                                                        $status = '<span class="badge badge-success">Sold</span>';
                                                                    } else {
                                                                        $highest_bidder_name = '-';
                                                                        $bid_price1 = '-';
                                                                        $status = '<span class="badge badge-danger">Unsold</span>';
                                                                    }
                                                                } else {
                                                                    $highest_bidder_name = '-';
                                                                    $bid_price1 = '-';
                                                                    $status = '<span class="badge badge-info">Pending</span>';
                                                                }
                                                                if (isset($value['is_withdrawn']) && $value['is_withdrawn'] == 1) {
                                                                    $highest_bidder_name = '-';
                                                                    $bid_price1 = '-';
                                                                    $status = '<span class="badge badge-warning">Withdrawn</span>';
                                                                }
                                                                ?>
                                                                <input type="hidden" name="auction_item_id" value="<?php echo @$value['id']; ?>">
                                                                <input type="hidden" name="auction_id" value="<?php echo @$value['auction_id']; ?>">
                                                                <input type="hidden" name="buyer_id" value="<?php echo session()->get('user_id'); ?>">
                                                                <input type="hidden" id="basebid_<?php echo $value['id']; ?>" value="<?php echo @$value['base_price']; ?>" />

                                                                <?php
                                                                if ($flag == 1) {
                                                                ?>

                                                                    <td>
                                                                        <?php echo $bid_price1; ?>

                                                                    </td>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                if (@$flag != 1) { ?>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" class="form-control min-bid-input" name="min_bid" value="<?php echo $value['min_price']; ?>" id="min_bid_val_<?php echo $value['id']; ?>" <?php echo ($value['is_withdrawn'] == 1) ? 'readonly' : ''; ?>>
                                                                            <span class="input-group-append">
                                                                                <button type="button" id="min_bid_id_<?php echo $value['id']; ?>" class="btn btn-success btn-flat save_min_bid" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                                                                    <i class="fa fa-save" title="Bid<?php echo $value['id']; ?>"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" class="form-control max-bid-input" name="max_bid" value="<?php echo $value['max_price']; ?>" id="max_bid_val_<?php echo $value['id']; ?>" <?php echo ($value['is_withdrawn'] == 1) ? 'readonly' : ''; ?>>
                                                                            <span class="input-group-append">
                                                                                <button type="button" id="max_bid_id_<?php echo $value['id']; ?>" class="btn btn-danger btn-flat save_max_bid" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                                                                    <i class="fa fa-save" title="Autobid"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" title="Delete" id="delete_<?php echo $value['id']; ?>" class="btn btn-sm btn-danger delete-biddata" onclick="deleteBidData(<?php echo $value['id']; ?>)" <?php echo ($value['is_withdrawn'] == 1) ? 'disabled' : ''; ?>>
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </td>
                                                                <?php } ?>
                                                                <td>
                                                                    <?php echo $status; ?>
                                                                </td>
                                                                <td class="action-column" id="is_catalog_<?php echo @$value['id']; ?>">
                                                                    <?php if ($flag == 1) : ?>
                                                                        <?php echo $highest_bidder_name; ?>
                                                                    <?php else : ?>
                                                                        <input type="checkbox" id="heart-checkbox-<?php echo @$value['id']; ?>" class="checkbox" style="display: none" onchange="Catalogchecked(<?php echo @$value['id']; ?>);">
                                                                        <?php
                                                                        $isCatalog = isset($value['is_catalog']) ? $value['is_catalog'] : 0;
                                                                        $style = $isCatalog == 1 ? 'color: red;' : '';
                                                                        ?>
                                                                        <label class="heart-checkbox" id="heart-<?php echo @$value['id']; ?>" for="heart-checkbox-<?php echo @$value['id']; ?>" style="<?php echo $style; ?>">
                                                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                                                        </label> &nbsp;
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>

                                                        <?php
                                                            $m++;
                                                        endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>


                                        </div>

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
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?= @$data['footer']; ?>
        <script src="<?= @basePath ?>admin_assets/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?= @basePath ?>admin_assets/dist/js/common.js"></script>
        <script src="<?= @basePath ?>admin_assets/plugins/toastr/toastr.min.js"></script>

        <script>
            // $(document).ready(function() {
            //     // Assuming end time is stored in a variable named 'end_time'
            //     var endTime = '<?php // echo $value['date'] . ' ' . $value['end_time']; 
                                    ?>'; // Assuming $value['end_time'] contains the end time in HH:MM:SS format

            //     // Get the current time
            //     var currentTime = new Date();
            //     var currentHour = currentTime.getHours();
            //     var currentMinute = currentTime.getMinutes();
            //     var currentSecond = currentTime.getSeconds();

            //     // Format current time to match the end time format
            //     var formattedCurrentTime = currentHour + ":" + currentMinute + ":" + currentSecond;

            //     // Split the time strings into arrays for comparison
            //     var endTimeArray = endTime.split(/[- :]/);
            //     var currentTimeArray = formattedCurrentTime.split(':');

            //     // Convert time strings to comparable format (total seconds)
            //     var endTimeInSeconds = (+endTimeArray[3]) * 60 * 60 + (+endTimeArray[4]) * 60 + (+endTimeArray[5]);
            //     var currentTimeInSeconds = (+currentTimeArray[0]) * 60 * 60 + (+currentTimeArray[1]) * 60 + (+currentTimeArray[2]);

            //     // Compare current time with end time
            //     if (currentTimeInSeconds > endTimeInSeconds) {
            //         // Hide action column and data rows
            //         $('#buyers-data th:last-child, #buyers-data td:last-child').hide();
            //     }
            // });

            // JavaScript for countdown timer
            function updateCountdown() {
                var endTimeStr = "<?php echo @$response_data[0]['end_time']; ?>";
                var endTimeParts = endTimeStr.split(":");
                var endTime = new Date();
                endTime.setHours(parseInt(endTimeParts[0], 10));
                endTime.setMinutes(parseInt(endTimeParts[1], 10));
                endTime.setSeconds(parseInt(endTimeParts[2], 10));

                var now = new Date().getTime();
                var distance = endTime - now;

                // Calculate remaining hours, minutes, and seconds
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in the element with id="countdown"
                document.getElementById("countdown").innerHTML = hours + "h " +
                    minutes + "m " + seconds + "s ";

                // If the countdown is over, display a message
                if (distance <= 0) {
                    clearInterval(timer);
                    document.getElementById("countdown").innerHTML = "Session Ended";
                }
            }

            // Update the countdown every second
            var timer = setInterval(updateCountdown, 1000);

            // Initial call to display the countdown immediately
            updateCountdown();
        </script>
        <script>
            function deleteBidData(auction_item_id) {
                var auction_id = <?php echo $response_data[0]['auction_id']; ?>;
                var buyer_id = $('input[name="buyer_id"]').val();
                var url = '<?= @basePath ?>BUYER/deleteBidData';
                var formmethod = 'post';
                // console.log(id);
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
                                        window.location.reload(); // Reload the page on success
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

                    $(this).val(value);
                });

                // Plus button click event
                $('.plus-btn').on('click', function() {
                    var inputField = $(this).closest('.input-groups').find('input[type="number"]');
                    var currentValue = parseFloat(inputField.val()) || 0;
                    inputField.val(currentValue + 0.01);
                });

                // Minus button click event
                $('.minus-btn').on('click', function() {
                    var inputField = $(this).closest('.input-groups').find('input[type="number"]');
                    var currentValue = parseFloat(inputField.val()) || 0;
                    if (currentValue > 0) {
                        inputField.val(currentValue - 0.01);
                    }
                });

                function handleBid(isMaxBid, bidType) {
                    var id = $(this).attr('id');
                    let parts = id.split('_');
                    var auction_item_id = parts[3];
                    var auction_id = <?= $response_data[0]['auction_id']; ?>;
                    var buyer_id = $('input[name="buyer_id"]').val();
                    var base_price = $("#basebid_" + auction_item_id).val();
                    var bid_amt = isMaxBid ? $('#max_bid_val_' + auction_item_id).val() : $('#min_bid_val_' + auction_item_id).val();
                    var url = isMaxBid ? '<?= @basePath ?>BUYER/addMaxBid' : '<?= @basePath ?>BUYER/addMinBid';
                    var check_price = parseFloat(base_price) + parseFloat(base_price / 2);

                    if (parseFloat(bid_amt) > check_price) {
                        Swal.fire({
                            title: bidType + ' Warning',
                            text: 'The ' + bidType + ' value is more than 50% greater than the highest bid or base price. Do you want to proceed?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, proceed',
                            cancelButtonText: 'No, cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                submitBid(url, auction_id, auction_item_id, buyer_id, isMaxBid ? bid_amt : null, isMaxBid ? null : bid_amt, isMaxBid ? 'Auto Bid Added Successfully.' : 'Bid Added Successfully.');
                            }else{
                                isMaxBid ? $('#max_bid_val_' + auction_item_id).val('') : $('#min_bid_val_' + auction_item_id).val('')
                            }
                        });
                    } else {
                        submitBid(url, auction_id, auction_item_id, buyer_id, isMaxBid ? bid_amt : null, isMaxBid ? null : bid_amt, isMaxBid ? 'Auto Bid Added Successfully.' : 'Bid Added Successfully.');
                    }
                }

                function submitBid(url, auction_id, auction_item_id, buyer_id, max_amt, min_amt, message) {
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: {
                            'auction_id': auction_id,
                            'auction_item_id': auction_item_id,
                            'buyer_id': buyer_id,
                            'max_amt': max_amt,
                            'min_amt': min_amt,
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: message,
                                timer: 1000,
                                timerProgressBar: true
                            });
                            console.log(response);
                        },
                        error: function(xhr) {
                            handleAjaxError(xhr);
                        }
                    });
                }

                function handleAjaxError(xhr) {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $('.error').remove();

                    if (xhr.status === 422) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Bid price is required.',
                            timer: 1000,
                            timerProgressBar: true
                        });
                    } else if (xhr.status === 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Internal Server Error',
                        });
                    }
                }

                $('.save_min_bid').on('click', function() {
                    handleBid.call(this, false, 'Bid'); // false for min bid
                });

                $('.save_max_bid').on('click', function() {
                    handleBid.call(this, true, 'Auto Bid'); // true for max bid
                });
            });
        </script>
        <script>
            function Catalogchecked(id) {
                var checkbox = document.getElementById('heart-checkbox-' + id);
                var row = $(checkbox).closest('tr');

                var formdata = {};
                formdata['is_checked'] = checkbox.checked ? 1 : 0; // 1 if checked, 0 if unchecked
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
                            // alert('hii');
                            $('#is_catalog_' + id).empty();

                            $('#is_catalog_' + id).append('<input type="checkbox" id="heart-checkbox-' + id + '" class="checkbox" style="display: none" onchange="Catalogchecked(' + id + ');">' +
                                '<label class="heart-checkbox" id="heart-' + id + '" for="heart-checkbox-' + id + '" style="color: red">' +
                                '<i class="fa fa-heart" aria-hidden="true"></i>' +
                                '</label> &nbsp;'
                            );
                        } else if (_response.message == 'Deleted') {
                            // alert('hii');
                            $('#is_catalog_' + id).empty();

                            $('#is_catalog_' + id).append('<input type="checkbox" id="heart-checkbox-' + id + '" class="checkbox" style="display: none" onchange="Catalogchecked(' + id + ');">' +
                                '<label class="heart-checkbox" id="heart-' + id + '" for="heart-checkbox-' + id + '">' +
                                '<i class="fa fa-heart" aria-hidden="true"></i>' +
                                '</label> &nbsp;'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Show error message using native alert
                        alert('An error occurred while adding to catalog');
                    }
                });
            }

            function autoBidding(id, auction_id) {
                $(".loading").show();
                console.log(id);
                $.ajax({
                    type: "post",
                    url: "<?= @basePath ?>BUYER/AutoBidding/View",
                    data: {
                        auctionitem_id: id,
                        auction_id: auction_id,
                    },
                    dataType: 'html',
                    success: function(response) {
                        $(".loading").hide();
                        $("#autobid_form").html(response);
                        $('#modal-sm-auto').modal('show');
                    },
                    error: function(error) {
                        $(".loading").hide();
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "An error occurred while deleting the state.",
                        });
                    }
                });
            }
            $(document).on("click", "#autobid-submit", function(event) {
                event.preventDefault();
                $("#autobid-submit").attr("disabled", true);

                var url = $("#autobid-form").attr("action");
                var formmethod = 'post';
                $.ajax({
                    url: url,
                    type: formmethod,
                    data: $('#autobid-form').serialize(),
                    success: function(_response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Form submitted successfully',
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location.reload(); // Reload the page on success
                            }
                        });
                    },
                    error: function(_response) {
                        var data = $.parseJSON(_response.responseText);
                        $('.error').remove();
                        if (_response.status === 422) {
                            var errors = $.parseJSON(_response.responseText);
                            error = errors.errors;
                            $.each(data.errors, function(key, value) {
                                if ($('input[name=' + key + ']').length != 0)
                                    $('input[name=' + key + ']').after('<span class="error ">' + value + '</span>');
                                else if ($('select[name=' + key + ']').length != 0)
                                    $('select[name=' + key + ']').after('<span class="error">' + value + '</span>');
                                else
                                    $('#' + key).after('<span class="error">' + value + '</span>');
                            });
                        } else if (_response.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Internal Server Error',
                            });
                        }
                    },
                    complete: function() {
                        // Re-enable the submit button after the request is complete
                        $("#autobid-submit").attr("disabled", false);
                    }
                });
            });
        </script>
</body>

</html>