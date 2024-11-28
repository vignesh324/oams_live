<form id="area-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Area</h4>
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
                <label for="name">Area Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Area Name">
            </div>
            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code">
            </div> -->
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
        <button type="button" id="add-area" class="btn btn-primary">Save changes</button>
    </div>
</form>