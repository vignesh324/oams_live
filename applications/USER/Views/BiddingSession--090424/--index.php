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
                <li class="breadcrumb-item active">Bidding Session</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-app float-sm-right" href="<?= @basePath ?>USER/BiddingSession/Add">
                <span class="badge bg-purple">New</span>
                <i class="fas fa-users"></i> ADD
              </a>
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
                  <h3 class="card-title">Bidding Session</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Auction Date</th>
                        <th>Center</th>
                        <th>Start Time</th>
                        <th>End time</th>
                        <th>Bidder Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data as $key => $val) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo date("d-m-Y", strtotime($val['date'])); ?></td>
                          <td><?php echo $val['center_name']; ?></td>
                          <td><?php echo $val['start_time']; ?></td>
                          <td><?php echo $val['end_time']; ?></td>
                          <td>tbs</td>
                          <td><span class="badge badge-success">Active</span></td>
                          <td>
                            <a class="btn btn-dark-cyne view_button" href="<?= @basePath ?>USER/BiddingSession/View/<?php echo base64_encode($val['id']); ?>">
                              <span><i class="fa fa-eye"></i></span>
                            </a>
                            |
                            <a class="btn btn-dark-cyne edit_button" onclick="editBiddingSession(<?php echo $val['id']; ?>)" href="#">
                              <span><i class="fa fa-clock"></i></span>
                            </a>
                            |
                            <a class="btn btn-dark-cyne edit_button" href="<?= @basePath ?>USER/BiddingSession/View">
                              <span><i class="fa fa-trash-alt"></i></span>
                            </a>
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
      function editBiddingSession(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/BiddingSession/Edit",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(xhr, status, error) {
            $(".loading").hide();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred while editing the session.",
            });
            console.error(xhr.responseText);
          }
        });
      };
    </script>