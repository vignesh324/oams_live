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

    .table td,
    .table th {
      padding: .5rem;
    }

    /* .table td .form-control {
      max-width: 100px;
      height: 30px;
      border: 0px;
      border-radius: 3px;
      padding: 6px 5px;
      font-size: 13px;
    } */
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
                  <div class="row">
                    <div class="col-md-2">
                      <h3 class="card-title"><?php echo @$title; ?> Reserve Price</h3>
                    </div>
                    <div class="col-md-10">

                    </div>
                  </div>

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
                        <b>Center Name :<?php echo @$auction_data['center_name']; ?></b><br>
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
                                <th>Each Nett</th>
                                <th>Total Nett</th>
                                <th>Auction Quantity</th>
                                <th>Sample Quantity</th>
                                <th>Base Price</th>
                                <th>Reserve Price</th>
                                <th>Valuation price</th>
                                <th>Highest Bid Price</th>
                                <?php if ($auction_data['settings_buyer_show'] == 1) { ?>
                                  <th>Highest Bidder</th>
                                <?php } ?>
                                <th>Warehouse</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if (!empty($auction_data['auctionItems'])) {
                                $m = 1;
                                $current_time = date('H:i:s');
                                foreach (@$auction_data['auctionItems'] as $key => $auction_item) {
                                  //if ( (strtotime($auction_item['start_time']) < strtotime($current_time)) && (strtotime($auction_item['end_time']) > strtotime($current_time))) {
                              ?>
                                  <tr <?php if ($auction_item['is_withdrawn'] == 1) echo 'style="background-color: yellow"'; ?>>
                                    <td><?php echo @$key + 1; ?></td>
                                    <td><?php echo @$auction_item['lot_no']; ?></td>
                                    <td><?php echo @$auction_item['invoice_id']; ?></td>
                                    <td><?php echo @$auction_item['garden_name']; ?></td>
                                    <td><?php echo @$auction_item['grade_name']; ?></td>
                                    <td><?php echo @$auction_item['no_of_bags']; ?></td>
                                    <td><?php echo @$auction_item['weight_net']; ?></td>
                                    <td><?php echo @$auction_item['weight_net'] * @$auction_item['no_of_bags']; ?></td>
                                    <td><?php echo @$auction_item['auction_quantity']; ?></td>
                                    <td><?php echo @$auction_item['sample_quantity']; ?></td>
                                    <td><?php echo !empty($auction_item['base_price']) ? $auction_item['base_price'] : '0'; ?></td>
                                    <td>
                                      <div class="input-group">
                                        <input type="text" class="form-control reserve_price highlight" name="reserve_price" id="reserve_price.<?php echo $key; ?>" placeholder="Reserve Price" value="<?php echo !empty($auction_item['reverse_price']) ? $auction_item['reverse_price'] : '0'; ?>" <?php if ($auction_item['is_withdrawn'] == 1) echo 'readonly' ?> fdprocessedid="vqvk2o">
                                        <span class="input-group-append">
                                          <a class="btn btn-success" href="#" id="update-reserve-price">
                                            <i class="fa fa-check"></i>
                                          </a>
                                        </span>
                                      </div>
                                    </td>
                                    <td>
                                      <?php echo !empty($auction_item['valuation_price']) ? $auction_item['valuation_price'] : '0'; ?>
                                      <input type="hidden" class="form-control valuation_price" name="valuation_price" id="valuation_price.<?php echo $key; ?>" placeholder="Valuation Price" value="<?php echo !empty($auction_item['valuation_price']) ? $auction_item['valuation_price'] : '0'; ?>" <?php if ($auction_item['is_withdrawn'] == 1) echo 'readonly' ?>>
                                      <input type="hidden" class="form-control auctionitem_id " name="auctionitem_id" id="auctionitem_id.<?php echo $key; ?>" value="<?php echo @$auction_item['auctionitem_id']; ?>">
                                      <input type="hidden" class="form-control auction_id" name="auction_id" value="<?php echo @$auction_data['id']; ?>">
                                      <input type="hidden" class="form-control base_price" name="base_price" id="base_price.<?php echo $key; ?>" placeholder="Base Price" value="<?php echo !empty($auction_item['base_price']) ? $auction_item['base_price'] : '0'; ?>" <?php if ($auction_item['is_withdrawn'] == 1) echo 'readonly' ?>>
                                    </td>
                                    <td>
                                      <div id="highestbid_<?php echo $auction_item['id']; ?>" data-auctionitem="<?php echo $auction_item['id']; ?> "><?php echo $auction_item['bid_price']; ?></div>
                                    </td>
                                    <?php if ($auction_data['settings_buyer_show'] == 1) { ?>
                                      <td>
                                        <div id="highestbidder_<?php echo $auction_item['id']; ?>" data-auctionitem="<?php echo $auction_item['id']; ?> " style="font-weight: bold;"><?php echo @$auction_item['highest_bidder_name']; ?></div>
                                      </td>
                                    <?php } ?>
                                    <td><?php echo $auction_item['warehouse_name']; ?></td>
                                  </tr>
                                <?php
                                  //}
                                  $m++;
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
                            <!-- <button type="button" id="update-reserve" class="btn btn-primary">Save changes</button> -->
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
      // console.log(isPublish)
      var global_id;
      let parts;
      var ws = new WebSocket("ws://eazyhosting.in:8081");

      ws.onopen = function() {
        console.log("WebSocket connected.");
      };

      ws.onmessage = function(event) {
        var message = event.data;
        console.log("Message received: ", event); // Debugging log

        var jsonString = message.replace('Message received: ', '');
        var parsedMessage = JSON.parse(jsonString);
        var bidValue = parsedMessage.bid_value;
        var bidderName = parsedMessage.buyer_name;
        var item_id = parsedMessage.auction_item_value;

        // Check if parts is defined before using it
        // if (!parts) {
        //     console.error("Parts is not defined.");
        //     return;
        // }


        $("#highestbid_" + item_id).text(bidValue);
        $("#highestbidder_" + item_id).text(bidderName);
        document.getElementById("highestbid_" + item_id).value = bidValue;
        document.getElementById("highestbidder_" + item_id).value = bidderName;

        parent.reloadFirstIframe();
        parent.reloadmybookIframe();
      };

      ws.onerror = function(event) {
        console.error("WebSocket error:", event);
      };

      ws.onclose = function() {
        console.log("WebSocket connection closed.");
      };


    });
    // document.addEventListener('DOMContentLoaded', function() {

    //   var item_cnt = '<?php echo count($auction_data['auctionItems']); ?>';

    //   function divreload(divid) {
    //     var parts = divid.split('_');
    //     var lastValue = parts.pop();
    //     var auction_item = $("#auction_bid_price_" + lastValue).attr('data-auctionitem');

    //     const divdata = document.getElementById(divid);
    //     const pricedata = document.getElementById('auction_bidder_' + lastValue);
    //     var url = '<?= @basePath ?>USER/BiddingSession/GetLiveBiddingPrice/' + auction_item;
    //     var formmethod = 'post';
    //     dataType: 'JSON',
    //       $.ajax({
    //         url: url,
    //         type: 'get',
    //         success: function(response) {
    //           console.log(response);
    //           var data = JSON.parse(response); // Parse JSON string to object
    //           if (data.bid_price !== undefined) {
    //             console.log(data);
    //             // Update the bid_price value
    //             //data.bid_price = "New Value"; // Update the value as needed

    //             // Update HTML element with the new value
    //             divdata.innerHTML = data.bid_price;
    //             pricedata.innerHTML = data.buyer_name;

    //             // Convert the updated object back to JSON string
    //             var updatedResponse = JSON.stringify(data);

    //             // Now updatedResponse contains the updated JSON string
    //             console.log(updatedResponse);
    //           }
    //         },
    //         error: function(xhr, status, error) {
    //           console.error(xhr.responseText);

    //         }
    //       });

    //     //divdata.innerHTML = Math.floor(Math.random() * 100); // Example: Update with random number
    //   }

    //   function runLoop() {
    //     for (let i = 1; i <= item_cnt; i++) {
    //       divreload("auction_bid_price_" + i);
    //     }
    //   }

    //   setInterval(runLoop, 2000);

    // });

    $(document).on("click", "#update-reserve-price", function(event) {
      event.preventDefault();
      $('.error').remove();
      $("#update-reserve-price").attr("disabled", true);

      var row = $(this).closest('tr'); // Get the closest row to the clicked button

      var url = '<?= @basePath ?>USER/BiddingSession/UpdateReservePrice';
      var formmethod = 'post';
      var formdata = {
        auction_id: row.find('[name="auction_id"]').val(),
        reserve_price: row.find('[name="reserve_price"]').val(),
        auctionitem_id: row.find('[name="auctionitem_id"]').val(),
        base_price: row.find('[name="base_price"]').val(),
        valuation_price: row.find('[name="valuation_price"]').val(),
        // If your backend requires a CSRF token, add it here:
        // csrf_token_name: $('input[name="csrf_token_name"]').val()
      };

      var allFieldsFilled = true; // Variable to track if all fields are filled

      var reserve_price_input = row.find('[name="reserve_price"]').val();
      // Regular expression to match numeric values
      var numericPattern = /^\d*\.?\d+$/;

      // Validate reserve price
      if (!numericPattern.test(reserve_price_input)) {
        row.find('[name="reserve_price"]').closest('td').append('<span class="error">Reserve Price must be a valid number</span>');
        allFieldsFilled = false;
      }
      var reserve_price = parseFloat(reserve_price_input);

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
          $("#update-reserve-price").attr("disabled", false);
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