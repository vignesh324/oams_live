<?php
$grandLeafQuantity = 0;
$grandDustQuantity = 0;
$totalGrandQuantity = 0;
$totalGrandBidPrice = 0;

$saleNos = [];
?>

<thead>
    <tr>
        <th class="text-center">Buyer Name</th>
        <th class="text-center">Leaf Qty in Kgs</th>
        <th class="text-center">Leaf Avg Price in Rs.</th>
        <th class="text-center">Dust Qty in Kgs</th>
        <th class="text-center">Dust Avg Price in Rs.</th>
        <th class="text-center">Total Qty in Kgs</th>
        <th class="text-center">Total Avg Price in Rs.</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($response_data['invoiceItems'] as $val) :
        // print_r($response_data);exit;
    ?>
        <?php
        $leafQuantity = $val['leaf_total_quantity'];
        $dustQuantity = $val['dust_total_quantity'];
        $leafBidPrice = $val['leaf_total_bid_price'];
        $dustBidPrice = $val['dust_total_bid_price'];
        $leafQuantity1 = $val['leaf_total_quantity1'];
        $dustQuantity1 = $val['dust_total_quantity1'];

        $grandTotalQuantity = $leafQuantity + $dustQuantity;
        $grandTotalQuantity1 = $leafQuantity1 + $dustQuantity1;
        $grandTotalBidPrice = $leafBidPrice + $dustBidPrice;
        $grandTotalAvgPrice = (isset($grandTotalQuantity1) && $grandTotalQuantity1 > 0) ? $grandTotalBidPrice / $grandTotalQuantity1 : 0;

        // Update grand totals
        $grandLeafQuantity += $leafQuantity;
        $grandDustQuantity += $dustQuantity;
        $totalGrandQuantity += $grandTotalQuantity;
        $totalGrandBidPrice += $grandTotalBidPrice;

        // Check for sale number row
        if (!in_array($val['sale_no'], $saleNos)) {
            // Add sale number row
        ?>
            <tr>
                <td colspan="7" class="text-center bg-gray"><?php echo htmlspecialchars($val['sale_no']); ?></td>
            </tr>
        <?php
            $saleNos[] = $val['sale_no'];
        }
        ?>
        <tr>
            <td><?php echo htmlspecialchars($val['buyer_name']); ?></td>
            <td class="text-center"><?php echo number_format($leafQuantity, 2); ?></td>
            <td class="text-center"><?php echo number_format($val['leaf_avg_price'], 2); ?></td>
            <td class="text-center"><?php echo number_format($dustQuantity, 2); ?></td>
            <td class="text-center"><?php echo number_format($val['dust_avg_price'], 2); ?></td>
            <td class="text-center"><?php echo number_format($grandTotalQuantity, 2); ?></td>
            <td class="text-center"><?php echo number_format($grandTotalAvgPrice, 2); ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>
<tfoot>
    <tr>
        <td><b>Grand Total</b></td>
        <td class="text-center"><b><?php echo number_format($grandLeafQuantity, 2); ?></b></td>
        <td></td>
        <td class="text-center"><b><?php echo number_format($grandDustQuantity, 2); ?></b></td>
        <td></td>
        <td class="text-center"><b><?php echo number_format($totalGrandQuantity, 2); ?></b></td>
        <td class="text-center"><b><?php //echo number_format($totalGrandBidPrice / ($totalGrandQuantity ?: 1), 2); 
                                    ?></b></td>
    </tr>
</tfoot>