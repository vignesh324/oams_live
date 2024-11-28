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
    <?php foreach ($response_data['invoiceItems'] as $data) :
        // print_r($response_data);exit;
        ?>
        <?php
        $leafQuantity = $data['leaf_total_quantity'];
        $dustQuantity = $data['dust_total_quantity'];
        $leafBidPrice = $data['leaf_total_bid_price'];
        $dustBidPrice = $data['dust_total_bid_price'];

        $grandTotalQuantity = $leafQuantity + $dustQuantity;
        $grandTotalBidPrice = $leafBidPrice + $dustBidPrice;
        $grandTotalAvgPrice = $grandTotalQuantity ? $grandTotalBidPrice / $grandTotalQuantity : 0;

        // Update grand totals
        $grandLeafQuantity += $leafQuantity;
        $grandDustQuantity += $dustQuantity;
        $totalGrandQuantity += $grandTotalQuantity;
        $totalGrandBidPrice += $grandTotalBidPrice;

        // Check for sale number row
        if (!in_array($data['sale_no'], $saleNos)) {
            // Add sale number row
        ?>
            <tr>
                <td colspan="7" class="text-center bg-gray"><?php echo htmlspecialchars($data['sale_no']); ?></td>
            </tr>
        <?php
            $saleNos[] = $data['sale_no'];
        }
        ?>
        <tr>
            <td><?php echo htmlspecialchars($data['buyer_name']); ?></td>
            <td class="text-center"><?php echo number_format($leafQuantity, 2); ?></td>
            <td class="text-center"><?php echo number_format($leafQuantity ? $leafBidPrice / $leafQuantity : 0, 2); ?></td>
            <td class="text-center"><?php echo number_format($dustQuantity, 2); ?></td>
            <td class="text-center"><?php echo number_format($dustQuantity ? $dustBidPrice / $dustQuantity : 0, 2); ?></td>
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
        <td class="text-center"><b><?php //echo number_format($totalGrandBidPrice / ($totalGrandQuantity ?: 1), 2); ?></b></td>
    </tr>   
</tfoot>