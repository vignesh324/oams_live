<footer class="main-footer">
  <strong>Copyright &copy; 2023 <a href="http://digiassociates.in/">Digital Associates</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Validation -->
<!-- jQuery -->
<script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="<?= @basePath ?>admin_assets/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?= @basePath ?>admin_assets/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= @basePath ?>admin_assets/dist/js/pages/dashboard3.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?= @basePath ?>admin_assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/moment/moment.min.js"></script>
<script src="<?= @basePath ?>admin_assets/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- AdminLTE App -->
<script src="<?= @basePath ?>admin_assets/dist/js/adminlte.min.js"></script>
<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "sorting": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-12:eq(0)');
  });
</script>