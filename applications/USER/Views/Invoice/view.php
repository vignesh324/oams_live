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
                                        <strong><?php echo $response_data['s_name']; ?></strong><br>
                                        <?php echo $response_data['s_address']; ?><br>
                                        <?php echo $response_data['s_area'] . ',' . $response_data['s_city'] . ',' . $response_data['s_state']; ?><br>
                                        <strong>Warehouse:</strong> <?php echo $response_data['invoiceItems'][0]['warehouse_name']; ?><br>
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
                                        <?php echo $response_data['b_area'] . ',' . $response_data['b_city'] . ',' . $response_data['b_state']; ?><br>
                                        <strong>FSSAI No:</strong> <?php echo $response_data['b_fssai']; ?><br>
                                        <strong>TeaBoard Reg No:</strong> <?php echo $response_data['b_tea']; ?><br>
                                        <strong>GST No:</strong> <?php echo $response_data['b_gst']; ?><br>

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
                                        <td>Mark: <?php echo $response_data['invoiceItems'][0]['garden_name']; ?></td>
                                        <td>
                                            <?php
                                            $hsn = $response_data['hsn_code'];
                                            ?>
                                            Hsn: <?php echo $hsn; ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Reverse Charges: NO</td>
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
                                            <div>&nbsp;</div>
                                            <div>Value</div>
                                        </th>
                                        <th>
                                            <div>Grade</div>
                                            <div>Mark</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Garden Invoice no</div>
                                            <div>Warehouse</div>
                                            <div>SGST 2.50%</div>
                                        </th>
                                        <th>
                                            <div>&nbsp;</div>
                                            <!-- <div>HSN Code</div> -->
                                            <div>&nbsp;</div>
                                            <div>CGST 2.50%</div>
                                        </th>
                                        <th>
                                            <div>Pkgs</div>
                                            <div>FTS</div>
                                            <div>IGST 5.00%</div>
                                        </th>
                                        <th>
                                            <div>Gross Kg.</div>
                                            <div>&nbsp;</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Net Kg Per Pkg</div>
                                            <div>&nbsp;</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Total Kgs</div>
                                            <div>&nbsp;</div>
                                            <div>&nbsp;</div>
                                        </th>
                                        <th>
                                            <div>Price/Kg</div>
                                            <div>&nbsp;</div>
                                            <div>Net Amount</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_sq = $total_net_sum = $total_pkgs = $total_kgs = $total_sgst = $total_cgst = $total_igst = $total_net_amount = $total_value = 0;

                                    // echo '<pre>';print_r($response_data);exit;
                                    if ($response_data['s_state'] == $response_data['b_state']) {
                                        $sgst = 2.5; // percentage
                                        $cgst = 2.5; // percentage
                                        $igst = 0.0; // percentage
                                    } else {
                                        $sgst = 0.0; // percentage
                                        $cgst = 0.0; // percentage
                                        $igst = 5.0; // percentage
                                    }

                                    foreach ($response_data['invoiceItems'] as $key => $value) {

                                        //    print_r($response_data['invoiceItems']);exit;
                                        $sq = $value['sample_quantity'];
                                        $total_sq += $value['sample_quantity'];

                                        $net_weight = $value['each_net'] * $value['qty'];
                                        $total_net_sum += $net_weight;
                                        $total_pkgs += $value['qty'];

                                        $net_kg = $net_weight - $sq;
                                        $value_price = $value['bid_price'] * $net_kg;

                                        $sgst_amount = cal_percentage($sgst, $value_price);
                                        $cgst_amount = cal_percentage($cgst, $value_price);
                                        $igst_amount = cal_percentage($igst, $value_price);

                                        $total_sgst += $sgst_amount;
                                        $total_cgst += $cgst_amount;
                                        $total_igst += $igst_amount;

                                        $net_amount = $value_price + $sgst_amount + $cgst_amount + $igst_amount;
                                        $total_net_amount += $net_amount;
                                        $total_value += $value_price;

                                        $total_kgs += $net_kg;
                                    ?>
                                        <tr>
                                            <td>
                                                <div><?php echo $value['lot_no']; ?></div>
                                                <div>&nbsp;</div>
                                                <div><?php echo $value_price ?: '&nbsp;'; ?></div>
                                            </td>
                                            <td>
                                                <div><?php echo $value['grade_name']; ?></div>
                                                <div><?php echo $value['garden_name']; ?></div>
                                                <div>&nbsp;</div>
                                            </td>
                                            <td>
                                                <div><?php echo $value['inward_invoice_id']; 
                                                            ?></div>
                                                <div><?php echo $value['warehouse_name']; ?></div>
                                                <div><?php echo $sgst_amount ?? 0.00; ?></div>
                                            </td>
                                            <td>
                                                <!-- <div><?php //echo $hsn; 
                                                            ?></div> -->
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                                <div><?php echo $cgst_amount ?? 0.00; ?></div>
                                            </td>
                                            <td>
                                                <div><?php echo $value['qty']; ?></div>
                                                <div>
                                                    <?php

                                                    echo $sq;
                                                    ?>
                                                </div>
                                                <div><?php echo $igst_amount ?? 0.00; ?></div>
                                            </td>
                                            <td>
                                                <div><?php echo @$value['weight_gross'] ?: '&nbsp;'; ?></div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                            </td>
                                            <td>
                                                <div><?php echo $value['each_net']; ?></div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                            </td>
                                            <td>
                                                <div><?php echo ($value['each_net'] * $value['qty']) - $sq; ?></div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                            </td>
                                            <td>
                                                <div><?php echo $value['bid_price']; ?></div>
                                                <div>&nbsp;</div>
                                                <div><?php echo $net_amount; ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <div class="bs-stepper-line"></div>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex p-2">
                                    <strong>Total Pkgs: </strong> <?php echo $total_pkgs; ?>
                                </div>
                                <div class="d-flex p-2">
                                    <strong>Total Net Kgs: </strong> <?php echo $total_net_sum; ?>
                                </div>
                                <div class="d-flex p-2">
                                    <strong>Total Sample Kgs: </strong> <?php echo $total_sq; ?>
                                </div>
                                <div class="d-flex p-2">
                                    <strong>Total Kgs: </strong> <?php echo $total_kgs; ?>
                                </div>
                            </div>

                            <div class="d-flex m-2">
                                <b>Grand Total:</b>
                            </div>
                            <div class="m-2">
                                <div class="row">
                                    <div class="col">
                                        <b>Value:</b><br> <?php echo $total_value; ?>
                                    </div>
                                    <div class="col">
                                        <b>SGST 2.50%:</b><br> <?php echo $total_sgst; ?>
                                    </div>
                                    <div class="col">
                                        <b>CGST 2.50%:</b><br> <?php echo $total_cgst; ?>
                                    </div>
                                    <div class="col">
                                        <b>IGST 5.00%:</b><br> <?php echo $total_igst; ?>
                                    </div>
                                    <div class="col">
                                        <b>Total Net Amount:</b><br> <?php echo $total_net_amount; ?>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <b>Rs.<?php echo convert_number_to_words($total_net_amount); ?></b>
                                </div>
                            </div>
                            <div class="bs-stepper-line"></div>


                            <div class="row m-2">
                                <h5>WE CERTIFY THAT FOOD(S) MENTIONED IN THIS INVOICE IS/ARE WARRANTED TO BE OF THE NATURE AND QUALITY WHICH IT/THESE PURPORT(S) TO BE.</h5>

                                <div class="col col-lg-9">

                                    <p>THE TEAS IN THE ABOVE INVOICE WERE MADE FROM GREEN LEAF WHICH HAVE ONLY USED PPF'S AS RECOMMENDED IN PLANT PROTECTION CODE AND HAVE NOT USED ANY
                                        CHEMICALS AND PESTICIDES BANNED IN INDIA. IN THE EVENT OF DETECTION OF ANY BANNED PESTICIDES OR CHEMICALS AND THEIR RESIDUES ARE BEYOND THE
                                        PRESCRIBED LIMITS, TEA BOARD IS FREE TO TAKE APPROPRIATE ACTIONS AS PER THE PROVISIONS OF THE TEA MARKETING (CONTROL) ORDER, 2003.</p>

                                    <ul>
                                        <li>Free Trade Sample Package No.</li>
                                        <li>Induding Sample and Shortage Qty</li>
                                        <li>As Per Pan India Auction Rules, the Free Trade Samples (FTS) drawn by the concerned Auctioneer from the above
                                            lot(s) and distributed to the Buyers.</li>
                                    </ul>
                                </div>

                                <div class="col col-lg-3">
                                    <b>Authorised Signatory</b>
                                    <div class="bs-stepper-line mt-5"></div>
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
        </div>
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