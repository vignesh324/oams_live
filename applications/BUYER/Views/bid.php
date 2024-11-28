<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Potout Solutions</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?= @$header ?>
        <?= @$sidebar ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Bid Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Bid Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <form>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice p-3 mb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>#LOT001</h4>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3">
                                            <strong>Start Time: 24.01.2024</strong><br>
                                            <strong>End Time: 26.01.2024</strong><br>
                                            <strong>Garden 1</strong><br>
                                            <strong>Base Price:</strong> 100<br>
                                            <strong>High Price:</strong> 400<br>
                                        </div>

                                        <div class="col-lg-3 invoice-col">
                                            <b>Invoice : #007612</b><br>
                                            <b>Grade :</b> 1st Grade<br>
                                            <b>Quantity :</b> 78<br>
                                            <b>Net Weight (Kgs) :</b> 2.5 Kgs
                                            <div class="form-group d-flex">
                                                <p style="margin-right:6px;"><b> Payment Type: </b></p>
                                                <div class="form-check" style="margin-right:6px;">
                                                    <input class="form-check-input" type="radio" name="radio1" checked="">
                                                    <label class="form-check-label">Partial</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="radio1">
                                                    <label class="form-check-label">Full</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="bid_price">Bid Price:</label>
                                                <input type="text" class="form-control" id="bid_price" placeholder="Enter Bid Price">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row no-print">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-success float-right">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?= @$footer ?>
    </div>

    <script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/dist/js/adminlte.js"></script>
    <script src="<?= @basePath ?>admin_assets/plugins/chart.js/Chart.min.js"></script>
    <script src="<?= @basePath ?>admin_assets/dist/js/demo.js"></script>
    <script src="<?= @basePath ?>admin_assets/dist/js/pages/dashboard3.js"></script>
</body>

</html>
