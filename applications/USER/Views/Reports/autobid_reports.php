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
  <!-- Date Range -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
  <style>
    @media print {
      @page {
        margin: 0;
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
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active">Auto Bid Reports</li>
              </ol>
            </div>
            <div class="col-sm-6">

            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>


      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Auto Bid Reports</h3>
                  <input type="hidden" value="autobid-report" class="excel-file-name">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                    <div class="col col-3">
                      <div class="form-group">
                        <label for="name">Date Filter</label>
                        <div class="card-tools" id="daterange">
                          <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                            <i class="far fa-calendar-alt"></i>
                          </button>
                          <p id="from-to-date" style="display: inline; margin-left: 5px"></p>

                        </div>
                      </div>
                    </div>

                    <div class="col col-2">
                      <div class="form-group">
                        <label for="sale_no">Sale No</label>
                        <select class="form-control" name="sale_no" id="sale_no">
                          <option value="">Select Sale No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col col-2">
                      <div class="form-group">
                        <label for="lot_no">Lot No</label>
                        <select class="form-control select2" name="lot_no[]" id="lot_no" multiple="multiple" data-placeholder="Select Lot No" data-dropdown-css-class="select2-purple" style="width: 100%;">
                          <option value="">Select Lot No</option>
                        </select>
                      </div>
                    </div>

                    <div class="col col-2">
                      <div class="form-group">
                        <?php echo render_go_and_export_button() ?>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <table class="table table-bordered manualbid_report" id="excel-export-table">
                      <thead>
                        <tr>
                          <th>Buyername</th>
                          <th>Max Price</th>
                          <th>Created At</th>
                        </tr>

                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="3" class="text-center">No data found</td>
                        </tr>
                      </tbody>
                    </table>
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
      $(document).ready(function() {
        var from_date, to_date;

        initializeDateRangePicker();
        setupEventHandlers();

        function initializeDateRangePicker() {
          $('.daterange').daterangepicker({
            opens: 'right',
            locale: {
              format: 'DD/MM/YYYY'
            },
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
          }, handleDateRangeSelection);
        }

        function handleDateRangeSelection(start, end, label) {
          from_date = start.format('YYYY-MM-DD');
          to_date = end.format('YYYY-MM-DD');
          $('#from-to-date').text(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

          $.ajax({
            type: "POST",
            url: "<?= @basePath ?>USER/DateWiseSaleno",
            data: {
              from_date: from_date,
              to_date: to_date
            },
            dataType: 'json',
            success: populateSaleNumbers,
            error: handleError('#sale_no', 'No data found')
          });
        }

        function populateSaleNumbers(response) {
          var $saleNo = $("#sale_no");
          $saleNo.empty();

          if (response.auction.length > 0) {
            $saleNo.append('<option value="">Select Sale No</option>');
            response.auction.forEach(function(value) {
              var option = `<option value="${value.id}">${value.sale_no}</option>`;
              $saleNo.append(option);
            });
          } else {
            $saleNo.append('<option value="">No data found</option>');
          }
        }

        function handleError(selector, message) {
          return function(error) {
            console.error(error);
            $(selector).empty().append(`<option value="">${message}</option>`);
          };
        }

        function setupEventHandlers() {
          $(document).on("change", "#sale_no", handleSaleNoChange);
          $(document).on("click", "#search-filter", handleSearchFilterClick);
        }

        function handleSaleNoChange() {
          var auction_id = $('#sale_no').val();

          $.ajax({
            url: "<?= @basePath ?>USER/Reports/SalenoWiseLot",
            type: "POST",
            data: {
              auction_id: auction_id
            },
            dataType: 'json',
            success: populateLotNo,
            error: handleError('#lot_no', 'No data found')
          });
        }

        function populateLotNo(response) {
          var $lot_no = $("#lot_no");

          $lot_no.empty();

          if (response.lot_no.length > 0) {
            $lot_no.append('<option value="">Select Lot No</option>');
            response.lot_no.forEach(function(value) {
              var option = `<option value="${value.id}">${value.lot_no}</option>`;
              $lot_no.append(option);
            });
          } else {
            $lot_no.append('<option value="">No data found</option>');
          }
        }

        function formatDateTime(dateTime) {
          var date = new Date(dateTime);
          var day = ('0' + date.getDate()).slice(-2);
          var month = ('0' + (date.getMonth() + 1)).slice(-2);
          var year = date.getFullYear();
          var hours = ('0' + date.getHours()).slice(-2);
          var minutes = ('0' + date.getMinutes()).slice(-2);
          var seconds = ('0' + date.getSeconds()).slice(-2);

          return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        function handleSearchFilterClick(event) {
          event.preventDefault();
          $("#search-filter").attr("disabled", true);

          var lot_no = $('#lot_no').val();
          var auction_id = $('#sale_no').val();

          function displayError(key, value) {
            if (key == 'lot_no') {
              $('#lot_no').next('.select2-container').after('<span class="error">' + value + '</span>');
            } else {
              $('#' + key).after('<span class="error">' + value + '</span>');
            }
            $("#search-filter").attr("disabled", false);
          }

          $('.error').remove();

          var hasError = false;

          if (!from_date || !to_date) {
            displayError('daterange', 'Please select Date');
            hasError = true;
          }
          if (!auction_id) {
            displayError('sale_no', 'Please select Sale No');
            hasError = true;
          }
          if (!lot_no || lot_no.length === 0) {
            displayError('lot_no', 'Please select Lot No');
            hasError = true;
          }

          if (hasError) {
            return false;
          }

          // console.log(from_date);
          $.ajax({
            url: '<?= @basePath ?>USER/AutoBidReportSubmit',
            type: 'post',
            dataType: 'JSON',
            data: {
              from_date: from_date,
              to_date: to_date,
              auction_id: auction_id,
              lot_no: lot_no
            },
            success: function(response) {
              $(".manualbid_report tbody").empty(); // Clear existing table data
              console.log(response);

              if (response && response.autoBiddings.length > 0) {
                var tableBody = '';
                var groupedBiddings = {};

                // Group biddings by lot_no
                response.autoBiddings.forEach(function(item) {
                  if (!groupedBiddings[item.lot_no]) {
                    groupedBiddings[item.lot_no] = [];
                  }
                  groupedBiddings[item.lot_no].push(item);
                });

                // Build the table rows
                for (var lotNo in groupedBiddings) {
                  if (groupedBiddings.hasOwnProperty(lotNo)) {
                    // Add a new row for the lot number
                    tableBody += `<tr><td colspan="3" class="text-center bg-gray">${lotNo}</td></tr>`;

                    // Add rows for each bidding under the lot number
                    groupedBiddings[lotNo].forEach(function(item) {
                      var row =
                        `<tr>
                        <td>${item.buyername}</td>
                        <td>${item.max_price}</td>
                        <td>${formatDateTime(item.created_at)}</td>
                    </tr>`;
                      tableBody += row;
                    });
                  }
                }

                $(".manualbid_report tbody").append(tableBody); // Append the rows to the table body
              } else {
                $(".manualbid_report tbody").append('<tr><td colspan="3" class="text-center">No data found</td></tr>');
              }
            },
            error: function(xhr, textStatus, errorThrown) {
              $('.error').remove();
              console.error(xhr.responseText);
            },
            complete: function() {
              $("#search-filter").prop("disabled", false);
            }
          });
        }
      });
    </script>
</body>

</html>