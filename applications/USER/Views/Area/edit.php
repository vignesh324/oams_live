<form id="area-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Area</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
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
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Area Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Area Name" value="<?php echo $response_data['name'] ?>">
            </div>
            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="<?php echo $response_data['code'] ?>">
            </div> -->
            <div class="form-group">
                <label for="name">Status</label>
                <select class="form-control" name="status">
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
        <button type="button" id="edit-area" class="btn btn-primary">Save changes</button>
    </div>
</form>