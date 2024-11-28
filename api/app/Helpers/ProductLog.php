<?php

namespace App\Helpers;

use App\Models\InwardItemModel;
use App\Models\AuctionItemModel;
use App\Models\ProductLogModel;

class ProductLog
{
    public static function logProductAction($inward_item_id, $status, $qty, $userId)
    {
        // print_r($userId);exit;
        $inwardItemModel = new InwardItemModel();
        $inward_details = $inwardItemModel->select('inward_items.*, garden.name AS garden_name, grade.name AS grade_name')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->where('inward_items.id', $inward_item_id)
            ->first();

        if (!$inward_details) {
            return false;
        }

        $auctionItemModel = new AuctionItemModel();
        $auction_details = $auctionItemModel->select('auction_items.*')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->where('auction_items.inward_item_id', $inward_item_id)
            ->first();

        $logData = [
            'invoice_no' => $inward_details['invoice_id'],
            'lot_no' => $auction_details ? $auction_details['lot_no'] : '',
            'status' => $status,
            'qty' => $qty,
            'garden_name' => $inward_details['garden_name'],
            'grade_name' => $inward_details['grade_name'],
        ];

        $logDataJson = json_encode($logData);

        log_message('debug', 'logProductAction - logDataJson: ' . $logDataJson);

        $productLog = new ProductLogModel();
        if (!$productLog->insert(['description' => $logDataJson, 'user_id' => $userId])) {
            log_message('error', 'logProductAction - Failed to insert logDataJson: ' . $logDataJson);
            return false;
        }

        return true;
    }


    public function getOldData($table, $id)
    {
        $db = \Config\Database::connect();
        $oldData = $db->table($table)->where('id', $id)->get()->getFirstRow('array');
        return $oldData;
    }

    public function mergeData($oldData, $newData)
    {
        $changes = [];

        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] === $value) {
                $changes[$key] = $value;
            } else {
                $changes[$key] = $oldData[$key] . "-" . $value;
            }
        }

        return $changes;
    }
}
