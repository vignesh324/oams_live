<form id="autobid-form" action="<?= $url; ?>">
    <div class="modal-header">
        <h4 class="modal-title"><?php echo $title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="name">LOT No: </label>
            <input type="text" class="form-control" style="width: 100% !important;" id="lot_no" value="<?php echo $response_data['auctionitem']['lot_no']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="name">Min Price: </label>
            <input type="text" name="min_price" class="form-control" style="width: 100% !important;" id="min_price" placeholder="Enter Min price" value="<?php echo @$response_data['min_price']; ?>">
        </div>
        <div class="form-group">
            <label for="name">Max Price: </label>
            <input type="text" name="max_price" class="form-control" style="width: 100% !important;" id="max_price" placeholder="Enter Max price" value="<?php echo @$response_data['max_price']; ?>">
            <input type="hidden" class="form-control" id="auction_id" name="auction_id" value="<?php echo @$data1['auction_id'] ?>">
            <input type="hidden" class="form-control" id="auctionitem_id" name="auctionitem_id" value="<?php echo @$data1['auctionitem_id'] ?>">
            <input type="hidden" name="buyer_id" id="buyer" value="<?php echo session()->get('user_id'); ?>" />
        </div>

    </div>
    <!-- /.card-body -->
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="autobid-submit" class="btn btn-primary">Save changes</button>
    </div>
</form>