<form id="grade-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Grade</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
        <div class="form-group">
                <label for="name">Grade Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Grade Name">
            </div>
            <div class="form-group">
                <label for="name">Grade Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="">Select Grade type</option>
                    <option value="1">LEAF</option>
                    <option value="2">DUST</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    <?php foreach ($category_data['category'] as $key => $data) : ?>
                        <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code">
            </div> -->
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
        <button type="button" id="add-grade" class="btn btn-primary">Save changes</button>
    </div>
</form>