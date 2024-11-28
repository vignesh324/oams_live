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
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

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
              <h1 class="m-0">Bidding Centers</h1>
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
          <?php foreach ($response_data['centers'] as $key => $value) : ?>
            <div class="col-lg-4">

                <div class="card">
                  <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                      <h2 class="card-title"><?php echo @$value['name']; ?></h2>
                      
                      <?php if(!empty($value['auctionItems'])) { ?>
                      <a class="btn btn-primary" href="<?= @basePath ?>BUYER/BiddingCenter/<?php echo base64_encode($value['id']);?>">Open</a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <tr>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Bags</th>
                      </tr>
                      <?php 
                      if(!empty($value['auctionItems']))
                      {
                      foreach($value['auctionItems'] as $k => $auction)
                      {
                        ?>
                      <tr>
                        <td><?php echo $auction['category_name'];?></td>
                        <td>
                          <a href="#">
                            <?php 
                            if($auction['gradetype']==1)
                              echo 'LEAF';
                            else
                              echo 'DUST';
                            ?>
                          </a>
                        </td>
                        <td><?php echo $auction['total_auction_qty'];?></td>
                      </tr>
                      <?php } 
                      }
                      else
                      {
                      ?>
                      <tr>
                        <td colspan="3">No active Bids</td>
                      </tr>
                      <?php } ?>
                      
                    </table>
                  </div>
                </div>
              

            </div>
            <?php endforeach; ?>
          <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?= @$footer ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE -->
  <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="<?= @basePath ?>admin_assets/plugins/chart.js/Chart.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="<?= @basePath ?>admin_assets/dist/js/pages/dashboard3.js"></script>
</body>

</html>