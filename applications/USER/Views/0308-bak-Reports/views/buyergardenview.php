<thead>
    <tr>
        <th>Garden Name</th>
        <th>Grade Name</th>
        <th>Offered Qty (Qty in Kgs)</th>
        <th>Sold Qty (Qty in Kgs)</th>
        <th>Avg Price (in Rs.)</th>
        <th>%</th>
    </tr>
</thead>
<tbody>
    <?php
    // print_r($response_data);
    // exit;

    if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
        $sum = 0;
        $grandTotalSoldQty = 0;
        foreach ($response_data as $value) {
            $sum = array_sum(array_column($value['grades'], 'sold_quantity'));
            $totalSoldQty = 0;
            $gTotalAvgPrice = 0;

            // echo $sum;exit;
            foreach ($value['grades'] as $grades) {
                $offeredQty = $grades['sold_quantity'] + $grades['unsold_quantity'];
                $percentage = (isset($sum) && $sum > 0) ? ($grades['sold_quantity'] / $sum) * 100 : 0;
                $totalSoldQty += $grades['sold_quantity'];
                $totalAvgPrice = (isset($totalSoldQty) && $totalSoldQty > 0) ? $grades['avg_sold_price'] / $totalSoldQty : 0;
                $gTotalAvgPrice += $totalAvgPrice;

    ?>
                <tr>
                    <td rowspan="<?php count($value['grades']); ?>"><?php echo $value['garden_name']; ?></td>
                    <td><?php echo $grades['grade_name']; ?></td>
                    <td><?php echo $offeredQty; ?></td>
                    <td><?php echo number_format($grades['sold_quantity'], 2); ?></td>
                    <td><?php echo number_format($grades['avg_sold_price'], 2) ?? '-'; ?></td>
                    <td><?php echo number_format($percentage, 2); ?></td>
                </tr>
            <?php
            }
            $grandTotalSoldQty += $totalSoldQty;
            $grandTotalAvgPrice = $grandTotalSoldQty ? number_format($gTotalAvgPrice / $grandTotalSoldQty) : 0;

            ?>

            <tr>
                <td colspan="2"><b>Garden Total</b></td>
                <td></td>
                <td><b><?php echo number_format($totalSoldQty, 2); ?></b></td>
                <td><b><?php echo number_format($totalAvgPrice, 2); ?></b></td>
                <td><strong><?php echo (isset($percentage) && $percentage > 0) ? '100.00%' : '0.00%'; ?></strong></td>
                </tr>
        <?php
        }
        ?>

        <tr>
            <td colspan="2"><b>Grand Total</b></td>
            <td></td>
            <td><b><?php echo $grandTotalSoldQty; ?></b></td>
            <td><b><?php echo $grandTotalAvgPrice; ?></b></td>
            <td></td>
        </tr>

    <?php
    }
    ?>


</tbody>