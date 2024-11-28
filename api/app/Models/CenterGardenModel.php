<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class CenterGardenModel extends Model
{
    protected $table = 'center_garden';
    protected $primaryKey = 'id';
    protected $allowedFields = ['center_id','garden_id', 'order_seq', 'created_by', 'updated_by'];

}