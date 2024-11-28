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
          <label for="name">Center Name</label>
          <input type="text" name="name" class="form-control" id="name" placeholder="Enter Center Name" value="<?php echo @$response_data['name']; ?>">
        </div>
        <!-- <div class="form-group col-md-6">
          <label for="code">Code</label>
          <input type="text" name="code" class="form-control" id="code" placeholder="Enter Code" value="<?php echo @$response_data['code']; ?>">
        </div> -->

        <div class="form-group col-md-6">
          <label for="name">State</label>
          <select class="form-control" name="state_id" id="state_id">
            <option value="">Select State</option>
            <?php foreach ($list_data['state'] as $state) : ?>
              <?php $selected_state = (@$response_data['state_id'] == $state['id']) ? 'selected' : ''; ?>
              <option value="<?php echo $state['id'] ?>" <?php echo $selected_state ?>><?php echo $state['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label for="name">City</label>
          <select class="form-control" name="city_id" id="city_id">
            <option value="">Select City</option>
            <?php if (isset($response_data['state_id'])) : ?>
              <?php foreach ($list_data['state'] as $state) {
                if ($state['id'] == $response_data['state_id']) {
                  foreach ($state['city'] as $city) {
                    $selected_city = ($response_data['city_id'] == $city['id']) ? 'selected' : '';
                    echo "<option value='{$city['id']}' $selected_city>{$city['name']}</option>";
                  }
                  break; // stop looping once city found
                }
              }
              ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label for="name">Area</label>
          <select class="form-control" name="area_id" id="area_id">
            <option value="">Select Area</option>
            <?php
            if (isset($response_data['state_id'], $response_data['city_id'])) {
              foreach ($list_data['state'] as $state) {
                if ($state['id'] == $response_data['state_id']) {
                  foreach ($state['city'] as $city) {
                    if ($city['id'] == $response_data['city_id']) {
                      foreach ($city['area'] as $area) {
                        $selected_area = ($response_data['area_id'] == $area['id']) ? 'selected' : '';
                        echo "<option value='{$area['id']}' $selected_area>{$area['name']}</option>";
                      }
                      break; // stop looping once area found
                    }
                  }
                  break; // stop looping once city found
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6">
          <label for="name">Status</label>
          <select class="form-control" name="status">
            <option value="1" selected <?php if (isset($response_data)) {
                                echo (@$response_data['status'] == 1) ? 'selected' : '';
                              } ?>>Active</option>
            <option value="2" <?php if (isset($response_data)) {
                                echo (@$response_data['status'] == 2) ? 'selected' : '';
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