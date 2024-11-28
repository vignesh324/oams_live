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
                <li class="breadcrumb-item active">Warehouse Archive Stock</li>
              </ol>
            </div>
            <!-- <div class="col-sm-6">
            <button class="btn btn-app float-sm-right" data-toggle="modal" data-target="#modal-sm">
              <span class="badge bg-purple">New</span>
              <i class="fas fa-users"></i> ADD
            </button>
          </div> -->
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
                  <h3 class="card-title">Warehouse Archive Stock Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Tea Grade</th>
                        <th>Chest/Bag Type </th>
                        <th>No of Bag/Chest</th>
                        <th>Gross Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php
                      $sno=1; 
                      foreach ($response_data['warehousearchivestock'] as $key => $value) { ?>
                        <tr>
                          <td><?php echo $sno++; ?></td>
                          <td><?php echo $value['invoice_id']; ?></td>
                          <td><?php echo $value['grade_name']; ?></td>
                          <td><?php echo ($value['bag_type'] == 1) ? 'Bag' : 'Chest'; ?></td>
                          <td><?php echo $value['no_of_bags']; ?></td>
                          <td><?php echo $value['total_gross']; ?></td>
                        </tr>
                      <?php } ?>

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