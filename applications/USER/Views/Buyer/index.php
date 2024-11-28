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
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php
    $permissions = session()->get('permissions');
    $filtered_permissions = array_filter($permissions, function ($value, $key) {
      return $value['module_id'] == 6;
    }, ARRAY_FILTER_USE_BOTH);

    $buyer_permission = array_values($filtered_permissions);
    // print_r($seller_permission);exit;
    ?>
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
                <li class="breadcrumb-item active">Buyer</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <?php echo render_add_button(6, 'addBuyer()'); ?>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">


          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Buyers Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Buyer Name</th>
                        <th>Code</th>
                        <th>Contact Person</th>
                        <th>GST</th>
                        <th>FSSAI</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Area</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data as $key => $value) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $value['name']; ?></td>
                          <td><?php echo BUYER.$value['id']; ?></td>
                          <td><?php echo $value['contact_person_name']; ?></td>
                          <td><?php echo $value['gst_no']; ?></td>
                          <td><?php echo $value['fssai_no']; ?></td>
                          <td><?php echo $value['state_name']; ?></td>
                          <td><?php echo $value['city_name']; ?></td>
                          <td><?php echo $value['area_name']; ?></td>
                          <td>
                            <?php if ($value['status'] == 1) : ?>
                              <span class="badge badge-success">Active</span>
                            <?php else : ?>
                              <span class="badge badge-warning">Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php
                            echo render_edit_button(6, "editBuyer({$value['id']})");
                            echo render_delete_button(6, "deleteBuyer({$value['id']})");
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>


                    </tbody>

                  </table>
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


    <!-- Page specific script -->
    <script>
      function editBuyer(id) {
        $('.loading').show();
        // console.log(id);
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Buyer/Show",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $('.loading').hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(error) {
            $('.loading').hide();
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'An error occurred, Please contact site admin.',
            }); // Handle errors gracefully
          }
        });
      };

      function addBuyer() {
        $('.loading').show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Buyer/Add",
          dataType: 'html',
          success: function(response) {
            $('.loading').hide();
            $(".modal-content").html('');
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(error) {
            $('.loading').hide();
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'An error occurred, Please contact site admin.',
            }); // Handle errors gracefully
          }
        });
      };

      function deleteBuyer(id) {
        // Display a confirmation dialog
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to delete this Buyer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'delete!'
        }).then((result) => {
          if (result.isConfirmed) {
            console.log(id);
            $.ajax({
              type: "post",
              url: "<?= @basePath ?>USER/Buyer/Delete",
              data: {
                id: id
              },
              dataType: 'html',
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Deleted successfully',
                }).then((result) => {
                  if (result.isConfirmed || result.isDismissed) {
                    window.location.reload(); // Reload the page on success
                  }
                });
              },
              error: function(error) {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'An error occurred while deleting the Seller.',
                });
              }
            });
          }
        });
      };
    </script>
</body>

</html>