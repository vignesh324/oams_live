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
                                                <h3 class="card-title">Reorder Gardens</h3>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row" style="float: right;">
                                                    <a class="btn bg-success btn-md" href="<?= @basePath ?>USER/BiddingSession/AuctionCartItems/<?php echo base64_encode($auction_data['id']); ?>">
                                                        Cart Detail
                                                    </a> &nbsp;
                                                    <a class="btn bg-primary btn-md" href="<?= @basePath ?>USER/BiddingSession/AddAuctionItems/<?php echo base64_encode($auction_data['id']); ?>">
                                                        Add Item
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-10">
                                        <div class="row">
                                        <div class="col-md-4">&nbsp;</div>
                                        <div class="col-md-4">
                                        <div class="menu-container">
                                            <ul id="draggable-menu" class="menu centerGardens d-flex flex-column align-items-center">
                                            <?php
                                            if(isset($centergarden_data['center_garden'])) :
                                                foreach (@$centergarden_data['center_garden'] as $key => $value) {
                                                ?>
                                                    <li class="item btn btn-success mb-2" style="width:100%">
                                                        <input type="hidden" class="sequence" value="<?php echo $key + 1; ?>" readonly>
                                                        <input type="hidden" class="garden_id" value="<?php echo @$value['garden_id']; ?>" readonly>
                                                        <span><?php echo @$value['garden_name']; ?></span>
                                                    </li>
                                                <?php
                                                }
                                            endif;
                                                ?>
                                            </ul>
                                        </div>
                                        </div>
                                        </div>
                                        <input type="hidden" class="center_id" id="center_id" value="<?php echo @$auction_data['center_id']; ?>" readonly>
                                        <input type="hidden" class="auction_id" id="auction_id" value="<?php echo @$auction_data['id']; ?>" readonly>
                                        <input type="hidden" value="<?php echo base64_encode(@$auction_data['id']); ?>" name="id">
                                        <div class="float-right m-2">
                                            <button type="button" id="add-bidding-session" class="btn btn-primary" >Save Order</button>
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
            $(function() {
                $("#draggable-menu").sortable({
                    axis: 'y',
                    items: 'li',
                    stop: function(event, ui) {
                        $("#draggable-menu li").each(function(index) {
                            $(this).find(".sequence").val(index + 1);
                        });
                        var dataToSend = [];
                        $("#draggable-menu li").each(function(index) {
                            var sequence = $(this).find(".sequence").val();
                            var id = $(this).find(".id").val();
                            var text = $(this).find("span").text();
                            var garden_id = $("#garden_id").val();
                            dataToSend.push({
                                sequence: sequence,
                                id: id,
                                garden_id: garden_id
                            });
                        });
                    }
                });
                $("#draggable-menu").disableSelection();
            });

            document.addEventListener('DOMContentLoaded', function() {
                window.stepper = new Stepper(document.querySelector('.bs-stepper'))
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


            function submitForm() {
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
            }


            // Event handler for clicking the add bidding session button
            $(document).on("click", "#add-bidding-session", function(event) {
                event.preventDefault();
                submitForm();
            });


            $(document).on("change", "#warehouse_id,#garden_id", function(event) {
                var centerId = $('#center_id').val();
                var warehouse_id = $('#warehouse_id').val();
                var garden_id = $('#garden_id').val();

                // console.log(centerId)

                $.ajax({
                    url: "<?= @basePath ?>USER/BiddingSession/GetInwardItemsByWarehouseId",
                    type: "POST",
                    data: {
                        center_id: centerId,
                        warehouse_id: warehouse_id,
                        garden_id: garden_id,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 200) {
                            $(".auction-items-table tbody").empty();
                            $.each(response.data, function(key, invdet) {
                                var newRow = '<tr>' +
                                    '<td><input type="checkbox" class="checkbox-item" id="check-auctionitem_' + (key) + '" name="check-auctionitem[]"></td>' +
                                    '<td>' + invdet.invoice_id + '</td>' +
                                    '<td>' + invdet.garden_name + '</td>' +
                                    '<td>' + invdet.grade_name + '<input type="hidden" class="form-control" name="total_quantitysss[]" id="total_quantity_' + (key) + '" value="' + invdet.stock_qty + '" placeholder="Auction Quantity" readonly></td>' +
                                    '<td>' + invdet.warehouse_name +
                                    '<input type="hidden" class="form-control item_garden_name" name="inward_item_garden[]" id="inward_item_garden_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_name + '">' +
                                    '<input type="hidden" class="form-control item_item_id" name="inward_item_id[]" id="inward_item_id_' + (key) + '" placeholder="Garden name" value="' + invdet.id + '">' +
                                    '<input type="hidden" class="form-control item_warehouse_name" name="inward_item_warehouse[]" id="inward_item_warehouse_' + (key) + '" value="' + invdet.warehouse_name + '">' +
                                    '<input type="hidden" class="form-control item_garden_id" name="inward_item_garden_id[]" id="inward_item_garden_id_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_id + '">' +
                                    '<input type="hidden" class="form-control item_grade_id" name="inward_item_grade_id[]" id="inward_item_grade_id_' + (key) + '" placeholder="Garden name" value="' + invdet.grade_id + '">' +
                                    '<input type="hidden" class="form-control item_grade_name" name="inward_item_grade[]" id="inward_item_grade_' + (key) + '" placeholder="Grade name" value="' + invdet.grade_name + '">' +
                                    '<input type="hidden" class="form-control inward_item" name="inward_item[]" id="inward_item_' + (key) + '" placeholder="Auction Quantity" value="' + invdet.id + '">' +
                                    '<input type="hidden" class="form-control each_nett" name="each_nett[]" id="each_nett_' + (key) + '"value="' + invdet.weight_net + '">' +
                                    '<input type="hidden" class="form-control total_nett" name="total_nett[]" id="total_nett_' + (key) + '"value="' + invdet.weight_gross + '">' +
                                    '</td>' +
                                    '<td>' + invdet.weight_net +
                                    '</td>' +
                                    '<td>' + invdet.stock_qty +
                                    '</td>' +
                                    '</tr>';
                                $(".auction-items-table tbody").append(newRow);
                            });
                        } else if (response.status == 404) {
                            $(".auction-items-table tbody").empty();
                            $(".auction-items-table tbody").append('<tr><td colspan="9" class="text-center">No data found</td></tr>');
                            $('#check-all').prop('checked', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    },
                });
            });


            function moveToFirstStep() {
                var count = 0;
                if ($("#center_id").val() == '')
                    return false;
                var formData = {};
                $(".checkbox-item").each(function() {
                    if ($(this).prop('checked')) {
                        $('#auction-items-table-error').remove();
                        count++;
                        var row = $(this).closest('tr');
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
                    }
                });

                console.log(count);
                if (count == 0) {
                    $('.auction-items-table').before('<span class="error" id="auction-items-table-error">Select atlest one Invoice</span>');
                    return;
                }

                stepper.next();
            }
        </script>
</body>

</html>