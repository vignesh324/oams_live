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
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
                <li class="breadcrumb-item active">Garden Grade</li>
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
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form>
              <div class="modal-header">
                <h4 class="modal-title">Assign Garden Grade</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">

                  <div class="form-group">
                    <label for="name">Grade</label>
                    <div class="select2-purple">
                    <select class="select2" multiple="multiple" data-placeholder="Select Garden" data-dropdown-css-class="select2-purple" style="width: 100%;">
                      <option>Select Grade</option>
                      <option value="1">Grade 1</option>
                      <option value="2" selected>Grade 2</option>
                      <option value="3" selected>Grade 3</option>
                    </select>
                    </div>  
                  </div>

                  <div class="timeline">
                    <div>
                      <div class="timeline-item">
                      <span class="time"><a href="#"><i class="fas fa-caret-down"></i></a></span>
                      <h3 class="timeline-header no-border"><a href="#">Grade 2</a></h3>
                      </div>
                    </div>
                    <div>
                      <div class="timeline-item">
                      <span class="time"><a href="#"><i class="fas fa-sort-up"></i></a></span>
                      <h3 class="timeline-header no-border"><a href="#">Grade 3</a></h3>
                      </div>
                    </div>
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
      <!-- /.modal -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Garden Grade Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Garden Name</th>
                        <th>Grade Name</th>
                        <th>Order</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Area</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data['garden_grade'] as $key => $data) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $data['garden_name']; ?></td>
                          <td><?php echo $data['grade_name']; ?></td>
                          <td><?php echo $data['seller_name']; ?></td>
                          <td><?php echo $data['state_name']; ?></td>
                          <td><?php echo $data['city_name']; ?></td>
                          <td><?php echo $data['area_name']; ?></td>
                        </tr>
                      <?php endforeach; ?>
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
  <script src="<?= @basePath ?>admin_assets/plugins/select2/js/select2.full.min.js"></script>
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

      $('.select2').select2();

      $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    
    });
  </script>
</body>

</html>