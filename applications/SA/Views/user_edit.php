<form id="user-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?php echo $response_data['name'] ?>">
            </div>
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="<?php echo $response_data['username'] ?>">
            </div>
            <div class="form-group">
                <label for="name">Role</label>
                <select class="form-control" name="role_id">
                    <option value="">Select Role</option>
                    <?php foreach ($list_response_data as $key => $role) : ?>
                        <?php if ($response_data['role_id'] == $role['id']) : ?>
                            <option value="<?php echo $role['id'] ?>" selected><?php echo $role['role'] ?></option>
                        <?php else : ?>
                            <option value="<?php echo $role['id'] ?>"><?php echo $role['role'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile_no">Mobile No</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Mobile No" value="<?php echo $response_data['phone'] ?>">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $response_data['email'] ?>">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
        </div>
        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $response_data['id'] ?>">

        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="user-edit-button" class="btn btn-primary">Save changes</button>
    </div>
</form>