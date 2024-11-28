<form id="seller-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Seller</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Seller Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Seller Name" value="<?php echo $response_data['name'] ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Seller Prefix</label>
                        <input type="text" class="form-control" id="seller_prefix" name="seller_prefix" placeholder="Enter Seller Prefix" value="<?php echo $response_data['seller_prefix'] ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="<?php echo $response_data['code'] ?>">
                    </div>
                </div> -->

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Enter Address"><?php echo $response_data['address'] ?></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tea_no">TEA Board No</label>
                        <input type="text" class="form-control" name="tea_board_no" id="tea_no" placeholder="Enter TEA Board No" value="<?php echo @$response_data['tea_board_no']; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="state_id">State</label>
                        <select class="form-control" name="state_id" id="state_id">
                            <option value="">Select State</option>
                            <?php foreach ($list_data['state'] as $state) : ?>
                                <?php $selected_state = ($response_data['state_id'] == $state['id']) ? 'selected' : ''; ?>
                                <option value="<?php echo $state['id'] ?>" <?php echo $selected_state ?>><?php echo $state['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <?php if (isset($response_data['state_id'])) : ?>
                        <?php foreach ($list_data['state'] as $state) : ?>
                            <?php if ($state['id'] == $response_data['state_id']) : ?>
                                <div class="form-group">
                                    <label for="city_id">City</label>
                                    <select class="form-control" name="city_id" id="city_id">
                                        <option value="">Select City</option>
                                        <?php foreach ($state['city'] as $city) : ?>
                                            <?php $selected_city = ($response_data['city_id'] == $city['id']) ? 'selected' : ''; ?>
                                            <option value="<?php echo $city['id'] ?>" <?php echo $selected_city ?>><?php echo $city['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php foreach ($state['city'] as $city) : ?>
                        <?php if ($city['id'] == $response_data['city_id']) : ?>
                            <div class="form-group">
                                <label for="area_id">Area</label>
                                <select class="form-control" name="area_id" id="area_id">
                                    <option value="">Select Area</option>
                                    <?php foreach ($city['area'] as $area) : ?>
                                        <?php $selected_area = ($response_data['area_id'] == $area['id']) ? 'selected' : ''; ?>
                                        <option value="<?php echo $area['id'] ?>" <?php echo $selected_area ?>><?php echo $area['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gst_no">GST No</label>
                        <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST No" value="<?php echo $response_data['gst_no'] ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fssai_no">FSSAI No</label>
                        <input type="text" class="form-control" id="fssai_no" name="fssai_no" placeholder="Enter FSSAI No" value="<?php echo $response_data['fssai_no'] ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" <?php echo ($response_data['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                            <option value="2" <?php echo ($response_data['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $response_data['id'] ?>">
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="edit-seller" class="btn btn-primary">Save changes</button>
    </div>
</form>