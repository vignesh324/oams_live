<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Services\ActivityLogger;
use App\Helpers\ProductLog;

class BaseModel extends Model
{
    protected $activityLogger;
    protected $userId;

    public function __construct()
    {
        parent::__construct();
        $this->activityLogger = new ActivityLogger();
        $this->userId = 1; // Assume user ID is stored in session
    }

    public function insert($data = null, bool $returnID = true)
    {
        $insertID = parent::insert($data, $returnID);
        $this->activityLogger->logCreate($data['created_by'], $this->table, $data);
        return $insertID;
    }

    public function update($id = null, $data = null): bool
    {
        $productLog = new ProductLog();
        $oldData = $productLog->getOldData($this->table, $id);
        
        //update data 
        $result = parent::update($id, $data);

        if ($result) {
            if (count($data) === 2) {
                // echo 'hii';exit;
                $this->activityLogger->logDelete($data['updated_by'], $this->table, $id);
            } else {
                $mergedData = $productLog->mergeData($oldData, $data);
                $this->activityLogger->logUpdate($data['updated_by'], $this->table, $mergedData);
            }
        }

        return $result;
    }

    public function delete($id = null, bool $purge = false)
    {
        $result = parent::delete($id, $purge);
        if ($result) {
            $this->activityLogger->logDelete($this->userId, $this->table, $id);
        }
        return $result;
    }
}
