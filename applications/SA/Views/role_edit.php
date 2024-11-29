<div class="form-group">
  <label for="name">Name</label>
  <input type="text" value="<?php echo $old_data['role_id']['role']; ?>" class="form-control" id="name" name="name" placeholder="Enter Name">
  <input type="hidden" value="<?php echo $old_data['role_id']['id']; ?>" class="form-control" id="role_id" name="role_id">
</div>
<div class="form-group">
  <label for="roles">Roles</label>
  <table class="table">
    <tr>
      <th>Module</th>
      <th>View</th>
      <th>Create</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>

    </thead>
    <tbody>
      <?php foreach ($response_data as $key => $value) : ?>
        <tr>
          <td>
            <span class="text-danger">
              <?php echo $value['name']; ?>
            </span>
          </td>
          <?php
          // Initialize variables for checkboxes
          $view_checked = '';
          $create_checked = '';
          $edit_checked = '';
          $delete_checked = '';

          // Set default permissions for specific conditions
          if ($key == 0) {
            $view_checked = 'checked';
          }
          if ($value['id'] == 17) {
            $edit_checked = 'disabled';
          }
          if (in_array($value['id'], [22, 23, 24, 26, 27, 28, 29])) {
            $create_checked = 'disabled';
            $edit_checked = 'disabled';
            $delete_checked = 'disabled';
          }
          if (in_array($value['id'], [18, 16])) {
            $delete_checked = 'disabled';
          }
          if (in_array($value['id'], [16])) {
            $delete_checked = 'disabled';
            $create_checked = 'disabled';
          }

          // Check permissions from $old_data['role_detail']
          foreach ($old_data['role_detail'] as $inkey => $invalue) {
            if ($value['id'] == $invalue['module_id']) {
              $view_checked = ($invalue['list_permission'] == 1) ? 'checked' : $view_checked;
              $create_checked = ($invalue['create_permission'] == 1) ? 'checked' : $create_checked;
              $edit_checked = ($invalue['update_permission'] == 1) ? 'checked' : $edit_checked;
              $delete_checked = ($invalue['delete_permission'] == 1) ? 'checked' : $delete_checked;
              break; // Exit loop once permissions are found
            }
          }
          ?>
          <td>
            <div class="form-check">
              <input type="checkbox" value="1" name="module_view[<?php echo $value['id']; ?>][0]" class="form-check-input" id="exampleCheck<?php echo $key + 1; ?>_0" <?php echo $view_checked; ?>>
              <label class="form-check-label" for="exampleCheck<?php echo $key + 1; ?>_0">View</label>
            </div>
          </td>
          <td>
            <div class="form-check">
              <input type="checkbox" value="1" name="module_view[<?php echo $value['id']; ?>][1]" class="form-check-input" id="exampleCheck<?php echo $key + 1; ?>_1" <?php echo $create_checked; ?>>
              <label class="form-check-label" for="exampleCheck<?php echo $key + 1; ?>_1">Create</label>
            </div>
          </td>
          <td>
            <div class="form-check">
              <input type="checkbox" value="1" name="module_view[<?php echo $value['id']; ?>][2]" class="form-check-input" id="exampleCheck<?php echo $key + 1; ?>_2" <?php echo $edit_checked; ?>>
              <label class="form-check-label" for="exampleCheck<?php echo $key + 1; ?>_2">Edit</label>
            </div>
          </td>
          <td>
            <div class="form-check">
              <input type="checkbox" value="1" name="module_view[<?php echo $value['id']; ?>][3]" class="form-check-input" id="exampleCheck<?php echo $key + 1; ?>_3" <?php echo $delete_checked; ?>>
              <label class="form-check-label" for="exampleCheck<?php echo $key + 1; ?>_3">Delete</label>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>

</div>