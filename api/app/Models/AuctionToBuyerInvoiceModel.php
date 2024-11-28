<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionToBuyerInvoiceModel extends Model
{
    protected $table = 'auction_buyer_invoice';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'date', 'hsn_code', 'prompt_days', 'buyer_charges',
        'seller_charges', 'auction_id', 'invoice_no', 'buyer_id',
        'created_by', 'created_at', 'updated_at',
        'b_name', 'b_state', 'b_city',
        'b_area',  'b_gst',
        'b_fssai',  'b_tea',  'b_address', 'b_state_id', 'b_city_id'
    ];
}
