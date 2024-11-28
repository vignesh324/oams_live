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
  <!-- Date time picker -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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


      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Bidding Session Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <!-- <div class="form-group">
                          <label for="name">Date</label>
                          <input type="date" class="form-control" name="date" id="date" value="<?php echo @$auction_data['date']; ?>" placeholder="Active Lots">
                        </div> -->
                      <div class="form-group">
                        <label>Date</label>
                        <div class="input-group date" id="date" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" name="date" value="<?php echo date("d-m-Y", strtotime(@$auction_data['date'])); ?>" data-target="#date" />
                          <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="center">Center</label>
                        <select class="form-control" name="center_id" id="center_id">
                          <option value="">Select Center</option>
                          <?php
                          foreach ($centers as $key => $values) :
                          ?>
                            <option value="<?php echo $values['id']; ?>" <?php if (@$auction_data['center_id'] == $values['id']) { ?> selected <?php } ?>><?php echo $values['name']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Sale No</label>
                        <input type="text" class="form-control" name="sale_no" id="sale_no" value="<?php echo @$auction_data['sale_no']; ?>" placeholder="Sale No">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Lot count</label>
                        <input type="text" class="form-control" name="lot_count" id="lot_count" value="<?php echo @$auction_data['lot_count']; ?>" placeholder="Lot Count">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Auction Start Time</label>
                        <!-- <input type="time" placeholder="Auction start time" name="start_time" id="start_time" value="<?php echo @$auction_data['start_time']; ?>" class="form-control"> -->

                        <div class="input-group date" id="start_time" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" name="start_time" data-target="#start_time" value="<?php echo @$auction_data['start_time']; ?>">
                          <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="name">Auction End Time</label>
                        <!-- <input type="time" placeholder="Auction end time" name="end_time" id="end_time" class="form-control" value="<?php echo @$auction_data['end_time']; ?>"> -->

                        <div class="input-group date" id="end_time" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" name="end_time" data-target="#end_time" value="<?php echo @$auction_data['end_time']; ?>">
                          <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Session Time / lot</label>
                        <div class="input-group" id="session_time">
                          <input type="text" class="form-control" name="session_time" id="timeInput" placeholder="HH:MM:SS" value="<?php echo @$auction_data['session_time']; ?>">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>



                <div class="card">
                  <div class="card-header">
                    <h5>View Item Details</h5>
                  </div>
                  <div class="card-body">

                    <table class="table table-bordered table-striped">
                      <tr>
                        <th>S.no</th>
                        <th>Inv No</th>
                        <th>Total Quantity</th>
                        <th>Auction Quantity</th>
                        <th>Base Price</th>
                        <th>Reserve Price</th>
                        <th>High Price</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php if (isset($auction_data['auctionItems'])) :
                        ?>
                          <input type="hidden" id="auto_value" value="<?php echo count($auction_data['auctionItems']); ?>">
                          <?php
                          foreach ($auction_data['auctionItems'] as $key => $value) { ?>
                            <tr>
                              <td><span class="auto_inc" id="auto_inc_<?php echo $key + 1; ?>"><?php echo $key + 1; ?></span></td>
                              <td>
                                <select class="form-control invoice_no" name="invoice_no[]" id="invoice_no_<?php echo $key; ?>">
                                  <?php foreach ($inward_data as $inward_key => $inward_value) :
                                    if ($inward_value['id'] == $value['inward_item_id'])
                                      $selected = "selected";
                                    else
                                      $selected = "";
                                  ?>
                                    <option value="<?php echo $inward_value['id']; ?>" <?php echo $selected; ?>><?php echo $inward_value['invoice_id']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </td>
                              <td>
                                <input type="text" name="total_qty[]" class="form-control total_qty" id="total_quantity_<?php echo $key; ?>" placeholder="Total Quantity" value="<?php echo $inward_value['no_of_bags']; ?>" readonly>
                              </td>
                              <td>
                                <input type="text" class="form-control auction_qty" name="auction_quantity[]" id="auction_quantity_<?php echo $key; ?>" placeholder="Auction Quantity" value="<?php echo $value['auction_quantity']; ?>" readonly>
                              </td>
                              <td>
                                <input type="text" class="form-control" name="base_price[]" id="base_price_<?php echo $key; ?>" placeholder="Base Price" value="<?php echo $value['base_price']; ?>" readonly>
                              </td>
                              <td>
                                <input type="text" class="form-control" name="reverse_price[]" id="reserved_price_<?php echo $key; ?>" value="<?php echo $value['reverse_price']; ?>" placeholder="Reserved Price" readonly>
                              </td>
                              <td>
                                <input type="text" class="form-control" name="high_price[]" id="high_price_<?php echo $key; ?>" placeholder="High Price" value="<?php echo $value['high_price']; ?>" readonly>
                              </td>
                            </tr>
                          <?php } ?>
                        <?php endif; ?>
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
      $(function() {
        $('#date').datetimepicker({
          format: 'DD-MM-YYYY'
        });

        $('#end_time').datetimepicker({
          format: 'LT'
        });

        $('#start_time').datetimepicker({
          format: 'LT'
        })
      });
    </script>

</body>

</html>