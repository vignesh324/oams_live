<form id="seller-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Seller</h4>
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
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Seller Name">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Seller Prefix</label>
                        <input type="text" class="form-control" id="seller_prefix" name="seller_prefix" placeholder="Enter Seller Prefix">
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter Code">
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gst_no">TEA Board No</label>
                        <input type="text" class="form-control" name="tea_board_no" id="tea_no" placeholder="Enter TEA Board No">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">State</label>
                        <select class="form-control" name="state_id" id="state_id">
                            <option value="">Select State</option>
                            <?php foreach ($state_list['state'] as $key => $data) : ?>
                                <option value="<?php echo $data['id'] ?>"><?php echo $data['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">City</label>
                        <select class="form-control" name="city_id" id="city_id">
                            <option value="">Select City</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Area</label>
                        <select class="form-control" name="area_id" id="area_id">
                            <option value="">Select Area</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gst_no">GST No</label>
                        <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST No">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">FSSAI No</label>
                        <input type="text" class="form-control" id="fssai_no" name="fssai_no" placeholder="Enter FSSAI No">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Status</label>
                        <select class="form-control" name="status">
                            <option value="1" selected>Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="add-seller" class="btn btn-primary">Save changes</button>
    </div>
</form>