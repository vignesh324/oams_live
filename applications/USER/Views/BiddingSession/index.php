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
  <style>
    .icon-container {
      position: relative;
      display: inline-block;
    }

    .icon-container i {
      font-size: 16px;
    }

    .icon-container .badge {
      position: absolute;
      top: -2px;
      right: -5px;
      z-index: 1;
      font-size: 3px;
      padding: 3px 4px;
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
                <li class="breadcrumb-item active">Auction Creation</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-app float-sm-right" onclick="addBiddingSession()" data-toggle="modal" data-target="#modal-sm">
                <span class="badge bg-purple">New</span>
                <i class="fas fa-users"></i> ADD
                </a>
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
      <!-- /.modal -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Auction Creation</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Sale No</th>
                        <th>Type</th>
                        <th>Auction Date</th>
                        <th>Start Time</th>
                        <th>Center</th>
                        <th>Created At</th>
                        <!-- <th>End time</th>
                        <th>Bidder Name</th>
                        <th>Status</th> -->
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($response_data as $key => $val) : ?>
                        <tr>
                          <td><?php echo $key + 1; ?></td>
                          <td><?php echo $val['sale_no']; ?></td>
                          <td>
                            <?php
                            if ($val['type'] == 1)
                              echo "Leaf";
                            else
                              echo "Dust";
                            ?>
                          </td>
                          <td><?php echo date("d-m-Y", strtotime($val['date'])); ?></td>
                          <td><?php echo $val['start_time']; ?></td>
                          <td><?php echo $val['center_name']; ?></td>
                          <td>
                            <?php
                            $utcDateTime = new DateTime($val['created_at'], new DateTimeZone('UTC'));
                            $utcDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
                            $istDateTime = $utcDateTime->format('d-m-Y g:i A');
                            echo $istDateTime;
                            ?>
                          </td>
                          <!-- <td><?php //echo $val['end_time']; 
                                    ?></td> -->
                          <!-- <td>tbs</td> -->
                          <!-- <td><span class="badge badge-success">Active</span></td> -->
                          <td>
                            <?php
                            if ($val['is_publish'] == 1) { ?>
                              <a class="btn btn-dark-cyne view_button" href="<?= @basePath ?>USER/BiddingSession/View/<?php echo base64_encode($val['id']); ?>">
                                <span><i class="fa fa-eye"></i></span>
                              </a>
                            <?php } ?>

                            <?php
                            if ($val['status'] != 2) {
                              if ($val['is_publish'] == 0) { ?>
                                <!-- <a class="btn btn-dark-cyne" id="add-auction-item-button_<?php echo $val['id']; ?>" href="<?= @basePath ?>USER/BiddingSession/AddAuctionItems/<?php echo base64_encode($val['id']); ?>">
                                <span><i class="fa fa-arrow-right"></i></span>
                              </a> -->
                                <a class="btn btn-dark-cyne" id="add-auction-cart-item-button_<?php echo $val['id']; ?>" href="<?= @basePath ?>USER/BiddingSession/AuctionCartItems/<?php echo base64_encode($val['id']); ?>">
                                  <span class="icon-container">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="badge badge-danger">&nbsp;</span> <!-- Example count badge -->
                                  </span>
                                </a>
                              <?php } ?>
                              <!-- <a class="btn btn-dark-cyne" id="add-auction-item-center-button_<?php echo $val['id']; ?>" href="<?= @basePath ?>USER/BiddingSession/reOrderGarden/<?php echo base64_encode($val['id']); ?>">
                                <span><i class="fas fa-sync-alt"></i></span>
                              </a> -->
                              <?php
                              $startDateTime = strtotime($val['date'] . ' ' . $val['start_time']);
                              $endDateTime = strtotime($val['date'] . ' ' . $val['end_time']);
                              $now = time();
                              $diff = $startDateTime - $now;
                              $hoursDifference = $diff / (60 * 60);
                              // print_r($now); 
                              ?>

                              <?php
                              if ($hoursDifference > (1 / 2) && $val['is_publish'] == 1) { // Show edit button if more than 30 mins before start time 
                              ?>
                                <a class="btn btn-dark-cyne edit_button" href="<?= @basePath ?>USER/BiddingSession/EditValuation/<?php echo base64_encode($val['id']); ?>">
                                  <span><i class="fa fa-edit"></i></span>
                                </a>
                              <?php } ?>

                              <?php
                              if ($val['is_publish'] == 1 && $hoursDifference > (1 / 2)) {
                              ?>
                                <a class="btn btn-dark-cyne edit_button" onclick="editBiddingSession(<?php echo $val['id']; ?>)" href="#">
                                  <span><i class="fa fa-clock"></i></span>
                                </a>

                                <!-- <a class="btn btn-dark-cyne edit_button" href="#" onclick="deleteBiddingSession(<?php echo $val['id']; ?>)">
                                <span><i class="fa fa-trash-alt"></i></span>
                              </a> -->
                              <?php } ?>

                              <?php
                              if ($val['is_publish'] == 1 && $val['status'] == 1 && $now >= $startDateTime && $now <= $endDateTime) { // Show edit and delete buttons if more than 24 hours before start time
                              ?>
                                <a class="btn btn-dark-cyne edit_button" href="<?= @basePath ?>USER/BiddingSession/EditReserve/<?php echo base64_encode($val['id']); ?>">
                                  <span><i class="fa fa-pen"></i></span>
                                </a>
                              <?php } ?>

                              <?php
                              if ($val['is_publish'] == 1 && $val['status'] == 1 && $now >= $endDateTime) { // Show edit and delete buttons if more than 24 hours before start time
                              ?>
                                <button title="Close Auction" onclick="closeCurrentAuctionManually(<?php echo $val['id']; ?>)" class="btn btn-dark-cyne">
                                  <span><i class="fas fa-times"></i></span>
                                </button>
                              <?php } ?>

                              <?php
                              if ($val['is_publish'] == 1 && $val['status'] == 3) { ?>
                                <a class="btn btn-dark-cyne view_button" title="Completed" href="<?= @basePath ?>USER/BiddingSession/CompletedAuctions/<?php echo base64_encode($val['id']); ?>">
                                  <span><i class="fa fa-check"></i></span>
                                </a>
                            <?php }
                            }
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
      function addBiddingSession() {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/BiddingSession/Create",
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
      };

      function editBiddingSession(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/BiddingSession/Edit",
          data: {
            id: id
          },
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
          },
          error: function(xhr, status, error) {
            $(".loading").hide();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred while editing the session.",
            });
            console.error(xhr.responseText);
          }
        });
      };

      function deleteBiddingSession(id) {
        // Display a confirmation dialog
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to delete this item.',
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
              url: "<?= @basePath ?>USER/BiddingSession/Delete",
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
                  text: 'An error occurred while deleting the area.',
                });
              }
            });
          }
        });
      };

      function closeCurrentAuctionManually(id) {
        // Display a confirmation dialog
        swal.fire({
          title: 'Are you sure?',
          text: 'You want to close this Auction.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Close!'
        }).then((result) => {
          if (result.isConfirmed) {
            console.log(id);
            $.ajax({
              type: "post",
              url: "<?= @basePath ?>USER/BiddingSession/CloseCurrentAuctionManually",
              data: {
                id: id
              },
              dataType: 'html',
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Auction Closed successfully',
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
                  text: 'An error occurred while deleting the area.',
                });
              }
            });
          }
        });
      };

      $(document).on("click", "#edit-biddingsession", function(event) {
        event.preventDefault();
        $("#edit-biddingsession").attr("disabled", true);

        var url = '<?= @basePath ?>USER/BiddingSession/Update';
        var formmethod = 'post';
        var formdata = $('form#biddingsession-form-edit').serialize();
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
                if ($('#' + key).length != 0)
                  $('#' + key).after('<span class="error ">' + value + '</span>');
                else if ($('#' + key).length != 0)
                  $('#' + key).after('<span class="error">' + value + '</span>');
                else
                  $('#' + key).after('<span class="error">' + value + '</span>');
              });
            }
          },
          complete: function() {
            // Re-enable the submit button after the request is complete
            $("#edit-biddingsession").attr("disabled", false);
          }
        });
      });

      $(document).on("click", "#add-auction", function(event) {
        event.preventDefault();
        $("#add-auction").attr("disabled", true);

        var url = '<?= @basePath ?>USER/BiddingSession/Store';
        var formmethod = 'post';
        var formdata = $('form#auction-form').serialize();
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

                if ($('#' + key).length != 0)
                  $('#' + key).after('<span class="error ">' + value + '</span>');
                else if ($('#' + key).length != 0)
                  $('#' + key).after('<span class="error">' + value + '</span>');
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
            $("#add-auction").attr("disabled", false);
          }
        });
      });
    </script>
</body>

</html>