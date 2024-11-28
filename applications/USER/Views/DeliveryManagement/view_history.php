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
                <table class="table table-bordered table-striped auction_items_table">
                    <thead>
                        <tr>
                            <th>Lot No</th>
                            <th>Grade Name</th>
                            <!-- <th>Warehouse Name</th>
                            <th>Garden Name</th>
                            <th>Each Net</th>
                            <th>Total Net</th>
                            <th>Total Gross</th> -->
                            <th>Delivery Quantity</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($response_data['history'])) {
                            foreach ($response_data['history'] as $key => $value) {
                        ?>
                                <tr>
                                    <td><?php echo $value['lot_no']; ?></td>
                                    <td><?php echo $value['grade_name']; ?></td>
                                    <!-- <td><?php //echo $value['warehouse_name']; ?></td>
                                    <td><?php //echo $value['garden_name']; ?></td>
                                    <td><?php //echo $value['weight_net']; ?></td>
                                    <td><?php //echo $value['total_net']; ?></td>
                                    <td><?php //echo $value['total_gross']; ?></td> -->
                                    <td><?php echo $value['delivery_qty']; ?></td>
                                    <td><?php echo date('d-m-Y h:i:s', strtotime($value['delivery_created_at'])); ?></td>
                                    
                                </tr>
                            <?php
                            }
                        } else { ?>
                            <tr>
                                <td colspan="10" class="text-center">No data found</td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>