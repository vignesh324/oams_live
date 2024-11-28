<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLog extends Model
{
    protected $table      = 'activity_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = ['user_id', 'action', 'table_name', 'description', 'created_at'];
}
