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
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/bootstrap-switch/css/bootstrap2/bootstrap-switch.min.css">

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
                <li class="breadcrumb-item active"> Settings</li>
              </ol>
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
                  <h3 class="card-title"> Settings</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="settings-form-add">
                    <div class="form-group">
                      <label for="name">Prompt Days</label>
                      <input type="text" name="delivery_time" id="delivery_time" class="form-control" placeholder="Enter Prompt Days" value="<?= @$response_data['delivery_time']; ?>">
                    </div>

                    <div class="form-group">
                      <label for="code">Increment Amount</label>
                      <input type="text" class="form-control" id="increment_amount" name="increment_amount" placeholder="Enter Increment Amount" value="<?= @$response_data['increment_amount']; ?>">
                    </div>

                    <div class="row">
                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Buyer Charges (%)</label>
                          <input type="text" class="form-control" id="buyer_charges" name="buyer_charges" placeholder="Enter Buyer Charges" value="<?= @$response_data['buyer_charges']; ?>">
                        </div>
                      </div>

                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Seller Charges (%)</label>
                          <input type="text" class="form-control" id="seller_charges" name="seller_charges" placeholder="Enter Seller Charges" value="<?= @$response_data['seller_charges']; ?>">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Leaf Sample Quantity</label>
                          <input type="text" class="form-control" id="leaf_sq" name="leaf_sq" placeholder="Enter Leaf Sample Quantity" value="<?= @$response_data['leaf_sq']; ?>">
                        </div>
                      </div>

                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Dust Sample Quantity</label>
                          <input type="text" class="form-control" id="dust_sq" name="dust_sq" placeholder="Enter Dust Sample Quantity" value="<?= @$response_data['dust_sq']; ?>">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Leaf HSN</label>
                          <input type="text" class="form-control" id="leaf_hsn" name="leaf_hsn" placeholder="Enter Leaf HSN" value="<?= @$response_data['leaf_hsn']; ?>">
                        </div>
                      </div>

                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Dust HSN</label>
                          <input type="text" class="form-control" id="dust_hsn" name="dust_hsn" placeholder="Enter Dust HSN" value="<?= @$response_data['dust_hsn']; ?>">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Auctioneer Buyer Prefix</label>
                          <input type="text" class="form-control" id="ab_prefix" name="ab_prefix" placeholder="Enter Auctioneer Buyer Prefix" value="<?= @$response_data['ab_prefix'] ?? ''; ?>">
                        </div>
                      </div>

                      <div class="col col-lg-6">
                        <div class="form-group">
                          <label for="code">Auctioneer Seller Prefix</label>
                          <input type="text" class="form-control" id="as_prefix" name="as_prefix" placeholder="Enter Auctioneer Seller Prefix" value="<?= @$response_data['as_prefix'] ?? ''; ?>">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="code">Buyer Show</label>
                      <input type="checkbox" name="buyer_show" data-bootstrap-switch data-off-color="danger" data-on-color="success" <?= isset($response_data['buyer_show']) && $response_data['buyer_show'] == 1 ? 'checked' : '' ?>>
                    </div>

                    <div class="float-right mt-2">
                      <button type="button" id="add-settings" class="btn btn-primary">Save settings</button>
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
      $(document).on("click", "#add-settings", function(event) {
        event.preventDefault();
        $("#add-settings").attr("disabled", true);

        var url = '<?= @basePath ?>USER/Settings/Create';
        var formmethod = 'post';
        var formdata = $('#settings-form-add').serialize();

        var state = $("input[name='buyer_show']").bootstrapSwitch('state');

        // Append buyer_show to formData based on the state
        if (state) {
          formdata += '&buyer_show=1';
        } else {
          formdata += '&buyer_show=0';
        }
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
                window.location.reload();
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
                if ($('input[name=' + key + ']').length != 0)
                  $('input[name=' + key + ']').after('<span class="error ">' + value + '</span>');
                else if ($('select[name=' + key + ']').length != 0)
                  $('select[name=' + key + ']').after('<span class="error">' + value + '</span>');
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
          },
          complete: function() {
            // Re-enable the submit button after the request is complete
            $("#add-settings").attr("disabled", false);
          }
        });
      });
    </script>
</body>

</html>