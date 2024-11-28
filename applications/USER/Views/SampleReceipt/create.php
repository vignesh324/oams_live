<form id="samplereceipt-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Sample Receipt</h4>
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
                    <?php foreach ($lot_response_data as $val) : ?>
                        <option value="<?php echo $val['id'] ?>"><?php echo $val['sale_no'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="lot_no">LOT No</label>
                <select class="form-control" name="lot_no" id="lot_no">
                    <option value="">Select LOT No</option>

                </select>
            </div>
            <div class="form-group">
                <label for="name">Buyer</label>
                <div class="select2-purple">
                    <select class="select2" name="buyer_id[]" multiple="multiple" data-placeholder="Select Buyer" data-dropdown-css-class="select2-purple" style="width: 100%;">
                        <?php foreach ($buyer_data as $data) : ?>
                            <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Quantity</label>
                <select class="form-control" name="quantity" id="quantity">
                    <option value="">Select Quantity</option>
                    <?php foreach ($sample_response_data as $data) : ?>
                        <option value="<?php echo $data['quantity'] ?>"><?php echo $data['quantity'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" selected>Active</option>
                    <option value="2">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="add-samplereceipt" class="btn btn-primary">Save changes</button>
    </div>
</form>