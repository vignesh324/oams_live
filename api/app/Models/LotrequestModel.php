<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class LotrequestModel extends BaseModel
{
    protected $table = 'lot_request';
    protected $primaryKey = 'id';
    protected $allowedFields = ['auction_id', 'lot_set'];

}