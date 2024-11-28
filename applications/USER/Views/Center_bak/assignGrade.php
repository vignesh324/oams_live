<form>
              <div class="modal-header">
                <h4 class="modal-title">Assign Garden</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card-body">
                  
                  <div class="form-group">
                    <label>Select Garden</label>
                    <div class="select2-purple">
                      <select class="select2" multiple="multiple" data-placeholder="Select Garden" data-dropdown-css-class="select2-purple" style="width: 100%;">
                      <option value="">Select Garden</option>
                      <?php foreach($garden_list as $key => $garden) : ?>
                      <option value="<?php echo $garden['id'];?>"><?php echo $garden['name'];?></option>
                      <?php endforeach;?>
                      </select>
                    </div>
                  </div>

                  <div class="timeline">
                  <?php foreach($garden_list as $key => $garden) : ?>
                    <div>
                      <div class="timeline-item">
                      <span class="time"><a href="#"><i class="fas fa-caret-down"></i></a></span>
                      <h3 class="timeline-header no-border"><a href="#"><?php echo $garden['name'];?></a></h3>
                      </div>
                    </div>
                    <?php endforeach;?>
                  </div>

                </div>
                <!-- /.card-body -->
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Assign Garden</button>
              </div>
            </form>
