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
                <li class="breadcrumb-item active">Roles</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-app float-sm-right" data-toggle="modal" data-target="#modal-sm">
                <span class="badge bg-purple">New</span>
                <i class="fas fa-users"></i> ADD
              </button>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form id="role-form-edit">
              <div class="modal-header">
                <h4 class="modal-title">Update Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body" id="modal-content-edit">
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="role-edit-button" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form id="role-form-add">
              <div class="modal-header">
                <h4 class="modal-title">Add New Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                    <label for="roles">Roles</label>
                    <table class="table">
                      <tr>
                        <th>Module</th>
                        <th>View</th>
                        <th>Create</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>

                      <?php foreach ($response_data as $key => $value) : ?>
                        <tr>
                          <td>
                            <span class="text-danger">
                              <?php echo $value['name']; ?>
                            </span>
                          </td>
                          <?php for ($i = 0; $i < 4; $i++) : ?>
                            <td>
                              <div class="form-check">
                                <input
                                  type="checkbox"
                                  value="1"
                                  name="module_view[<?php echo $value['id']; ?>][<?php echo $i; ?>]"
                                  class="form-check-input"
                                  id="exampleCheck<?php echo $key + 1; ?>_<?php echo $i; ?>"
                                  <?php echo ($key == 0 && $i == 0) ? 'checked' : ''; ?>
                                  <?php echo ($value['id'] == 17 && $i == 2) ? 'disabled' : ''; ?>
                                  <?php echo (in_array($value['id'], [22, 23, 24, 26, 27, 28, 29]) && in_array($i, [1, 2, 3])) ? 'disabled' : ''; ?>
                                  <?php echo (in_array($value['id'], [18]) && $i == 3) ? 'disabled' : ''; ?>
                                  <?php echo (in_array($value['id'], [16]) && in_array($i, [1, 3])) ? 'disabled' : ''; ?>>
                                <label class="form-check-label" for="exampleCheck<?php echo $key + 1; ?>_<?php echo $i; ?>">
                                  <?php
                                  echo ($i == 0) ? 'View' : (($i == 1) ? 'Create' : (($i == 2) ? 'Edit' : 'Delete'));
                                  ?>
                                </label>
                              </div>

                            </td>
                          <?php endfor; ?>
                        </tr>
                      <?php endforeach; ?>




                    </table>
                  </div>

                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="role-add-button" class="btn btn-primary">Save changes</button>
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
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Roles Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>S.no</th>
                        <th>Role</th>
                        <th>Permissions(s)</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($list_response_data as $key => $value) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $value['role']; ?></td>
                          <td>Manage <?php echo $value['module_names']; ?> </td>
                          <td>
                            <a href="#" class="btn btn-dark-cyne edit_button" data-id="<?php echo $value['id']; ?>" id="edit_id_<?php echo $key + 1; ?>" style="margin-right: 5px;"><i class="fa fa-edit" title="Edit"></i> </a> |
                            <a href="#" title="Delete" onclick="deleteRole(<?php echo $value['id']; ?>)" id="ids" class="btn btn-dark-cyne"><i class="fa fa-trash-alt"></i></a>
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
  <!-- AdminLTE App -->
  <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
  <script src="<?= @basePath ?>admin_assets/dist/js/common.js"></script>
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
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });

    $(function() {

      $(".edit_button").click(function() {
        $("#modal-edit").modal('show');
        var role_id = $(this).data('id');
        $.ajax({
          url: '<?= @basePath ?>SA/roles/edit', // <-- point to server-side PHP script 
          cache: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            "id": role_id
          },
          type: 'post',
          success: function(response) {
            $("#modal-content-edit").html("");
            $("#modal-content-edit").html(response);
          }
        });

      })
    });


    function deleteRole(id) {
      // Display a confirmation dialog
      swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this state.',
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
            url: '<?= @basePath ?>SA/DeleteRole',
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
                text: 'An error occurred while deleting the state.',
              });
            }
          });
        }
      });
    };



    $(document).on("click", "#role-add-button", function(event) {
      event.preventDefault();
      $("#role-add-button").attr("disabled", true);

      var url = '<?= @basePath ?>SA/StoreRole';
      var formmethod = 'post';
      var formdata = $('#role-form-add').serialize();
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
          $("#role-add-button").attr("disabled", false);
        }
      });
    });


    $(document).on("click", "#role-edit-button", function(event) {
      event.preventDefault();
      $("#role-edit-button").attr("disabled", true);

      var url = '<?= @basePath ?>SA/UpdateRole';
      var formmethod = 'post';
      var formdata = $('form#role-form-edit').serialize();
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
          $("#role-edit-button").attr("disabled", false);
        }
      });
    });
  </script>
</body>

</html>