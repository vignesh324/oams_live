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
                <li class="breadcrumb-item active">Users</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-app float-sm-right" onclick="addUser()" data-toggle="modal" data-target="#modal-sm">
                <span class="badge bg-purple">New</span>
                <i class="fas fa-users"></i> ADD
              </button>
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
                  <h3 class="card-title">Users Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data['users'] as $key => $data) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $data['name']; ?></td>
                          <td><?php echo $data['role']; ?></td>
                          <td><?php echo $data['email']; ?></td>
                          <td><?php echo $data['phone']; ?></td>
                          <td>
                            <a href="#" onclick="editUser(<?php echo $data['id']; ?>)" class="btn btn-dark-cyne edit_button" id="editButton" style="margin-right: 5px;" data-toggle="modal" data-target="#modal-sm">
                              <i class="fa fa-edit" title="Edit"></i>
                            </a>
                            |
                            <a href="#" title="Delete" onclick="deleteUser(<?php echo $data['id']; ?>)" id="ids" class="btn btn-dark-cyne delete_button">
                              <i class="fa fa-trash-alt"></i>
                            </a>
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
    <?= @$footer ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
  <!-- SweetAlert CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Validation -->
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?= @basePath ?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/jszip/jszip.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>

  <!-- Page specific script -->
  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });



    function editUser(id) {
      console.log(id);
      $.ajax({
        type: "post",
        url: "<?php echo @basePath; ?>SA/Users/Show",
        data: {
          id: id
        },
        dataType: 'html',
        success: function(response) {
          $(".modal-content").html(response);
          $('#modal-sm').modal('show');
        },
        error: function(error) {
          console.error('Error loading modal content:', error);
          // Handle errors gracefully
        }
      });
    };

    function addUser(id) {
      console.log(id);
      $.ajax({
        type: "post",
        url: "<?php echo @basePath; ?>SA/Users/Add",
        data: {
          id: id
        },
        dataType: 'html',
        success: function(response) {
          $(".modal-content").html(response);
          $('#modal-sm').modal('show');
        },
        error: function(error) {
          console.error('Error loading modal content:', error);
          // Handle errors gracefully
        }
      });
    };

    function deleteUser(id) {
      // Display a confirmation dialog
      swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this user.',
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
            url: "<?php echo @basePath; ?>SA/Users/Delete",
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
                text: 'An error occurred while deleting the user.',
              });
            }
          });
        }
      });
    };



    $(document).on("click", "#user-add-button", function(event) {
      event.preventDefault();
      $("#user-add-button").attr("disabled", true);

      var url = '<?php echo @basePath; ?>SA/Users/Create';
      var formmethod = 'post';
      $.ajax({
        url: url,
        type: formmethod,
        data: $('form#user-add-form').serialize(),
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
          $("#user-add-button").attr("disabled", false);
        }
      });
    });


    $(document).on("click", "#user-edit-button", function(event) {
      event.preventDefault();
      $("#user-add-button").attr("disabled", true);

      var url = '<?php echo @basePath; ?>SA/Users/Update';
      var formmethod = 'post';
      var formdata = $('form#user-form-edit').serialize();
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
          $("#user-add-button").attr("disabled", false);
        }
      });
    });
  </script>
</body>

</html>