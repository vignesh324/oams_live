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
                <li class="breadcrumb-item active">Samples Receipt</li>
              </ol>
            </div>
            <div class="col-sm-6">
              <a href="#" class="btn btn-app float-sm-right" onclick="addSampleReceipt()" data-toggle="modal" data-target="#modal-sm">
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
                  <h3 class="card-title">Samples Receipt Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>LOT No</th>
                        <th>Buyer</th>
                        <th>Sample Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (isset($response_data['sampleReceipt'])) {
                        foreach (@$response_data['sampleReceipt'] as $key => $value) : ?>
                          <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo @$value['lot_no']; ?></td>
                            <td><?php echo @$value['buyer_name']; ?></td>
                            <td><?php echo @$value['quantity']; ?></td>
                            <td>
                              <?php if ($value['status'] == 1) : ?>
                                <span class="badge badge-success">Active</span>
                              <?php else : ?>
                                <span class="badge badge-warning">Inactive</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php //if ($state_permission[0]['update_permission'] == 1) : 
                              ?>
                              <a href="#" onclick="editSampleReceipt(<?php echo $value['id']; ?>)" class="btn btn-dark-cyne edit_button" id="editButton" style="margin-right: 5px;" data-toggle="modal" data-target="#modal-sm">
                                <i class="fa fa-edit" title="Edit"></i>
                              </a>
                              |
                              <?php //endif; 
                              ?>
                              <?php //if ($state_permission[0]['delete_permission'] == 1) : 
                              ?>
                              <a href="#" title="Delete" onclick="deleteSampleReceipt(<?php echo $value['id']; ?>)" id="ids" class="btn btn-dark-cyne delete_button">
                                <i class="fa fa-trash-alt"></i>
                              </a>
                              <?php //endif; 
                              ?>
                            </td>
                          </tr>
                      <?php
                        endforeach;
                      }
                      ?>
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
      function editSampleReceipt(id) {
        // console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/SampleReceipt/Show",
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

      function addSampleReceipt() {
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/SampleReceipt/Add",
          dataType: 'html',
          success: function(response) {
            $(".loading").hide();
            $(".modal-content").html(response);
            $('#modal-sm').modal('show');
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
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

      function deleteSampleReceipt(id) {
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
              url: "<?= @basePath ?>USER/SampleReceipt/Delete",
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



      $(document).on("click", "#add-samplereceipt", function(event) {
        event.preventDefault();
        $("#add-samplereceipt").attr("disabled", true);

        var url = '<?= @basePath ?>USER/SampleReceipt/Create';
        var formmethod = 'post';
        var formdata = $('form#samplereceipt-form').serialize();
        var selectedOption = $('#lot_no').find('option:selected');
        var auctionitem_id = selectedOption.data('auction-item-id');

        // Append auctionitem_id to formdata
        formdata += '&auctionitem_id=' + auctionitem_id;
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
            $("#add-samplereceipt").attr("disabled", false);
          }
        });
      });


      $(document).on("click", "#edit-samplereceipt", function(event) {
        event.preventDefault();
        $("#edit-samplereceipt").attr("disabled", true);

        var url = '<?= @basePath ?>USER/SampleReceipt/Update';
        var formmethod = 'post';
        var formdata = $('form#samplereceipt-form-edit').serialize();
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
            $("#edit-samplereceipt").attr("disabled", false);
          }
        });
      });

      $(document).on("change", "#sale_no", function(event) {
        var auction_id = $(this).val();
        // console.log(auction_id);

        // alert('hiii');
        if (sale_no == "") {
          $("#lot_no").empty();
          $("#lot_no").append('<option value="">Select LOT No</option>');
        } else {
          $.ajax({
            url: "<?= @basePath ?>USER/SampleReceipt/SalenoWiseLot",
            type: "POST",
            data: {
              auction_id: auction_id,
            },
            dataType: "json",
            success: function(response) {
              $("#lot_no").empty();
              $("#lot_no").append('<option value="">Select LOT No</option>');

              if (response.status == 200) {
                // console.log(response.data)
                $.each(response.data.lot_no, function(key, value) {
                  $("#lot_no").append(
                    '<option value="' + value.lot_no + '" data-auction-item-id="' + value.id + '">' + value.lot_no + "</option>"
                  );
                });
              } else if (response.status == 404) {
                $("#lot_no").append('<option value="">No data found</option>');
              }
            },
            error: function(xhr, status, error) {
              console.error(error);
            },
          });
        }
      });
    </script>
</body>

</html>