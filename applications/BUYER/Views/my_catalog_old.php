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
                <th>B.P</th>
                <th>Valuation</th>
                <th>LSP / SP</th>
                <th>HBP</th>
                <th>Status</th>
                <th style="width:5%;">BQ</th>
                <th style="width:5%;">Bid Price</th>
            </tr>
        </thead>
        <tbody id="my-catalog">
            <?php
            if (!empty($mycatalog_response_data)) {
                $m = 1;
                $current_time = date('H:i:s');
                foreach ($mycatalog_response_data as $key => $value) :
                    // $startTime = new DateTime($start_time);

                    // $sessionSeconds = strtotime('1970-01-01 ' . $each_session) - strtotime('1970-01-01 00:00:00');
                    // $secondsToAdd = $sessionSeconds * $m;
                    // $startTime->add(new DateInterval("PT{$secondsToAdd}S"));
                    // $closing_time = $startTime->format('H:i:s');
                    // if ($closing_time < $current_time) {
                    //     $completed = "yes";
                    //     $set_bg_color = 'style="background-color: #918989;color:#FFFFFF"';
                    // } else {
                    //     $completed = "no";
                    //     $set_bg_color = 'style="background-color: #f44336;color:#ffffff"';
                    // }
            ?>
                    <tr>
                        <td><?php echo @$value['lot_no']; ?></td>
                        <td><?php echo @$value['gardenname']; ?></td>
                        <td><?php echo @$value['gradename']; ?></td>
                        <td><?php echo @$value['auction_quantity']; ?></td>
                        <td><?php echo @$value['weight_net']; ?></td>
                        <td><?php echo @$value['total_net']; ?></td>
                        <td><?php echo @$value['base_price']; ?></td>
                        <td><?php echo @$value['valuation_price']; ?></td>
                        <td>-</td>
                        <td><?php echo @$value['high_price']; ?></td>
                        <td>
                            <?php
                            if (@$value['status'] == 1) {
                                echo 'Active';
                            } elseif (@$value['status'] == 2) {
                                echo 'Pending';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="input-groups">
                                <input type="number" readonly value="<?php echo @$value['auction_quantity']; ?>" class="form-control" />
                                <div class="input-groups-append">
                                    <i class="fa fa-caret-up plus-btn" aria-hidden="true"></i>
                                    <i class="fa fa-caret-down minus-btn" aria-hidden="true"></i>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="input-groups">

                                <input type="hidden" id="buyer" value="<?php echo session()->get('user_id'); ?>" />
                                <input type="hidden" name="auction_id" id="auction_id_<?php echo $key + 1; ?>" value="<?php echo @$value['auction_id']; ?>" />
                                <input type="hidden" id="auction_item_id_<?php echo $key + 1; ?>" value="<?php echo @$value['id']; ?>" />
                                <input type="text" id="result_<?php echo $key + 1; ?>" onchange="sendMessage(this)" step="0.01" class="form-control message-receiver" value="<?php echo @$value['max_bid_price']; ?>" />
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
