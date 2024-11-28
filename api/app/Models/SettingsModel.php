<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'delivery_time', 'buyer_show','buyer_charges','seller_charges',
        'leaf_sq','dust_sq','leaf_hsn','dust_hsn', 'increment_amount', 
        'created_by', 'updated_by', 'status', 'as_prefix', 'ab_prefix','as_suffix', 'ab_suffix'
    ];

}