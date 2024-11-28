<?php

namespace App\Helpers;

class AutoDelete
{
    public static function deleteRelations($table_column, $statusId)
    {

        $db = \Config\Database::connect();
        $tables = $db->query("SHOW TABLES")->getResultArray();
        $result = array();
        foreach ($tables as $table) {
            $tableName = reset($table);
            if (
                $tableName != 'inward' && $tableName != 'inward_items'
                && $tableName != 'inward_return' && $tableName != 'auction' && $tableName != 'warehouse_stock'
                && $tableName != 'stock' && $tableName != 'garden_grade' && $tableName != 'auction_garden_order'
                && $tableName != 'auction_biddings' && $tableName != 'center_garden' && $tableName != 'auction_items'
                && $tableName != 'sample_receipt' &&  $tableName != 'user' && $tableName != 'user_roles'
                && $tableName != 'invoice' && $tableName != 'auction_buyer_invoice' && $tableName != 'auction_seller_invoice'
                && $tableName != 'auction_biddings' && $tableName != 'auto_bidding' && $tableName != 'sold_stock'
                && $tableName != 'buyer_catalog'
            ) {
                $columns = $db->getFieldNames($tableName);
                if (in_array($table_column, $columns)) {
                    $result[] = $tableName;
                    $db->table($tableName)->update(['status' => 0], [$table_column => $statusId]);
                }
            }
        }
        return $result;
    }
}
