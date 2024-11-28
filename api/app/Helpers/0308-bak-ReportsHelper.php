<?php

namespace App\Helpers;

class ReportsHelper
{
    public static function getBaseInvoiceQuery($invoiceItemModel, $from_date, $to_date, $auction_id, $seller_id = null, $garden_id = null, $state_id = null, $city_id = null)
    {
        $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            invoice_item.qty,
            invoice_item.bid_price,
            grade.type,
            seller.name AS seller_name,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty * inward_items.weight_net ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity1,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty * invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty * inward_items.weight_net ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity1,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty * invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty * invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty * invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
        ')
        ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
        ->join('auction', 'auction.id = invoice.auction_id', 'left')
        ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
        ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
        ->join('inward', 'inward.id = inward_items.inward_id', 'left')
        ->join('garden', 'garden.id = inward.garden_id', 'left')
        ->join('seller', 'seller.id = invoice.seller_id', 'left')
        ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
        ->join('grade', 'grade.id = inward_items.grade_id', 'left')
        ->where('DATE(auction.date) >=', $from_date)
        ->where('DATE(auction.date) <=', $to_date)
        ->whereIn('auction.id', $auction_id);
    
        if ($seller_id) {
            $invoiceItemModel->where('seller.id', $seller_id);
        }
    
        if ($garden_id) {
            $invoiceItemModel->where('garden.id', $garden_id);
        }
    
        if ($state_id) {
            $invoiceItemModel->where('buyer.state_id', $state_id);
        }
    
        if ($city_id) {
            $invoiceItemModel->where('buyer.city_id', $city_id);
        }
    
        return $invoiceItemModel;
    }
    
}
