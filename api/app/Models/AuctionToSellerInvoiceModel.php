<?php

namespace App\Models;

use CodeIgniter\Model;

class AuctionToSellerInvoiceModel extends Model
{
    protected $table = 'auction_seller_invoice';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'date', 'hsn_code', 'prompt_days', 'buyer_charges',
        'seller_charges', 'auction_id', 'invoice_no', 'buyer_id',
        'seller_id', 'created_by', 'created_at', 'updated_at',
        's_name', 's_state',
        's_city', 's_area', 's_gst',
        's_fssai', 's_tea', 's_address','s_state_id','s_city_id'
    ];
}
