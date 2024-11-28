<form id="samplequantity-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Sample Quantity</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Quantity</label>
                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
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
        <button type="button" id="add-samplequantity" class="btn btn-primary">Save changes</button>
    </div>
</form>