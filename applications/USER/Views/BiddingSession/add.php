<form id="auction-form">
    <div class="modal-header">
        <h4 class="modal-title">Add Auction</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <div class="form-group">
                <label for="center">Center</label>
                <select class="form-control" name="center_id" id="center_id">
                    <option value="">Select Center</option>
                    <?php
                    foreach (@$centers as $key => $values) :
                    ?>
                        <option value="<?php echo $values['id']; ?>" <?php if (@$auction_data['center_id'] == $values['id']) { ?> selected <?php } ?>><?php echo $values['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="center">Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">Select Type</option>
                    <option value="1">Leaf</option>
                    <option value="2">Dust</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <div class="input-group date" id="date" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" name="date" value="<?php echo isset($auction_data['date']) ? date("d-m-Y", strtotime($auction_data['date'])) : ''; ?>" data-target="#date" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Auction Start Time</label>
                <!-- <input type="time" placeholder="Auction start time" name="start_time" id="start_time" value="<?php echo @$auction_data['start_time']; ?>" class="form-control"> -->
                <div class="input-group date" id="start_time" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" name="start_time" data-target="#start_time" value="<?php echo @$auction_data['start_time']; ?>">
                    <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                    </div>
                </div>
            </div>

            <input type="hidden" value="2" class="form-control" name="lot_count" id="lot_count" placeholder="Lot Count">
                <input type="hidden" class="form-control" name="session_time" id="sessiontimeInput" placeholder="00:00" value="00:10">

            <div class="form-group">
                <label for="name">Auction End Time</label>
                <!-- <input type="time" placeholder="Auction start time" name="start_time" id="start_time" value="<?php echo @$auction_data['start_time']; ?>" class="form-control"> -->
                <div class="input-group date" id="end_time" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" name="end_time" data-target="#end_time" value="<?php echo @$auction_data['end_time']; ?>">
                    <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.card-body -->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="add-auction" class="btn btn-primary">Save changes</button>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(function() {
        $('#date').datetimepicker({
            format: 'DD-MM-YYYY',
            placeholder: 'dd-mm-yyyy',
            minDate: moment().startOf('day').add(1, 'days'),
            disabledDates: [moment().startOf('day')]
        });

        $('#end_time').datetimepicker({
            format: 'HH:mm'
        });

        $('#start_time').datetimepicker({
            format: 'HH:mm'
        });
        $('#timeInput').inputmask('99:99', {
            placeholder: '00:00'
        });
    });
</script>