<form id="garden-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Garden</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">

            <div class="form-group row">
                <label for="name" class="col-form-label col-sm-2">Garden Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Garden Name">
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="vacumm_bag" name="vacumm_bag">
                        <label for="vacumm_bag">Vacuum Bag</label>
                    </div>
                </div>
            </div>

            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code">
            </div> -->
            <div class="form-group">
                <label for="name">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    <?php foreach ($category_data['category'] as $key => $data) : ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Seller</label>
                <select class="form-control" name="seller_id" id="seller_id">
                    <option value="">Select Seller</option>
                    <?php foreach ($seller_list['seller'] as $key => $data) : ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="code">Address</label>
                <textarea class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
            </div>
            <div class="form-group">
                <label for="name">State</label>
                <select class="form-control" name="state_id" id="state_id">
                    <option value="">Select State</option>
                    <?php foreach ($state_list['state'] as $key => $data) : ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">City</label>
                <select class="form-control" name="city_id" id="city_id">
                    <option value="">Select City</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Area</label>
                <select class="form-control" name="area_id" id="area_id">
                    <option value="">Select Area</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Status</label>
                <select class="form-control" name="status">
                    <option value="1" selected>Active</option>
                    <option value="2">Inactive</option>
                </select>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="add-garden" class="btn btn-primary">Save changes</button>
    </div>
</form>