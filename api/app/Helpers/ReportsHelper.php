<?php

namespace App\Helpers;

class ReportsHelper
{
    public static function getBaseInvoiceQuery($invoiceItemModel, $from_date, $to_date, $auction_id, $seller_id = null, $garden_id = null, $state_id = null, $city_id = null)
    {
        $invoiceItemModel->select('
        invoice.buyer_id,
        invoice.b_name AS buyer_name,
        auction.sale_no,
        invoice.seller_id,
        invoice.s_name AS seller_name,
        SUM(CASE WHEN auction.type = 1 THEN (invoice_item.qty * invoice_item.each_net)-invoice_item.sample_quantity ELSE 0 END) AS leaf_total_quantity,
        SUM(CASE WHEN auction.type = 2 THEN (invoice_item.qty * invoice_item.each_net)-invoice_item.sample_quantity ELSE 0 END) AS dust_total_quantity,
        SUM(CASE WHEN auction.type = 1 THEN ((invoice_item.qty * invoice_item.each_net)-invoice_item.sample_quantity)*invoice_item.bid_price ELSE 0 END) AS leaf_total_value,
        SUM(CASE WHEN auction.type = 2 THEN ((invoice_item.qty * invoice_item.each_net)-invoice_item.sample_quantity)*invoice_item.bid_price ELSE 0 END) AS dust_total_value
    ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id);

        if ($seller_id) {
            $invoiceItemModel->where('invoice.seller_id', $seller_id);
        }

        if ($garden_id) {
            $invoiceItemModel->where('invoice_item.garden_id', $garden_id);
        }

        if ($state_id) {
            $invoiceItemModel->where('invoice.b_state_id', $state_id);
        }

        if ($city_id) {
            $invoiceItemModel->where('invoice.b_city_id', $city_id);
        }

        return $invoiceItemModel;
    }


    public static function getTabledata2(array $results)
    {
        $resultsByGardenGrade = [];
        $grandSoldTotal = 0;
        $grandUnsoldTotal = 0;
        $grandAvgPrice = 0;
        $grandUpSoldTotal = 0;
        $grandUpUnsoldTotal = 0;
        $grandUpAvgPrice = 0;
        $grandOffQty = 0;
        $grandUpOffQty = 0;

        // First pass to aggregate data by garden and calculate totals
        foreach ($results as $result) {
            if (!isset($resultsByGardenGrade[$result['garden_id']])) {
                $resultsByGardenGrade[$result['garden_id']] = [
                    'garden_name' => $result['garden_name'],
                    'garden_id' => $result['garden_id'],
                    'total_sold_quantity' => 0,
                    'total_sold_quantity1' => 0,
                    'total_unsold_quantity' => 0,
                    'total_avg_sold_price' => 0,
                    'total_offered_qty' => 0,
                    'total_upsale_sold_quantity' => 0,
                    'total_upsale_sold_quantity1' => 0,
                    'total_upsale_unsold_quantity' => 0,
                    'total_upsale_avg_sold_price' => 0,
                    'total_upsale_offered_qty' => 0,
                    'grades' => []
                ];
            }

            $resultsByGardenGrade[$result['garden_id']]['total_sold_quantity'] += $result['sold_quantity'];
            $resultsByGardenGrade[$result['garden_id']]['total_sold_quantity1'] += $result['sold_quantity1'];
            $resultsByGardenGrade[$result['garden_id']]['total_unsold_quantity'] += $result['unsold_quantity'];
            $resultsByGardenGrade[$result['garden_id']]['total_avg_sold_price'] += $result['avg_sold_price'];
            $resultsByGardenGrade[$result['garden_id']]['total_offered_qty'] += $result['sold_quantity'] + $result['unsold_quantity'];

            $resultsByGardenGrade[$result['garden_id']]['total_upsale_sold_quantity'] += $result['upsale_sold_quantity'];
            $resultsByGardenGrade[$result['garden_id']]['total_upsale_sold_quantity1'] += $result['upsale_sold_quantity1'];
            $resultsByGardenGrade[$result['garden_id']]['total_upsale_unsold_quantity'] += $result['upsale_unsold_quantity'];
            $resultsByGardenGrade[$result['garden_id']]['total_upsale_avg_sold_price'] += $result['upsale_avg_sold_price'];
            $resultsByGardenGrade[$result['garden_id']]['total_upsale_offered_qty'] += $result['upsale_sold_quantity'] + $result['upsale_unsold_quantity'];

            $grandSoldTotal += $result['sold_quantity'];
            $grandUnsoldTotal += $result['unsold_quantity'];
            $grandAvgPrice += $result['avg_sold_price'];
            $grandOffQty += $result['sold_quantity'] + $result['unsold_quantity'];

            $grandUpSoldTotal += $result['upsale_sold_quantity'];
            $grandUpUnsoldTotal += $result['upsale_unsold_quantity'];
            $grandUpAvgPrice += $result['upsale_avg_sold_price'];
            $grandUpOffQty += $result['upsale_sold_quantity'] + $result['upsale_unsold_quantity'];
        }

        // Second pass to calculate percentages and organize grades
        foreach ($resultsByGardenGrade as $gardenId => &$gardenData) {
            $totalSoldQuantity = $gardenData['total_sold_quantity'];
            // $totalUpSoldQuantity = $gardenData['total_sold_quantity1'];
            // $totalUpSoldQuantity = $gardenData['total_sold_quantity1'];

            foreach ($results as $result) {
                if ($result['garden_id'] == $gardenId) {
                    $percentage = $totalSoldQuantity > 0 ? ($result['sold_quantity'] / $totalSoldQuantity) * 100 : 0;
                    $upsale_percentage = $totalSoldQuantity > 0 ? ($result['upsale_sold_quantity'] / $totalSoldQuantity) * 100 : 0;
                    $result['percentage'] = $percentage;
                    $result['upsale_percentage'] = $upsale_percentage;
                    $gardenData['grades'][] = $result;
                }
            }
        }

        // Re-index array to get numeric keys
        $resultsByGardenGrade = array_values($resultsByGardenGrade);

        // Add grand totals
        $resultsByGardenGrade['grand_sold_quantity'] = $grandSoldTotal;
        $resultsByGardenGrade['grand_unsold_quantity'] = $grandUnsoldTotal;
        $resultsByGardenGrade['grand_avg_sold_price'] = $grandAvgPrice;
        $resultsByGardenGrade['grand_offered_qty'] = $grandOffQty;
        $resultsByGardenGrade['grand_upsale_sold_quantity'] = $grandUpSoldTotal;
        $resultsByGardenGrade['grand_total_upsale_unsold_quantity'] = $grandUpUnsoldTotal;
        $resultsByGardenGrade['grand_upsale_avg_sold_price'] = $grandUpAvgPrice;
        $resultsByGardenGrade['total_upsale_offered_qty'] = $grandUpOffQty;

        return $resultsByGardenGrade;
    }
}
