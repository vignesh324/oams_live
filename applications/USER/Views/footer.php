<footer class="main-footer">
  <strong>Copyright &copy; 2023 <a href="http://digiassociates.in/">Digital Associates</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
  </div>
</footer>

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
<!-- Validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= @basePath ?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/moment/moment.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/inputmask/jquery.inputmask.min.js"></script>

<script src="<?= @basePath ?>admin_assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/jszip/jszip.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- custom excel export -->
<script src="<?= @basePath ?>admin_assets/plugins/excel-export/xlsx.full.min.js"></script>

<script src="<?= @basePath ?>admin_assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/chart.js/Chart.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= @basePath ?>admin_assets/dist/js/pages/dashboard3.js"></script>
<script src="<?= @basePath ?>admin_assets/dist/js/jquery-ui.js"></script>
<script src="<?= @basePath ?>admin_assets/dist/js/common.js"></script>

<script src="<?= @basePath ?>admin_assets/dist/js/custom-xls-export.js"></script>

<!-- AdminLTE App -->
<script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Page specific script -->
<script>
  $(function() {
    $('body').addClass('layout-navbar-fixed layout-fixed layout-footer-fixed');
    
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    function getDynamicFileName() {
      var title = $('.card-title').html();
      var date = new Date();
      var formattedDate = date.getFullYear() + '-' +
        ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
        ('0' + date.getDate()).slice(-2);
      return title + '_' + formattedDate;
    }

    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "buttons": [{
          extend: 'csv',
          title: function() {
            return getDynamicFileName();
          }
        },
        {
          extend: 'excel',
          title: function() {
            return getDynamicFileName();
          }
        },
        {
          extend: 'print',
          title: function() {
            return getDynamicFileName();
          }
        }
      ]
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    $(document).on('keydown', function(event) {
      if (event.which === 13) {
        event.preventDefault();
        return false;
      }
    });

    $("input[data-bootstrap-switch]").each(function() {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('.select2').select2()

    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });


  });
</script>
<script>
  let BASE_URL = "<?= @basePath ?>";
</script>