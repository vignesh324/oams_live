<div class="card-body">
  <table id="example1" class="table table-bordered table-striped second-page-table">
    <thead>
      <tr>
        <!-- <th><input type="checkbox" id="check-all1" checked></th> -->
        <th>#</th>
        <th>Lot No</th>
        <th>Garden Name</th>
        <th>Grade</th>
        <th>No of Bags</th>
        <th>Each Net</th>
        <th>Total Net</th>
        <th>Auction Quantity</th>
        <th>Base Price</th>
        <th>Reserve Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      //$auction_data = session('auction_data');
      if (isset($auction_data)) {
        foreach ($auction_data as $key => $auction_items) {
      ?>
          <tr>
            <td><input type="checkbox" class="checkbox-item1" id="check-auctionitem_<?php echo $key ?>" name="check-auctionitem[]" checked></td>
            <td><?php echo $key + 1001; ?></td>
            <td><?= $auction_items['inward_item_garden']; ?></td>
            <td><?= $auction_items['inward_item_grade']; ?></td>
            <td><?= $auction_items['total_quantity']; ?></td>
            <td><?= $auction_items['each_nett']; ?></td>
            <td><?= $auction_items['total_nett']; ?></td>
            <td><input type="text" class="form-control auction_quantity" name="auction_quantity_final[]" id="auction_quantity_final<?php echo $key ?>" value="<?= $auction_items['total_quantity']; ?>"></td>
            <td><input type="text" class="form-control base_price" name="base_price_final[]" id="base_price_final<?php echo $key ?>"></td>
            <td><input type="text" class="form-control reserve_price" name="reserve_price_final[]" id="reserve_price_final<?php echo $key ?>"></td>

            <input type="hidden" class="form-control" name="total_quantitysss_final[]" id="total_quantity_final<?php echo $key ?>" value="<?= $auction_items['total_quantity']; ?>" placeholder="Auction Quantity" readonly>
            <input type="hidden" class="form-control item_garden_name" name="inward_item_garden_final[]" id="inward_item_garden_final<?php echo $key ?>" placeholder="Garden name" value="<?= $auction_items['inward_item_garden']; ?>">
            <input type="hidden" class="form-control item_item_id" name="inward_item_id_final[]" id="inward_item_id_final<?php echo $key ?>" placeholder="Garden name" value="<?= $auction_items['inward_item_id']; ?>">
            <input type="hidden" class="form-control item_warehouse_name" name="inward_item_warehouse_final[]" id="inward_item_warehouse_final<?php echo $key ?>" value="<?= $auction_items['inward_item_warehouse']; ?>">
            <input type="hidden" class="form-control item_garden_id" name="inward_item_garden_id_final[]" id="inward_item_garden_id_final<?php echo $key ?>" placeholder="Garden name" value="<?= $auction_items['inward_item_garden_id']; ?>">
            <input type="hidden" class="form-control item_grade_id" name="inward_item_grade_id_final[]" id="inward_item_grade_id_final<?php echo $key ?>" placeholder="Garden name" value="<?= $auction_items['inward_item_grade_id']; ?>">
            <input type="hidden" class="form-control item_grade_name" name="inward_item_grade_final[]" id="inward_item_grade_final<?php echo $key ?>" placeholder="Grade name" value="<?= $auction_items['inward_item_grade']; ?>">
            <input type="hidden" class="form-control inward_item" name="inward_item_final[]" id="inward_item_final<?php echo $key ?>" placeholder="Auction Quantity" value="<?= $auction_items['inward_item']; ?>">
            <input type="hidden" class="form-control each_nett" name="each_nett_final[]" id="each_nett_final<?php echo $key ?>" value="<?= $auction_items['each_nett']; ?>">
            <input type="hidden" class="form-control total_nett" name="total_nett_final[]" id="total_nett_final<?php echo $key ?>" value="<?= $auction_items['total_nett']; ?>">
          </tr>
      <?php
        }
      }
      ?>
    </tbody>
  </table>
</div>

<div class="float-right m-2">
  <button type="button" class="btn btn-danger" onclick="gotoPreviousStep()">Reset</button>
  <button type="button" class="btn btn-primary" onclick="moveToFinalStep()">Next</button>
</div>
<script>
  $(document).ready(function() {
    $("#lot_count").val(<?php echo count($auction_data); ?>)
  });

  $(document).on('change', '#check-all1', function() {
    var isChecked = $(this).prop('checked');
    $('.checkbox-item1').prop('checked', isChecked);
  });

  $(document).on('change', '.checkbox-item1', function() {
    var allChecked = true;
    $('.checkbox-item1').each(function() {
      if (!$(this).prop('checked')) {
        allChecked = false;
        return false; // Exit each loop early
      }
    });

    $('#check-all1').prop('checked', allChecked);
    $(this).closest('tr').find('.error').remove();
  });
</script>