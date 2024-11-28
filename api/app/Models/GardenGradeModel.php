<?php

namespace App\Models;

use CodeIgniter\Model;

class GardenGradeModel extends Model
{
    protected $table = 'garden_grade';
    protected $primaryKey = 'id';
    protected $allowedFields = ['garden_id', 'order_seq', 'grade_id', 'category_id'];
}
