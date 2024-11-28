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
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <h3 class="card-title">Manage Auction cart</h3>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row" style="float: right;">
                                                    <a class="btn bg-success btn-md" href="<?= @basePath ?>USER/BiddingSession/AuctionCartItems/<?php echo $id; ?>">
                                                        Cart Details
                                                    </a> &nbsp;
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="center">Center</label>
                                                    <input type="text" class="form-control" value="<?= @$auction_data['center_name'] ?>" readonly>
                                                    <input type="hidden" class="form-control" name="center_id" id="center_id" value="<?= $auction_data['center_id'] ?>">
                                                    <input type="hidden" class="form-control" name="type" id="type" value="<?= $auction_data['type'] ?>">
                                                    <input type="hidden" class="form-control" name="auction_id" id="auction_id" value="<?= $auction_data['id'] ?>">
                                                    <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>" placeholder="Enter Name">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input type="text" class="form-control datetimepicker-input" id="date" name="date" value="<?= @$auction_data['date'] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Auction Start Time</label>
                                                    <input type="text" class="form-control" name="start_time" value="<?= @$auction_data['start_time'] ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Session Time per lot</label>
                                                    <div class="input-group" id="session_time">
                                                        <input type="text" class="form-control" name="session_time" value="<?= @$auction_data['session_time'] ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Auction LOT count</label>
                                                    <input type="text" class="form-control" name="lot_count" id="lot_count" value="<?php echo @$auction_data['lot_count']; ?>" placeholder="Lot Count" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="warehouse">Warehouse</label>
                                                    <select class="form-control" name="warehouse_id" id="warehouse_id">
                                                        <option value="all">All</option>
                                                        <?php
                                                        foreach (@$warehouse_response_data['warehouse'] as $key => $val) :
                                                        ?>
                                                            <option value="<?php echo @$val['id']; ?>"><?php echo @$val['name']; ?></option>
                                                        <?php endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Garden</label>
                                                    <select class="form-control" name="garden_id" id="garden_id">
                                                        <option value="all">All</option>
                                                        <?php
                                                        foreach (@$centergarden_data['data'] as $key => $val1) :
                                                        ?>
                                                            <option value="<?php echo @$val1['id']; ?>"><?php echo @$val1['garden_name']; ?></option>
                                                        <?php endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-bordered table-striped auction-items-table">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="check-all"></th>
                                                    <th>Invoice No</th>
                                                    <th>Garden name</th>
                                                    <th>Grade Name</th>
                                                    <th>No of Bags</th>
                                                    <th>Each Nett</th>
                                                    <th>Total Nett</th>
                                                    <th>Sample Quantity</th>
                                                    <th>Auction Quantity</th>
                                                    <th>Warehouse</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($auction_items)) {
                                                    foreach ($auction_items as $key => $auction_item) { ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="checkbox-item" id="check-auctionitem_<?php echo @$auction_item['id']; ?>" name="check-auctionitem[]"></td>
                                                            <td><?php echo $auction_item['invoice_id'] ?></td>
                                                            <td><?php echo $auction_item['garden_name'] ?></td>
                                                            <td><?php echo $auction_item['grade_name'] ?></td>
                                                            <td>
                                                                <?php echo $auction_item['stock_qty']; ?>
                                                                <input type="hidden" class="form-control" name="total_quantity[]" value="<?= @$auction_item['stock_qty'] ?>" readonly>
                                                                <input type="hidden" class="form-control" name="inward_item_id[]" value="<?= @$auction_item['id'] ?>" readonly>
                                                                <input type="hidden" class="form-control" name="inward_id[]" value="<?= @$auction_item['inward_id'] ?>" readonly>
                                                                <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">
                                                                <input type="hidden" class="form-control" name="garden_id[]" value="<?= @$auction_item['garden_id'] ?>" readonly>

                                                            </td>
                                                            <td><?php echo $auction_item['weight_net'] ?></td>
                                                            <td><?php echo $auction_item['stock_qty'] * $auction_item['weight_net'] ?></td>
                                                            <td>
                                                                <?php if ($auction_data['type'] == 1 && @$auction_item['vacumm_bag'] == 0) { ?>
                                                                    <input type="text" class="form-control" name="sample_quantity[]" value="<?= @$auction_item['leaf_sq'] ?>">
                                                                <?php } elseif ($auction_data['type'] == 2 && @$auction_item['vacumm_bag'] == 0) { ?>
                                                                    <input type="text" class="form-control" name="sample_quantity[]" value="<?= @$auction_item['dust_sq'] ?>">
                                                                <?php } else { ?>
                                                                    <input type="text" class="form-control" name="sample_quantity[]" value="0" readonly>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="qty[]" value="<?= @$auction_item['stock_qty'] ?>">
                                                            </td>
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

                                        <input type="hidden" value="<?php echo base64_encode(@$auction_data['id']); ?>" name="id">
                                        <div class="float-right m-2">
                                            <button type="button" id="save-publish" class="btn btn-primary">Add to cart</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
        </div>
        </section>

        <?= @$data['footer']; ?>

        <script>
            // var dataToSend = []; // Declare dataToSend globally

            // $(function() {
            //     $("#draggable-menu").sortable({
            //         axis: 'y',
            //         items: 'li',
            //         stop: function(event, ui) {
            //             $("#draggable-menu li").each(function(index) {
            //                 $(this).find(".sequence").val(index + 1);
            //             });
            //             dataToSend = []; // Reset dataToSend when sorting stops
            //             $("#draggable-menu li").each(function(index) {
            //                 var sequence = $(this).find(".sequence").val();
            //                 var id = $(this).find(".id").val();
            //                 var text = $(this).find("span").text();
            //                 var garden_id = $(".garden_id").val();
            //                 dataToSend.push({
            //                     sequence: sequence,
            //                     id: id,
            //                     garden_id: garden_id
            //                 });
            //             });
            //         }
            //     });
            //     $("#draggable-menu").disableSelection();
            // });

            // document.addEventListener('DOMContentLoaded', function() {
            //     window.stepper = new Stepper(document.querySelector('.bs-stepper'))
            // });

            // $(document).on('submit', '#bidding-session-form', function(e) {
            //     e.preventDefault(); // Prevent the default form submission behavior

            //     // Display a confirmation dialog
            //     swal.fire({
            //         title: 'Are you sure?',
            //         text: 'Do you want to Publish!',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#10a37f',
            //         cancelButtonColor: '#3085d6',
            //         confirmButtonText: 'Publish!'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             // If confirmed, proceed with form submission
            //             var url = $(this).attr("action");
            //             var formmethod = "post";
            //             var formdata = $('#bidding-session-form').serialize();
            //             var gardenOrderData = JSON.stringify(dataToSend);


            //             console.log(formdata);
            //             // $.ajax({
            //             //     url: url,
            //             //     type: formmethod,
            //             //     data: formdata,
            //             //     success: function(response) {
            //             //         Swal.fire({
            //             //             icon: "success",
            //             //             title: "Success!",
            //             //             text: "Form submitted successfully",
            //             //         }).then((result) => {
            //             //             if (result.isConfirmed || result.isDismissed) {
            //             //                 window.location = "<?= @basePath ?>USER/BiddingSession";
            //             //             }
            //             //         });
            //             //     },
            //             //     error: function(response) {
            //             //         $('.error').remove();
            //             //         if (response.status === 422) {
            //             //             var errors = response.responseJSON.errors;
            //             //             $.each(errors, function(key, value) {
            //             //                 if ($("input#" + key).length != 0)
            //             //                     $("input#" + key).after('<span class="error ">' + value + "</span>");
            //             //                 else if ($("select#" + key).length != 0)
            //             //                     $("select#" + key).after('<span class="error">' + value + "</span>");
            //             //                 else
            //             //                     $('#' + key).after('<span class="error">' + value + '</span>');
            //             //             });
            //             //         } else if (response.status === 500) {
            //             //             Swal.fire({
            //             //                 icon: 'error',
            //             //                 title: 'Error!',
            //             //                 text: 'Internal Server Error',
            //             //             });
            //             //         }
            //             //     }
            //             // });
            //         }
            //     });
            // });


            // function removeCart(id) {
            //     // Display a confirmation dialog
            //     swal.fire({
            //         title: 'Are you sure?',
            //         text: 'You want to remove from cart.',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#d33',
            //         cancelButtonColor: '#3085d6',
            //         confirmButtonText: 'delete!'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             console.log(id);

            //             $.ajax({
            //                 url: "<?= @basePath ?>USER/BiddingSession/deleteCart",
            //                 type: "POST",
            //                 data: {
            //                     id: id
            //                 },
            //                 dataType: "json",
            //                 success: function(response) {
            //                     if (response.status == 200) {

            //                         Swal.fire({
            //                             icon: 'success',
            //                             title: 'Success!',
            //                             text: 'Deleted successfully',
            //                         }).then((result) => {
            //                             if (result.isConfirmed || result.isDismissed) {
            //                                 window.location.reload(); // Reload the page on success
            //                             }
            //                         });
            //                     }
            //                 }
            //             });
            //         }
            //     });
            // }

            // function moveToFirstStep() {
            //     var count = 0;
            //     if ($("#center_id").val() == '')
            //         return false;
            //     var formData = {};
            //     var allFieldsFilled = true; // Flag to track if all fields are filled
            //     $('.error').remove();

            //     $(".checkbox-item:checked").each(function() {
            //         var row = $(this).closest('tr');
            //         count++;
            //         $('#auction-items-table-error').remove();
            //         row.find('input, select').each(function() {
            //             var inputName = $(this).attr('name');
            //             var inputValue = $(this).val();

            //             if (inputName.endsWith('[]')) {
            //                 if (!formData[inputName]) {
            //                     formData[inputName] = [];
            //                 }
            //                 formData[inputName].push(inputValue);
            //             } else {
            //                 formData[inputName] = inputValue;
            //             }
            //         });

            //         // Validate auction quantity for checked rows
            //         var auction_quantity = parseFloat(row.find('[name="qty[]"]').val());
            //         var total_quantity = parseFloat(row.find('[name="total_quantity[]"]').val());

            //         // Regular expression to match numeric values
            //         var numericPattern = /^\d+(\.\d+)?$/;
            //         console.log("auction_quantity:", auction_quantity);

            //         console.log("numericPattern.test(auction_quantity):", numericPattern.test(auction_quantity));

            //         // Validate auction quantity
            //         if (!numericPattern.test(auction_quantity) || auction_quantity <= 0) {
            //             row.find('[name="qty[]"]').closest('td').append('<span class="error">Auction Quantity must be a valid number and greater than 0</span>');
            //             allFieldsFilled = false;
            //         }

            //         // Check if auction quantity exceeds total quantity
            //         if (auction_quantity > total_quantity) {
            //             row.find('[name="qty[]"]').after('<span class="error">Auction Quantity cannot exceed Total Quantity</span>');
            //             allFieldsFilled = false;
            //         }
            //     });

            //     // Check if no items are selected
            //     if (count === 0) {
            //         $('.auction-items-table').before('<span class="error" id="auction-items-table-error">Select at least one Invoice</span>');
            //         return;
            //     }

            //     // Check if all fields are filled
            //     if (!allFieldsFilled) {
            //         return;
            //     }

            //     // Move to the next step (assuming stepper is defined somewhere else)
            //     stepper.next();
            // }

            $(document).on("change", "#warehouse_id,#garden_id", function(event) {
                var centerId = $('#center_id').val();
                var type = $('#type').val();
                var warehouse_id = $('#warehouse_id').val();
                var garden_id = $('#garden_id').val();

                $.ajax({
                    url: "<?= @basePath ?>USER/BiddingSession/GetInwardItemsByWarehouseId",
                    type: "POST",
                    data: {
                        center_id: centerId,
                        type: type,
                        warehouse_id: warehouse_id,
                        garden_id: garden_id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200 && response.data.length !== 0) {
                            $(".auction-items-table tbody tr").each(function() {
                                var rowId = $(this).find('.checkbox-item').attr('id');
                                var isChecked = $(this).find('.checkbox-item').prop('checked');
                                // Check if the checkbox is unchecked and remove the row
                                if (!isChecked) {
                                    $(this).remove();
                                }
                            });

                            $.each(response.data, function(key, invdet) {
                                // Check if the item with the same ID is already checked or exists in the table
                                var isChecked = $("#check-auctionitem_" + invdet.id).prop('checked');
                                var existsInTable = $("#inward_item_id_" + invdet.id).length > 0;
                                var auctionType = <?php echo json_encode($auction_data["type"]); ?>;
                                var invdetVacummBag = invdet.vacumm_bag;
                                if (!isChecked && !existsInTable) {
                                    var sampleQuantityInput = '';
                                    if (auctionType == 1 && invdetVacummBag == 0) {
                                        sampleQuantityInput = '<input type="text" class="form-control" name="sample_quantity[]" value="' + invdet.leaf_sq + '">';
                                    } else if (auctionType == 2 && invdetVacummBag == 0) {
                                        sampleQuantityInput = '<input type="text" class="form-control" name="sample_quantity[]" value="' + invdet.dust_sq + '">';
                                    } else {
                                        sampleQuantityInput = '<input type="text" class="form-control" name="sample_quantity[]" value="0" readonly>';
                                    }

                                    var newRow = '<tr>' +
                                        '<td><input type="checkbox" class="checkbox-item" id="check-auctionitem_' + invdet.id + '" name="check-auctionitem[]"></td>' +
                                        '<td>' + invdet.invoice_id + '</td>' +
                                        '<td>' + invdet.garden_name + '</td>' +
                                        '<td>' + invdet.grade_name + '</td>' +
                                        '<td>' + invdet.stock_qty +
                                        '<input type="hidden" class="form-control no_of_bags" name="total_quantity[]" id="total_quantity_' + invdet.id + '" value="' + invdet.stock_qty + '">' +
                                        '<input type="hidden" class="form-control item_item_id" name="inward_item_id[]" id="inward_item_id_' + invdet.id + '" placeholder="Garden name" value="' + invdet.id + '">' +
                                        '<input type="hidden" class="form-control inward_item" name="inward_item[]" id="inward_item_' + invdet.id + '" placeholder="Auction Quantity" value="' + invdet.id + '">' +
                                        '<input type="hidden" class="form-control inward_id" name="inward_id[]" id="inward_id_' + invdet.id + '" placeholder="Auction Quantity" value="' + invdet.inward_id + '">' +
                                        '<input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">' +
                                        '<input type="hidden" class="form-control" name="invoice_id[]" value="' + invdet.invoice_id + '" readonly><input type="hidden" class="form-control" name="garden_id[]" value="' + invdet.garden_id + '" readonly>' +
                                        '</td>' +
                                        '<td>' + invdet.weight_net +
                                        '</td>' +
                                        '<td>' + invdet.total_net +
                                        '</td>' +
                                        '<td>' + sampleQuantityInput + '</td>' +
                                        '<td><input type="text" class="form-control auction_qty" name="qty[]" value="' + invdet.stock_qty + '"></td>' +
                                        '<td>' + invdet.warehouse_name + '</td>' +
                                        '</tr>';

                                    $(".auction-items-table tbody").append(newRow);
                                }

                                var allChecked = true;
                                $('.checkbox-item').each(function() {
                                    if (!$(this).prop('checked')) {
                                        allChecked = false;
                                        return false; // Exit each loop early
                                    }
                                });

                                $('#check-all').prop('checked', allChecked);
                            });
                        } else {
                            // Do not remove already checked rows
                            $(".auction-items-table tbody tr").each(function() {
                                var isChecked = $(this).find('.checkbox-item').prop('checked');
                                if (!isChecked) {
                                    $(this).remove();
                                }
                            });

                            $(".auction-items-table tbody").append('<tr><td colspan="10" class="text-center">No data found</td></tr>');
                            $('#check-all').prop('checked', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    },
                });
            });

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


            // function finalizeStep() {
            //     submitForm();
            // }

            $(document).on("click", "#save-publish", function(event) {
                var url = $("#bidding-session-form").attr("action");
                var formmethod = "post";
                var formdata = {
                    'center_id': $('#center_id').val(),
                    'auction_id': $('#auction_id').val(),
                    'sequence': [],
                    'garden_id': [],
                };

                $("#sortable li").each(function(index) {
                    $(this).find(".sequence").val(index + 1);
                });
                $(".sequence").each(function(index) {
                    formdata['sequence'].push($(this).val());
                });
                $(".garden_id").each(function() {
                    formdata['garden_id'].push($(this).val());
                });

                $(".checkbox-item:checked").each(function() {
                    var row = $(this).closest('tr');
                    row.find('input, select').each(function() {
                        var inputName = $(this).attr('name');
                        var inputValue = $(this).val();

                        if (inputName.endsWith('[]')) {
                            if (!formdata[inputName]) {
                                formdata[inputName] = [];
                            }
                            formdata[inputName].push(inputValue);
                        } else {
                            formdata[inputName] = inputValue;
                        }
                    });
                });

                var count = 0;
                if ($("#center_id").val() == '')
                    return false;
                var formData = {};
                var allFieldsFilled = true; // Flag to track if all fields are filled
                $('.error').remove();

                $(".checkbox-item:checked").each(function() {
                    var row = $(this).closest('tr');
                    count++;
                    $('#auction-items-table-error').remove();
                    row.find('input, select').each(function() {
                        var inputName = $(this).attr('name');
                        var inputValue = $(this).val();

                        if (inputName.endsWith('[]')) {
                            if (!formData[inputName]) {
                                formData[inputName] = [];
                            }
                            formData[inputName].push(inputValue);
                        } else {
                            formData[inputName] = inputValue;
                        }
                    });

                    // Validate auction quantity for checked rows
                    var auction_quantity = parseFloat(row.find('[name="qty[]"]').val());
                    var total_quantity = parseFloat(row.find('[name="total_quantity[]"]').val());

                    // Regular expression to match numeric values
                    var numericPattern = /^\d+(\.\d+)?$/;
                    console.log("auction_quantity:", auction_quantity);

                    console.log("numericPattern.test(auction_quantity):", numericPattern.test(auction_quantity));

                    // Validate auction quantity
                    if (!numericPattern.test(auction_quantity) || auction_quantity <= 0) {
                        row.find('[name="qty[]"]').closest('td').append('<span class="error">Auction Quantity must be a valid number and greater than 0</span>');
                        allFieldsFilled = false;
                    }

                    // Check if auction quantity exceeds total quantity
                    if (auction_quantity > total_quantity) {
                        row.find('[name="qty[]"]').after('<span class="error">Auction Quantity cannot exceed Total Quantity</span>');
                        allFieldsFilled = false;
                    }
                });

                // Check if no items are selected
                if (count === 0) {
                    $('.auction-items-table').before('<span class="error" id="auction-items-table-error">Select at least one Invoice</span>');
                    return;
                }

                // Check if all fields are filled
                if (!allFieldsFilled) {
                    return;
                }

                $.ajax({
                    url: url,
                    type: formmethod,
                    data: formdata,
                    success: function(_response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Form submitted successfully",
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                window.location = "<?= @basePath ?>USER/BiddingSession/AuctionCartItems/<?php echo base64_encode($auction_data['id']); ?>";
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
                                if ($("input#" + key).length != 0)
                                    $("input#" + key).after('<span class="error ">' + value + "</span>");
                                else if ($("select#" + key).length != 0)
                                    $("select#" + key).after('<span class="error">' + value + "</span>");
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
                    }
                });
            });
        </script>
</body>

</html>