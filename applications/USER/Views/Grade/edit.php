<form id="grade-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Grade</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
        <div class="form-group">
                <label for="name">Grade Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Grade Name" value="<?php echo $response_data['name'] ?>">
            </div>
            <div class="form-group">
                <label for="name">Grade Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="">Select Grade type</option>
                    <option value="1" <?php echo ($response_data['type'] == 1) ? 'selected' : ''; ?>>LEAF</option>
                    <option value="2" <?php echo ($response_data['type'] == 2) ? 'selected' : ''; ?>>DUST</option>
                </select>
            </div>
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
            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code" value="<?php echo $response_data['code'] ?>">
            </div> -->
            <div class="form-group">
                <label for="name">Status</label>
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
        <button type="button" id="edit-grade" class="btn btn-primary">Save changes</button>
    </div>
</form>