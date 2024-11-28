<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\ProductLogModel;
use App\Models\ActivityLog;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;

class Log extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function productLog()
    {
        $model = new ProductLogModel();

        $transformedLogs['productLog'] = $model->select('product_log.*, user.name AS user_name')
            ->join('user', 'user.id = product_log.user_id', 'left')
            ->orderBy('product_log.id', 'DESC')
            ->findAll();

        $data = [];

        foreach ($transformedLogs['productLog'] as $log) {
            $descriptionData = json_decode($log['description'], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($descriptionData)) {
                // Merge the description data into the log entry
                $log = array_merge($log, $descriptionData);
            }

            // Add the transformed log entry to the response data
            $data[] = $log;
        }

        return $this->respond($data);
    }


    public function productLogByDate()
    {

        $model = new ProductLogModel();

        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');

        $transformedLogs['productLog'] = $model->select('product_log.*,user.name AS user_name')
            ->join('user', 'user.id = product_log.user_id', 'left')
            ->where('DATE(product_log.created_at) >=', $fromDate)
            ->where('DATE(product_log.created_at) <=', $toDate)
            ->orderBy('id', 'DESC')
            ->findAll();

        $data = [];

        foreach ($transformedLogs['productLog'] as $log) {
            $descriptionData = json_decode($log['description'], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($descriptionData)) {
                $log = array_merge($log, $descriptionData);
            }

            $data[] = $log;
        }

        return $this->respond($data);
    }

    public function activityLog()
    {
        $model = new ActivityLog();

        $currentDate = date('Y-m-d');

        $data['activityLog'] = $model->select('activity_logs.*, user.name AS user_name')
            ->join('user', 'user.id = activity_logs.user_id', 'left')
            ->where('DATE(activity_logs.created_at)', $currentDate)
            ->orderBy('activity_logs.id', 'DESC')
            ->findAll();

        foreach ($data['activityLog'] as &$log) {
            if (isset($log['description'])) {
                $parts = explode(':', $log['description'], 2);

                if (count($parts) == 2) {
                    $extractedData = trim($parts[1]);
                    $log['extractedData'] = $extractedData;

                    if (isset($log['action']) && $log['action'] === 'delete') {
                        $deletedId = $log['extractedData'];
                        $tableName = $log['table_name'];

                        $db = \Config\Database::connect();
                        $builder = $db->table($tableName);
                        $deletedItem = $builder->select('name')->where('id', $deletedId)->get()->getRowArray();

                        if ($deletedItem && isset($deletedItem['name'])) {
                            $log['deletedItemName'] = $deletedItem['name'];
                        }
                    } elseif (isset($log['action']) && $log['action'] === 'logout') {
                        $tableName = $log['table_name'];

                        $db = \Config\Database::connect();
                        $builder = $db->table($tableName);
                        $username = $builder->select('name')->where('id', $log['user_id'])->get()->getRowArray();

                        if ($username && isset($username['name'])) {
                            $log['user_name'] = $username['name'];
                        }
                    } else {
                        $decodedData = json_decode($extractedData, true);
                        $log['extractedData'] = $decodedData;

                        if (
                            is_array($decodedData) && count($decodedData) === 4 &&
                            isset($decodedData['name']) && isset($decodedData['gst_no']) &&
                            isset($decodedData['address']) && isset($decodedData['updated_by'])
                        ) {

                            $buyerTable = 'buyer'; // Assuming 'buyer' is the name of the table
                            $db = \Config\Database::connect();
                            $builder = $db->table($buyerTable);
                            $buyerName = $builder->select('name')->where('id', $log['user_id'])->get()->getRowArray();

                            if ($buyerName && isset($buyerName['name'])) {
                                $log['user_name'] = $buyerName['name'];
                            }
                        }
                    }
                }
            }
        }

        // Uncomment the following lines if you want to debug the data
        // print_r($data);
        // exit;

        return $this->respond($data);
    }

    public function activityLogByDate()
    {
        $model = new ActivityLog();

        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');

        if (!$fromDate || !$toDate) {
            return $this->respond(['error' => 'Invalid date range'], 400);
        }

        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        $data['activityLog'] = $model->select('activity_logs.*, user.name AS user_name')
            ->join('user', 'user.id = activity_logs.user_id', 'left')
            ->where('DATE(activity_logs.created_at) >=', $fromDate)
            ->where('DATE(activity_logs.created_at) <=', $toDate)
            ->orderBy('activity_logs.id', 'DESC')
            ->findAll();

        foreach ($data['activityLog'] as &$log) {
            if (isset($log['description'])) {
                $parts = explode(':', $log['description'], 2);

                if (count($parts) == 2) {
                    $extractedData = trim($parts[1]);
                    $log['extractedData'] = $extractedData;

                    if (isset($log['action']) && $log['action'] === 'delete') {
                        $deletedId = $log['extractedData'];
                        $tableName = $log['table_name'];

                        $db = \Config\Database::connect();
                        $builder = $db->table($tableName);
                        $deletedItem = $builder->select('name')->where('id', $deletedId)->get()->getRowArray();

                        if ($deletedItem && isset($deletedItem['name'])) {
                            $log['deletedItemName'] = $deletedItem['name'];
                        }
                    } elseif (isset($log['action']) && $log['action'] === 'logout') {
                        $tableName = $log['table_name'];

                        $db = \Config\Database::connect();
                        $builder = $db->table($tableName);
                        $username = $builder->select('name')->where('id', $log['user_id'])->get()->getRowArray();

                        if ($username && isset($username['name'])) {
                            $log['user_name'] = $username['name'];
                        }
                    } else {
                        $decodedData = json_decode($extractedData, true);
                        $log['extractedData'] = $decodedData;

                        if (
                            is_array($decodedData) && count($decodedData) === 4 &&
                            isset($decodedData['name']) && isset($decodedData['gst_no']) &&
                            isset($decodedData['address']) && isset($decodedData['updated_by'])
                        ) {

                            $buyerTable = 'buyer'; // Assuming 'buyer' is the name of the table
                            $db = \Config\Database::connect();
                            $builder = $db->table($buyerTable);
                            $buyerName = $builder->select('name')->where('id', $log['user_id'])->get()->getRowArray();

                            if ($buyerName && isset($buyerName['name'])) {
                                $log['user_name'] = $buyerName['name'];
                            }
                        }
                    }
                }
            }
        }

        return $this->respond($data);
    }
}
