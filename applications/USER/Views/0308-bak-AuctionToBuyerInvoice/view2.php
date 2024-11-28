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
        <div class="wrapper">
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
                                <h3 class="card-title">Tax Invoice</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <b>TOP HILL TEA FACTORY</b>
                                        <div>TOPHILL TEA FACTORY 5/378, AMMAN NAGAR, DENAD VILLAGE,<br> KILKOTAGIRI BAZAAR POST, THE NILGIRIS<br>
                                            THE NILGIRIS</div>
                                        <div><strong>Warehouse:</strong> SRI THIRUMALAI VENKATASAMY TEXTILES (P) LTD,<br>D.NO.1/191, BELLATHI, SIRUMUGAI ROAD<br> COIMBATORE DT</div>
                                        <div><strong>PAN NO:</strong> BSZPG7327L</div>
                                        <div><strong>GSTIN:</strong> 33BSZPG7327L1Z2</div>
                                        <div><strong>CIN No:</strong></div>
                                        <div><strong>TeaBoard Reg No.:</strong> RC-665</div>
                                        <div><strong>Factory FSSAI No:</strong> 10015042001935</div>
                                        <div><strong>Place Of Supply:</strong> 33- Tamil Nadu</div>
                                        <div><strong>Auctioneer Name:</strong> PARAMOUNT TEA MARKETING (SI) PVT LTD</div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <address>
                                            <strong>To,</strong><br>
                                            REGAL COMMODITIES PVT LTD<br>
                                            379, ULAGAPPAR STREET, VELLAKINAR, COIMBATORE - 641029 COIMBATORE<br>
                                            PAN No: ACCR2890Q<br>
                                            FSSAI No: 1241200300071<br>
                                            TeaBoard Reg No: KOU/E-3693<br>
                                            GSTIN: 33AACCR2890J1ZV<br>
                                            Place of Supply: 33 - TAMIL NADU
                                        </address>
                                    </div>
                                </div>

                                <div class="bs-stepper-line"></div>
                                <p>DEAR SIR/MADAM,</p>
                                <p>AUCTIONEER CHARGES TOWARDS THE FOLLOWING TEAS PURCHASED BY YOU THIS DAY FROM COONOOR AUCTION CENTER</p>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Reference No.: 3/TV1/P/01/0019/0156</td>
                                            <td>Invoice No: PTASLS232409250</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Sale No: 33</td>
                                            <td>Sale Date: 17/08/2023</td>
                                            <td>Prompt Date: 30/08/2023</td>
                                        </tr>
                                        <tr>
                                            <td>SAC Code: 996111</td>
                                            <td>Description of Services: Auctioneer Services</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Reverse Charges: NO</td>
                                            <td></td>
                                            <td>Invoice Date: 17/08/2023</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="bs-stepper-line"></div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Lot No<br>Deal Id No.<br>Value</th>
                                            <th>Grade<br>Mark</th>
                                            <th>Garden Invoice No<br>Warehouse<br>SGST 2.50%</th>
                                            <th>HSN Code<br>Description of Goods<br>CGST 2.50%</th>
                                            <th>Pkgs<br>FTS*Pkgs with Kgs<br>IGST 5.00%</th>
                                            <th>Gross Kg.</th>
                                            <th>**Net Kg Per Pkg<br>Short Pkgs with Kgs</th>
                                            <th>Total Kgs<br>Short kg.</th>
                                            <th>Price/Kg.<br>Seller Premium<br>Net Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PT0414-S<br>23033CNL07333089<br>26605.80</td>
                                            <td>BOPL<br>TOPHILL SUPREME</td>
                                            <td>420<br>TEAPARK KMD MILL<br>665.15</td>
                                            <td>090240<br>BLACK TEA<br>665.15</td>
                                            <td>10<br>4.38<br>0.00</td>
                                            <td>30.26</td>
                                            <td>30.00<br>0.00</td>
                                            <td>295.62<br>0.00</td>
                                            <td>90.00<br>0.00<br>27936.10</td>
                                        </tr>
                                        <tr>
                                            <td>PT0414-S<br>23033CNL07333089<br>26605.80</td>
                                            <td>BOPL<br>TOPHILL SUPREME</td>
                                            <td>420<br>TEAPARK KMD MILL<br>665.15</td>
                                            <td>090240<br>BLACK TEA<br>665.15</td>
                                            <td>10<br>4.38<br>0.00</td>
                                            <td>30.26</td>
                                            <td>30.00<br>0.00</td>
                                            <td>295.62<br>0.00</td>
                                            <td>90.00<br>0.00<br>27936.10</td>
                                        </tr>
                                        <tr>
                                            <td>PT0414-S<br>23033CNL07333089<br>26605.80</td>
                                            <td>BOPL<br>TOPHILL SUPREME</td>
                                            <td>420<br>TEAPARK KMD MILL<br>665.15</td>
                                            <td>090240<br>BLACK TEA<br>665.15</td>
                                            <td>10<br>4.38<br>0.00</td>
                                            <td>30.26</td>
                                            <td>30.00<br>0.00</td>
                                            <td>295.62<br>0.00</td>
                                            <td>90.00<br>0.00<br>27936.10</td>
                                        </tr>
                                        <tr>
                                            <td>PT0414-S<br>23033CNL07333089<br>26605.80</td>
                                            <td>BOPL<br>TOPHILL SUPREME</td>
                                            <td>420<br>TEAPARK KMD MILL<br>665.15</td>
                                            <td>090240<br>BLACK TEA<br>665.15</td>
                                            <td>10<br>4.38<br>0.00</td>
                                            <td>30.26</td>
                                            <td>30.00<br>0.00</td>
                                            <td>295.62<br>0.00</td>
                                            <td>90.00<br>0.00<br>27936.10</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="bs-stepper-line"></div>
                                <div class="d-flex m-2">
                                    <strong>Total Kgs:</strong> 1214.28
                                </div>
                                <div class="d-flex m-2">
                                    <b>Grand Total:</b>
                                </div>
                                <div class="m-2">
                                    <div class="row">
                                        <div class="col">
                                            <b>Brokerage:</b><br> 8.00
                                        </div>
                                        <div class="col">
                                            <b>Lot Money:</b><br> 8.00
                                        </div>
                                        <div class="col">
                                            <b>SGST 2.50%:</b> 0.72
                                        </div>
                                        <div class="col">
                                            <b>CGST 2.50%:</b><br> 0.72
                                        </div>
                                        <div class="col">
                                            <b>IGST 18.00%:</b><br> 0.00
                                        </div>
                                        <div class="col">
                                            <b>Total Amount:</b><br> 9.44
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <b>Rs. Nine and Fourty Four Paise Only</b>
                                    </div>
                                </div>
                                <div class="bs-stepper-line"></div>
                                <div class="d-flex m-2 justify-content-between">
                                    <div class="">
                                        <p>E. & O.E.</p>
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
        </div>
        <?= @$footer ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?= @basePath ?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/jszip/jszip.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>