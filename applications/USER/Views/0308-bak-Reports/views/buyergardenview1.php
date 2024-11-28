<thead>
    <tr>
        <th rowspan="2">GARDEN</th>
        <th rowspan="2">GRADE</th>
        <th colspan="4">FOR THE PERIOD OR SALE</th>
        <th colspan="4">UPTO THE SALE OR PERIOD</th>
    </tr>
    <tr>
        <th>OFFERED QTY</th>
        <th>SOLD QTY</th>
        <th>AVG PRICE</th>
        <th>%</th>
        <th>OFFERED QTY</th>
        <th>SOLD QTY</th>
        <th>AVG PRICE</th>
        <th>%</th>
    </tr>
</thead>
<tbody>
    <?php
    // print_r($response_data);
    // exit;

    if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
        $sum = 0;
        $sumUp = 0;
        $grandTotalSoldQty = 0;
        $grandTotalSoldQtyUp = 0;
        foreach ($response_data as $value) {
            $sum = array_sum(array_column($value['grades'], 'sold_quantity'));
            $sumUp = array_sum(array_column($value['grades'], 'sold_quantity'));
            $totalSoldQty = 0;
            $gTotalAvgPrice = 0;

            $totalSoldQtyUp = 0;
            $gTotalAvgPriceUp = 0;

            // echo $sum;exit;
            foreach ($value['grades'] as $grades) {
                $offeredQty = $grades['sold_quantity'] + $grades['unsold_quantity'];
                $percentage = (isset($sum) && $sum > 0) ? ($grades['sold_quantity'] / $sum) * 100 : 0;
                $totalSoldQty += $grades['sold_quantity'];
                $totalAvgPrice = (isset($totalSoldQty) && $totalSoldQty > 0) ? number_format($grades['avg_sold_price'] / $totalSoldQty) : 0;
                $gTotalAvgPrice += $totalAvgPrice;

                $offeredQtyUp = $grades['upsale_sold_quantity'] + $grades['upsale_unsold_quantity'];
                $percentageUp = (isset($sumUp) && $sumUp > 0) ? ($grades['upsale_sold_quantity'] / $sumUp) * 100 : 0;
                $totalSoldQtyUp += $grades['upsale_sold_quantity'];
                $totalAvgPriceUp = (isset($totalSoldQtyUp) && $totalSoldQtyUp > 0) ? number_format($grades['upsale_avg_sold_price'] / $totalSoldQtyUp) : 0;
                $gTotalAvgPriceUp += $totalAvgPriceUp;

    ?>
                <tr>
                    <td rowspan="<?php count($value['grades']); ?>"><?php echo $value['garden_name']; ?></td>
                    <td><?php echo $grades['grade_name']; ?></td>
                    <td><?php echo number_format($offeredQty, 2); ?></td>
                    <td><?php echo number_format($grades['sold_quantity'], 2); ?></td>
                    <td><?php echo number_format($grades['avg_sold_price'], 2) ?? '-'; ?></td>
                    <td><?php echo number_format($percentage, 2); ?></td>
                    <td><?php echo number_format($offeredQtyUp, 2); ?></td>
                    <td><?php echo number_format($grades['upsale_sold_quantity'], 2); ?></td>
                    <td><?php echo number_format($grades['upsale_avg_sold_price'], 2) ?? '-'; ?></td>
                    <td><?php echo number_format($percentageUp, 2); ?></td>
                </tr>
            <?php
            }
            $grandTotalSoldQty += $totalSoldQty;
            $grandTotalAvgPrice = (isset($grandTotalSoldQty) && $grandTotalSoldQty > 0) ? number_format($gTotalAvgPrice / $grandTotalSoldQty) : 0;

            $grandTotalSoldQtyUp += $totalSoldQtyUp;
            $grandTotalAvgPriceUp = (isset($grandTotalSoldQtyUp) && $grandTotalSoldQtyUp > 0) ? number_format($gTotalAvgPriceUp / $grandTotalSoldQtyUp) : 0;

            ?>

            <tr>
                <td colspan="2"><b>Garden Total</b></td>
                <td></td>
                <td><b><?php echo number_format($totalSoldQty, 2); ?></b></td>
                <td><b><?php echo number_format($totalAvgPrice, 2); ?></b></td>
                <td><strong><?php echo (isset($percentage) && $percentage > 0) ? '100.00%' : '0.00%'; ?></strong></td>
                <td></td>
                <td><b><?php echo number_format($totalSoldQtyUp, 2); ?></b></td>
                <td><b><?php echo number_format($totalAvgPriceUp, 2); ?></b></td>
                <td><strong><?php echo (isset($percentageUp) && $percentageUp > 0) ? '100.00%' : '0.00%'; ?></strong></td>
            </tr>
        <?php
        }
        ?>

        <tr>
            <td colspan="2"><b>Grand Total</b></td>
            <td></td>
            <td><b><?php echo number_format($grandTotalSoldQty, 2); ?></b></td>
            <td><b><?php echo number_format($grandTotalAvgPrice, 2); ?></b></td>
            <td></td>
            <td></td>
            <td><b><?php echo number_format($grandTotalSoldQtyUp, 2); ?></b></td>
            <td><b><?php echo number_format($grandTotalAvgPriceUp, 2); ?></b></td>
            <td></td>
        </tr>

    <?php
    }
    ?>


</tbody>