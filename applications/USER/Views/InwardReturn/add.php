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
                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/InwardReturn">Inward Return</a></li>
                <li class="breadcrumb-item active">Add</li>
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
                  <h3 class="card-title">Inward Return Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="user-form" method="post" action="<?= $url; ?>">
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="name">Select Invoice</label>
                        <select class="form-control" name="invoice_id" id="invoice_id">
                          <option value="">Select Invoice</option>
                          <?php foreach ($response_data as $key => $item) : ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo $item['garden_name'] . "-" . $item['invoice_id']; ?></option>
                          <?php endforeach; ?>

                        </select>
                      </div>
                      <div class="col-md-6">
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
                    </div>
                    <div class="row">

                      <div class="form-group col-lg-6">
                        <label for="code">Tea Grade</label>
                        <input type="text" class="form-control" id="tea_grade" readonly>
                        <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>" placeholder="Enter Grade">
                      </div>

                      <div class="form-group col-lg-6">
                        <label for="code">Type</label>
                        <input type="text" class="form-control" id="bag_type" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="code">No of Bags</label>
                        <input type="text" class="form-control" id="no_of_bags" readonly>
                      </div>

                      <div class="form-group col-lg-6">
                        <label for="code">Serial Number From</label>
                        <input type="text" class="form-control" id="sno_from" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="code">Serial Number To</label>
                        <input type="text" class="form-control" id="sno_to" readonly>
                      </div>

                      <div class="form-group col-lg-6 col-lg-6">
                        <label for="code">Weight per C/B (Kgs) Nett.</label>
                        <input type="text" class="form-control" id="wgt_nett" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="code">Weight per C/B (Kgs) Tare</label>
                        <input type="text" class="form-control" id="wgt_tare" readonly>
                      </div>

                      <div class="form-group col-lg-6">
                        <label for="code">Weight per C/B (Kgs) Gross</label>
                        <input type="text" class="form-control" id="wgt_gross" readonly>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="code">Return Bags Qty</label>
                        <input type="text" class="form-control" name="return_quantity" id="return_quantity" placeholder="Please enter Return Bags Qty">
                      </div>
                      <div class="form-group col-lg-6">
                        <label for="name">Reason</label>
                        <textarea class="form-control" name="reason" id="reason" cols="45" rows="5"></textarea>
                      </div>
                    </div>
                    <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">

                    <div class="row float-right">
                      <a href="<?= @basePath ?>USER/InwardReturn" class="btn btn-default mr-2">Back</a>
                      <button type="button" id="open_form_submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </form>
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
    <!-- /.content-wrapper -->
    <?= @$data['footer'] ?>

    <script>
      $(function() {
        $('#date').datetimepicker({
          format: 'DD-MM-YYYY',
          placeholder: 'dd-mm-yyyy'
        });
      });


      $(function() {
        $("#return_quantity").prop("disabled", true);
        $("#reason").prop("disabled", true);
        $(document).on("change", "#invoice_id", function(event) {
          var invoice_no = $(this).val();

          $.ajax({
            url: "<?= @basePath ?>USER/InwardReturn/getInvoiceDetail",
            type: "POST",
            data: {
              invoice_no: invoice_no,
            },
            dataType: "json",
            success: function(response) {
              if (response.bag_type == 1)
                var bag_type = "bag";
              else
                var bag_type = "Chest";
              $("#tea_grade").val(response.grade_name);
              $("#bag_type").val(bag_type);
              $("#no_of_bags").val(response.stock_qty);
              $("#sno_from").val(response.sno_from);
              $("#sno_to").val(response.sno_to);
              $("#wgt_nett").val(response.weight_net);
              $("#wgt_tare").val(response.weight_tare);
              $("#wgt_gross").val(response.weight_gross);

              $("#return_quantity").prop("disabled", false);
              $("#return_quantity").val("");
              $("#reason").prop("disabled", false);
              $("#reason").val("");
            },
            error: function(xhr, status, error) {
              console.error(error);
            },
          });
        });
      });

      // $("#return_quantity").on("change", function() {
      //   var returnQuantity = parseInt($("#return_quantity").val());
      //   var numberOfBags = parseInt($("#no_of_bags").val());
      //   //$("#return_quantity").html("");
      //   $('#return_quantity').next('.error').remove();
      //   // Check if returnQuantity is greater than or equal to numberOfBags
      //   if (returnQuantity >= numberOfBags) {
      //     $("#return_quantity").after('<span class="error">Return quantity must be less than the number of bags.</span>');
      //     return false;
      //   }
      //   return true;
      // });

      $(document).on("click", "#open_form_submit", function(event) {
        event.preventDefault();
        $("#open_form_submit").attr("disabled", true);

        var returnQuantity = parseInt($("#return_quantity").val());
        var numberOfBags = parseInt($("#no_of_bags").val());
        //$("#return_quantity").html("");
        $('#return_quantity').next('.error').remove();
        if (returnQuantity > numberOfBags) {
          $("#return_quantity").after('<span class="error">Return quantity must be less than the number of bags.</span>');
          return false;
        }

        var url = $("#user-form").attr("action");
        var formmethod = "post";
        var formdata = $("form").serialize();
        console.log(formdata);
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
                window.location = "<?= @basePath ?>USER/InwardReturn"; // Reload the page on success
              }
            });
          },
          error: function(_response) {
            var data = $.parseJSON(_response.responseText);
            $(".error").remove();
            if (_response.status === 422) {
              var errors = $.parseJSON(_response.responseText);
              error = errors.errors;
              $.each(data.errors, function(key, value) {
                if ($("input#" + key).length != 0)
                  $("input#" + key).after('<span class="error ">' + value + "</span>");
                else if ($("select#" + key).length != 0)
                  $("select#" + key).after('<span class="error">' + value + "</span>");
                else $("#" + key).after('<span class="error">' + value + "</span>");
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
      });
    </script>
</body>

</html>