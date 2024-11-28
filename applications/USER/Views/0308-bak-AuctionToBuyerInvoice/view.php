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
        .bs-stepper-line {
            flex: 1 0 32px;
            min-width: 1px;
            min-height: 2px;
            margin: auto;
            background-color: rgb(0 0 0 / 72%);
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Invoice</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Invoice</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" id="card-title">Tax Invoice</h3>
                            <div class="float-right">
                                <a href="#" class="btn btn-default" id="print-btn" onclick="printDiv('printableArea')">Print</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" id="printableArea">

                            <div class="row mt-3">
                                <div class="col-6">
                                    <address>
                                        <strong>Reegal Commodities</strong><br>
                                        CLUB ROAD SIMS PARK, COONOOR<br>
                                        <strong>Phone:</strong> 9487875160<br>
                                        <strong>Email:</strong> PTMCRNR@GMAIL.COM<br>
                                        <strong>TeaBoard Reg No:</strong> BL4<br>
                                        <strong>FSSAI No:</strong> 10013042000485<br>
                                        <strong>GSTIN:</strong> 33AACCP8659B1ZL
                                    </address>
                                </div>
                                <div class="col-6 text-right">
                                    <address>
                                        <strong>To,</strong><br>
                                        <?php echo $response_data['buyer_name']; ?><br>
                                        <?php echo $response_data['b_address']; ?><br>
                                        <?php echo $response_data['b_area_name'] . ',' . $response_data['b_city_name'] . ',' . $response_data['b_state_name']; ?><br>
                                        <strong>FSSAI No:</strong> <?php echo $response_data['b_fssai']; ?><br>
                                        <strong>TeaBoard Reg No:</strong> <?php echo $response_data['b_tea']; ?><br>
                                        <strong>GSTIN:</strong> <?php echo $response_data['b_gst']; ?><br>
                                    </address>
                                </div>
                            </div>
                            <div class="bs-stepper-line"></div>
                            <strong>
                                <p>Dear Sir/Madam,<br>
                            </strong>
                            Auctioneer Charges Towards The Following Teas Purchased By You This Day From Coonoor Auction Center</p>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>Invoice No: <?php echo $response_data['invoice_no']; ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Sale No: <?php echo $response_data['sale_no']; ?></td>
                                        <td>Sale Date: <?php echo date("d-m-Y", strtotime($response_data['auction_date'])); ?></td>
                                        <td>Prompt Date: <?php echo date("d-m-Y", strtotime($response_data['date'] . ' +' . $response_data['prompt_days'] . ' days')); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Description of Services: Auctioneer Services</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Reverse Charges: NO</td>
                                        <td></td>
                                        <td>Invoice Date: <?php echo date("d-m-Y", strtotime($response_data['date'])); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="bs-stepper-line"></div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>Lot No</div>
                                            <div>Mark</div>
                                        </th>
                                        <th>
                                            <div>Tea Tax Invoice No</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Total kgs</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Service Charge</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>SGST 9.00%</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>CGST 9.00%</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>IGST 18.00%</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Total Amount</div>
                                            <div>&nbsp;</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_net_amount = 0;
                                    $total_buyer_charges = 0;
                                    $total_sgst = 0;
                                    $total_cgst = 0;
                                    $total_igst = 0;
                                    $total_kgs = 0;
                                    $net_amount = 0;
                                    $total_sq = 0;

                                    if ($response_data['b_state_name'] == 'Tamil Nadu' || $response_data['b_state_id'] == 31) {
                                        $sgst = 9; // percentage
                                        $cgst = 9; // percentage
                                        $igst = 0.0; // percentage
                                    } else {
                                        $sgst = 0.0; // percentage
                                        $cgst = 0.0; // percentage
                                        $igst = 18; // percentage
                                    }
                                    // echo '<pre>';print_r($response_data['invoiceItems']);exit;

                                    foreach ($response_data['invoiceItems'] as $key => $value) {
                                        $sq = $value['sample_quantity'];
                                        $total_sq += $value['sample_quantity'];

                                        $qty = ($value['auction_each_net'] * $value['pkgs']) - $sq;
                                        $total_kgs += $qty;
                                        $value_price = $value['bid_price'] * $qty;
                                        // print_r($response_data['buyer_charges']);exit;
                                        $buyer_charges = cal_percentage($response_data['buyer_charges'], $value_price);
                                        $sgst_amount = cal_percentage($sgst, $buyer_charges);
                                        $cgst_amount = cal_percentage($cgst, $buyer_charges);
                                        $igst_amount = cal_percentage($igst, $buyer_charges);
                                        $net_amount = $buyer_charges + $sgst_amount + $cgst_amount + $igst_amount;
                                        $total_net_amount += $net_amount;
                                        $total_sgst += $sgst_amount;
                                        $total_cgst += $cgst_amount;
                                        $total_igst += $igst_amount;
                                        $total_buyer_charges += $buyer_charges;
                                    ?>
                                        <tr>
                                            <td><?php echo $value['lot_no']; ?><br><?php echo $value['garden_name']; ?></td>
                                            <!-- <td>K295PT2324L00156</td> -->
                                            <td><?php echo $response_data['tax_invoice_no']; ?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td><?php echo $buyer_charges; ?></td>
                                            <td><?php echo $sgst_amount; ?></td>
                                            <td><?php echo $cgst_amount; ?></td>
                                            <td><?php echo $igst_amount; ?></td>
                                            <td><?php echo $net_amount; ?></td>

                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <div class="bs-stepper-line"></div>
                            <div class="d-flex m-2">
                                <strong>Total Kgs:</strong> <?php echo $total_kgs; ?>
                            </div>
                            <div class="d-flex m-2">
                                <b>Grand Total:</b>
                            </div>
                            <div class="m-2">
                                <div class="row">
                                    <div class="col">
                                        <b>Service Charge:</b><br> <?php echo $total_buyer_charges; ?>
                                    </div>
                                    <div class="col">
                                        <b>SGST 9.00%:</b><br> <?php echo $total_sgst; ?>
                                    </div>
                                    <div class="col">
                                        <b>CGST 9.00%:</b><br> <?php echo $total_cgst; ?>
                                    </div>
                                    <div class="col">
                                        <b>IGST 18.00%:</b><br> <?php echo $total_igst; ?>
                                    </div>
                                    <div class="col">
                                        <b>Total Amount:</b><br> <?php echo $total_net_amount; ?>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <b>Rs.<?php echo convert_number_to_words($total_net_amount); ?></b>
                                </div>
                            </div>
                            <div class="bs-stepper-line"></div>
                            <div class="d-flex m-2 justify-content-between">
                                <div class="">
                                    <p></p>
                                </div>
                                <div class="">
                                    <b>Authorised Signatory</b>
                                    <p>Digitally signed by ANTHONY DASS<br>Date: 2023.08.16 19:03:59 +05:30<br>Reason: Coonoor<br>Location: </p>
                                </div>
                            </div>
                            <!-- <div class="text-center">
                                    <p>RCP-L-BC/04/24-rds/1 x x x x. SC</p>
                                </div> -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
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