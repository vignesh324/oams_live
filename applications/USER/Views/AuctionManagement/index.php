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
                <li class="breadcrumb-item active">Bidding Management</li>
              </ol>
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
      <div class="modal fade" id="modal-rp">
        <div class="modal-dialog modal-l">
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">Old Price</label>
                      <input type="text" class="form-control" id="gp_date" placeholder="Old Price" value="89" readonly>
                    </div>
                  </div>
                  <div class="col-md-12">
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
                  <h3 class="card-title">Bidding Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sale No</th>
                        <th>Lot No</th>
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

                        $startDateTime = strtotime($value['date'] . ' ' . $value['start_time']);
                        $endDateTime = strtotime($value['date'] . ' ' . $value['end_time']);
                        $diff = $startDateTime - $endDateTime;

                        // Get the start and end of the date
                        $startDate = strtotime($value['date']);
                        $endDate = strtotime($value['date']);


                        // if($diff >= 0 && date("Y-m-d",$startDateTime)==date("Y-m-d"))
                        if ($diff >= 0) {
                      ?>
                          <tr>
                            <td><?php echo @$value['sale_no']; ?></td>
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
                              <a href="#" class="btn btn-dark-cyne edit_button" onclick="showAuctionBiddings(<?php echo @$value['id']; ?>)" title="View Bid(s)" id="ids" data-toggle="modal" data-target="#modal-sm"><span><i class="fa fa-eye" title="View"></i></span></a>
                            </td>
                          </tr>
                      <?php
                        }
                      } ?>

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
      function showAuctionBiddings(id) {
        console.log(id);
        $(".loading").show();
        $.ajax({
          type: "post",
          url: "<?= @basePath ?>USER/AuctionBiddings/Show",
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
              text: "An error occurred",
            });
          }
        });
      };

      function finalize(id) {
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
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "An error occurred",
            });
          }
        });
      }
    </script>