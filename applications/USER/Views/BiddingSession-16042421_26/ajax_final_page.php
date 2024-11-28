<div class="card-body">
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label for="center">Center</label>
        <input type="text" class="form-control" value="<?= @$header_data['center_name'] ?>" readonly>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="date">Date</label>
        <input type="text" class="form-control datetimepicker-input" id="date" name="date" value="<?= @$header_data['date'] ?>" readonly>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="name">Auction Start Time</label>
          <input type="text" class="form-control" value="<?= @$header_data['start_time'] ?>" readonly>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        <label>Session Time / lot</label>
        <div class="input-group" id="session_time">
          <input type="text" class="form-control" value="<?= @$header_data['session_time'] ?>" readonly>
          <input type="hidden" class="form-control" name="lot_count" id="lot_count" value="<?php echo @$auction_data['lot_count']; ?>" placeholder="Lot Count">
        </div>
      </div>
    </div>
  </div>

  <table id="example1" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Lot No</th>
        <th>Garden</th>
        <th>Grade</th>
        <th>Warehouse</th>
        <th>No of Bags</th>
        <th>Each Nett</th>
        <th>Total Nett</th>
        <th>Auction Quantity</th>
        <th>Base Price</th>
        <th>Reserve Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      //$auction_data = session('auction_data');
      if (isset($auction_data)) {
        // echo '<pre>';
        // print_r($auction_data);
        // exit;
        foreach (@$auction_data as $key => $auction_items) {
      ?>
          <tr>
            <td><?php echo $key + 1001; ?></td>
            <td><?= @$auction_items['inward_item_garden']; ?></td>
            <td><?= @$auction_items['inward_item_grade']; ?></td>
            <td><?= @$auction_items['inward_item_warehouse']; ?></td>
            <td><?= @$auction_items['total_quantity']; ?></td>
            <td><?= @$auction_items['each_nett']; ?></td>
            <td><?= @$auction_items['total_nett']; ?></td>
            <td><?= @$auction_items['auction_quantity']; ?></td>
            <td><?= @$auction_items['base_price']; ?></td>
            <td><?= @$auction_items['reserve_price']; ?></td>
          </tr>
      <?php
        }
      }
      ?>
    </tbody>
  </table>
</div>

<div class="float-right m-2">
  <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
  <button type="button" id="add-bidding-session" class="btn btn-primary">Submit</button>
</div>
<script>
  $(document).ready(function() {
    $("#lot_count").val(<?php echo count($auction_data); ?>)
  });
</script>