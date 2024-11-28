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
    .error {
      color: red;
    }
  </style>
</head>

<body class="sidebar-mini layout-top-nav" style="height: auto;">
  <div class="wrapper">
    <!-- Navbar -->
    <?= @$header ?>
    <!-- /.navbar -->

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
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
                  <h3 class="card-title">Edit Profile</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="user-form">
                    <input type="hidden" class="form-control" id="id" name="id" value="<?= htmlspecialchars(@$response_data['id'], ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-group">
                      <label for="name">Buyer Name</label>
                      <input type="text" name="name" class="form-control" id="name" placeholder="Enter Buyer Name" value="<?= htmlspecialchars(@$response_data['name'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                      <label for="gst_no">GST No</label>
                      <input type="text" class="form-control" name="gst_no" id="gst_no" placeholder="Enter GST No" value="<?= htmlspecialchars(@$response_data['gst_no'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="form-group">
                      <label for="address">Address</label>
                      <input type="text" class="form-control" name="address" id="address" placeholder="Enter Address" value="<?= htmlspecialchars(@$response_data['address'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <button type="button" id="profile-update" class="btn btn-primary float-right">Save changes</button>
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
      $(document).on("click", "#profile-update", function(event) {
        event.preventDefault();
        $("#profile-update").attr("disabled", true);

        var url = '<?= @basePath ?>BUYER/ProfileUpdate';
        var formmethod = 'post';
        var formdata = $('form#user-form').serialize();
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
                window.location.reload(); // Reload the page on success
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
            $("#profile-update").attr("disabled", false);
          }
        });
      });
    </script>
</body>

</html>