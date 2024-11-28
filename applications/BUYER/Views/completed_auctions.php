<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= @CompanyName ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
</head>


<body class="hold-transition sidebar-mini layout-top-nav">
  <div class="wrapper">
    <!-- Navbar -->
    <?= @$header ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Completed Auctions</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard </li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-lg-6">

              <div class="card">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between">
                    <h2 class="card-title">Completed Auctions</h2>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <th>Sale No</th>
                        <th>Center</th>
                        <th>Date</th>
                        <th>Time</th>
                      </thead>
                      <tbody>
                        <?php foreach ($response_data as $key => $value) : ?>
                          <?php
                          $startDateTime = strtotime($value['date'] . ' ' . $value['start_time']);
                          $endDateTime = strtotime($value['date'] . ' ' . $value['end_time']);
                          $now = time();

                          // Check if start time is greater than current time

                          ?>
                          <tr>
                            <td>
                              <a href="<?= @basePath ?>BUYER/AuctionDetailsCompleted/<?php echo base64_encode($value['id']); ?>">
                                <?php echo $value['sale_no']; ?>
                              </a>
                            </td>
                            <td><?php echo $value['center_name']; ?></td>
                            <td><?php echo $value['date']; ?></td>
                            <td><?php echo $value['start_time'] . "-" . $value['end_time']; ?></td>
                          </tr>

                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


            </div>

            <!-- /.container-fluid -->
          </div>
          <!-- /.content -->
        </div>
      </div>
      <!-- /.content-wrapper -->
      </div>

      <!-- Main Footer -->
      <?= @$data['footer']; ?>
    <!-- ./wrapper -->


</body>

</html>