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
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
  <style>
    ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .menu-container {
      text-align: center;
    }

    .menu-container>ul {
      display: inline-block;
    }

    .menu:after {
      content: '';
      display: table;
      clear: both;
    }

    .menu .item {
      cursor: move;
      margin: 5px;
      position: relative;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .menu .item-content {
      border: 1px solid #ddd;
      padding: 10px 50px 10px 10px;
    }

    .menu .remove {
      cursor: pointer;
      color: #fff;
      background-color: #ff7171;
      padding: 4px;
      font-size: 15px;
      width: 10px;
      height: 10px;
    }

    .sortable-placeholde {
      float: left;
      border: 1px dashed #000;
      width: 100%;
    }

    .ui-sortable-placeholder {
      border: 1px dashed;
      visibility: visible !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <?php
    $permissions = session()->get('permissions');
    $filtered_permissions = array_filter($permissions, function ($value, $key) {
      return $value['module_id'] == 5;
    }, ARRAY_FILTER_USE_BOTH);

    $garden_permission = array_values($filtered_permissions);
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
                <li class="breadcrumb-item active">Garden</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <?php echo render_add_button(5, 'addGarden()'); ?>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-l">
          <div class="modal-content">

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

      <div class="modal fade" id="modal-assign">
        <div class="modal-dialog modal-l">
          <div class="modal-content" id="assign_order">
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
                <div class="card-header d-flex justify-content-between">
                  <h3 class="card-title">Garden Management</h3>
                  <div class="ml-auto">
                    <a class="btn bg-success btn-md" href="<?= @basePath ?>USER/Garden/Category">
                      Grade Reorder
                    </a>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Garden Name</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Seller</th>
                        <th>Adress</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Area</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($response_data['garden'] as $key => $value) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td>
                            <?php echo $value['name']; ?>&nbsp;&nbsp;
                            <?php if ($value['vacumm_bag'] == 1) {
                              echo '<i class="fab fa-vimeo-v"></i>';
                            } ?>
                          </td>
                          <td><?php echo GARDEN . $value['id']; ?></td>
                          <td><?php echo $value['category_name']; ?></td>
                          <td><?php echo $value['seller_name']; ?></td>
                          <td><?php echo $value['address']; ?></td>
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
                            <a href="#" onclick="assignGrade(<?php echo $value['id']; ?>,<?php echo $value['category_id']; ?>)" data-toggle="modal" data-target="#modal-assign" class="btn btn-dark-cyne edit_button" id="#"><i class="fa fa-arrow-right" title="Assign"></i> </a>

                            <?php
                            echo render_edit_button(5, "editGarden({$value['id']})");
                            echo render_delete_button(5, "deleteGarden({$value['id']})");
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
    <?= @$data['footer'] ?>


    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Page specific script -->
    <script>
      function editGarden(id) {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Garden/Show",
          data: {
            id: id
          },
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
              text: "An error occurred while deleting the state.",
            });
          }
        });
      };

      function addGarden() {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Garden/Add",
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
              text: "An error occurred while deleting the state.",
            });
          }
        });
      };

      function deleteGarden(id) {
        // Display a confirmation dialog
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to delete this Garden.',
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
              url: "<?= @basePath ?>USER/Garden/Delete",
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
                  text: 'An error occurred while deleting the Garden.',
                });
              }
            });
          }
        });
      };

      function assignGrade(id,category_id) {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Garden/AssignGrade",
          data: {
            id: id,
            category_id: category_id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $("#assign_order").html(response);
            $('#modal-assign').modal('show');

            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
          },
          error: function(error) {
            $(".loading").hide();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred while deleting the state.",
            });

          }
        });
      }


      $(document).on("click", "#add-garden", function(event) {
        event.preventDefault();
        $("#add-garden").attr("disabled", true);

        var url = '<?= @basePath ?>USER/Garden/Create';
        var formmethod = 'post';
        var formdata = $('#garden-form').serialize();
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
            $("#add-garden").attr("disabled", false);
          }
        });
      });


      $(document).on("click", "#edit-garden", function(event) {
        event.preventDefault();
        $("#edit-garden").attr("disabled", true);

        var url = '<?= @basePath ?>USER/Garden/Update';
        var formmethod = 'post';
        var formdata = $('#garden-form-edit').serialize();
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
            $("#edit-garden").attr("disabled", false);
          }
        });
      });

      $(document).on("click", "#assign-grade", function(event) {
        $("#assign-grade").attr("disabled", true);

        function captureOrderSequence() {
          var orderSeq = [];
          $('.timeline-item').each(function() {
            var gradeId = $(this).find('a[name="order_seq"]').attr('value');
            orderSeq.push(gradeId);
          });
          return orderSeq;
        }

        var gradeIds = $('select[name="grade_id"]').val();
        var gardenId = $('input[name="garden_id"]').val();
        var orderSeq = captureOrderSequence();

        // Prepare data for submission
        var formdata = {
          garden_id: gardenId,
          grade_id: gradeIds,
          order_seq: orderSeq
        };

        event.preventDefault();
        var url = '<?= @basePath ?>USER/Garden/SaveGrade';
        var formmethod = 'post';
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
            // console.log(_response);
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
            $("#assign-grade").attr("disabled", false);
          }
        });
      });
    </script>

</body>

</html>