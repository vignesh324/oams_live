<form id="finalize-form">
    <div class="modal-header">
        <h4 class="modal-title">Bidding Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Buyer</th>
                        <th>Bid amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($response_data)) {
                        foreach ($response_data as $key => $value) :
                            // echo '<pre>';print_r($value);exit;
                            $auto = '';
                            if ($value['bid_type'] == 1) {
                                $auto = '<span class="badge badge-danger">Auto Calculate</span>';
                            } elseif ($value['bid_type'] == 3) {
                                $auto = '<span class="badge badge-warning">Auto Bid</span>';
                            }
                    ?>
                            <tr>
                                <td><?php echo date('d-m-Y H:i:s', strtotime(@$value['created_at'])); ?></td>
                                <td><?php echo @$value['buyername']; ?></td>
                                <td><?php echo @$value['bid_price'] . ' ' . $auto; ?></td>
                            </tr>
                        <?php
                        endforeach;
                    } else {
                        ?>
                        <td colspan="3" style="text-align: center;">No Items Found</td>
                    <?php
                    }
                    ?>
                    <!-- <tr>
                        <td>20-01-2024</td>
                        <td>Puspam traders</td>
                        <td>6000</td>
                        <td><span type="button" class="btn btn-success final_btn" id="1"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                    </tr>
                    <tr>
                        <td>20-01-2024</td>
                        <td>AK Traders</td>
                        <td>6500</td>
                        <td><span type="button" class="btn btn-success final_btn" id="2"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                    </tr>
                    <tr>
                        <td>20-01-2024</td>
                        <td>Tea Shop</td>
                        <td>6600</td>
                        <td><span type="button" class="btn btn-success final_btn" id="3"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                    </tr>
                    <tr>
                        <td>20-01-2024</td>
                        <td>Trade hunt</td>
                        <td>6700</td>
                        <td><span type="button" class="btn btn-success final_btn" id="4"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                    </tr>
                    <tr>
                        <td>20-01-2024</td>
                        <td>Auction bid</td>
                        <td>6710</td>
                        <td><span type="button" class="btn btn-success final_btn" id="5"><i class="fas fa-check"></i> &nbsp;Finalize</span></td>
                    </tr> -->
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>