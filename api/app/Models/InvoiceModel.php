<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'date', 'hsn_code', 'prompt_days', 'buyer_charges',
        'seller_charges', 'auction_id', 'invoice_no', 'buyer_id',
        'seller_id', 'created_by', 'created_at', 'updated_at',
        'b_name', 's_name', 'b_state', 's_state', 'b_city',
        's_city', 'b_area', 's_area', 'b_gst', 's_gst',
        'b_fssai', 's_fssai', 'b_tea', 's_tea', 'b_address', 's_address',
        's_state_id','s_city_id','b_state_id','b_city_id'
    ];
}
