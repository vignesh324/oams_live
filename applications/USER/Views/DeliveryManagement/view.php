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
            text-align: center;
            vertical-align: top !important;
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

        @media print {
            #print-btn {
                display: none;
            }

            @page {
                margin: 0;
            }

            body {
                margin: 1.6cm;
            }
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

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Delivery Management</h3>
                                    <div class="float-right">
                                        <a href="#" class="btn btn-default" id="print-btn" onclick="printDiv('printableArea')">Print</a>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" id="printableArea">
                                    <div class="row invoice-info pb-3">
                                        <div class="col-6">
                                            <address>
                                                <strong><?php echo $response_data['s_name']; ?></strong><br>
                                                <?php echo $response_data['s_address']; ?><br>
                                                <strong>Warehouse:</strong> <?php echo $response_data['deliveryItems'][0]['warehouse_name']; ?><br>
                                                <strong>GST No:</strong> <?php echo $response_data['s_gst']; ?><br>
                                                <strong>TeaBoard Reg No:</strong> <?php echo $response_data['s_tea']; ?><br>
                                                <strong>FSSAI No:</strong> <?php echo $response_data['s_fssai']; ?><br>

                                            </address>
                                        </div>
                                        <div class="col-6 text-right">
                                            <address>
                                                <strong>To,</strong><br>
                                                <?php echo $response_data['b_name']; ?><br>
                                                <?php echo $response_data['b_address']; ?><br>
                                                <strong>FSSAI No:</strong> <?php echo $response_data['b_fssai']; ?><br>
                                                <strong>TeaBoard Reg No:</strong> <?php echo $response_data['b_tea']; ?><br>
                                                <strong>GST No:</strong> <?php echo $response_data['b_gst']; ?><br>

                                            </address>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col mb-2">
                                            <strong>Sale Invoice No:</strong> <?php echo $response_data['invoice_no']; ?><br>
                                        </div>
                                    </div>

                                    <form id="update-deleviryqty-form">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table class="table table-bordered table-striped auction_items_table">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Lot No</th>
                                                            <th colspan="2">Description</th>
                                                            <th rowspan="2">Delivery Quantity</th>
                                                            <th rowspan="2">Price</th>
                                                            <th rowspan="2">Total Price</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Grade Name</th>
                                                            <th>Garden Name</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <?php
                                                        $total_value = 0;
                                                        $total_delivery_quantity = 0;
                                                        // echo '<pre>';print_r($response_data);exit;
                                                        if (!empty($response_data['deliveryItems'])) {
                                                            foreach ($response_data['deliveryItems'] as $key => $value) {
                                                                $sq = $value['sample_quantity'];
                                                                $hsn = $response_data['hsn_code'];
                                                                if ($value['is_sample_calc'] == 1) {
                                                                    $net_weight = ($value['each_net'] * $value['qty']) - $sq;
                                                                } else {
                                                                    $net_weight = ($value['each_net'] * $value['qty']);
                                                                }
                                                                $value_price = $value['bid_price'] * $net_weight;
                                                                $total_value += $value_price;
                                                                $total_delivery_quantity += $value['qty']; // Add to total delivery quantity
                                                        ?>
                                                                <tr>
                                                                    <td><?php echo $value['lot_no']; ?></td>
                                                                    <td><?php echo $value['grade_name']; ?></td>
                                                                    <td><?php echo $value['garden_name']; ?></td>
                                                                    <td id="exitsting_qty"><?php echo $value['qty']; ?></td>
                                                                    <td><?php echo $value['bid_price']; ?></td>
                                                                    <td><?php echo $value_price; ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="9" class="text-center">No data found</td>
                                                            </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                                <div class="d-flex m-2">
                                                    <b>Grand Total:</b>
                                                </div>
                                                <div class="m-2">
                                                    <div class="row">
                                                        <div class="col">
                                                            <b>Total Value Price:</b><br> <?php echo $total_value; ?>
                                                        </div>
                                                        <div class="col">
                                                            <b>Total Quantity:</b><br> <?php echo $total_delivery_quantity; ?>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="text-center">
                                                        <b>Rs.<?php //echo convert_number_to_words($total_value); 
                                                                ?></b>
                                                    </div> -->
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
            function printDiv(divId) {
                var printContents = document.getElementById(divId).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
</body>

</html>