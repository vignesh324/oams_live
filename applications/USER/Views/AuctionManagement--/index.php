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
                <li class="breadcrumb-item active">Auction Management</li>
              </ol>
            </div>

          </div>
        </div><!-- /.container-fluid -->
      </section>


      <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form>
              <div class="modal-header">
                <h4 class="modal-title">Bidding Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Buyer</th>
                        <th>Bid amount</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>20-01-2024</td>
                        <td>Puspam traders</td>
                        <td>6000</td>
                        <td><span type="button" class="btn btn-success final_btn" id="1"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                      </tr>
                      <tr>
                        <td>20-01-2024</td>
                        <td>AK Traders</td>
                        <td>6500</td>
                        <td><span type="button" class="btn btn-success final_btn" id="2"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                      </tr>
                      <tr>
                        <td>20-01-2024</td>
                        <td>Tea Shop</td>
                        <td>6600</td>
                        <td><span type="button" class="btn btn-success final_btn" id="3"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                      </tr>
                      <tr>
                        <td>20-01-2024</td>
                        <td>Trade hunt</td>
                        <td>6700</td>
                        <td><span type="button" class="btn btn-success final_btn" id="4"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                      </tr>
                      <tr>
                        <td>20-01-2024</td>
                        <td>Auction bid</td>
                        <td>6710</td>
                        <td><span type="button" class="btn btn-success final_btn" id="5"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal fade" id="modal-rp">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form>
              <div class="modal-header">
                <h4 class="modal-title">Revise Price</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Old Price</label>
                      <input type="text" class="form-control" id="gp_date" placeholder="Old Price" value="89" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Reverse Price</label>
                      <input type="text" class="form-control" id="gp_date" placeholder="Reverse Price">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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
                  <h3 class="card-title">Auction Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>LotNo</th>
                        <th>Mark</th>
                        <th>Grade</th>
                        <th>No.of Bags</th>
                        <th>Each Net</th>
                        <th>Total Net</th>
                        <th>B.P</th>
                        <th>Valuation</th>
                        <th>LSP / SP</th>
                        <th>HBP</th>
                        <th>SQ</th>
                        <th>BQ</th>
                        <th>Bidder Name</th>
                        <th>Action</th>
                      </tr>

                    </thead>
                    <tbody>
                      <?php
                      $sno = 1;
                      foreach ($response_data as $value) {
                      ?>
                        <tr>
                          <td><?php echo @$value['lot_no']; ?></td>
                          <td><?php echo @$value['gardenname']; ?></td>
                          <td><?php echo @$value['gradename']; ?></td>
                          <td><?php echo @$value['auction_quantity']; ?></td>
                          <td><?php echo @$value['weight_net']; ?></td>
                          <td><?php echo @$value['total_net']; ?></td>
                          <td><?php echo @$value['base_price']; ?></td>
                          <td style="background-color: #0131e8;">-</td>
                          <td style="background-color: #0131e8;">-</td>
                          <td><?php echo @$value['high_price']; ?></td>
                          <td>121.00-125.00</td>
                          <td>160</td>
                          <td>Devon</td>
                          <td>
                            <!--a href="#" class="btn btn-dark-cyne edit_button" title="Revise Price" id="ide" data-toggle="modal" data-target="#modal-rp"><span><i class="fas fa-edit" title="Revise Price"></i></span></a-->
                            <a href="#" class="btn btn-dark-cyne edit_button" title="View Bid(s)" id="ids" data-toggle="modal" data-target="#modal-sm"><span><i class="fa fa-eye" title="View"></i></span></a>
                          </td>
                        </tr>
                      <?php } ?>

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
      $(".final_btn").click(function() {
        event.preventDefault();
        var url = '<?= @basePath ?>USER/AuctionManagement/Finalize';
        var formmethod = 'post';
        var formdata = $('form#finalize-form').serialize();
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
          }
        });
      })
    </script>