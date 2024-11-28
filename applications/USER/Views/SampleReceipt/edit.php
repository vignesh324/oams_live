<form id="samplereceipt-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Sample Quantity</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="lot_no">Sale No</label>
                <select class="form-control" name="sale_no" id="sale_no">
                    <option value="">Select Sale No</option>
                    <?php foreach ($lot_response_data['auction'] as $val) : ?>
                        <?php $selected_sale = ($response_data['auction_id'] == $val['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $val['id'] ?>" <?php echo $selected_sale ?>><?php echo $val['sale_no'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (isset($response_data['lot_no'])) : ?>
                <?php foreach ($lot_response_data['auction'] as $val) : ?>
                    <?php if ($val['id'] == $response_data['auction_id']) : ?>
                        <div class="form-group">
                            <label for="name">LOT No</label>
                            <select class="form-control" name="lot_no" id="lot_no">
                                <option value="">Select LOT No</option>
                                <?php foreach ($val['auction_item'] as $lot) : ?>
                                    <?php $selected_lot = ($response_data['lot_no'] == $lot['lot_no']) ? 'selected' : ''; ?>
                                    <option value="<?php echo $lot['lot_no'] ?>" <?php echo $selected_lot ?>><?php echo $lot['lot_no'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-group">
                <label for="buyer_id">Buyer</label>
                <select class="form-control" name="buyer_id" id="buyer_id">
                    <option value="">Select Buyer</option>
                    <?php foreach ($buyer_data as $data) : ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo ($response_data['buyer_id'] == $data['id']) ? 'selected' : ''; ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <select class="form-control" name="quantity" id="quantity">
                    <option value="">Select Quantity</option>
                    <?php foreach ($sample_response_data as $data) : ?>
                        <option value="<?php echo $data['quantity'] ?>" <?php if ($response_data['quantity'] == $data['quantity']) { ?> selected <?php } ?>><?php echo $data['quantity'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" <?php echo ($response_data['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="2" <?php echo ($response_data['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $response_data['id'] ?>">
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="edit-samplereceipt" class="btn btn-primary">Save changes</button>
    </div>
</form>