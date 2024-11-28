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
                                <li class="breadcrumb-item ">Center</li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <div class="modal fade" id="modal-sm">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <h4 class="modal-title">Payment</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><strong>Lot No:</strong></td>
                                                    <td>#LOT001</td>
                                                    <td><strong>Invoice:</strong></td>
                                                    <td>#007612</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Start Time:</strong></td>
                                                    <td>24.01.2024</td>
                                                    <td><strong>End Time:</strong></td>
                                                    <td>26.01.2024</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Grade:</strong></td>
                                                    <td>1st Grade</td>
                                                    <td><strong>Quantity:</strong></td>
                                                    <td>78</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Garden:</strong></td>
                                                    <td>Garden 1</td>
                                                    <td><strong>Net Weight (Kgs):</strong></td>
                                                    <td>2.5 Kgs</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Base Price:</strong></td>
                                                    <td>100</td>
                                                    <td><strong>High Price:</strong></td>
                                                    <td>400</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="modal-autobid">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form>
                            <div class="modal-header">
                                <h4 class="modal-title">Auto Bid</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><strong>Lot No:</strong></td>
                                                    <td>#LOT001</td>
                                                    <td><strong>Invoice:</strong></td>
                                                    <td>#007612</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Start Time:</strong></td>
                                                    <td>24.01.2024</td>
                                                    <td><strong>End Time:</strong></td>
                                                    <td>26.01.2024</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Grade:</strong></td>
                                                    <td>1st Grade</td>
                                                    <td><strong>Quantity:</strong></td>
                                                    <td>78</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Garden:</strong></td>
                                                    <td>Garden 1</td>
                                                    <td><strong>Net Weight (Kgs):</strong></td>
                                                    <td>2.5 Kgs</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Base Price:</strong></td>
                                                    <td>100</td>
                                                    <td><strong>High Price:</strong></td>
                                                    <td>400</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Min Bid Price:</label>
                                        <input type="text" class="form-control" id="bid_price" placeholder="Enter Min Bid Price">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="name">Max Bid Price:</label>
                                        <input type="text" class="form-control" id="bid_price" placeholder="Enter Max Bid Price">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
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
                                    <h3 class="card-title">View Bidding Center</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Grade:</label>
                                        <select name="grade" class="form-Control">
                                            <option value="">Select Grade</option>
                                            <option value="1" selected>BOP</option>
                                            <option value="2">BOPL</option>
                                            <option value="3">BOPF</option>
                                        </select>
                                    </div>
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                            <th>#</th>
                                                <th>Date</th>
                                                <th>Close Date</th>
                                                <th>Lot No</th>
                                                <th>Mark</th>
                                                <th>Grade</th>
                                                <th>Base Price</th>
                                                <th>High Price</th>
                                                <th>No. of Pkg</th>
                                                <th>Each NetKgs</th>
                                                <th>Total Bid(s)</th>
                                                <th>Status</th>
                                                <th>Manual Bid</th>
                                                <th>Auto Bid</th>
                                                <th>Bid History</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                                <td>1</td>
                                                <td>25-01-2024</td>
                                                <td>30-01-2024</td>
                                                <td>AUCT-123</td>
                                                <td>NANITHAA ESTATE</td>
                                                <td>BOP</td>
                                                <td>6000</td>
                                                <td>7500</td>
                                                <td>75</td>
                                                <td>3kgs</td>
                                                <td>40</td>
                                                <td>
                                                    <p class="text-warning"><b>Ongoing</b></p>
                                                </td>
                                                <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" placeholder="Amount" class="form-control">
                                                    <span class="input-group-append">
                                                    <button type="button" class="btn btn-success btn-flat"><i class="fas fa-check"></i></button>
                                                    </span>
                                                </div>
                                                </td>
                                                <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" placeholder="Min">
                                                    <input type="text" placeholder="Max" class="form-control">
                                                    <span class="input-group-append">
                                                    <button type="button" class="btn btn-success btn-flat"><i class="fas fa-check"></i></button>
                                                    </span>
                                                </div>
                                                </td>
                                                <td>Puspam Traders</td>
                                                <td> <a href="#" data-toggle="modal" data-target="#modal-sm" class="btn btn-dark-cyne" id="#" style="margin-right: 5px;"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>20-01-2024</td>
                                                <td>24-01-2024</td>
                                                <td>AUCT-587</td>
                                                <td>NANITHAA ESTATE</td>
                                                <td>BOP</td>
                                                <td>8000</td>
                                                <td>8500</td>
                                                <td>30</td>
                                                <td>8kgs</td>
                                                <td>72</td>
                                                <td>
                                                    <p class="text-success bold"><b>Closed</b></p>
                                                </td>
                                                <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" placeholder="Amount" class="form-control">
                                                    <span class="input-group-append">
                                                    <button type="button" class="btn btn-success btn-flat"><i class="fas fa-check"></i></button>
                                                    </span>
                                                </div>
                                                </td>
                                                <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" placeholder="Min">
                                                    <input type="text" placeholder="Max" class="form-control">
                                                    <span class="input-group-append">
                                                    <button type="button" class="btn btn-success btn-flat"><i class="fas fa-check"></i></button>
                                                    </span>
                                                </div>
                                                </td>
                                                <td>Puspam Traders</td>
                                                <td> <a href="#" data-toggle="modal" data-target="#modal-sm" class="btn btn-dark-cyne" id="#" style="margin-right: 5px;"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                           

                                        </tbody>

                                    </table>
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