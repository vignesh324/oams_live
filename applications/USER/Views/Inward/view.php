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
  <style>
    .table-bordered th {
      font-size: smaller !important;
    }

    .table td .form-control {
      max-width: 100px;
      height: 30px;
      border: 0px;
      border-radius: 3px;
      padding: 6px 5px;
      font-size: 13px;
    }

    @media print {
      #print-btn {
        display: none;
      }

      @page {
        margin: 0;
      }

      body {
        margin: 1.6cm;
      }
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
                <li class="breadcrumb-item"><a href="<?= @basePath ?>USER/Inward">Inward</a></li>
                <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
                  <h3 class="card-title"><?php echo @$title; ?> Inward</h3>
                  <div class="float-right">
                    <a href="#" class="btn btn-default" id="print-btn" onclick="printDiv('printableArea')">Print</a>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="printableArea">
                  <div class="invoice p-3 mb-3">

                    <div class="row invoice-info">
                      <div class="col-sm-4 invoice-col">
                        From
                        <address>
                          <strong><?php echo @$detail_data['seller_name']; ?></strong><br>
                          <?php echo @$detail_data['seller_address']; ?><br>
                          GST: <?php echo @$detail_data['gst_no']; ?><br>
                          FSSAI: <?php echo @$detail_data['fssai_no']; ?>
                        </address>
                      </div>

                      <div class="col-sm-4 invoice-col">
                        &nbsp;
                      </div>

                      <div class="col-sm-4 invoice-col">
                        <b>Center Name : <?php echo @$detail_data['center_name']; ?></b><br>
                        <b>Arrival Date : <?php echo date("d-m-Y", strtotime(@$detail_data['arrival_date'])); ?></b><br>
                        <b>GP Date:</b> <?php echo date("d-m-Y", strtotime(@$detail_data['gp_date'])); ?><br>
                        <b>GP No:</b> <?php echo @$detail_data['gp_no']; ?><br>
                        <b>Warehouse:</b> <?php echo @$detail_data['warehouse_name']; ?> <br>
                        <b>Garden:</b> <?php echo @$detail_data['garden_name']; ?>
                      </div>

                    </div>


                    <div class="row">
                      <div class="col-12 table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="3%">#</th>
                              <th>Inv No</th>
                              <th>Tea Grade</th>
                              <th>No. of Bags</th>
                              <th>S.No From</th>
                              <th>S.No To</th>
                              <th>Weight Nett</th>
                              <th>Weight Tare</th>
                              <th>Weight Gross</th>
                              <th>Total Wt. Nett.</th>
                              <th>Total Wt. Tare</th>
                              <th>Total Wt. Gross</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $id = 0;
                            $i = 1;
                            if ((isset($detail_data)) && count(@$detail_data['inwardItems'])) {
                              foreach ($detail_data['inwardItems'] as $key => $items) :
                            ?>
                                <tr>
                                  <td><?php echo $i; ?></td>
                                  <td><?php echo $items['invoice_id']; ?><?php if ($items['return_status'] != 0) { ?><span class="error">*</span><?php } ?></td>
                                  <td><?php echo $items['grade_name']; ?></td>
                                  <td><?php echo $items['no_of_bags']; ?></td>
                                  <td><?php echo $items['sno_from']; ?></td>
                                  <td><?php echo $items['sno_to']; ?></td>
                                  <td><?php echo $items['weight_net']; ?></td>
                                  <td><?php echo $items['weight_tare']; ?></td>
                                  <td><?php echo $items['weight_gross']; ?></td>
                                  <td><?php echo $items['total_net']; ?></td>
                                  <td><?php echo $items['total_tare']; ?></td>
                                  <td><?php echo $items['total_gross']; ?></td>
                                </tr>
                              <?php
                                $id++;
                                $i++;
                              endforeach;
                            } else {
                              ?>
                              <tr>
                                <td colspan="13">No records found</td>
                              </tr>
                            <?php
                            }
                            ?>

                          </tbody>
                        </table>
                      </div>

                    </div>

                    <div class="row">

                      <div class="col-9">
                        &nbsp;
                      </div>

                      <div class="col-3">
                        <div class="table-responsive">
                          <table class="table">
                            <tr>
                              <th>Total Qty:</th>
                              <td><?php echo @$detail_data['quantity']; ?></td>
                            </tr>
                            <tr>
                              <th>Gross Weight:</th>
                              <td><?php echo @$detail_data['gross_total_weight']; ?></td>
                            </tr>
                            <tr>
                              <th>Nett Weight:</th>
                              <td><?php echo @$detail_data['nett_total_weight']; ?></td>
                            </tr>
                          </table>
                        </div>
                      </div>

                    </div>

                  </div>
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
      function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
      }

      $(document).ready(function() {
        var uniqueIdCounter = 0;
        var autoIncCounter = $("#auto_value").val();

        $(document).on("click", ".add-row", function() {
          autoIncCounter++;
          $("#auto_value").val(autoIncCounter);
          var newRow = $("#sortable-table tbody tr:first").clone();
          var uniqueId = ++uniqueIdCounter;
          newRow.attr("id", "row_" + uniqueId);
          newRow.find(".auto_inc").attr("id", "auto_inc_" + autoIncCounter).text(autoIncCounter);
          newRow.find('span.error').remove();

          newRow.find('input[type="text"], select').each(function(index) {
            var name = $(this).attr('id');
            name = name.slice(0, -2);
            $(this).attr("id", name + "." + uniqueId); // Modified line
          });

          newRow.find('input[type="text"]').val("");
          newRow.find("select").prop("selectedIndex", 0);

          // Append remove button nearest to the bay input field
          newRow.find('#remove').append('<span class="btn btn-xs btn-danger remove-row"><i class="fa fa-minus"></i></span>');

          $("#sortable-table tbody").append(newRow);
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
          if ($(this).closest('tr').index() !== 0) {
            $(this).closest('tr').remove();
            autoIncCounter--;
            $("#auto_value").val(autoIncCounter);
            var totalGross = 0;
            $(".total_gross").each(function() {
              totalGross += parseFloat($(this).val()) || 0; // Use parseFloat to ensure it's treated as a number
            });
            totalGross = parseFloat(totalGross).toFixed(3); // Ensure totalGross is a number with 3 decimal places
            $("#total_qty").val(totalGross);
          }
        });

        // Get the current year
        var currentYear = new Date().getFullYear();
        var minDate = (currentYear - 1) + "-12-31";
        var maxDate = (currentYear + 1) + "-12-31";

        document.getElementById("gp_date").setAttribute("min", minDate);
        document.getElementById("gp_date").setAttribute("max", maxDate);
      });


      $(function() {
        $('#datemask').inputmask('dd/mm/yyyy', {
          'placeholder': 'dd/mm/yyyy'
        })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', {
          'placeholder': 'mm/dd/yyyy'
        })
        //Money Euro
        $('[data-mask]').inputmask();
      });

      $(document).on("click", "#open_form_submit", function(event) {
        event.preventDefault();
        var url = $("#user-form").attr("action");
        var formmethod = "post";
        var formdata = $("form").serialize();
        $.ajax({
          url: url,
          type: formmethod,
          data: formdata,
          success: function(_response) {
            Swal.fire({
              icon: "success",
              title: "Success!",
              text: "Form submitted successfully",
            }).then((result) => {
              if (result.isConfirmed || result.isDismissed) {
                window.location.href = basePath + "USER/Inward"; // Reload the page on success
              }
            });
          },
          error: function(_response) {
            var data = $.parseJSON(_response.responseText);
            // console.log(response);
            $(".error").remove();
            if (_response.status === 422) {
              var errors = $.parseJSON(_response.responseText);
              error = errors.errors;
              $.each(data.errors, function(key, value) {
                //console.log(key);
                var modifiedKey = key.includes('.') ? key.split('.').join('\\.') : key;
                if ($("input#" + modifiedKey).length != 0)
                  $("input#" + modifiedKey).after('<span class="error ">' + value + "</span>");
                else if ($("select#" + modifiedKey).length != 0)
                  $("select#" + modifiedKey).after('<span class="error">' + value + "</span>");
                else
                  $("#" + modifiedKey).after('<span class="error">' + value + "</span>");
              });
            } else if (_response.status === 500) {
              Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Internal Server Error",
              });
            }
          },
        });
      });


      $(document).on("change", "#seller_id", function(event) {
        var cityId = $(this).val();
        $.ajax({
          url: '<?= @basePath ?>USER/Seller/SellerGarden',
          type: 'POST',
          data: {
            "seller_id": cityId
          },
          dataType: 'json',
          success: function(response) {
            $('#garden_id').empty();

            if (response.status == 200) {
              $('#garden_id').append('<option value="">Select Garden</option>');
              $.each(response.data.sellerGarden, function(key, garden) {
                $('#garden_id').append('<option value="' + garden.id + '">' + garden.name + '</option>');
              });
            } else if (response.status == 404) {
              $('#garden_id').append('<option value="">No data found</option>');
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        });
      });

      $(document).on("change", "#garden_id", function(event) {
        var gardenId = $(this).val();
        $.ajax({
          url: basePath + "/USER/Inward/GetGardenGrades",
          type: "POST",
          data: {
            garden_id: gardenId,
          },
          dataType: "json",
          success: function(response) {

            if (response.status == 200) {
              $(".grade_dropdown").empty();
              $(".grade_dropdown").append('<option value="">Select Invoice</option>');

              $.each(response.data, function(key, invdet) {
                $(".grade_dropdown").append(
                  '<option value="' + invdet.id + '">' + invdet.name + "</option>"
                );
              });
            } else if (response.status == 404) {
              $(".grade_dropdown").empty();
              $(".grade_dropdown").append('<option value="">No data found</option>');
            }
          },
          error: function(xhr, status, error) {
            console.error(error);
          },
        });
      });

      $(document).on("change", ".nett_cb,.tare_cb,.no_of_bags", function(event) {
        var originalNumber = parseFloat($(this).val());
        var formattedNumber = originalNumber.toFixed(3);


        var id_cnt = $(this).attr('id');
        id_cnt = id_cnt.split('.');

        if (id_cnt[0] != 'no_of_bags')
          $(this).val(formattedNumber);

        var tarewgt = $("#weight_tare\\." + id_cnt[1]).val() || 0;
        var nettwgt = $("#weight_nett\\." + id_cnt[1]).val() || 0;
        var no_of_bags = $("#no_of_bags\\." + id_cnt[1]).val() || 0;
        var weight_gross = (parseFloat(nettwgt) + parseFloat(tarewgt)).toFixed(3);
        $("#weight_gross\\." + id_cnt[1]).val(weight_gross);

        var gross_tare = (parseFloat(tarewgt) * no_of_bags).toFixed(3);
        var gross_nett = (parseFloat(nettwgt) * no_of_bags).toFixed(3);
        var gross_weight = (parseFloat(weight_gross) * no_of_bags).toFixed(3);
        $("#total_net\\." + id_cnt[1]).val(gross_nett);
        $("#total_tare\\." + id_cnt[1]).val(gross_tare);
        $("#total_gross\\." + id_cnt[1]).val(gross_weight);

        var totalGross = 0;
        $(".total_gross").each(function() {
          totalGross += parseFloat($(this).val()) || 0; // Use parseFloat to ensure it's treated as a number
        });
        totalGross = parseFloat(totalGross).toFixed(3); // Ensure totalGross is a number with 3 decimal places
        $("#total_qty").val(totalGross);

      })
    </script>

</body>

</html>