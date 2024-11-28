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
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/Inward">Inward</a></li>
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
        <div class="modal-dialog modal-l">
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
                  <h3 class="card-title"><?php echo @$title; ?> Inward</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="user-form" method="post" action="<?= $url; ?>">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="name">Select Center</label>
                          <input type="hidden" value="<?php echo base64_encode(@$detail_data['id']); ?>" name="id">
                          <select name="center_id" id="center_id" class="form-control">
                            <option value="">Select Center</option>
                            <?php
                            foreach ($response_data['centers'] as $key => $value) {
                            ?>
                              <option value="<?php echo $value['id']; ?>" <?php if (@$detail_data['center_id'] == $value['id']) { ?>selected <?php } ?>><?php echo $value['name']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="name">Select Seller</label>
                          <div class="input-group">
                            <select class="form-control seller_id" name="seller_id" id="seller_id">
                              <option value="">Select Seller</option>
                              <?php
                              foreach ($response_data['sellers_with_gardens'] as $key => $value) {
                              ?>
                                <option value="<?php echo $value['id']; ?>" <?php if (@$detail_data['seller_id'] == $value['id']) { ?>selected <?php } ?>><?php echo $value['name']; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                            <div class="input-group-append">
                              <button type="button" onclick="showLoadingAndFetch('<?= @basePath ?>USER/Seller/Add')" class="btn btn-success add-garden"><i class="fa fa-plus"></i></button>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="col-md-3">

                        <div class="form-group">
                          <div class="d-flex justify-content-between">
                            <label for="name" class="">Select Garden</label>
                            <div id="is_vacumm" style="display:none"><span class="badge badge-info">Vacuum Bag</span></div>
                          </div>
                          <div class="input-group">
                            <select class="form-control" name="garden_id" id="garden_id">
                              <option value="">Select Garden</option>

                            </select>
                            <div class="input-group-append">
                              <button type="button" onclick="showLoadingAndFetch('<?= @basePath ?>USER/Garden/Add')" class="btn btn-success add-garden"><i class="fa fa-plus"></i></button>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="name">Select Warehouse</label>
                          <div class="input-group">
                            <select class="form-control" name="warehouse_id" id="warehouse_id">
                              <option value="">Select Warehouse</option>
                              <?php
                              foreach ($response_data['warehouses'] as $key => $value) {
                              ?>
                                <option value="<?php echo $value['id']; ?>" <?php if (@$detail_data['warehouse_id'] == $value['id']) { ?>selected <?php } ?>><?php echo $value['name']; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                            <div class="input-group-append">
                              <button type="button" onclick="showLoadingAndFetch('<?= @basePath ?>USER/Warehouse/Add')" class="btn btn-success add-garden"><i class="fa fa-plus"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="name">Gp No</label>
                          <input type="text" value="<?php echo @$detail_data['gp_no']; ?>" class="form-control" id="gp_no" name="gp_no" placeholder="GP No">
                          <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>" placeholder="Enter Name">
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="name">Gp Total</label>
                          <input type="text" value="<?php echo @$detail_data['gp_total']; ?>" class="form-control" id="gp_total" name="gp_total" placeholder="GP Total">
                        </div>
                      </div>

                      <div class="col-md-3">
                        <!-- <div class="form-group">
                          <label>GP Date:</label>
                          <input type="date" class="form-control" id="gp_date" value="<?php echo @$detail_data['gp_date']; ?>" name="gp_date">
                        </div> -->

                        <div class="form-group">
                          <label>GP Date:</label>
                          <div class="input-group date" id="gp_date" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="gp_date" data-target="#gp_date" value="<?php echo isset($auction_data['gp_date']) ? date("d-m-Y", strtotime($auction_data['gp_date'])) : ''; ?>" />
                            <div class="input-group-append" data-target="#gp_date" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <!-- <div class="form-group">
                          <label>Arrival Date:</label>
                          <input type="date" class="form-control" name="arrival_date" id="arrival_date" value="<?php echo @$detail_data['arrival_date']; ?>" placeholder="Arrival Date">
                        </div> -->
                        <div class="form-group">
                          <label>Arrival Date:</label>
                          <div class="input-group date" id="arrival_date" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="arrival_date" data-target="#arrival_date" value="<?php echo isset($auction_data['arrival_date']) ? date("d-m-Y", strtotime($auction_data['arrival_date'])) : ''; ?>" />
                            <div class="input-group-append" data-target="#arrival_date" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                      <h5 id="add-item-details">Add Item Details</h5>
                      <h5 class="float-right">
                        <button type="button" onclick="showLoadingAndFetch('<?= @basePath ?>USER/Grade/Add')" class="btn btn-success add-grade">Add Grade</button>
                      </h5>
                    </div>
                    <hr>

                    <table class="table table-bordered" data-repeater-list="items" id="sortable-table">
                      <thead>
                        <tr>
                          <th width="3%">#</th>
                          <th>Inv No</th>
                          <th>Tea Grade</th>
                          <th>Total Bags</th>
                          <th>Each Net</th>
                          <th>Weight Tare</th>
                          <th>Weight Gross</th>
                          <th>Total Net</th>
                          <th>Total Tare</th>
                          <th>Total Gross</th>
                          <th>S.No From</th>
                          <th>S.No To</th>
                          <th><span class="btn btn-xs btn-success add-row"><i class="fa fa-plus"></i></span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="table-active">
                          <td>
                            <input type="hidden" id="auto_value" value="1">
                            <span class="auto_inc" id="auto_inc_0">1</span>
                          </td>
                          <td><input type="text" name="invoice_no[]" class="form-control" id="invoice_no.0" placeholder="(Eg:INV-001)"></td>
                          <td>
                            <div class="input-group">
                              <select class="form-control grade_dropdown" name="grade_id[]" id="grade_id.0">
                                <option value="">Select Grade</option>
                              </select>
                              <div class="input-group-append">
                                <button type="button" onclick="reloadGrade(this, '<?= @basePath ?>USER/Inward/GetGardenGrades')" class="btn btn-info btn-xs reload-grade">
                                  <i class="fas fa-redo fa-xm"></i>
                                </button>
                              </div>
                            </div>
                          </td>
                          <td>
                            <input type="text" class="form-control no_of_bags" name="no_of_bags[]" id="no_of_bags.0" placeholder="Total No of Bag">
                          </td>
                          <td>
                            <input type="text" class="form-control nett_cb" id="weight_nett.0" name="weight_nett[]" placeholder="Weight per C/B (Kgs) Nett">
                          </td>
                          <td>
                            <input type="text" class="form-control tare_cb" id="weight_tare.0" name="weight_tare[]" placeholder="Weight per C/B (Kgs) Tare">
                          </td>
                          <td>
                            <input type="text" class="form-control gross_cb" readonly id="weight_gross.0" name="weight_gross[]" placeholder="Weight per C/B (Kgs) Gross">
                          </td>
                          <td>
                            <input type="text" class="form-control total_nett" readonly id="total_net.0" name="total_net[]" placeholder="Total Wt. (Kgs) Nett.">
                          </td>
                          <td>
                            <input type="text" class="form-control" readonly id="total_tare.0" name="total_tare[]" placeholder="Total Wt. (Kgs) Tare">
                          </td>
                          <td>
                            <input type="text" class="form-control total_gross" readonly id="total_gross.0" name="total_gross[]" placeholder="Total Wt. (Kgs) Gross">
                          </td>
                          <td>
                            <input type="text" class="form-control sno_from" id="serial_no_from.0" name="serial_no_from[]" placeholder="Serial no from">
                          </td>
                          <td>
                            <input type="text" class="form-control" id="serial_no_to.0" name="serial_no_to[]" placeholder="Serial no to">
                            <input type="hidden" class="form-control" id="" name="bag_type[]" value="1">
                          </td>
                          <td id="remove" style="white-space: nowrap;"></td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="row">
                      <div class="col-md-9">
                        <div class="form-group">
                          <label for="name">Remarks</label>
                          <textarea class="form-control" id="remarks" name="remarks" cols="45" rows="9"><?php echo @$detail_data['remark']; ?></textarea>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="row">
                          <div class="form-group">
                            <label for="name">Total bags</label>
                            <input type="text" class="form-control" id="total_qty" value="<?php echo @$detail_data['quantity']; ?>" name="total_qty" placeholder="Total Bags" readonly>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group">
                            <label for="name">Gross Total Weight</label>
                            <input type="text" class="form-control" id="gross_total_weight" value="<?php echo @$detail_data['gross_total_weight']; ?>" name="gross_total_weight" placeholder="Gross Total Weight" readonly>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group">
                            <label for="name">Nett Total Weight</label>
                            <input type="text" class="form-control" id="nett_total_weight" value="<?php echo @$detail_data['nett_total_weight']; ?>" name="nett_total_weight" placeholder="Nett Total Weight" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="float-right">
                      <a href="<?= @basePath ?>USER/Inward" class="btn btn-default">Back</a>
                      <button type="button" id="open_form_submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </form>
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
        $('#gp_date').datetimepicker({
          format: 'DD-MM-YYYY'
        });
        $('#arrival_date').datetimepicker({
          format: 'DD-MM-YYYY'
        });
      });

      $(document).ready(function() {
        var uniqueIdCounter = 0;
        var autoIncCounter = parseInt($("#auto_value").val());

        $(document).on("click", ".add-row", function() {
          autoIncCounter++;
          $("#auto_value").val(autoIncCounter);

          var newRow = $("#sortable-table tbody tr:last").clone();
          var uniqueId = ++uniqueIdCounter;
          newRow.attr("id", "row_" + uniqueId);
          newRow.find(".auto_inc").attr("id", "auto_inc_" + autoIncCounter).text(autoIncCounter);
          newRow.find('span.error').remove();

          newRow.find('input[type="text"], select').each(function(index) {
            var name = $(this).attr('id');
            name = name.slice(0, -2);
            $(this).attr("id", name + "." + uniqueId);
          });

          if ($("#sortable-table tbody tr").length === 1) {
            newRow.find('#remove').append(
              '<span class="btn btn-xs btn-success mx-1 add-row" style="display: inline-block;"><i class="fa fa-plus"></i></span>' +
              '<span class="btn btn-xs btn-danger remove-row" style="display: inline-block; margin-right: 5px;"><i class="fa fa-minus"></i></span>'
            );
          }

          // Check if the invoice_no is numeric and increment it
          var lastInvoiceNo = $("#sortable-table tbody tr:last").find("input[name='invoice_no[]']").val();
          if ($.isNumeric(lastInvoiceNo)) {
            newRow.find("input[name='invoice_no[]']").val(parseInt(lastInvoiceNo) + 1);
          } else {
            newRow.find("input[name='invoice_no[]']").val(lastInvoiceNo);
          }

          $("#sortable-table tbody").append(newRow);
          $("input[name='invoice_no[]'], select[name='grade_id[]']").focus(function() {
            $(this).addClass("highlight");
          });
          newRow.find("input[name='invoice_no[]']").focus();

          $("table input, table select").focus(function() {
            $(this).addClass("highlight");
          });
          $("table input, table select").blur(function() {
            $(this).removeClass("highlight");
          });
          updateSerialNumbers(uniqueId);
          updateTotalQuantitiesAndWeights();
        });



        $(document).on("click", ".remove-row", function() {
          var currentRow = $(this).closest('tr');
          var removedId = currentRow.attr('id').split('_')[1];
          var removedNetWeight = parseFloat($("#total_nett\\." + removedId).val()) || 0;
          var removedGrossWeight = parseFloat($("#total_gross\\." + removedId).val()) || 0;

          currentRow.remove();
          autoIncCounter--;
          $("#auto_value").val(autoIncCounter);

          // Update uniqueIdCounter when row is removed
          uniqueIdCounter--;

          // Update all rows after the removed row
          $("#sortable-table tbody tr").each(function(index) {
            if (index >= removedId) {
              var newRowId = "row_" + index;
              $(this).attr("id", newRowId);
              $(this).find('.auto_inc').text(index + 1);

              $(this).find('input[type="text"], select').each(function() {
                var idParts = $(this).attr('id').split('.');
                var newName = idParts[0] + "." + index;
                $(this).attr("id", newName);
              });

              // Update serial numbers for the current row
              updateSerialNumbers(index);
            }
          });

          // Update total quantities and weights
          updateTotalQuantitiesAndWeights();
        });

        function updateTotalQuantitiesAndWeights() {
          var totalGross = 0;
          var totalGrossWeight = 0;
          var totalNetWeight = 0;

          $(".no_of_bags").each(function() {
            var bags = parseInt($(this).val()) || 0;
            totalGross += bags;
          });

          $(".total_gross").each(function() {
            var grossWeight = parseFloat($(this).val()) || 0;
            totalGrossWeight += grossWeight;
          });

          $(".total_nett").each(function() {
            var netWeight = parseFloat($(this).val()) || 0;
            totalNetWeight += netWeight;
          });

          $("#total_qty").val(totalGross.toFixed(3));
          $("#gross_total_weight").val(totalGrossWeight.toFixed(3));
          $("#nett_total_weight").val(totalNetWeight.toFixed(3));
        }

        function updateSerialNumbers(uniqueId) {
          var prevSnoTo = parseInt($("#serial_no_to\\." + (uniqueId - 1)).val());
          if (!isNaN(prevSnoTo)) {
            var snoFrom = prevSnoTo + 1;
            $("#serial_no_from\\." + uniqueId).val(snoFrom);
          }

          var sno_from = parseInt($("#serial_no_from\\." + uniqueId).val());
          var bag = parseInt($("#no_of_bags\\." + uniqueId).val());
          if (!isNaN(bag) && !isNaN(sno_from)) {
            var snoTo = bag + sno_from - 1;
            $('#serial_no_to\\.' + uniqueId).val(snoTo);
          } else {
            $('#serial_no_to\\.' + uniqueId).val('');
          }
        }


        $(document).on("change", ".sno_from,.no_of_bags", function(event) {
          var id_cnt = $(this).attr('id').slice(-1);
          $('#serial_no_to\\.' + id_cnt).empty();

          var sno_from = parseInt($("#serial_no_from\\." + id_cnt).val());
          var bag = parseInt($("#no_of_bags\\." + id_cnt).val());

          if (bag != '' && !isNaN(sno_from)) {
            var count = bag + (sno_from - 1);
            console.log(sno_from);
            $('#serial_no_to\\.' + id_cnt).val(count);
          } else {
            $('#serial_no_to\\.' + id_cnt).val('');
          }
        });
      });

      $(function() {
        $('#datemask').inputmask('dd/mm/yyyy', {
          'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
          'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask();
      });

      $(document).on("click", "#open_form_submit", function(event) {
        $("#open_form_submit").attr("disabled", true);

        var alphanumericFields = ["invoice_no"];
        var valid = true;

        alphanumericFields.forEach(function(field) {
          $("input[name='" + field + "[]']").each(function() {
            var value = $(this).val();
            var key = $(this).attr('id').split('.')[1];
            if (value !== "" && !/^(?!-)(?!.*-$)(?=.*[0-9])[0-9a-zA-Z-]+$/.test(value)) {
              $(this).next(".error").remove(); // Remove any existing error message
              $(this).after('<span class="error">' + field.replace("_", " ") + " must contain at least one digit and be alphanumeric</span>");
              valid = false;
            } else {
              // Remove existing error messages
              $("#" + field + '\\.' + key).next(".error").remove();
            }
          });
        });

        if (!valid) {
          $("#open_form_submit").attr("disabled", false);
          return;
        }

        var numericFields = ["gp_no", "no_of_bags", "serial_no_from", "serial_no_to"];

        numericFields.forEach(function(field) {
          $("input[name='" + field + "[]']").each(function() {
            var value = $(this).val();
            var key = $(this).attr('id').split('.')[1];
            if (value !== "" && !$.isNumeric(value)) {
              $(this).next(".error").remove(); // Remove any existing error message
              $(this).after('<span class="error">' + field.replace("_", " ") + " must be numeric</span>");
              valid = false;
            } else {
              // Remove existing error messages
              $("#" + field + '\\.' + key).next(".error").remove();
            }
          });
        });

        if (!valid) {
          $("#open_form_submit").attr("disabled", false);
          return;
        }

        $("input[name='invoice_no[]']").next(".error").remove();
        $("select[name='grade_id[]']").next(".error").remove();
        var invoiceNumbers = [];
        var duplicateFound = false;
        var invoiceAndGradeCombinations = {};
        $("input[name='invoice_no[]']").each(function() {
          var invoice_no = $(this).attr('id');
          var parts = invoice_no.split('.');

          var invoiceNumber = $(this).val();
          var grade_name = $(this).closest('tr').find("select[name='grade_id[]']").val();
          if (invoiceNumber !== "" && grade_name !== "") { // Check if the invoice number is not empty
            var combinedValue = invoiceNumber + "-" + grade_name;
            if (invoiceAndGradeCombinations[combinedValue]) {
              duplicateFound = true;
              // Append message next to the duplicate invoice number input
              $(this).next(".error").remove(); // Remove any existing error message
              $(this).after('<span class="error">Invoice no must be unique</span>');
            } else {
              invoiceAndGradeCombinations[combinedValue] = true;
            }
            invoiceNumbers.push(invoiceNumber);
          }
        });

        if (duplicateFound) {
          $("#open_form_submit").attr("disabled", false);
          return;
        }

        var gpTotal = parseFloat($('#gp_total').val());
        var nett_total_weight = parseFloat($('#nett_total_weight').val());
        console.log(nett_total_weight);

        if (gpTotal !== nett_total_weight && !isNaN(gpTotal) && !isNaN(nett_total_weight) && gpTotal !== null && nett_total_weight !== null) {
          Swal.fire({
            title: 'Total Value Mismatch!',
            text: 'Do you want to proceed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Proceed'
          }).then((result) => {
            if (result.isConfirmed) {
              sendAjaxRequest();
            } else if (result.isDismissed) {
              $("#open_form_submit").attr("disabled", false);
            }
          });
        } else {
          sendAjaxRequest();
        }
      });

      function sendAjaxRequest() {
        event.preventDefault();
        var url = $("#user-form").attr("action");
        var formmethod = "post";
        var formdata = $("form").serialize();

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
                window.location.href = "<?= @basePath ?>USER/Inward"; // Reload the page on success
              }
            });
          },
          error: function(_response) {
            var data = $.parseJSON(_response.responseText);
            // console.log(response);
            $(".error").remove();
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
          },
          complete: function() {
            // Re-enable the submit button after the request is complete
            $("#open_form_submit").attr("disabled", false);
          }
        });
      }


      $(document).on("change", ".seller_id", function(event) {
        var seller_id = $(this).val();
        console.log($('#garden_id').val());
        $.ajax({
          url: '<?= @basePath ?>USER/Seller/SellerGarden',
          type: 'POST',
          data: {
            "seller_id": seller_id
          },
          dataType: 'json',
          success: function(response) {
            if ($('#garden_id').val() != '') {
              Swal.fire({
                title: 'Are you sure?',
                text: 'Selected data will be removed.',
                icon: 'warning',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Remove!'
              }).then((result) => {
                if (result.isConfirmed || result.isDismissed) {
                  $("#is_vacumm").hide();
                  $(".grade_dropdown").empty();
                  $(".grade_dropdown").append('<option value="">Select Grade</option>');
                  $('#garden_id').empty();

                  if (response.status == 200) {
                    $('#garden_id').append('<option value="">Select Garden</option>');
                    $.each(response.data.sellerGarden, function(key, garden) {
                      console.log(garden.id);

                      var vacumm_bag = garden.vacumm_bag;
                      var gardenOption = '<option value="' + garden.id + '" data-vacuum-bag="' + vacumm_bag + '">' + garden.name + '</option>';
                      $('#garden_id').append(gardenOption);
                    });
                  } else {
                    $('#garden_id').append('<option value="">No data found</option>');
                  }
                }
              });
            } else {
              $(".grade_dropdown").empty();
              $(".grade_dropdown").append('<option value="">Select Grade</option>');
              $('#garden_id').empty();
              $("#is_vacumm").hide();
              if (response.status == 200) {
                $('#garden_id').append('<option value="">Select Garden</option>');
                $.each(response.data.sellerGarden, function(key, garden) {
                  console.log(garden.id);

                  var vacumm_bag = garden.vacumm_bag;
                  var gardenOption = '<option value="' + garden.id + '" data-vacuum-bag="' + vacumm_bag + '">' + garden.name + '</option>';
                  $('#garden_id').append(gardenOption);
                });
              } else {
                $('#garden_id').append('<option value="">No data found</option>');
              }
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        });
      });

      $(document).on("change", "#garden_id", function(event) {
        var gardenId = $(this).val();
        var vacuumBag = $(this).find('option:selected').data('vacuum-bag');
        console.log(vacuumBag)
        // Check for vacumm_bag and add badge if necessary
        if (vacuumBag == 1) {
          $("#is_vacumm").show();
        } else {
          $("#is_vacumm").hide(); // Remove the badge if not applicable
        }

        $.ajax({
          url: "<?= @basePath ?>USER/Inward/GetGardenGrades",
          type: "POST",
          data: {
            garden_id: gardenId,
          },
          dataType: "json",
          success: function(response) {
            if ($('.grade_dropdown').val() != '') {
              Swal.fire({
                title: 'Are you sure?',
                text: 'Selected data will be removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Remove!'
              }).then((result) => {
                if (response.status == 200) {
                  $(".grade_dropdown").empty();
                  $(".grade_dropdown").append('<option value="">Select Grade</option>');

                  $.each(response.data, function(key, invdet) {

                    $(".grade_dropdown").append(
                      '<option value="' + invdet.id + '">' + invdet.name + "</option>"
                    );
                  });
                } else if (response.status == 404) {
                  $(".grade_dropdown").empty();
                  $(".grade_dropdown").append('<option value="">No data found</option>');
                }
              });
            } else {
              if (response.status == 200) {
                $(".grade_dropdown").empty();
                $(".grade_dropdown").append('<option value="">Select Grade</option>');

                $.each(response.data, function(key, invdet) {
                  var type = invdet.type;
                  var type_name = type == 1 ? 'Leaf' : 'Dust';
                  $(".grade_dropdown").append(
                    '<option value="' + invdet.id + '">' + invdet.name + '</option>'
                  );
                });
              } else if (response.status == 404) {
                $(".grade_dropdown").empty();
                $(".grade_dropdown").append('<option value="">No data found</option>');
              }
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });


      $(document).on("change", ".nett_cb,.tare_cb,.no_of_bags", function(event) {
        var originalNumber = parseFloat($(this).val());
        var formattedNumber = originalNumber.toFixed(3);

        var id_cnt = $(this).attr('id');
        id_cnt = id_cnt.split('.');

        if (id_cnt[0] != 'no_of_bags')
          $(this).val(formattedNumber);

        var tarewgt = $("#weight_tare\\." + id_cnt[1]).val() || 0;
        var nettwgt = $("#weight_nett\\." + id_cnt[1]).val() || 0;
        var no_of_bags = $("#no_of_bags\\." + id_cnt[1]).val() || 0;
        var weight_gross = (parseFloat(nettwgt) + parseFloat(tarewgt)).toFixed(3);
        $("#weight_gross\\." + id_cnt[1]).val(weight_gross);

        var gross_tare = (parseFloat(tarewgt) * no_of_bags).toFixed(3);
        var gross_nett = (parseFloat(nettwgt) * no_of_bags).toFixed(3);
        var gross_weight = (parseFloat(weight_gross) * no_of_bags).toFixed(3);
        $("#total_net\\." + id_cnt[1]).val(gross_nett);
        $("#total_tare\\." + id_cnt[1]).val(gross_tare);
        $("#total_gross\\." + id_cnt[1]).val(gross_weight);

        var totalGross = 0;
        $(".no_of_bags").each(function() {
          totalGross += parseInt($(this).val()) || 0; // Use parseFloat to ensure it's treated as a number
        });
        totalGross = parseFloat(totalGross); // Ensure totalGross is a number with 3 decimal places
        $("#total_qty").val(totalGross);
        var totalGrosswgt = 0;
        $(".total_gross").each(function() {
          totalGrosswgt += parseFloat($(this).val()) || 0;
        });
        totalGrosswgt = parseFloat(totalGrosswgt).toFixed(3);
        $("#gross_total_weight").val(totalGrosswgt);

        var totalnettwgt = 0;
        $(".total_nett").each(function() {
          totalnettwgt += parseFloat($(this).val());
        });
        totalnettwgt = parseFloat(totalnettwgt).toFixed(3);

        // console.log(totalnettwgt);
        $("#nett_total_weight").val(totalnettwgt);
      });

      $(document).ready(function() {
        function updateGardens(selectedSellerId, selectedGardenId = null) {
          let gardensDropdown = $('#garden_id');

          gardensDropdown.empty().append('<option value="">Select Garden</option>');
          let sellersWithGardens = <?php echo json_encode($response_data['sellers_with_gardens']); ?>;
          $.each(sellersWithGardens, function(index, seller) {
            if (seller.id == selectedSellerId) {
              $.each(seller.gardens, function(i, garden) {
                gardensDropdown.append(new Option(garden.name, garden.id, false, garden.id == selectedGardenId));
              });
            }
          });
        }
        let initialSellerId = $('#seller_id').val();
        let initialGardenId = '<?php echo @$detail_data['garden_id']; ?>'; // Assuming this is available
        if (initialSellerId) {
          updateGardens(initialSellerId, initialGardenId);
        }

        // Add highlight class on focus
        $("table input, table select").focus(function() {
          $(this).addClass("highlight");
        });
        $("table input, table select").blur(function() {
          $(this).removeClass("highlight");
        });
      });

      function showLoadingAndFetch(url) {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: url,
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
              text: "An error occurred.",
            });
          }
        });
      }

      function reloadGrade(button, url) {
        var gardenId = $('#garden_id').val();

        if (gardenId != '' && gardenId != null) {
          $.ajax({
            type: "post",
            url: url,
            data: {
              garden_id: gardenId,
            },
            dataType: "json",
            success: function(response) {
              if (response.status == 200) {
                var row = $(button).closest('tr');
                var gradeSelect = row.find('.grade_dropdown');

                gradeSelect.empty();
                gradeSelect.append('<option value="">Select Grade</option>');

                $.each(response.data, function(key, invdet) {
                  var newOption = '<option value="' + invdet.id + '">' + invdet.name + '</option>';
                  gradeSelect.append(newOption);
                });
              }
            },
            error: function(xhr, status, error) {
              console.error(error);
            },
          });
        }
      }


      function handleFormSubmit(event, url, formSelector, modalSelector, buttonSelector, title) {
        event.preventDefault();
        $(buttonSelector).attr("disabled", true);

        var formData = $(formSelector).serialize();
        console.log(formData);

        var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });

        $.ajax({
          url: url,
          type: 'post',
          data: formData,
          success: function(response) {
            $.ajax({
              url: '<?= @basePath ?>USER/Inward/AddAjax',
              type: 'POST',
              dataType: 'json',
              success: function(response) {
                Toast.fire({
                  icon: 'success',
                  title: title + ' Added Successfully.',
                  timer: 3000,
                  timerProgressBar: true
                }).then(function() {
                  if (buttonSelector != '#add-grade') {
                    $('#garden_id').val('').trigger('change');
                    $('.seller_id').val('').trigger('change');
                    $('#warehouse_id').val('').trigger('change');

                    $('#garden_id').empty().append('<option value="" selected>Select Garden</option>');
                    $('.seller_id').empty().append('<option value="" selected>Select Seller</option>');
                    $('#warehouse_id').empty().append('<option value="" selected>Select Warehouse</option>');
                    $.each(response.sellers, function(_key, seller) {
                      $('.seller_id').append('<option value="' + seller.id + '">' + seller.name + '</option>');
                    });
                    $.each(response.warehouses, function(_key, warehouse) {
                      $('#warehouse_id').append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
                    });
                  }
                  $(modalSelector).modal('hide');
                });
              },
              error: function(xhr, status, error) {
                console.error(error);
              }
            });
          },
          error: function(xhr, status, error) {
            var errors = $.parseJSON(xhr.responseText);
            $('.error').remove();

            if (xhr.status === 422) {
              $.each(errors.errors, function(key, value) {
                var errorMessage = '<span class="error">' + value + '</span>';
                if ($('input[name="' + key + '"]').length != 0) {
                  $('input[name="' + key + '"]').after(errorMessage);
                } else if ($('select[name="' + key + '"]').length != 0) {
                  $('select[name="' + key + '"]').after(errorMessage);
                } else {
                  $('#' + key).after(errorMessage);
                }
              });
            } else if (xhr.status === 500) {
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Internal Server Error',
              });
            }
          },
          complete: function() {
            $(buttonSelector).attr("disabled", false);
          }
        });
      }

      $(document).on("click", "#add-garden", function(event) {
        handleFormSubmit(event, '<?= @basePath ?>USER/Garden/Create', '#garden-form', '#modal-sm', "#add-garden", 'Garden');
      });

      $(document).on("click", "#add-grade", function(event) {
        handleFormSubmit(event, '<?= @basePath ?>USER/Grade/Create', '#grade-form', '#modal-sm', "#add-grade", 'Grade');
      });

      $(document).on("click", "#add-warehouse", function(event) {
        handleFormSubmit(event, '<?= @basePath ?>USER/Warehouse/Create', 'form#warehouse-form', '#modal-sm', "#add-warehouse", 'Warehouse');
      });

      $(document).on("click", "#add-seller", function(event) {
        handleFormSubmit(event, '<?= @basePath ?>USER/Seller/Create', 'form#seller-form', '#modal-sm', "#add-seller", 'Seller');
      });
    </script>

</body>

</html>