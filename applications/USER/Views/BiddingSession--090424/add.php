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
  <!-- BS Stepper -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- Date time picker -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bs-stepper/css/bs-stepper.min.css">
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
    <?php
    //session()->remove('auction_data');
    ?>
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
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
              </ol>
            </div>
            <div class="col-sm-6">
              &nbsp;
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form id="cart-form" action="22121">
              <input type="hidden" class="form-control" id="id" name="id" value="<?php echo @$response_data['id'] ?>">
              <div class="modal-header">
                <h4 class="modal-title">Add Values to cart</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="name">Stock</label>
                      <input type="text" class="form-control stock_qty" name="total_qty" id="modal_total_quantity_stock" placeholder="No of bags" readonly>
                      <input type="hidden" class="form-control garden_name" name="garden_name" id="modal_garden_name" placeholder="Garden name" readonly>
                      <input type="hidden" class="form-control garden_id" name="garden_id" id="modal_garden_id" placeholder="Garden id" readonly>
                      <input type="hidden" class="form-control grade_name" name="grade_name" id="modal_grade_name" placeholder="Grade Name" readonly>
                      <input type="hidden" class="form-control inward_item" name="inward_item" id="modal_inward_item" placeholder="Inward Item" readonly>
                      <input type="hidden" class="form-control grade_id" name="grade_id" id="modal_grade_id" placeholder="grade_id" readonly>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="name">No of Bags</label>
                      <input type="text" class="form-control auction_qty" name="auction_quantity" id="auction_quantity_" value="" placeholder="No of bags">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="name">Base Price</label>
                      <input type="text" class="form-control" name="base_price" id="base_price" placeholder="Base Price">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="name">Reserve Price</label>
                      <input type="text" class="form-control" name="reverse_price" id="reverse_price" placeholder="Reserved Price">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <button type="button" id="store_cart" class="btn btn-primary">Store Cart</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <form id="bidding-session-form" method="post" action="<?= $url; ?>">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-default">
                  <div class="card-header">
                    <h3 class="card-title">Auction Items</h3>
                  </div>
                  <div class="card-body p-0">
                    <div class="bs-stepper">
                      <div class="bs-stepper-header" role="tablist">
                        <!-- your steps here -->
                        <div class="step" data-target="#logins-part">
                          <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" id="logins-part-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Items</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#information-part">
                          <button type="button" class="step-trigger" role="tab" aria-controls="information-part" id="information-part-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Garden</span>
                          </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#session-part">
                          <button type="button" class="step-trigger" role="tab" aria-controls="session-part" id="session-part-trigger">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Session</span>
                          </button>
                        </div>
                      </div>


                      <div class="bs-stepper-content">
                        <!-- your steps content here -->
                        <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                          <div class="card-body">
                            <div class="row">


                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="center">Center</label>
                                  <select class="form-control" name="center_id" id="center_id">
                                    <option value="">Select Center</option>
                                    <?php
                                    foreach (@$centers as $key => $values) :
                                    ?>
                                      <option value="<?php echo $values['id']; ?>" <?php if (@$auction_data['center_id'] == $values['id']) { ?> selected <?php } ?>><?php echo $values['name']; ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label>Date</label>
                                  <div class="input-group date" id="date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" name="date" value="<?php echo isset($auction_data['date']) ? date("d-m-Y", strtotime($auction_data['date'])) : ''; ?>" data-target="#date" />
                                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                  </div>
                                </div>
                              </div>

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
                                  <label>Session Time / lot</label>
                                  <div class="input-group" id="session_time">
                                    <input type="text" class="form-control" name="session_time" id="timeInput" placeholder="HH:MM:SS" value="<?php echo @$auction_data['session_time']; ?>">

                                  </div>
                                </div>

                              </div>

                            </div>
                            <div class="row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="warehouse">Warehouse</label>
                                  <select class="form-control" name="warehouse_id" id="warehouse_id">
                                    <option value="all">All</option>
                                    <?php
                                    foreach (@$warehouses as $key => $val) :
                                    ?>
                                      <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                              </div>

                            </div>
                          </div>



                          <div class="card-body">
                            <table class="table table-bordered table-striped auction-items-table" data-repeater-list="items" id="sortable-table">
                              <thead>
                                <tr>
                                  <th>S.no</th>
                                  <th>Invoice No</th>
                                  <th>Garden name</th>
                                  <th>Grade Name</th>
                                  <th>Warehouse</th>
                                  <th>Total Gross</th>
                                  <th>Quantity</th>
                                  <th>Action</th>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td colspan="9" class="text-center">No data found</td>
                                </tr>
                                <!-- On change -->
                              </tbody>
                            </table>
                            <hr>
                            <h5>Cart Data</h5>
                            <div class="cart_data">
                              <table id="session_data" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Garden Name</th>
                                    <th>Grade</th>
                                    <th>Lot No</th>
                                    <th>Auction Quantity</th>
                                    <th>Base Price</th>
                                    <th>Reserve Price</th>
                                    <th>High Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="float-right m-2">
                            <button type="button" class="btn btn-primary" onclick="moveToNextStep()">Next</button>
                          </div>
                        </div>

                        <div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">

                          <div class="menu-container">
                            <ul id="draggable-menu" class="menu centerGardens d-flex flex-column align-items-center">

                              <?php if (isset($auction_data['auctionItems'])) :

                                foreach (@$centergarden_data as $key => $value) {

                              ?>
                                  <li class="item btn btn-success mb-2" style="width:20%">
                                    <input type="hidden" class="sequence" value="<?php echo $value['sequ']; ?>" readonly>
                                    <input type="hidden" class="garden_id" value="<?php echo $value['id']; ?>" readonly>
                                    <span><?php echo $value['garden_name']; ?></span>
                                  </li>
                              <?php

                                }
                              endif; ?>
                            </ul>
                          </div>

                          <input type="hidden" value="<?php echo base64_encode(@$auction_data['id']); ?>" name="id">
                          <div class="float-right m-2">
                            <button type="button" class="btn btn-primary" type="button" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="moveToFinalStep()">Next</button>
                          </div>

                        </div>

                        <div id="session-part" class="content" role="tabpanel" aria-labelledby="session-part-trigger">


                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>

    <?= @$data['footer']; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
      $(function() {
        $('#date').datetimepicker({
          format: 'DD-MM-YYYY',
          placeholder: 'dd-mm-yyyy'
        });

        $('#end_time').datetimepicker({
          format: 'HH:mm'
        });

        $('#start_time').datetimepicker({
          format: 'HH:mm'
        })
      });



      $(document).ready(function() {
        var uniqueIdCounter = 0;
        var autoIncCounter = $("#auto_value").val();

        $(document).on('click', '.add-row', function() {
          if ($("#center_id").val() == '')
            return false;

          autoIncCounter++;
          $("#auto_value").val(autoIncCounter);
          var newRow = $('#sortable-table tbody tr:first').clone();
          var uniqueId = ++uniqueIdCounter;
          newRow.attr('id', 'row_' + uniqueId);
          newRow.find('.auto_inc').attr('id', 'auto_inc_' + autoIncCounter).text(autoIncCounter);

          // Append remove button nearest to the bay input field
          newRow.find('#remove').append('<span class="btn btn-xs btn-danger remove-row"><i class="fa fa-minus"></i></span>');

          newRow.find('input[type="text"], select').each(function(index) {
            var name = $(this).attr('id');
            name = name.slice(0, -2);
            $(this).attr('id', name + '_' + uniqueId);
          });
          newRow.find('input[type="text"]').val('');
          newRow.find('select').prop('selectedIndex', 0);
          $('#sortable-table tbody').append(newRow);
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
          if ($(this).closest('tr').index() !== 0) {
            $(this).closest('tr').remove();
            autoIncCounter--;
            $("#auto_value").val(autoIncCounter);
          }
        });

        // Get the current year
        var currentYear = new Date().getFullYear();
        var minDate = (currentYear - 1) + "-12-31";
        var maxDate = (currentYear + 1) + "-12-31";

        document.getElementById("gp_date").setAttribute("min", minDate);
        document.getElementById("gp_date").setAttribute("max", maxDate);
      });

      // BS-Stepper Init
      document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
      });

      function generateUniqueId() {
        return new Date().getTime();
      }
    </script>

    <script>
      $(function() {
        $("#draggable-menu").sortable({
          axis: 'y',
          items: 'li',
          stop: function(event, ui) {
            $("#draggable-menu li").each(function(index) {
              $(this).find(".sequence").val(index + 1);
            });
            var dataToSend = [];
            $("#draggable-menu li").each(function(index) {
              var sequence = $(this).find(".sequence").val();
              var id = $(this).find(".id").val();
              var text = $(this).find("span").text();
              var garden_id = $("#garden_id").val();
              dataToSend.push({
                sequence: sequence,
                id: id,
                garden_id: garden_id
              });
            });


          }
        });
        $("#draggable-menu").disableSelection();
      });

      $(document).on("change", "#center_id", function(event) {
        var centerId = $(this).val(); // Get the selected center ID

        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/GetInwardItems",
          type: "POST",
          data: {
            center_id: centerId,
          },
          dataType: "json",
          success: function(response) {
            if (response.status == 200) {
              $(".auction-items-table tbody").empty(); // Clear existing rows before appending new ones
              $.each(response.data, function(key, invdet) {
                var newRow = '<tr>' +
                  '<td>' + (key + 1) + '</td>' +
                  '<td>' + invdet.invoice_id + '</td>' +
                  '<td>' + invdet.garden_name + '</td>' +
                  '<td>' + invdet.grade_name + '<input type="hidden" class="form-control" name="total_quantitysss[]" id="total_quantity_' + (key) + '" value="' + invdet.stock_qty + '" placeholder="Auction Quantity" readonly></td>' +
                  '<td>' + invdet.warehouse_name + '<input type="hidden" class="form-control" name="auction_aty[]" id="auction_quantity_' + (key) + '" value="' + invdet.stock_qty + '" placeholder="Auction Quantity" readonly>' +
                  '<input type="hidden" class="form-control item_garden_name" name="inward_item_garden[]" id="inward_item_garden_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_name + '">' +
                  '<input type="hidden" class="form-control item_item_id" name="inward_item_id[]" id="inward_item_id_' + (key) + '" placeholder="Garden name" value="' + invdet.id + '">' +
                  '<input type="hidden" class="form-control item_garden_id" name="inward_item_garden_id[]" id="inward_item_garden_id_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_id + '">' +
                  '<input type="hidden" class="form-control item_grade_id" name="inward_item_grade_id[]" id="inward_item_grade_id_' + (key) + '" placeholder="Garden name" value="' + invdet.grade_id + '">' +
                  '<input type="hidden" class="form-control item_grade_name" name="inward_item_grade[]" id="inward_item_grade_' + (key) + '" placeholder="Grade name" value="' + invdet.grade_name + '">' +
                  '<input type="hidden" class="form-control inward_item" name="inward_item[]" id="inward_item_' + (key) + '" placeholder="Auction Quantity" value="' + invdet.id + '">' +
                  '</td>' +
                  '<td>' + invdet.total_gross +
                  '</td>' +
                  '<td>' + invdet.stock_qty +
                  '</td>' +
                  '<td>' +
                  '<button type="button" data-key="' + key + '" class="btn btn-sm btn-success add_to_cart" data-toggle="modal" data-target="#modal-sm"><i class="fa fa-save"></i></button>' +
                  '</td>' +
                  '</tr>';
                $(".auction-items-table tbody").append(newRow);
              });
            } else if (response.status == 404) {
              $(".auction-items-table tbody").empty();
              $(".auction-items-table tbody").append('<tr><td colspan="9" class="text-center">No data found</td></tr>');
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });

      $(document).on("click", ".add_to_cart", function(event) {
        var key = $(this).data("key");
        var gardenName = $("#inward_item_garden_" + key).val();
        var gardenId = $("#inward_item_garden_id_" + key).val();
        var gradeId = $("#inward_item_grade_id_" + key).val();
        var itemId = $("#inward_item_id_" + key).val();
        var gradeName = $("#inward_item_grade_" + key).val();
        var inwardItem = $("#inward_item_id_" + key).val();
        var totalQuantity = $("#total_quantity_" + key).val();
        var auctionQuantity = $("#auction_quantity_" + key).val();
        var basePrice = $("#base_price_" + key).val();
        var reversePrice = $("#reverse_price_" + key).val();
        var highPrice = $("#high_price_" + key).val();
        if (gardenId === undefined) {
          gardenId = $("#inward_item_garden_id_" + key).prop('value');
        }
        if (itemId === undefined) {
          itemId = $("#inward_item_id_" + key).prop('value');
        }
        if (gradeId === undefined) {
          gradeId = $("#inward_item_grade_id_" + key).prop('value');
        }
        // Create data object for AJAX request
        var data = {
          inward_item_garden: gardenName,
          inward_item_garden_id: gardenId,
          item_id: itemId,
          inward_item_grade: gradeName,
          inward_item: inwardItem,
          auction_quantity: auctionQuantity,
          base_price: basePrice,
          reverse_price: reversePrice,
          high_price: highPrice,
          total_quantity: totalQuantity,
          grade_id: gradeId,
        };


        $("#modal_total_quantity_stock").val(totalQuantity);
        $("#modal_garden_name").val(gardenName);
        $("#modal_garden_id").val(gardenId);
        $("#modal_grade_name").val(gradeName);
        $("#modal_inward_item").val(inwardItem);
        $("#modal_grade_id").val(gradeId);
        //     $.ajax({
        //     url: "<?= @basePath ?>USER/BiddingSession/storeEachSession",
        //     type: "POST",
        //     data: data,
        //     success: function(response) {
        //         $(".cart_data").html('');
        //         $(".cart_data").html(response);
        //     },
        //     error: function(xhr, status, error) {
        //         // Handle error response here
        //         console.error(error);
        //     }
        // });
        //     return false;
        //     //alert(id);
      });

      $(document).on("change", "#warehouse_id", function(event) {
        var centerId = $('#center_id').val();
        var warehouse_id = $('#warehouse_id').val();

        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/GetInwardItemsByWarehouseId",
          type: "POST",
          data: {
            center_id: centerId,
            warehouse_id: warehouse_id,
          },
          dataType: "json",
          success: function(response) {
            if (response.status == 200) {
              $(".auction-items-table tbody").empty(); // Clear existing rows before appending new ones
              $.each(response.data, function(key, invdet) {
                var newRow = '<tr>' +
                  '<td>' + (key + 1) + '</td>' +
                  '<td>' + invdet.invoice_id + '</td>' +
                  '<td>' + invdet.garden_name + '</td>' +
                  '<td>' + invdet.grade_name + '<input type="hidden" class="form-control" name="total_quantitysss[]" id="total_quantity_' + (key) + '" value="' + invdet.stock_qty + '" placeholder="Auction Quantity" readonly></td>' +
                  '<td>' + invdet.warehouse_name +
                  '<input type="hidden" class="form-control item_garden_name" name="inward_item_garden[]" id="inward_item_garden_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_name + '">' +
                  '<input type="hidden" class="form-control item_item_id" name="inward_item_id[]" id="inward_item_id_' + (key) + '" placeholder="Garden name" value="' + invdet.id + '">' +
                  '<input type="hidden" class="form-control item_garden_id" name="inward_item_garden_id[]" id="inward_item_garden_id_' + (key) + '" placeholder="Garden name" value="' + invdet.garden_id + '">' +
                  '<input type="hidden" class="form-control item_grade_id" name="inward_item_grade_id[]" id="inward_item_grade_id_' + (key) + '" placeholder="Garden name" value="' + invdet.grade_id + '">' +
                  '<input type="hidden" class="form-control item_grade_name" name="inward_item_grade[]" id="inward_item_grade_' + (key) + '" placeholder="Grade name" value="' + invdet.grade_name + '">' +
                  '<input type="hidden" class="form-control inward_item" name="inward_item[]" id="inward_item_' + (key) + '" placeholder="Auction Quantity" value="' + invdet.id + '">' +
                  '</td>' +
                  '<td>' + invdet.total_gross +
                  '</td>' +
                  '<td>' + invdet.stock_qty +
                  '</td>' +
                  '<td>' +
                  '<button type="button" data-key="' + key + '" class="btn btn-sm btn-success add_to_cart"><i class="fa fa-save"></i></button>' +
                  '</td>' +
                  '</tr>';
                $(".auction-items-table tbody").append(newRow);
              });
            } else if (response.status == 404) {
              $(".auction-items-table tbody").empty();
              $(".auction-items-table tbody").append('<tr><td colspan="9" class="text-center">No data found</td></tr>');
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });

      $(document).on("change", ".invoice_no", function(event) {
        var invoiceId = $(this).val();
        var id_cnt = $(this).attr('id');
        id_cnt = id_cnt.slice(-1);
        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/GetInwardItemDetails",
          type: "POST",
          data: {
            invoice_id: invoiceId,
          },
          dataType: "json",
          success: function(response) {
            console.log(response);

            if (response) {
              console.log(response.stock_qty);
              $("#total_quantity_" + id_cnt).val(response.stock_qty);
            } else if (response.status == 500) {
              $("#total_quantity_" + id_cnt).val("No data found");
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });


      $(document).on("change", "#center_id", function(event) {
        var centerId = $(this).val(); // Get the selected state ID
        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/GetCenterGardens",
          type: "POST",
          data: {
            center_id: centerId,
          },
          dataType: "json",
          success: function(response) {
            $(".centerGardens").empty();

            if (response.status == 200) {
              // console.log(response);

              $.each(response.data, function(key, invdet) {
                $(".centerGardens").append(
                  '<li class="item btn btn-success mb-2" style="width:20%">' +
                  '<input type="hidden" class="sequence" value="' + (key + 1) + '" readonly>' +
                  '<input type="hidden" name="step_garden_ids[]" class="garden_ids" value="' + invdet.id + '" readonly>' +
                  '<span>' + invdet.garden_name + '</span>' +
                  '</li>'
                );
              });
            } else if (response.status == 404) {
              $(".centerGardens").html('No data found');
            }
          },

          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });


      // Function for form submission
      function submitForm() {
        var url = $("#bidding-session-form").attr("action");
        var formmethod = "post";
        var formdata = $("form").serialize();

        $("#sortable li").each(function(index) {
          $(this).find(".sequence").val(index + 1);
        });
        $(".sequence").each(function(index) {
          formdata += "&sequence[]=" + $(this).val();
        });
        $(".garden_id").each(function() {
          formdata += "&garden_id[]=" + $(this).val();
        });

        $.ajax({
          url: url,
          type: formmethod,
          data: formdata,
          success: function(_response) {
            Swal.fire({
              icon: "success",
              title: "Success!",
              text: "Form submitted successfully",
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
                if ($("input#" + key).length != 0)
                  $("input#" + key).after('<span class="error ">' + value + "</span>");
                else if ($("select#" + key).length != 0)
                  $("select#" + key).after('<span class="error">' + value + "</span>");
                else
                  $('#' + key).after('<span class="error">' + value + '</span>');
              });
            } else if (_response.status === 500) {
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Internal Server Error',
              });
            }
          }
        });
      }

      // Event handler for clicking the add bidding session button
      $(document).on("click", "#add-bidding-session", function(event) {
        event.preventDefault();
        submitForm();
      });


      $(document).on("click", ".delete_btn", function(event) {
        var id_cnt = $(this).attr('id');
        id_cnt = id_cnt.slice(-1);
        var temp_session_id = $("#unique_id" + id_cnt).val();
        //alert(id_cnt);
        //return false;
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to remove from cart.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'delete!'
        }).then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: "post",
              url: "<?= @basePath ?>USER/BiddingSession/TempDelete",
              data: {
                id: temp_session_id
              },
              dataType: 'html',
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Removed successfully',
                }).then((result) => {
                  $(".cart_data").html('');
                  $(".cart_data").html(response);
                });
              },

            });
          }
        });
        return false;
      });
      
      $(document).on("change", "#auction_quantity_", function(event) {
        var auction_qty = parseFloat($(this).val());
        var actual_qty = parseFloat($("#modal_total_quantity_stock").val());

        $('#auction_quantity_').next('.error').remove();
        if (auction_qty > actual_qty) {
          // alert('hii');
          $('#auction_quantity_').after('<span class="error">Auction qty should be less than total qty</span>');
          // $('#auction_quantity_'+ id_cnt).val("");
        } else {
          $('#auction_quantity_').next('.error').remove();
        }
      })

      $(function() {
        $('#timeInput').mask('00:00:00', {
          placeholder: "hh:mm:ss"
        });
        $('#timeInput').keyup(function() {
          var timeValue = $(this).val();
          var timeParts = timeValue.split(':');
          var minutes = parseInt(timeParts[1]);
          var seconds = parseInt(timeParts[2]);

          if (minutes > 59) {
            timeParts[1] = '59';
          }

          if (seconds > 59) {
            timeParts[2] = '59';
          }

          $(this).val(timeParts.join(':'));
        });
      });

      document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
      })

      $(document).on("click", "#store_cart", function(event) {
        var auction_qty = parseFloat($("#auction_quantity_").val());
        var actual_qty = parseFloat($("#modal_total_quantity_stock").val());
        var base_price = parseFloat($("#base_price").val());
        var reverse_price = parseFloat($("#reverse_price").val());

        // Clear existing error messages
        $('.error').remove();

        var errors = [];

        if (isNaN(auction_qty) || auction_qty <= 0 || auction_qty > actual_qty) {
          errors.push({
            field: '#auction_quantity_',
            message: 'Please enter a valid auction quantity'
          });
        }

        if (isNaN(base_price) || base_price <= 0) {
          errors.push({
            field: '#base_price',
            message: 'Please enter a valid base price'
          });
        }

        if (isNaN(reverse_price) || reverse_price <= 0) {
          errors.push({
            field: '#reverse_price',
            message: 'Please enter a valid reverse price'
          });
        }

        // If there are errors, display them and stop further processing
        if (errors.length > 0) {
          $.each(errors, function(index, error) {
            $(error.field).after('<span class="error">' + error.message + '</span>');
          });
          return false;
        }

        var formdata = $("#cart-form").serialize();
        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/storeSession",
          type: "POST",
          data: formdata,
          success: function(response) {
            $('#modal-sm').modal('toggle');
            $(".cart_data").empty();
            $(".cart_data").html(response);
          },
          error: function(xhr, status, error) {
            // Handle errors here
            // You can display server-side errors as necessary
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Internal Server Error',
            });
          },
        });
      });


      $(document).on("click", ".add_to_cart", function(event) {
        $("#base_price").val('');
        $("#auction_quantity_").val('');
        $("#reverse_price").val('');
        $(".error").remove();
      });


      var stepper = new Stepper(document.getElementById('wizard'), {
        linear: true,
        animation: true
      });

      function validateStep() {

        return true;
      }

      function moveToNextStep() {
        if ($("#center_id").val() == '')
          return false;
        var formData = {};
        $('#logins-part input, #logins-part select').each(function() {
          var inputName = $(this).attr('name');
          var inputValue = $(this).val();
          if (inputName.endsWith('[]')) {
            if (!formData[inputName]) {
              formData[inputName] = [];
            }
            formData[inputName].push(inputValue);
          } else {
            if (inputValue.trim() !== '') {
              formData[inputName] = inputValue;
            }
          }
        });
        console.log(formData);
        //return false;

        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/steponeStore",
          type: "POST",
          data: formData,
          success: function(response) {

            $("#session-part").empty();
            $("#session-part").html(response);

          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });


        stepper.next();
      }

      function moveToFinalStep() {
        if ($("#center_id").val() == '')
          return false;
        var formData = {};


        var gardenIds = [];
        $('.garden_ids').each(function() {
          gardenIds.push($(this).val());
        });


        $.ajax({
          url: "<?= @basePath ?>USER/BiddingSession/steptwoStore",
          type: "POST",
          data: {
            'garden_id': gardenIds
          },
          success: function(response) {

            $("#session-part").empty();
            $("#session-part").html(response);

          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });


        stepper.next();
      }
    </script>
</body>

</html>