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
                                    <div class="row">
                                        <div class="form-group col-3">
                                            <label for="name">Sale No</label>
                                            <select class="form-control" name="auction_id" id="auction_id">
                                                <option value="">Select Sale No</option>
                                                <?php foreach ($auction_data['auction'] as $key => $value) : ?>
                                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['sale_no'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <label for="name">Invoice</label>
                                            <select class="form-control" name="invoice_id" id="invoice_id">
                                                <option value="">Select Invoice</option>

                                            </select>
                                        </div>

                                        <!-- <div class="form-group col-3">
                                            <label for="name">Sale No</label>
                                            <input type="text" class="form-control" name="sale_no" id="sale_no" readonly>
                                        </div> -->

                                        <div class="form-group col-3">
                                            <label for="name">Seller Name</label>
                                            <input type="text" class="form-control" name="seller_name" id="seller_name" readonly>
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="name">Buyer Name</label>
                                            <input type="text" class="form-control" name="buyer_name" id="buyer_name" readonly>
                                        </div>

                                    </div>

                                    <form id="update-deleviryqty-form">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <?php
                                                // $readonly_button = '';
                                                // $submit_button = '';
                                                // $checked = '';
                                                // $currentDate = date('d-m-Y');
                                                // if (empty($response_data['invoiceItems'])) {
                                                //     $readonly_button = 'disabled';
                                                //     $submit_button = 'disabled';
                                                // }
                                                // if (date('Y-m-d', strtotime($deliveryDate)) < date('Y-m-d', strtotime($currentDate))) {
                                                //     $readonly_button = 'readonly';
                                                //     $checked = 'checked';
                                                // }
                                                ?>
                                                <table class="table table-bordered table-striped auction_items_table">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="check-all" <?php echo @$checked ?> <?php echo @$readonly_button ?>></th>
                                                            <th>Lot No</th>
                                                            <th>Grade Name</th>
                                                            <th>Warehouse Name</th>
                                                            <th>Garden Name</th>
                                                            <th>Quantity</th>
                                                            <th>Delivery Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="on-change">
                                                        <!-- on-change -->
                                                        <tr>
                                                            <td colspan="10" class="text-center">No data found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <div class="float-right">
                                                    <a href="<?= @basePath ?>USER/DeliveryManagement" class="btn btn-default">Back</a>

                                                    <button type="button" id="add-submit" class="btn btn-primary" <?php echo @$submit_button ?>>Save changes</button>
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

            $(document).on("change", "#auction_id", function(event) {
                var auction_id = $(this).val();
                console.log($('#auction_id').val());
                $.ajax({
                    url: '<?= @basePath ?>USER/DeliveryManagement/GetInvoiceByAuctionId',
                    type: 'POST',
                    data: {
                        "auction_id": auction_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $("#invoice_id").empty();
                        $("#invoice_id").append('<option value="">Select Invoice</option>');

                        $.each(response.invoices, function(key, invoice) {
                            var gardenOption = '<option value="' + invoice.id + '">' + invoice.invoice_no + '</option>';
                            $('#invoice_id').append(gardenOption);
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#invoice_id').append('<option value="">No data found</option>');
                    }
                });
            });

            $(document).on("change", "#invoice_id", function(event) {
                const invoice_id = $('#invoice_id').val();
                const url = '<?= @basePath ?>USER/DeliveryManagement/GetInvoiceItems';

                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        invoice_id: invoice_id
                    },
                    success: function(_response) {
                        const response = JSON.parse(_response);
                        const response_data = response.data.invoices;

                        const $sale_no = $('#sale_no');
                        const $seller_name = $('#seller_name');
                        const $buyer_name = $('#buyer_name');
                        const $on_change = $("#on-change");
                        const $check_all = $('#check-all');

                        if (response.status === 200 && response_data) {
                            $sale_no.val(response_data.sale_no);
                            $seller_name.val(response_data.s_name);
                            $buyer_name.val(response_data.b_name);
                            $check_all.prop('disabled', false);

                            const currentDate = new Date();
                            const deliveryDate = new Date(response_data.date);
                            deliveryDate.setDate(deliveryDate.getDate() + parseInt(response_data.prompt_days));

                            const currentDateFormatted = currentDate.toISOString().slice(0, 10);
                            const deliveryDateFormatted = deliveryDate.toISOString().slice(0, 10);

                            const readonly_button = deliveryDateFormatted < currentDateFormatted ? 'readonly' : '';
                            const checked = readonly_button ? 'checked' : '';

                            if (readonly_button) {
                                $check_all.prop('readonly', true);
                            }

                            if (response_data.invoiceItems && response_data.invoiceItems.length > 0) {
                                const rows = response_data.invoiceItems.map((invdet, key) =>
                                    `<tr>
                                            <td><input type="checkbox" ${checked} ${readonly_button} class="checkbox-item" id="check-auctionitem_${key}" name="check-auctionitem[]"></td>
                                            <td>${invdet.lot_no}</td>
                                            <td>${invdet.grade_name}</td>
                                            <td>${invdet.warehouse_name}</td>
                                            <td>${invdet.garden_name}</td>
                                            <td id="exitsting_qty">${invdet.stock_qty}</td>
                                            <td>
                                                <input type="text" class="form-control" id="qty_${key}" name="qty[]" value="${invdet.stock_qty}" ${readonly_button}>
                                                <input type="hidden" class="form-control auction_item_id" name="auction_item_id[]" value="${invdet.auction_item_id}">
                                                <input type="hidden" class="form-control auction_id" name="auction_id" value="${response_data.auction_id}">
                                                <input type="hidden" class="form-control invoice_id" name="invoice_id" value="${response_data.id}">
                                            </td>
                                        </tr>`).join('');

                                $on_change.empty().append(rows);

                                const allChecked = $('.checkbox-item').length === $('.checkbox-item:checked').length;
                                $check_all.prop('checked', allChecked);
                            } else {
                                $check_all.prop('disabled', true);
                                $on_change.empty().append('<tr><td colspan="10" class="text-center">No data found</td></tr>');
                            }
                        } else {
                            $sale_no.val('');
                            $seller_name.val('');
                            $buyer_name.val('');
                            $check_all.prop('readonly', false);
                            $check_all.prop('disabled', true);
                            $on_change.empty().append('<tr><td colspan="10" class="text-center">No data found</td></tr>');
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    },
                });
            });


            $(document).on("click", "#add-submit", function(event) {
                event.preventDefault();

                $('.error').remove();
                $(this).attr("disabled", true);

                var url = '<?= @basePath ?>USER/DeliveryManagement/StoreItems';
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
                        $("#add-submit").attr("disabled", false);
                    }
                });
            });
        </script>
</body>

</html>