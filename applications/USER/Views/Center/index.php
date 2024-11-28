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
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php
    $permissions = session()->get('permissions');
    $filtered_permissions = array_filter($permissions, function ($value, $key) {
      return $value['module_id'] == 7;
    }, ARRAY_FILTER_USE_BOTH);

    $center_permission = array_values($filtered_permissions);
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
                <li class="breadcrumb-item active">Center</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <?php echo render_add_button(7, 'addCenter()'); ?>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content" id="user_form">
            <!-- user_form -->
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
                <div class="card-header">
                  <h3 class="card-title">Center Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Center Name</th>
                        <th>Code</th>
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
                          <td><?php echo CENTER . $value['id']; ?></td>
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
                            <!-- <a href="#" onclick="assignGarden(<?php echo $value['id']; ?>)" data-toggle="modal" data-target="#modal-assign" class="btn btn-dark-cyne edit_button" id="#" style="margin-right: 5px;"><i class="fa fa-arrow-right" title="Assign"></i> </a> -->

                            <?php
                            echo render_edit_button(7, "editCenter({$value['id']})");
                            echo render_delete_button(7, "deleteCenter({$value['id']})");
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


    <script>
      function editCenter(id) {
        $(".loading").show();
        // console.log(id);
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Center/Show",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $("#user_form").html(response);
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
      }



      function addCenter() {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Center/Add",
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $("#user_form").html(response);
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

      function deleteCenter(id) {
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
              url: "<?= @basePath ?>USER/Center/Delete",
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

      function assignGarden(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/Center/AssignGarden",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $("#assign_order").html(response);
            $('#modal-assign').modal('show');
            $('.select2').select2()

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

      $(document).on("click", "#assign-garden", function(event) {
        $("#assign-garden").attr("disabled", true);

        function captureOrderSequence() {
          var orderSeq = [];
          $('.timeline-item').each(function() {
            var gardenId = $(this).find('a[name="order_seq"]').attr('value');
            orderSeq.push(gardenId);
          });
          return orderSeq;
        }

        var gardenIds = $('select[name="garden_id"]').val();
        var centerId = $('input[name="center_id"]').val();
        var orderSeq = captureOrderSequence();

        // Prepare data for submission
        var formdata = {
          center_id: centerId,
          garden_id: gardenIds,
          order_seq: orderSeq
        };

        event.preventDefault();
        var url = '<?= @basePath ?>USER/Center/SaveGarden';
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
            $("#assign-garden").attr("disabled", false);
          }
        });
      });
    </script>
    <script>
      // jQuery code to change order sequence on click
      $(document).ready(function() {
        $('.time').on('click', function() {
          var timelineItem = $(this).closest('.timeline-item');
          if ($(this).find('i').hasClass('fa-caret-down')) {
            timelineItem.insertBefore(timelineItem.prev());
          } else if ($(this).find('i').hasClass('fa-sort-up')) {
            timelineItem.insertAfter(timelineItem.next());
          }
          alert("hiii");
        });
      });
    </script>

</body>

</html>