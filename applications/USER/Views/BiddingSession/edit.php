<form id="biddingsession-form-edit">
    <div class="modal-header">
        <h4 class="modal-title">Edit Bidding Session</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Center Name</label>
                <input type="text" class="form-control" value="<?php echo @$auction_data['center_name'] ?>" readonly>
            </div>

            <div class="form-group">
                <label for="name">Type</label>
                <input type="text" class="form-control" value="<?php echo isset($auction_data['type']) && $auction_data['type'] == 1 ? 'Leaf' : 'Dust'; ?>" readonly>
            </div>
            
            <?php
            $startDateTime = strtotime($auction_data['date'] . ' ' . $auction_data['start_time']);
            $now = time();
            $diff = $startDateTime - $now;
            $hoursDifference = $diff / (60 * 60);
            // print_r($hoursDifference); 

            if ($hoursDifference < (1 / 2)) {
            ?>
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" class="form-control" name="date" value="<?php echo @$auction_data['date'] ?>" readonly>
                </div>
            <?php } else { ?>
                <div class="form-group">
                    <label>Date</label>
                    <div class="input-group date" id="date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="date" value="<?php echo isset($auction_data['date']) ? date("d-m-Y", strtotime($auction_data['date'])) : ''; ?>" data-target="#date" />
                        <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            <?php }
            if ($hoursDifference < (1 / 2)) {
            ?>
                <div class="form-group">
                    <label>Auction Start Time</label>
                    <input type="text" class="form-control" name="start_time" value="<?php echo @$auction_data['start_time'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Auction End Time</label>
                    <input type="text" class="form-control" name="end_time" value="<?php echo @$auction_data['end_time'] ?>" readonly>
                </div>
            <?php } else { ?>
                <div class="form-group">
                    <label for="name">Auction Start Time</label>

                    <div class="input-group date" id="start_time" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="start_time" data-target="#start_time" value="<?php echo @$auction_data['start_time']; ?>">
                        <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Auction End Time</label>

                    <div class="input-group date" id="end_time" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="end_time" data-target="#end_time" value="<?php echo @$auction_data['end_time']; ?>">
                        <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <label for="name">Reason</label>
                <textarea class="form-control" id="reason" name="reason" placeholder="Enter Reason"><?php echo @$auction_data['reason']; ?></textarea>
            </div>

            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">
            <input type="hidden" class="form-control" id="session_user_id" name="session_user_id" value="<?php echo session()->get('session_user_id'); ?>">
        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="edit-biddingsession" class="btn btn-primary">Save changes</button>
    </div>
</form>


<script>
    $(function() {
        $('#date').datetimepicker({
            format: 'DD-MM-YYYY',
            placeholder: 'dd-mm-yyyy',
            minDate: moment().startOf('day'),
            disabledDates: [moment().startOf('day')]
        });

        $('#end_time').datetimepicker({
            format: 'HH:mm'
        });

        $('#start_time').datetimepicker({
            format: 'HH:mm'
        })
    });
</script>