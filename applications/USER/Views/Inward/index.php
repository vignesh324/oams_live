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
                <li class="breadcrumb-item active">Inward</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-app float-sm-right" href="<?= @basePath ?>USER/Inward/Add">
                <span class="badge bg-purple">New</span>
                <i class="fas fa-users"></i> ADD
              </a>
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
                  <h3 class="card-title">Inward Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Arrival Date</th>
                        <th>Center</th>
                        <th>Seller</th>
                        <th>Garden</th>
                        <th>Warehouse</th>
                        <th>No of Bags</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($response_data as $key => $value) :
                      ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo date("d-m-Y", strtotime($value['arrival_date'])); ?></td>
                          <td><?php echo $value['center_name']; ?></td>
                          <td><?php echo $value['seller_name']; ?></td>
                          <td><?php echo $value['garden_name']; ?></td>
                          <td><?php echo $value['warehouse_name']; ?></td>
                          <td><?php echo $value['quantity']; ?></td>
                          <td>
                            <a href="<?= @basePath ?>USER/Inward/View/<?php echo  base64_encode($value['id']); ?>" class="btn btn-dark-cyne" id="#" style="margin-right: 5px;"><i class="fa fa-eye" title="View"></i> </a>
                            <!-- <a href="<?= @basePath ?>USER/Inward/Edit/<?php echo  base64_encode($value['id']); ?>" class="btn btn-dark-cyne edit_button" id="#" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i> </a> -->
                            
                            <?php if ($value['cnt'] == 0 && $value['auction_cnt'] == 0 && $value['cart_cnt'] == 0) {
                              echo render_delete_button(17, "deleteInward({$value['id']})");
                            } ?>

                            <?php
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
      function deleteInward(id) {
        // Display a confirmation dialog
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to delete this Center.',
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
              url: "<?= @basePath ?>USER/Inward/Delete",
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
      }
    </script>
</body>

</html>