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
  <style>
    @media print {
      @page {
        margin: 0;
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
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active">Inward Reports</li>
              </ol>
            </div>
            <div class="col-sm-6">

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
                </div>
                <div class="card-header">
                  <h3 class="card-title">Inward Reports</h3>
                  <input type="hidden" value="inward_report" class="excel-file-name">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col col-3">
                      <div class="form-group">
                        <label for="name">Garden</label>
                        <select class="form-control" name="garden_id" id="garden_id">
                          <option value="">All</option>
                          <?php foreach ($garden_list['garden'] as $key => $val) : ?>
                            <option value="<?php echo $val['id'] ?>"><?php echo $val['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col col-3">
                      <div class="form-group">
                        <label for="name">Grade</label>
                        <select class="form-control" name="grade_id" id="grade_id">
                          <option value="">All</option>
                          <?php foreach ($grade_list['grade'] as $key => $val1) : ?>
                            <option value="<?php echo $val1['id'] ?>"><?php echo $val1['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col col-3">
                      <div class="form-group">
                        <label for="warehouse">Warehouse</label>
                        <select class="form-control" name="warehouse_id" id="warehouse_id">
                          <option value="">All</option>
                          <?php
                          foreach ($warehouse_list['warehouse'] as $val2) :
                          ?>
                            <option value="<?php echo $val2['id']; ?>"><?php echo @$val2['name']; ?></option>
                          <?php endforeach;
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="col col-3">
                      <div class="form-group">
                        <label for="name">Date Filter</label>
                        <div class="card-tools">
                          <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                            <i class="far fa-calendar-alt"></i>
                          </button>
                        </div>
                      </div>
                    </div>


                  </div>
                  <div class="row">
                    <table class="table table-striped"  id="excel-export-table">
                      <thead>
                        <tr>
                          <th>Inv No</th>
                          <th>Grade</th>
                          <th>Garden</th>
                          <th>Warehouse</th>
                          <th>No. of Bags</th>
                          <th>S.No From</th>
                          <th>S.No To</th>
                          <th>Weight Nett</th>
                          <th>Weight Tare</th>
                          <th>Weight Gross</th>
                          <th>Total Wt. Nett.</th>
                          <th>Total Wt. Tare</th>
                          <th>Total Wt. Gross</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if ((isset($response_data)) && count(@$response_data['inwardItems'])) {
                          foreach ($response_data['inwardItems'] as $key => $items) :
                        ?>
                            <tr>
                              <td><?php echo $items['invoice_id']; ?></td>
                              <td><?php echo $items['grade_name']; ?></td>
                              <td><?php echo $items['garden_name']; ?></td>
                              <td><?php echo $items['warehouse_name']; ?></td>
                              <td><?php echo $items['no_of_bags']; ?></td>
                              <td><?php echo $items['sno_from']; ?></td>
                              <td><?php echo $items['sno_to']; ?></td>
                              <td><?php echo $items['weight_net']; ?></td>
                              <td><?php echo $items['weight_tare']; ?></td>
                              <td><?php echo $items['weight_gross']; ?></td>
                              <td><?php echo $items['total_net']; ?></td>
                              <td><?php echo $items['total_tare']; ?></td>
                              <td><?php echo $items['total_gross']; ?></td>
                            </tr>
                          <?php
                          endforeach;
                        } else {
                          ?>
                          <tr>
                            <td colspan="13">No records found</td>
                          </tr>
                        <?php
                        }
                        ?>

                      </tbody>
                    </table>
                  </div>
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
          var fromDate = start.format('YYYY-MM-DD');
          var toDate = end.format('YYYY-MM-DD');
          var grade_id = $('#grade_id').val();
          var warehouse_id = $('#warehouse_id').val();
          var garden_id = $('#garden_id').val();

          $.ajax({
            type: "POST",
            url: "<?= @basePath ?>USER/InwardSearchFilter",
            data: {
              from_date: fromDate,
              to_date: toDate,
              grade_id: grade_id,
              warehouse_id: warehouse_id,
              garden_id: garden_id,
            },
            dataType: 'json',
            success: function(response) {
              var dataTable = $('#example2').DataTable();
              dataTable.clear().draw();

              if (response && response.data && response.data.length > 0) {
                $.each(response.data, function(index, item) {
                  var row = [
                    item.invoice_id,
                    item.grade_name,
                    item.garden_name,
                    item.warehouse_name,
                    item.no_of_bags,
                    item.sno_from,
                    item.sno_to,
                    item.weight_net,
                    item.weight_tare,
                    item.weight_gross,
                    item.total_net,
                    item.total_tare,
                    item.total_gross
                  ];

                  dataTable.row.add(row);
                });
                dataTable.draw();
              } else {
                console.error("No data received or empty data array.");
              }
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


      $(document).on("change", "#warehouse_id,#garden_id,#grade_id", function(event) {
        var grade_id = $('#grade_id').val();
        var warehouse_id = $('#warehouse_id').val();
        var garden_id = $('#garden_id').val();

        $.ajax({
          url: "<?= @basePath ?>USER/InwardSearchFilter",
          type: "POST",
          data: {
            grade_id: grade_id,
            warehouse_id: warehouse_id,
            garden_id: garden_id,
          },
          dataType: 'json',
          success: function(response) {
            var dataTable = $('#example2').DataTable();
            dataTable.clear().draw();

            if (response && response.data && response.data.length > 0) {
              $.each(response.data, function(index, item) {
                var row = [
                  item.invoice_id,
                  item.grade_name,
                  item.garden_name,
                  item.warehouse_name,
                  item.no_of_bags,
                  item.sno_from,
                  item.sno_to,
                  item.weight_net,
                  item.weight_tare,
                  item.weight_gross,
                  item.total_net,
                  item.total_tare,
                  item.total_gross
                ];

                dataTable.row.add(row);
              });
              dataTable.draw();
            } else {
              console.error("No data received or empty data array.");
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });
    </script>
</body>

</html>