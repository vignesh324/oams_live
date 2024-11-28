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

        input[type="checkbox"][readonly] {
            cursor: not-allowed !important;
            pointer-events: none;

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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Delivery Management</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">
                            &nbsp;
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <div class="modal fade" id="modal-sm">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h3 class="card-title">Delivery Management</h3>
                                        </div>
                                        <div class="col-md-10">
                                            <!-- Extra buttons or elements can be added here -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row invoice-info pb-3">
                                        <div class="col-sm-4 invoice-col">
                                            Invoice No: <strong><?php echo @$response_data['invoice_no']; ?></strong><br>
                                            Sale No: <strong><?php echo @$response_data['sale_no']; ?></strong><br>
                                            Invoice Date: <strong><?php echo date("d-m-Y", strtotime(@$response_data['date'])); ?></strong><br>
                                        </div>

                                        <div class="col-sm-4 invoice-col">
                                            Seller Name: <strong><?php echo @$response_data['seller_name']; ?></strong><br>
                                            Buyer Name: <strong><?php echo @$response_data['buyer_name']; ?></strong><br>
                                            <?php
                                            // Calculate the delivery date by adding settingsDeliveryTime to the original date
                                            $date = new DateTime(@$response_data['date']);
                                            $date->modify("+" . @$response_data['settings_delivery_time'] . " days");
                                            $deliveryDate = $date->format('d-m-Y');
                                            ?>
                                            Invoice Date: <strong><?= $deliveryDate; ?></strong><br>
                                        </div>

                                        <div class="col-sm-4 invoice-col">
                                            <button onclick="history(<?php echo $response_data['id']; ?>)" class="btn btn-info float-right">History
                                            </button>
                                        </div>
                                    </div>

                                    <form id="update-deleviryqty-form">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <?php
                                                $readonly_button = '';
                                                $submit_button = '';
                                                $checked = '';
                                                $currentDate = date('d-m-Y');
                                                if (empty($response_data['invoiceItems'])) {
                                                    $readonly_button = 'disabled';
                                                    $submit_button = 'disabled';
                                                }
                                                if (date('Y-m-d', strtotime($deliveryDate)) < date('Y-m-d', strtotime($currentDate))) {
                                                    $readonly_button = 'readonly';
                                                    $checked = 'checked';
                                                }
                                                ?>
                                                <table class="table table-bordered table-striped auction_items_table">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="check-all" <?php echo @$checked ?> <?php echo @$readonly_button ?>></th>
                                                            <th>Lot No</th>
                                                            <th>Grade Name</th>
                                                            <th>Warehouse Name</th>
                                                            <th>Garden Name</th>
                                                            <th>Each Net</th>
                                                            <th>Total Net</th>
                                                            <th>Total Gross</th>
                                                            <th>Quantity</th>
                                                            <th>Delivery Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($response_data['invoiceItems'])) {
                                                            foreach ($response_data['invoiceItems'] as $key => $value) {
                                                        ?>
                                                                <tr>
                                                                    <td><input type="checkbox" <?php echo @$checked ?> <?php echo @$readonly_button ?> class="checkbox-item" id="check-auctionitem_<?php echo @$response_data['auction_id']; ?>" name="check-auctionitem[]"></td>
                                                                    <td><?php echo $value['lot_no']; ?></td>
                                                                    <td><?php echo $value['grade_name']; ?></td>
                                                                    <td><?php echo $value['warehouse_name']; ?></td>
                                                                    <td><?php echo $value['garden_name']; ?></td>
                                                                    <td><?php echo $value['weight_net']; ?></td>
                                                                    <td><?php echo $value['total_net'] - $value['sample_quantity']; ?></td>
                                                                    <td><?php echo $value['total_gross']; ?></td>
                                                                    <td id="exitsting_qty"><?php echo $value['stock_qty']; ?></td>
                                                                    <td>
                                                                        <input type="text" class="form-control" id="qty.<?php echo @$key++ ?>" name="qty[]" value="<?= @$value['stock_qty'] ?>" <?php echo @$readonly_button ?>>
                                                                        <input type="hidden" class="form-control auction_item_id" name="auction_item_id[]" value="<?php echo @$value['auction_item_id']; ?>">
                                                                        <input type="hidden" class="form-control invoice_id" name="invoice_id" value="<?php echo @$response_data['id']; ?>">
                                                                    </td>
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

                                                <div class="float-right">
                                                    <a href="<?= @basePath ?>USER/DeliveryManagement" class="btn btn-default">Back</a>

                                                    <button type="button" id="update-stock-qty" class="btn btn-primary" <?php echo @$submit_button ?>>Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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

        <script>
            function history(id) {
                $(".loading").show();
                // console.log(id);
                $.ajax({
                    type: "post",
                    url: "<?= @basePath ?>USER/DeliveryManagement/History",
                    data: {
                        invoice_id: id
                    },
                    dataType: 'html',
                    success: function(response) {
                        $(".loading").hide();
                        $(".modal-content").html(response);
                        $('#modal-sm').modal('show');
                    },
                    error: function(error) {
                        $(".loading").hide();
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "An error occurred.",
                        });
                    }
                });
            };

            $(document).on('change', '#check-all', function() {
                var isChecked = $(this).prop('checked');
                $('.checkbox-item').prop('checked', isChecked);
                $('.error').remove(); // Remove error messages when check-all checkbox is changed
            });

            $(document).on('change', '.checkbox-item', function() {
                var allChecked = true;
                $('.checkbox-item').each(function() {
                    if (!$(this).prop('checked')) {
                        allChecked = false;
                        return false; // Exit each loop early
                    }
                });
                $('#check-all').prop('checked', allChecked);
            });

            $(document).on("click", "#update-stock-qty", function(event) {
                event.preventDefault();

                $('.error').remove();
                $(this).attr("disabled", true);

                var url = '<?= @basePath ?>USER/DeliveryManagement/UpdateStock';
                var formdata = $('.checkbox-item:checked').closest('tr').find('input, select').serializeArray();
                var errorExists = false;

                $('.checkbox-item:checked').each(function() {
                    var qtyInput = $(this).closest('tr').find('input[name="qty[]"]');
                    var qty = parseFloat(qtyInput.val());
                    var existingQty = parseFloat($(this).closest('tr').find('#exitsting_qty').text());

                    if (qty > existingQty || qty <= 0) {
                        errorExists = true;
                        qtyInput.closest('td').append('<span class="error">Quantity should be greater than 0 and cannot exceed existing quantity</span>');
                    }
                });

                if (errorExists) {
                    $(this).attr("disabled", false);
                    return;
                }

                if ($('.checkbox-item:checked').length === 0) {
                    $('.auction_items_table').before('<span class="error" id="auction-items-table-error">Select at least one Item</span>');
                    $(this).attr("disabled", false);
                    return;
                }


                $.ajax({
                    url: url,
                    type: 'post',
                    data: $.param(formdata), // Serialize the collected data
                    success: function(_response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Form submitted successfully',
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location = "<?= @basePath ?>USER/DeliveryManagement";
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
                                var modifiedKey = key.includes('.') ? key.split('.').join('\\.') : key;
                                if ($("input#" + modifiedKey).length != 0)
                                    $("input#" + modifiedKey).after('<span class="error ">' + value + "</span>");
                                else if ($("select#" + modifiedKey).length != 0)
                                    $("select#" + modifiedKey).after('<span class="error">' + value + "</span>");
                                else
                                    $("#" + modifiedKey).after('<span class="error">' + value + "</span>");
                            });
                        } else if (_response.status === 500) {
                            Swal.fire({
                                icon: "error",
                                title: "Error!",
                                text: "Internal Server Error",
                            });
                        }
                    },
                    complete: function() {
                        // Re-enable the submit button after the request is complete
                        $("#update-stock-qty").attr("disabled", false);
                    }
                });
            });
        </script>
</body>

</html>