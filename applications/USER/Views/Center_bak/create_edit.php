<form id="user-form" action="<?= $url; ?>">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo @$response_data['id'] ?>">
  <div class="modal-header">
    <h4 class="modal-title"><?php echo $title; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <div class="card-body">
      <div class="row">
        <div class="form-group col-md-6">
          <label for="name">Name</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo @$response_data['name']; ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="code">Code</label>
          <input type="text" name="code" class="form-control" id="code" placeholder="Enter center Code" value="<?php echo @$response_data['code']; ?>">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label for="name">State</label>
          <select class="form-control" name="state_id" id="state_id">
            <option value="">Select State</option>
            <?php foreach ($state_list['state'] as $key => $data) : ?>
              <option value="<?php echo $data['id'] ?>" <?php echo (@$response_data['state_id'] == $data['id']) ? "selected" : ""; ?>><?php echo $data['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="name">City</label>
          <select class="form-control" name="city_id" id="city_id">
            <option value="">Select City</option>
            <?php
            if (isset($city_list)) {
              foreach (@$city_list['data']['city'] as $key => $data) : ?>
                <?php $selected = ($response_data['city_id'] == $data['id']) ? 'selected' : ''; ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
            <?php endforeach;
            }
            ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="name">Area</label>
          <select class="form-control" name="area_id" id="area_id">
            <option value="">Select Area</option>
            <?php
            if (isset($area_list)) {
              foreach ($area_list['data']['area'] as $key => $data) : ?>
                <?php $selected = ($response_data['area_id'] == $data['id']) ? 'selected' : ''; ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['name'] ?></option>
            <?php
              endforeach;
            }
            ?>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="name">Status</label>
          <select class="form-control" name="status">
            <option value="1" <?php if (isset($response_data)) {
                                echo (@$response_data['status'] == 1) ? 'selected' : '';
                              } ?>>Active</option>
            <option value="0" <?php if (isset($response_data)) {
                                echo (@$response_data['status'] == 0) ? 'selected' : '';
                              } ?>>Inactive</option>
          </select>
        </div>
      </div>

    </div>
    <!-- /.card-body -->
  </div>
  <div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" id="submit-buyer" class="btn btn-primary">Save changes</button>
  </div>