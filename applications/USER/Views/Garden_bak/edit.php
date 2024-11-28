<form id="garden-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Garden</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?php echo $response_data['name'] ?>">
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="<?php echo $response_data['code'] ?>">
            </div>
            <div class="form-group">
                <label for="name">Seller</label>
                <select class="form-control" name="seller_id" id="seller_id">
                    <option value="">Select Seller</option>
                    <?php foreach ($seller_list['seller'] as $key => $data) : ?>
                        <?php $selected = ($response_data['seller_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="code">Address</label>
                <textarea class="form-control" id="address" name="address" placeholder="Enter Address Code"><?php echo $response_data['address'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="name">State</label>
                <select class="form-control" name="state_id" id="state_id">
                    <option value="">Select State</option>
                    <?php foreach ($state_list['state'] as $key => $data) : ?>
                        <?php $selected = ($response_data['state_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">City</label>
                <select class="form-control" name="city_id" id="city_id">
                    <option value="">Select City</option>
                    <?php foreach ($city_list['data']['city'] as $key => $data) : ?>
                        <?php $selected = ($response_data['city_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Area</label>
                <select class="form-control" name="area_id" id="area_id">
                    <option value="">Select Area</option>
                    <?php foreach ($area_list['data']['area'] as $key => $data) : ?>
                        <?php $selected = ($response_data['area_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" <?php echo ($response_data['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($response_data['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $response_data['id'] ?>">
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="edit-garden" class="btn btn-primary">Save changes</button>
    </div>
</form>