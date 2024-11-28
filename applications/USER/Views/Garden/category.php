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
                                <li class="breadcrumb-item active">Common Reorder By Category</li>
                            </ol>
                        </div>
                        <div class="col-sm-6">

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
                                    <h3 class="card-title">Category Management</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category Name</th>
                                                <th>Code</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($response_data['category'] as $key => $value) : ?>
                                                <tr>
                                                    <td><?php echo $key + 1; ?></td>
                                                    <td><?php echo $value['name']; ?></td>
                                                    <td><?php echo CATEGORY . $value['id']; ?></td>
                                                    <td>
                                                        <?php if ($value['status'] == 1) : ?>
                                                            <span class="badge badge-success">Active</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-warning">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>

                                                        <a href="#" onclick="assignGrade(<?php echo $value['id']; ?>)" data-toggle="modal" data-target="#modal-assign" class="btn btn-dark-cyne edit_button" id="#"><i class="fa fa-arrow-right" title="Assign"></i> </a>


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
            function assignGrade(id) {
                $(".loading").show();
                $.ajax({
                    type: "post",
                    url: "<?= @basePath ?>USER/Garden/AssignCategoryGrade",
                    data: {
                        id: id
                    },
                    dataType: 'html',
                    success: function(response) {
                        $(".loading").hide();
                        $("#assign_order").html(response);
                        $('#modal-assign').modal('show');
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
        </script>
</body>

</html>