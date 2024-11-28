<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=@CompanyName?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=@basePath?>admin_assets/dist/css/adminlte.min.css">
  <style>
    .table-bordered th {
    font-size: smaller!important;
    }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?=@$header?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?=@$sidebar?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a  href="<?=@basePath?>USER/Inward">Inward</a></li>
              <li class="breadcrumb-item active">Add</li>
            </ol>
          </div>
          <div class="col-sm-6">
            &nbsp;
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
                <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="name">Select Center</label>
                    <select name="center_id" id="center_id" class="form-control">
                      <option>Select Center</option>
                      <?php 
                      foreach ($response_data['centers'] as $key => $value) {
                        ?>
                        <option value="1"><?php echo $value['name'];?></option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Select Seller</label>
                    <select class="form-control" name="seller_id" id="seller_id">
                      <option>Select Seller</option>
                      <?php 
                      foreach ($response_data['sellers'] as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                </div>
                </div>
                <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="name">Select Garden</label>
                    <select class="form-control" name="garden_id" id="garden_id">
                      <option>Select Garden</option>
                      <?php 
                      foreach ($response_data['gardens'] as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Select Warehouse</label>
                    <select class="form-control" name="warehouse_id" id="warehouse_id">
                      <option>Select Warehouse</option>
                      <?php 
                      foreach ($response_data['warehouses'] as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                </div>
                </div>
               <hr>
               <h5>Add Item Details</h5>
               <hr>
                <table class="table table-bordered table-striped" data-repeater-list="items" id="sortable-table">
                  <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Inv No</th>
                        <th>Tea Grade</th>
                        <th>Chest/Bag Type</th>
                        <th>No. of Chest/Bag</th>
                        <th>Serial Number From</th>
                        <th>Serial Number To</th>
                        <th>Weight per C/B (Kgs) Nett.</th>
                        <th>Weight per C/B (Kgs) Tare</th>
                        <th>Weight per C/B (Kgs) Gross</th>
                        <th>Total Wt. (Kgs) Nett.</th>
                        <th>Total Wt. (Kgs) Tare</th>
                        <th>Total Wt. (Kgs) Gross</th>
                        <th>Bay</th>
                        <th><span class="btn btn-xs btn-success add-row"><i class="fa fa-plus"></i></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr >
                        <td>1</td>
                        <td><input type="text" class="form-control" id="inv_no" placeholder="Inv no"></td>
                        <td>
                            <select class="form-control" name="grade_id[]" id="grade_id">
                            <option>Select Grade</option>
                            <option value="1">First Quality</option>
                            <option value="2">Low Quality</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="bag_type[]" id="bag_type">
                                <option>Select Bag</option>
                                <option value="1">Small</option>
                                <option value="2">Large</option>
                                <option value="2">Medium</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="no_of_bags[]" id="no_of_bag" placeholder="No of Bag">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="serial_no_from" name="serial_no_from[]" placeholder="Serial no from">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="serial_no_to" name="serial_no_to[]" placeholder="Serial no to">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="nett" name="weight_nett[]" placeholder="Weight per C/B (Kgs) Nett">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="tare" name="weight_tare[]" placeholder="Weight per C/B (Kgs) Tare">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="gross" name="weight_gross[]" placeholder="Weight per C/B (Kgs) Gross">
                        </td>
                        <td>
                            
                            <input type="text" class="form-control" id="nett1" name="total_net[]" placeholder="Total Wt. (Kgs) Nett.">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="tare1" name="total_tare[]" placeholder="Total Wt. (Kgs) Tare">
                        </td>
                        <td>
                        <input type="text" class="form-control" id="grosss1" name="total_gross[]" placeholder="Total Wt. (Kgs) Gross">
                        </td>
                        <td><input type="text" class="form-control" id="bay1" name="bay[]" placeholder="Bay"></td>
                        <td><span class="btn btn-xs btn-danger"><i class="fa fa-minus"></i></span></td>
                    </tr>
                    </tbody>
                </table>
                <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="name">Gp No</label>
                    <input type="text" class="form-control" id="gp_no" name="gp_no" placeholder="GP No">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Gp Date</label>
                    <input type="text" class="form-control" id="gp_date" name="gp_date" placeholder="Gp Date">
                </div>
                </div>
                </div>
                <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                    <label for="name">Arrival Date</label>
                    <input type="text" class="form-control" id="arrival_date" name="arrival_date" placeholder="Arrival Date">
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Total Qty</label>
                    <input type="text" class="form-control" id="total_qty" name="total_qty" placeholder="Total Qty">
                </div>
                </div>
                </div>
                
                
              <div class="form-group">
                    <label for="name">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" cols="45" rows="5">

                    </textarea>                
                
                </div>
                <a  href="<?=@basePath?>USER/Inward" class="btn btn-default">Back</a>
                <a  href="<?=@basePath?>USER/Inward" class="btn btn-primary">Save changes</a>
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
  <?=@$footer?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?=@basePath?>admin_assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=@basePath?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?=@basePath?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/jszip/jszip.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?=@basePath?>admin_assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=@basePath?>admin_assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=@basePath?>admin_assets/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
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

$(document).ready(function(){
    // Add row
    // $(document).on('click', '.add-row', function(){
    //     var newRow = '<tr>' + 
    //                     '<td>1</td>' +
    //                     '<td><input type="text" class="form-control inv-no" placeholder="Inv no"></td>' +
    //                     '<td>' +
    //                         '<select class="form-control grade" name="grade_id[]">' +
    //                             '<option>Select Grade</option>' +
    //                             '<option value="1">First Quality</option>' +
    //                             '<option value="2">Low Quality</option>' +
    //                         '</select>' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<select class="form-control bag-type" name="bag_type[]">' +
    //                             '<option>Select Bag</option>' +
    //                             '<option value="1">Small</option>' +
    //                             '<option value="2">Large</option>' +
    //                         '</select>' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control no-of-bags" name="no_of_bags[]" placeholder="No of Bag">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control serial-no-from" name="serial_no_from[]" placeholder="Serial no from">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control serial-no-to" name="serial_no_to[]" placeholder="Serial no to">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control nett" name="weight_nett[]" placeholder="Weight per C/B (Kgs) Nett">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control tare" name="weight_tare[]" placeholder="Weight per C/B (Kgs) Tare">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control gross" name="weight_gross[]" placeholder="Weight per C/B (Kgs) Gross">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control total-net" name="total_net[]" placeholder="Total Wt. (Kgs) Nett.">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control total-tare" name="total_tare[]" placeholder="Total Wt. (Kgs) Tare">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control total-gross" name="total_gross[]" placeholder="Total Wt. (Kgs) Gross">' +
    //                     '</td>' +
    //                     '<td>' +
    //                         '<input type="text" class="form-control bay" name="bay[]" placeholder="Bay">' +
    //                     '<td><span class="btn btn-xs btn-danger remove-row"><i class="fa fa-minus"></i></span></td>' +'</tr>';
    //                   $('#sortable-table tbody').append(newRow);
    //                   });

    //                   // Remove row
    //                   $(document).on('click', '.remove-row', function(){
    //                   $(this).closest('tr').remove();
    //                   });

    $(document).on('click', '.add-row', function(){
        var newRow = $('#sortable-table tbody tr:first').clone();
        newRow.find('input[type="text"]').val('');
        newRow.find('select').prop('selectedIndex', 0);
        $('#sortable-table tbody').append(newRow); 
    });

    // Remove row
    $(document).on('click', '.remove-row', function(){
        $(this).closest('tr').remove();
    });
                      });
</script>
</script>
</body>
</html>
