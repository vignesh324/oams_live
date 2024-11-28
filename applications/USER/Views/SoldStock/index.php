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
                <li class="breadcrumb-item active">Sold Stock</li>
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
                  <h3 class="card-title">Sold Stock Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sale No</th>
                        <th>LOT No</th>
                        <th>Tea Grade</th>
                        <th>Garden</th>
                        <th>No of Bags</th>
                        <th>Each Net</th>
                        <th>Total Net</th>
                        <th>Buyer Name</th>
                        <th>Deal Price</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // echo '<pre>';print_r($response_data);exit;
                      foreach ($response_data as $key => $val) : ?>
                        <tr>
                          <td><?php echo $val['sale_no']; ?></td>
                          <td><?php echo $val['lot_no']; ?></td>
                          <td><?php echo $val['grade_name']; ?></td>
                          <td><?php echo $val['garden_name']; ?></td>
                          <td><?php echo $val['qty']; ?></td>
                          <td><?php echo $val['weight_net']; ?></td>
                          <td><?php echo ($val['weight_net'] * $val['qty']) - $val['auction_items_sample_quantity']; ?></td>
                          <td><?php echo $val['highest_bidder_name']; ?></td>
                          <td><?php echo $val['highest_bid_price']; ?></td>
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