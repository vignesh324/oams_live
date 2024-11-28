<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=@CompanyName?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?=@$header?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?=@$sidebar?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Inward</li>
            </ol>
          </div>
          <div class="col-sm-6">
            <a class="btn btn-app float-sm-right"  href="<?=@basePath?>USER/Inward/Add">
              <span class="badge bg-purple">New</span>
              <i class="fas fa-users"></i> ADD
            </a>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- /.modal -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Inward Management</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Arrival Date</th>
                    <th>Center</th>
                    <th>Seller</th>
                    <th>Garden</th>
                    <th>Warehouse</th>
                    <th>Total Qty</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
                    <td>20-01-2024</td>
                    <td>Coimbatore</td>
                    <td>3 roses</td>
                    <td>Nilgri</td>
                    <td>Goodown1</td>
                    <td>250kg</td>
                    <td>
                    <a href="#" class="btn btn-dark-cyne edit_button" id="#" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i>  </a> |
                    <a href="#" title="Delete" id="ids" class="btn btn-dark-cyne edit_button"><i class="fa fa-trash-alt"></i></a>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>20-01-2024</td>
                    <td>Coimbatore</td>
                    <td>3 roses</td>
                    <td>Ooty</td>
                    <td>Goodown1</td>
                    <td>1000kg</td>
                    <td>
                    <a href="#" class="btn btn-dark-cyne edit_button" id="#" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i>  </a> |
                    <a href="#" title="Delete" id="ids" class="btn btn-dark-cyne edit_button"><i class="fa fa-trash-alt"></i></a>
                    </td>
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
  <?=@$footer?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?=@basePath?>admin_assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=@basePath?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?=@basePath?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/jszip/jszip.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=@basePath?>admin_assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=@basePath?>admin_assets/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
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
