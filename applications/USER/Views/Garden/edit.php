<form id="garden-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Garden</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">

            <div class="form-group row">
                <label for="name" class="col-form-label col-sm-2">Garden Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Garden Name" value="<?php echo $response_data['name'] ?>">
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="vacumm_bag" name="vacumm_bag" <?php echo $response_data['vacumm_bag'] == 1 ? 'checked' : ''; ?>>
                        <label for="vacumm_bag">Vacuum Bag</label>
                    </div>
                </div>
            </div>

            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="<?php echo $response_data['code'] ?>">
            </div> -->
            <div class="form-group">
                <label for="name">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    <?php foreach ($category_data['category'] as $key => $data) : ?>
                        <?php $selected = ($response_data['category_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Seller</label>
                <select class="form-control" name="seller_id" id="seller_id">
                    <option value="">Select Seller</option>
                    <?php foreach (@$seller_list['seller'] as $key => $data) : ?>
                        <?php $selected = ($response_data['seller_id'] == $data['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="code">Address</label>
                <textarea class="form-control" id="address" name="address" placeholder="Enter Address"><?php echo $response_data['address'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="name">State</label>
                <select class="form-control" name="state_id" id="state_id">
                    <option value="">Select State</option>
                    <?php foreach ($list_data['state'] as $state) : ?>
                        <?php $selected_state = ($response_data['state_id'] == $state['id']) ? 'selected' : ''; ?>
                        <option value="<?php echo $state['id'] ?>" <?php echo $selected_state ?>><?php echo $state['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (isset($response_data['state_id'])) : ?>
                <?php foreach ($list_data['state'] as $state) : ?>
                    <?php if ($state['id'] == $response_data['state_id']) : ?>
                        <div class="form-group">
                            <label for="name">City</label>
                            <select class="form-control" name="city_id" id="city_id">
                                <option value="">Select City</option>
                                <?php foreach ($state['city'] as $city) : ?>
                                    <?php $selected_city = ($response_data['city_id'] == $city['id']) ? 'selected' : ''; ?>
                                    <option value="<?php echo $city['id'] ?>" <?php echo $selected_city ?>><?php echo $city['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php foreach ($state['city'] as $city) : ?>
                            <?php if ($city['id'] == $response_data['city_id']) : ?>
                                <div class="form-group">
                                    <label for="name">Area</label>
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
            <div class="form-group">
                <label for="name">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" <?php echo ($response_data['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="2" <?php echo ($response_data['status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
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