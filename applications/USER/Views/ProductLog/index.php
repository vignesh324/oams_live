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
  <!-- Date Range -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
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
                <li class="breadcrumb-item active">Product Log</li>
              </ol>
            </div>
            <div class="col-sm-6">

            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-l">
          <div class="modal-content">

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
                  <h3 class="card-title">Product Log</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                      <i class="far fa-calendar-alt"></i>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data as $key => $value) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td>
                            <?php
                            echo $value['invoice_no'];
                            if (!empty($value['lot_no'])) {
                              echo ' - ' . $value['lot_no'];
                            }
                            echo ' ' . $value['status'] . ' with quantity of ' . $value['qty'] . ' from garden ' . $value['garden_name'] . ' of grade ' . $value['grade_name'];
                            ?>
                          </td>
                          <td>
                            <?php
                            echo date('d-m-Y h:i', strtotime($value['created_at']));
                            ?>
                          </td>

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
    <?= @$data['footer']; ?>

    <script>
      $(document).ready(function() {
        // Initialize date range picker
        $('.daterange').daterangepicker({
          opens: 'right',
          locale: {
            format: 'MM/DD/YYYY'
          },
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
         
        }, function(start, end, label) {
          // Callback function when a predefined range is clicked
          var fromDate = start.format('YYYY-MM-DD');
          var toDate = end.format('YYYY-MM-DD');

          // Perform AJAX request
          $.ajax({
            type: "POST",
            url: "<?= @basePath ?>USER/ProductLogByDate",
            data: {
              from_date: fromDate,
              to_date: toDate
            },
            dataType: 'json',
            success: function(response) {
              // Clear the existing rows in the DataTable
              var dataTable = $('#example1').DataTable();
              dataTable.clear();

              // Loop through the response data and append rows
              $.each(response, function(key, value) {
                var row = [
                  key + 1,
                  value.invoice_no + (value.lot_no ? ' - ' + value.lot_no : '') + ' ' +
                  value.status + ' with quantity of ' + value.qty + ' from garden ' +
                  value.garden_name + ' of grade ' + value.grade_name,
                  formatDateTime(value.created_at)
                ];
                dataTable.row.add(row);
              });

              // Redraw the DataTable with the new data
              dataTable.draw();
            },
            error: function(error) {
              console.error(error);
              // Handle errors here
            }
          });
        });

        // Function to format date and time
        function formatDateTime(dateTime) {
          var date = new Date(dateTime);
          var day = ('0' + date.getDate()).slice(-2);
          var month = ('0' + (date.getMonth() + 1)).slice(-2);
          var year = date.getFullYear();
          var hours = ('0' + date.getHours()).slice(-2);
          var minutes = ('0' + date.getMinutes()).slice(-2);

          return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
        }
      });
    </script>
</body>

</html>