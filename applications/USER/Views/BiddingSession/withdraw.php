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

    .highlight {
      border: 2px solid #007BFF !important;
      /* You can change the color as per your preference */
      outline: none !important;
      /* Remove default outline */
      box-shadow: 0 0 5px #007BFF !important;
      /* Optional: Add a shadow for a more pronounced effect */
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
                  <h3 class="card-title"> Withdraw Item</h3>
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
                          <table class="table table-bordered table-striped auction_items_table">
                            <thead>
                              <tr>
                                <th>S.no</th>
                                <th>Lot No</th>
                                <th>Inv No</th>
                                <th>Garden</th>
                                <th>Grade</th>
                                <th>No of Bags</th>
                                <th>Sample Quantity</th>
                                <th>Each Nett</th>
                                <th>Base Price</th>
                                <th>Reserve Price</th>
                                <th>Valuation price</th>
                                <th>Warehouse</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if (!empty($auction_data['auctionItems'])) {
                                foreach (@$auction_data['auctionItems'] as $key => $auction_item) { ?>
                                  <tr>
                                    <td><?php echo @$key + 1; ?></td>
                                    <td><?php echo @$auction_item['lot_no']; ?></td>
                                    <td><?php echo @$auction_item['invoice_id']; ?></td>
                                    <td><?php echo @$auction_item['garden_name']; ?></td>
                                    <td><?php echo @$auction_item['grade_name']; ?></td>
                                    <td><input type="text" readonly class="form-control auction_quantity" name="auction_quantity[]" id="auction_quantity.<?php echo $key; ?>" placeholder="Auction Quantity" value="<?php echo !empty($auction_item['auction_quantity']) ? $auction_item['auction_quantity'] : $auction_item['no_of_bags']; ?>" readonly></td>
                                    <td><?php echo @$auction_item['sample_quantity']; ?></td>
                                    <td><?php echo @$auction_item['weight_net']; ?></td>
                                    <td><input type="text" readonly class="form-control base_price" name="base_price[]" id="base_price.<?php echo $key; ?>" placeholder="Base Price" value="<?php echo !empty($auction_item['base_price']) ? $auction_item['base_price'] : '0'; ?>"></td>
                                    <td><input type="text" readonly class="form-control reserve_price " name="reserve_price[]" id="reserve_price.<?php echo $key; ?>" placeholder="Reserve Price" value="<?php echo !empty($auction_item['reverse_price']) ? $auction_item['reverse_price'] : '0'; ?>"></td>
                                    <td>
                                      <input type="text" readonly class="form-control valuation_price " name="valuation_price[]" id="valuation_price.<?php echo $key; ?>" placeholder="Valuation Price" value="<?php echo !empty($auction_item['valuation_price']) ? $auction_item['valuation_price'] : '0'; ?>">
                                      <input type="hidden" class="form-control auctionitem_id " name="auctionitem_id[]" id="auctionitem_id.<?php echo $key; ?>" value="<?php echo @$auction_item['auctionitem_id']; ?>">
                                      <input type="hidden" class="form-control inward_item_id" name="inward_item_id[]" id="inward_item_id.<?php echo $key; ?>" value="<?php echo @$auction_item['inward_item_id']; ?>">
                                    </td>
                                    <td><?php echo $auction_item['warehouse_name']; ?></td>
                                    <?php if ($auction_data['is_publish'] == 1) { ?>
                                      <td>
                                        <input type="checkbox" class="checkbox-item is_withdrawn" id="is_withdrawn_<?php echo $key; ?>" <?php echo $auction_item['is_withdrawn'] == 1 ? 'checked' : ''; ?>>
                                      </td>
                                    <?php } ?>
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
                          <input type="hidden" class="form-control auction_id" name="auction_id" value="<?php echo @$auction_data['id']; ?>">

                          <div class="float-right">
                            <a href="<?= @basePath ?>USER/BiddingSession" class="btn btn-default">Back</a>
                            <button type="button" id="update-valuation" class="btn btn-primary">Withdraw</button>
                          </div>
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
    $(document).ready(function() {
      var isPublish = <?php echo json_encode($auction_data['is_publish'] ?? 0); ?>;
      console.log(isPublish)
      if (isPublish == 1) {
        // Add the Publish column header to the table
        $('.auction_items_table thead tr').append('<th>Withdraw</th>');
      }
    });

    $(document).on("click", "#update-valuation", function(event) {
      event.preventDefault();
      $('.error').remove();
      $("#update-valuation").attr("disabled", true);

      var url = '<?= @basePath ?>USER/BiddingSession/UpdateValuation';
      var formmethod = 'post';
      var formdata = $('form#update-valuation-form').serialize();
      var allFieldsFilled = true; // Variable to track if all fields are filled

      // Initialize an empty array to store the values of is_withdrawn
      var is_withdrawn_values = [];

      // Loop through each checkbox with the class .is_withdrawn
      $('.is_withdrawn').each(function() {
        if ($(this).prop('checked')) {
          is_withdrawn_values.push(1);
        } else {
          is_withdrawn_values.push(0);
        }
      });

      // To send is_withdrawn_values as an array in formdata, convert it to a string with proper serialization
      var is_withdrawn_serialized = $.param({
        'is_withdrawn[]': is_withdrawn_values
      }, true);
      formdata += '&' + is_withdrawn_serialized;


      $(".auction_items_table tbody tr").each(function(index, row) {
        var row = $(this); // No need to call closest('tr') again
        var auction_quantity_input = row.find('[name="auction_quantity[]"]').val();
        console.log(auction_quantity_input)
        var base_price_input = row.find('[name="base_price[]"]').val();
        var reserve_price_input = row.find('[name="reserve_price[]"]').val();
        var total_quantity_input = row.find('[name="total_quantity[]"]').val();

        // Regular expression to match numeric values
        var numericPattern = /^\d*\.?\d+$/;

        // Validate auction quantity
        if (!numericPattern.test(auction_quantity_input)) {
          row.find('[name="auction_quantity[]"]').closest('td').append('<span class="error">Auction Quantity must be a valid number</span>');
          allFieldsFilled = false;
        }
        var auction_quantity = parseFloat(auction_quantity_input);

        // Validate base price
        if (!numericPattern.test(base_price_input)) {
          row.find('[name="base_price[]"]').closest('td').append('<span class="error">Base Price must be a valid number</span>');
          allFieldsFilled = false;
        }
        var base_price = parseFloat(base_price_input);

        // Validate reserve price
        if (!numericPattern.test(reserve_price_input)) {
          row.find('[name="reserve_price[]"]').closest('td').append('<span class="error">Reserve Price must be a valid number</span>');
          allFieldsFilled = false;
        }
        var reserve_price = parseFloat(reserve_price_input);

        // Check if any value is not greater than 0
        if (auction_quantity <= 0) {
          row.find('[name="auction_quantity[]"]').after('<span class="error">All numeric values must be greater than 0</span>');
          allFieldsFilled = false;
        }

        // Check if auction quantity exceeds total quantity
        if (auction_quantity > total_quantity_input) {
          row.find('[name="auction_quantity[]"]').after('<span class="error">Auction Quantity cannot exceed Total Quantity</span>');
          allFieldsFilled = false;
        }
      });

      // Check if all fields are filled
      if (!allFieldsFilled) {
        return;
      }

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
        },
        complete: function() {
          // Re-enable the submit button after the request is complete
          $("#update-valuation").attr("disabled", false);
        }
      });
    });
    // $(document).ready(function() {
    //             $("table input, table select").focus(function() {
    //               $(this).addClass("highlight");
    //           });
    //           $("table input, table select").blur(function() {
    //               $(this).removeClass("highlight");
    //           });
    //         })
  </script>
</body>

</html>