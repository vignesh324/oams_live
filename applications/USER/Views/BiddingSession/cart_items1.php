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
    <!-- BS Stepper -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- Date time picker -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?= @$header ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= @$sidebar ?>
        <?php
        //session()->remove('auction_data');
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/BiddingSession">BiddingSession</a></li>
                                <li class="breadcrumb-item active"><?php echo $title; ?></li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div class="container-fluid">
                    <form id="bidding-session-form" method="post" action="<?= $url; ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-default">

                                    <div class="card-body p-0">

                                        <div class="bs-stepper">
                                            <div class="card-body">
                                                <div class="row invoice-info">
                                                    <div class="col-sm-4 invoice-col">
                                                        Sale No: <strong><?php echo @$auction_data3['sale_no']; ?></strong><br>
                                                        Lot count: <?php echo @$auction_data3['lot_count']; ?><br>
                                                        Auction Date : <?php echo date("d-m-Y", strtotime(@$auction_data3['date'])); ?><br>
                                                    </div>

                                                    <div class="col-sm-4 invoice-col">
                                                        &nbsp;
                                                    </div>

                                                    <div class="col-sm-4 invoice-col">
                                                        <b>Center Name :<?php echo @$auction_data3['center_name']; ?>
                                                        </b><br>
                                                        <b>Auction Start Time:</b> <?php echo @$auction_data3['start_time']; ?><br>
                                                        <b>Auction End Time:</b> <?php echo @$auction_data3['end_time']; ?><br>
                                                        <b>Session Time / lot:</b> <?php echo @$auction_data3['session_time']; ?> <br>
                                                    </div>

                                                </div>
                                                <table class="table table-bordered table-striped auction-items-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Lot no</th>
                                                            <th>Invoice No</th>
                                                            <th>Garden name</th>
                                                            <th>Grade Name</th>
                                                            <th>No of Bags</th>
                                                            <th>Each Nett</th>
                                                            <th>Total Nett</th>
                                                            <th>Sample Quantity</th>
                                                            <th>Warehouse</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if ($auction_data3['type'] == 1) {
                                                            $lot_inc = 1;
                                                        } else {
                                                            $lot_inc = 1001;
                                                        }

                                                        if (!empty($auction_data)) {
                                                            foreach ($auction_data as $key => $auction_item) { ?>
                                                                <tr>
                                                                    <td><?php echo $key + $lot_inc ?></td>
                                                                    <td><?php echo $auction_item['invoice_id'] ?></td>
                                                                    <td><?php echo $auction_item['garden_name'] ?></td>
                                                                    <td><?php echo $auction_item['grade_name'] ?></td>
                                                                    <td>
                                                                        <?php echo $auction_item['cart_qty']; ?>
                                                                        <input type="hidden" class="form-control" name="cart_id[]" id="cart_item_id_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['cart_id'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="auction_id[]" id="auction_id_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['auction_id'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="inward_item_id[]" id="inward_item_id_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['id'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="inward_id[]" id="inward_id_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['inward_id'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="invoice_id[]" id="invoice_id_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['invoice_id'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="qty[]" id="qty_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['cart_qty'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="sample_quantity[]" id="sample_quantity_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['sample_quantity'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" name="auction_each_net[]" id="auction_each_net_<?= @$auction_item['id'] ?>" value="<?= @$auction_item['weight_net'] ?>" readonly>
                                                                        <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">
                                                                    </td>
                                                                    <td><?php echo $auction_item['weight_net'] ?></td>
                                                                    <td><?php echo number_format(($auction_item['cart_qty'] * $auction_item['weight_net'])-$auction_item['sample_quantity'], 3); ?></td>
                                                                    <td><?php echo $auction_item['sample_quantity'] ?></td>
                                                                    <td><?php echo $auction_item['warehouse_name'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="10" class="text-center">No data found</td>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="float-right m-2">
                                                <a href="<?= @basePath ?>USER/BiddingSession/AuctionCartItems/<?php echo $id; ?>" id="back-button" class="btn btn-default me-2">Back</a>
                                                <button type="button" id="save-publish" class="btn btn-primary">Publish</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <?= @$data['footer']; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.stepper = new Stepper(document.querySelector('.bs-stepper'))
            });

            $(document).on('click', '#save-publish', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior
                $("#save-publish").attr("disabled", true);
                var auctionLotCount = <?php echo isset($auction_data3['lot_count']) ? $auction_data3['lot_count'] : '0'; ?>;

                // Check if the selected lot count is greater than or equal to the auction lot count
                // if ($('[name="invoice_id[]"]').length < auctionLotCount) {
                //     // Display an error message
                //     $('.auction-items-table').before('<span class="error" id="auction-items-table-error">Selected Lot count should be greater than or equal to Auction Lot count</span>');
                //     return; // Exit the function if the condition is not met
                // }
                // Show a confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to publish this cart.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'publish!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, proceed with form submission
                        var url = $('#bidding-session-form').attr("action");
                        var formmethod = "post";
                        var formdata = $('#bidding-session-form').serialize();

                        // Submit the form using AJAX
                        $.ajax({
                            url: url,
                            type: formmethod,
                            data: formdata,
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: "Form submitted successfully",
                                }).then((result) => {
                                    // return false;
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href = "<?= @basePath ?>USER/BiddingSession";
                                    }
                                });
                            },
                            error: function(response) {
                                $('.error').remove();
                                if (response.status === 422) {
                                    var errors = response.responseJSON.errors;
                                    $.each(errors, function(key, value) {
                                        if ($("input#" + key).length != 0)
                                            $("input#" + key).after('<span class="error ">' + value + "</span>");
                                        else if ($("select#" + key).length != 0)
                                            $("select#" + key).after('<span class="error">' + value + "</span>");
                                        else
                                            $('#' + key).after('<span class="error">' + value + '</span>');
                                    });
                                } else if (response.status === 500) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Internal Server Error',
                                    });
                                }
                            },
                            complete: function() {
                                // Re-enable the submit button after the request is complete
                                $("#save-publish").attr("disabled", false);
                            }
                        });
                    } else if (result.isDismissed) {
                        $("#save-publish").attr("disabled", false);
                    }
                });
            });



            function removeCart(id) {
                // Display a confirmation dialog
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to remove from cart.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(id);

                        $.ajax({
                            url: "<?= @basePath ?>USER/BiddingSession/deleteCart",
                            type: "POST",
                            data: {
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Deleted successfully',
                                    }).then((result) => {
                                        if (result.isConfirmed || result.isDismissed) {
                                            window.location.reload(); // Reload the page on success
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>
</body>

</html>