<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLog();
    }

    public function log($userId, $action, $description = null,$model)
    {
        $data = [
            'user_id'    => $userId,
            'action'     => $action,
            'description'=> $description,
            'table_name'=> $model,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->activityLogModel->save($data);
        // echo '<pre>';print_r($data);exit;
    }

    public function logCreate($userId, $model, $data)
    {
        $this->log($userId, 'create', 'Created ' . $model . ' with data: ' . json_encode($data),$model);
    }

    public function logUpdate($userId, $model, $data)
    {
        $this->log($userId, 'update', 'Updated ' . $model . ' with data: ' . json_encode($data),$model);
    }

    public function logDelete($userId, $model, $id)
    {
        $this->log($userId, 'delete', 'Deleted ' . $model . ' with ID: ' . $id,$model);
    }
}
