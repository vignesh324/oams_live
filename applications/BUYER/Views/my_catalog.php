<head>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= @basePath ?>admin_assets/dist/css/style.css">


    <style>
        input[type=number]::-webkit-inner-spin-button {
            opacity: 1
        }

        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0% !important;
        }

        .table td,
        .table th {
            padding: 0.20rem !important;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<div class="card-body">
    <h2>My Catalog</h2>
    <table class="table table-bordered table-striped" id="buyers-data">
        <thead>
            <tr>
                <th>LotNo</th>
                <th>Mark</th>
                <th>Grade</th>
                <th>No.of Bags</th>
                <th>Each Net</th>
                <th>Total Net</th>
                <th>Base Price</th>
                <th>Valuation Price</th>
                <th>Last Sold Price</th>
                <th>Highest Bidding price</th>
                <th style="width:5%;">Bidding Quantity</th>
                <th style="width:5%;">Bid Price</th>
            </tr>
        </thead>
        <tbody id="my-catalog">
            <?php
            if (!empty($mycatalog_response_data)) {
                $m = 1;
                $current_time = date('H:i:s');
                foreach ($mycatalog_response_data as $key => $value) :
            ?>
                    <tr>
                        <td><?php echo @$value['lot_no']; ?></td>
                        <td><?php echo @$value['gardenname']; ?></td>
                        <td><?php echo @$value['gradename']; ?></td>
                        <td><?php echo @$value['auction_quantity']; ?></td>
                        <td><?php echo @$value['weight_net']; ?></td>
                        <td><?php echo number_format(@$value['weight_net'] * @$value['auction_quantity'], 2, '.', ','); ?></td>
                        <td><?php echo @$value['base_price']; ?></td>
                        <td><?php echo @$value['valuation_price']; ?></td>
                        <td><?php echo isset($value['last_sold_price']) ? $value['last_sold_price'] : '-'; ?></td>
                        <td><?php echo @$value['bid_price']; ?></td>
                        <td>
                            <div class="input-groups">
                                <input type="number" readonly value="<?php echo @$value['auction_quantity']; ?>" class="form-control" />

                            </div>
                        </td>
                        <td>
                            <div class="input-groups">

                                <input type="hidden" id="buyer" value="<?php echo session()->get('user_id'); ?>" />
                                <input type="hidden" name="auction_id" id="auction_id_<?php echo $key + 1; ?>" value="<?php echo @$value['auction_id']; ?>" />
                                <input type="hidden" id="auction_item_id_<?php echo $key + 1; ?>" value="<?php echo @$value['id']; ?>" />
                                <input type="text" readonly id="result_<?php echo $key + 1; ?>" onchange="sendMessage(this)" step="0.01" class="form-control message-receiver" value="<?php echo @$value['bid_price']; ?>" />
                                <div class="input-groups-append">
                                    <i class="fa fa-caret-up plus-btn" aria-hidden="true"></i>
                                    <i class="fa fa-caret-down minus-btn" aria-hidden="true"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    $m++;
                endforeach ?>
            <?php } else { ?>
                <td colspan="13">No Items Found</td>
            <?php } ?>
        </tbody>

    </table>
</div>
<script src="<?= @basePath ?>admin_assets/plugins/jquery/jquery.min.js"></script>