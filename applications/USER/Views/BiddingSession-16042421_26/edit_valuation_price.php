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
                  <div class="invoice p-3 mb-3">

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
                        <b>Session Time / lot:</b> <?php echo @$auction_data['session_time']; ?> <br>
                      </div>

                    </div>

                    <form id="update-valuation-form">
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
                                <th>Base Price</th>
                                <th>Reserve Price</th>
                                <th>Valuation price</th>
                                <th>Warehouse</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              foreach ($auction_data['auctionItems'] as $key => $auction_item) { ?>
                                <tr>
                                  <td><?php echo $key+1; ?></td>
                                  <td><?php echo $auction_item['lot_no']; ?></td>
                                  <td><?php echo $auction_item['invoice_id']; ?></td>
                                  <td><?php echo $auction_item['garden_name']; ?></td>
                                  <td><?php echo $auction_item['grade_name']; ?></td>
                                  <td><?php echo $auction_item['auction_quantity']; ?></td>
                                  <td><?php echo $auction_item['weight_net']; ?></td>
                                  <td><?php echo $auction_item['base_price']; ?></td>
                                  <td><input type="text" class="form-control reserve_price" name="reserve_price[]" id="reserve_price.<?php echo $key; ?>" placeholder="Reserve Price" value="<?php echo $auction_item['reverse_price']; ?>"></td>
                                  <td>
                                    <input type="text" class="form-control valuation_price" name="valuation_price[]" id="valuation_price.<?php echo $key; ?>" placeholder="Valuation Price" value="<?php echo $auction_item['valuation_price']; ?>">
                                    <input type="hidden" class="form-control auctionitem_id" name="auctionitem_id[]" id="auctionitem_id.<?php echo $key; ?>" value="<?php echo $auction_item['id']; ?>">
                                  </td>
                                  <td><?php echo $auction_item['warehouse_name']; ?></td>
                                </tr>
                              <?php
                              } ?>
                            </tbody>
                          </table>

                        </div>

                        <div class="float-right">
                          <button type="button" class="btn btn-default">Back</button>
                          <button type="button" id="update-valuation" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
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
    $(document).on("click", "#update-valuation", function(event) {
      event.preventDefault();
      var url = '<?= @basePath ?>USER/BiddingSession/UpdateValuation';
      var formmethod = 'post';
      var formdata = $('form#update-valuation-form').serialize();
      console.log(formdata);
      $.ajax({
        url: url,
        type: formmethod,
        data: formdata,
        success: function(_response) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Form submitted successfully',
          }).then((result) => {
            if (result.isConfirmed || result.isDismissed) {
              window.location = "<?= @basePath ?>USER/BiddingSession";
            }
          });

        },
        error: function(_response) {

          var data = $.parseJSON(_response.responseText);

          $('.error').remove();
          if (_response.status === 422) {
            var errors = $.parseJSON(_response.responseText);
            error = errors.errors;
            $.each(data.errors, function(key, value) {
              //console.log(key);
              var modifiedKey = key.includes('.') ? key.split('.').join('\\.') : key;
              if ($("input#" + modifiedKey).length != 0)
                $("input#" + modifiedKey).after('<span class="error ">' + value + "</span>");
              else if ($("select#" + modifiedKey).length != 0)
                $("select#" + modifiedKey).after('<span class="error">' + value + "</span>");
              else
                $("#" + modifiedKey).after('<span class="error">' + value + "</span>");
            });
          } else if (_response.status === 500) {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: "Internal Server Error",
            });
          }
        }
      });
    });
  </script>
</body>

</html>