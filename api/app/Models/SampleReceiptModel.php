<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class SampleReceiptModel extends Model
{
    protected $table = 'sample_receipt';
    protected $primaryKey = 'id';
    protected $allowedFields = ['quantity','auction_id','auction_item_id','lot_no','inward_item_id','buyer_id', 'created_by', 'updated_by', 'status'];

}