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
  <style>
    .table-bordered th {
      font-size: smaller !important;
    }

    .table td .form-control {
      max-width: 100px;
      height: 30px;
      border: 0px;
      border-radius: 3px;
      padding: 6px 5px;
      font-size: 13px;
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

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/BiddingSession">BiddingSession</a></li>
                <li class="breadcrumb-item active"><?= $title; ?></li>
              </ol>
            </div>
            <div class="col-sm-6">
              &nbsp;
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
                  <h3 class="card-title"><?php echo @$title; ?> Bidding Session</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Sale No: <strong><?php echo @$auction_data['sale_no']; ?></strong><br>
                      Lot count: <?php echo @$auction_data['lot_count']; ?><br>
                      Auction Date : <?php echo date("d-m-Y", strtotime(@$auction_data['date'])); ?><br>
                    </div>

                    <div class="col-sm-4 invoice-col">
                      &nbsp;
                    </div>

                    <div class="col-sm-4 invoice-col">
                      <b>Center Name :</b> <?php echo @$auction_data['center_name']; ?><br>
                      <b>Auction Start Time:</b> <?php echo @$auction_data['start_time']; ?><br>
                      <b>Auction End Time:</b> <?php echo @$auction_data['end_time']; ?><br>
                      <b>Session Time / lot:</b> <?php echo @$auction_data['session_time']; ?> <br>
                    </div>

                  </div>


                  <div class="row">
                    <div class="col-12 table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>S.no</th>
                            <th>Lot No</th>
                            <th>Inv No</th>
                            <th>Garden</th>
                            <th>Grade</th>
                            <th>No of Bags</th>
                            <th>Each Nett</th>
                            <th>Total Nett</th>
                            <th>Base Price</th>
                            <th>Reserve Price</th>
                            <th>Valuation Price</th>
                            <th>Last Sold Price</th>
                            <th>Highest Bidding Price</th>
                            <?php if ($auction_data['settings_buyer_show'] == 1) : ?>
                              <th>Bidder Name</th>
                            <?php endif; ?>
                            <th>Status</th>
                            <!--th>Bidder Name</th-->
                            <th>Warehouse</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $key = 0;
                          if (!empty($completed_data)) {
                            // echo '<pre>';print_r($auction_data);exit;
                            foreach ($completed_data as $auction_item) { ?>
                              <tr>
                                <td><?php echo ++$key; ?></td>
                                <td><?php echo @$auction_item['lot_no']; ?></td>
                                <td><?php echo @$auction_item['invoice_id']; ?></td>
                                <td><?php echo @$auction_item['garden_name']; ?></td>
                                <td><?php echo @$auction_item['grade_name']; ?></td>
                                <td><?php echo @$auction_item['auction_quantity']; ?></td>
                                <td><?php echo @$auction_item['weight_net']; ?></td>
                                <td><?php echo number_format(@$auction_item['auction_quantity'] * @$auction_item['weight_net'], 3); ?></td>
                                <td><?php echo !empty($auction_item['base_price']) ? $auction_item['base_price'] : '-'; ?></td>
                                <td><?php echo !empty($auction_item['reverse_price']) ? $auction_item['reverse_price'] : '-'; ?></td>
                                <td><?php echo !empty($auction_item['valuation_price']) ? $auction_item['valuation_price'] : '-'; ?></td>
                                <td><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                                <?php
                                $bid_price = !empty($auction_item['highest_bid_price']) ? $auction_item['highest_bid_price'] : '0';

                                $status = '';

                                if ($bid_price >= $auction_item['reverse_price'] && $bid_price != 0) {
                                  $status = '<span class="badge badge-success">Sold</span>';
                                  $status1 = 1;
                                } else {
                                  $status = '<span class="badge badge-danger">Unsold</span>';
                                  $status1 = 0;
                                }
                                if ($auction_item['is_withdrawn'] == 1) {
                                  $status = '<span class="badge badge-warning">Withdrawn</span>';
                                }
                                ?>
                                <td>
                                  <?php echo !empty($auction_item['highest_bid_price']) && $status1 != 0 ? $auction_item['highest_bid_price'] : '-'; ?>
                                </td>
                                <?php if ($auction_data['settings_buyer_show'] == 1) : ?>
                                  <td><?php echo isset($auction_item['highest_bidder_name']) && $status1 != 0 ? $auction_item['highest_bidder_name'] : '-'; ?></td>
                                <?php endif; ?>

                                <td>
                                  <?php echo $status; ?>
                                </td>
                                <td><?php echo $auction_item['warehouse_name']; ?></td>
                              </tr>
                            <?php
                            }
                          } else { ?>
                            <tr>
                              <td colspan="12" class="text-center">No data found</td>
                            </tr>
                          <?php
                          } ?>
                        </tbody>
                      </table>

                    </div>

                  </div>
                  <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">

                  <div class="float-right">
                    <a href="#" class="btn btn-default mr-2">Back</a>
                    <button id="is-completed" class="btn btn-primary">Complete Auction</button>
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
      $(document).on('click', '#is-completed', function(e) {
        e.preventDefault(); // Prevent the default form submission behavior
        var auction_id = <?php echo @$auction_data['id']; ?>;
        // console.log(auction_id);
        // Show a confirmation dialog
        Swal.fire({
          title: 'Are you sure?',
          text: 'You want to Complete this Auction.',
          icon: 'success',
          showCancelButton: true,
          confirmButtonColor: '#65c655',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Complete!'
        }).then((result) => {
          if (result.isConfirmed) {

            // Submit the form using AJAX
            $.ajax({
              url: "<?= @basePath ?>USER/BiddingSession/closeBidding",
              type: 'post',
              data: {
                auction_id: auction_id
              },
              success: function(response) {
                Swal.fire({
                  icon: "success",
                  title: "Success!",
                  text: "Form submitted successfully",
                }).then((result) => {
                  //return false;
                  if (result.isConfirmed || result.isDismissed) {
                    window.location.href = "<?= @basePath ?>USER/BiddingSession";
                  }
                });
              },
              error: function(response) {
                $('.error').remove();
                if (response.status === 422) {
                  var errors = response.responseJSON.errors;
                  $.each(errors, function(key, value) {
                    if ($("input#" + key).length != 0)
                      $("input#" + key).after('<span class="error ">' + value + "</span>");
                    else if ($("select#" + key).length != 0)
                      $("select#" + key).after('<span class="error">' + value + "</span>");
                    else
                      $('#' + key).after('<span class="error">' + value + '</span>');
                  });
                } else if (response.status === 500) {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Internal Server Error',
                  });
                }
              }
            });
          }
        });
      });
    </script>
</body>

</html>