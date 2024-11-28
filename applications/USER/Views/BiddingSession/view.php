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

    @media print {
      #print-btn {
        display: none;
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

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/BiddingSession">BiddingSession </a></li>
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
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?php echo @$title; ?> Bidding Session</h3>
                  <div class="float-right">
                    <a href="#" class="btn btn-default" id="print-btn" onclick="printDiv('printableArea')">Print</a>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="printableArea">
                  <div class="invoice p-3 mb-3" id="invoiceDiv">

                    <div class="row invoice-info">
                      <div class="col-sm-4 invoice-col">
                        Sale No: <strong><?php echo @$auction_data['sale_no']; ?></strong><br>
                        <!-- Lot count: <?php //echo @$auction_data['lot_count']; 
                                        ?><br> -->
                        Auction Date : <?php echo date("d-m-Y", strtotime(@$auction_data['date'])); ?><br>
                      </div>

                      <div class="col-sm-4 invoice-col">
                        &nbsp;
                      </div>

                      <div class="col-sm-4 invoice-col">
                        <b>Center Name :
                          <?php
                          foreach ($centers as $center) {
                            if ($auction_data['center_id'] == $center['id']) {
                              echo $center['name'];
                              break;
                            }
                          }
                          ?>
                        </b><br>
                        <b>Auction Start Time:</b> <?php echo @$auction_data['start_time']; ?><br>
                        <b>Auction End Time:</b> <?php echo @$auction_data['end_time']; ?><br>
                        <!-- <b>Session Time / lot:</b> <?php //echo @$auction_data['session_time']; 
                                                        ?> <br> -->
                      </div>

                    </div>


                    <div class="row">
                      <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Lot No</th>
                              <th>Inv No</th>
                              <th>Garden</th>
                              <th>Grade</th>
                              <th>No of Bags</th>
                              <th>Sample Quantity</th>
                              <th>Each Nett</th>
                              <th>Total Nett</th>
                              <th>Base Price</th>
                              <th>Reserve Price</th>
                              <th>Valuation Price</th>
                              <th>Last Sold Price</th>
                              <th>Deal Price</th>
                              <?php if ($inward_data[0]['settings_buyer_show'] == 1) { ?>
                                <th>Buyer Name</th>
                              <?php } ?>
                              <th>Status</th>
                              <!-- <th>Last sold price</th> -->
                              <th>Warehouse</th>
                              <th>History</th>
                              <!-- <th>Upcoming History</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $key = 0;
                            if (!empty($inward_data)) {
                              foreach ($inward_data as $auction_item) { ?>
                                <tr>
                                  <td><?php echo $auction_item['lot_no']; ?></td>
                                  <td><?php echo $auction_item['invoice_id']; ?></td>
                                  <td><?php echo $auction_item['garden_name']; ?></td>
                                  <td><?php echo $auction_item['grade_name']; ?></td>
                                  <td><?php echo $auction_item['auction_quantity']; ?></td>
                                  <td><?php echo $auction_item['sample_quantity']; ?></td>
                                  <td><?php echo $auction_item['weight_net']; ?></td>
                                  <td><?php echo number_format($auction_item['auction_quantity'] * $auction_item['weight_net'], 3); ?></td>
                                  <td><?php echo !empty($auction_item['base_price']) ? $auction_item['base_price'] : 0; ?></td>
                                  <td><?php echo !empty($auction_item['reverse_price']) ? $auction_item['reverse_price'] : 0; ?></td>
                                  <td><?php echo !empty($auction_item['valuation_price']) ? $auction_item['valuation_price'] : 0; ?></td>
                                  <td><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                                  <?php
                                  $startDateTime = strtotime($auction_data['date'] . ' ' . $auction_item['start_time']);
                                  $endDateTime = strtotime($auction_data['date'] . ' ' . $auction_item['end_time']);
                                  $now = time();
                                  $diff = $startDateTime - $now;
                                  $hoursDifference = $diff / (60 * 60);

                                  $bid_price = !empty($auction_item['highest_bid_price']) ? $auction_item['highest_bid_price'] : '0';

                                  $status = '';
                                  $status1 = '';
                                  if ($bid_price >= $auction_item['reverse_price'] && $bid_price != 0) {
                                    $status = '<span class="badge badge-success">Sold</span>';
                                    $status1 = 1;
                                  } elseif ($now < $endDateTime) {
                                    $status = '<span class="badge badge-info">Pending</span>';
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
                                  <?php if ($inward_data[0]['settings_buyer_show'] == 1) : ?>
                                    <td><?php echo isset($auction_item['highest_bidder_name']) && $status1 != 0 ? $auction_item['highest_bidder_name'] : '-'; ?></td>
                                  <?php endif; ?>

                                  <td>
                                    <?php echo $status; ?>
                                  </td>
                                  <!-- <td><?php //echo $auction_item['high_price']; 
                                            ?></td> -->
                                  <td><?php echo $auction_item['warehouse_name']; ?></td>
                                  <td class="d-flex">
                                    <a href="#" class="btn btn-dark-cyne edit_button" onclick="showAuctionBiddings(<?php echo @$auction_item['id']; ?>)" title="View Bid(s)" id="ids" data-toggle="modal" data-target="#modal-sm"><span><i class="fa fa-eye" title="View"></i></span></a>
                                    <a href="#" class="btn btn-dark-cyne" onclick="showAuctionBiddings1(<?php echo @$auction_item['id']; ?>)" title="View Upcoming Bid(s)" id="ids" data-toggle="modal" data-target="#modal-sm"><span><i class="fa fa-calendar" title="View Upcoming Bids"></i></span></a>
                                  </td>
                                  
                                </tr>
                              <?php
                              }
                            } else { ?>
                              <tr>
                                <td colspan="13" class="text-center">No data found</td>
                              </tr>
                            <?php
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>

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
      function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
      }

      function showAuctionBiddings(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/AuctionBiddings/Show",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(error) {
            $(".loading").hide();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred",
            });
          }
        });
      };

      function showAuctionBiddings1(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/AuctionBiddings/Show1",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(error) {
            $(".loading").hide();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred",
            });
          }
        });
      };
    </script>
</body>

</html>