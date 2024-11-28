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
                $percentage = $grades['sold_quantity'] ? ($grades['sold_quantity'] / $sum) * 100 : 0;
                $totalSoldQty += $grades['sold_quantity'];
                $totalAvgPrice = $grades['sold_quantity'] ? number_format($grades['avg_sold_price'] / $totalSoldQty) : 0;
                $gTotalAvgPrice += $totalAvgPrice;

                $offeredQtyUp = $grades['upsale_sold_quantity'] + $grades['upsale_unsold_quantity'];
                $percentageUp = $grades['upsale_sold_quantity'] ? ($grades['upsale_sold_quantity'] / $sumUp) * 100 : 0;
                $totalSoldQtyUp += $grades['upsale_sold_quantity'];
                $totalAvgPriceUp = $grades['upsale_sold_quantity'] ? number_format($grades['upsale_avg_sold_price'] / $totalSoldQtyUp) : 0;
                $gTotalAvgPriceUp += $totalAvgPriceUp;

    ?>
                <tr>
                    <td rowspan="<?php count($value['grades']); ?>"><?php echo $value['garden_name']; ?></td>
                    <td><?php echo $grades['grade_name']; ?></td>
                    <td><?php echo $offeredQty; ?></td>
                    <td><?php echo $grades['sold_quantity']; ?></td>
                    <td><?php echo $grades['avg_sold_price'] ?? '-'; ?></td>
                    <td><?php echo $percentage; ?></td>
                    <td><?php echo $offeredQtyUp; ?></td>
                    <td><?php echo $grades['upsale_sold_quantity']; ?></td>
                    <td><?php echo $grades['upsale_avg_sold_price'] ?? '-'; ?></td>
                    <td><?php echo $percentageUp; ?></td>
                </tr>
            <?php
            }
            $grandTotalSoldQty += $totalSoldQty;
            $grandTotalAvgPrice = $grandTotalSoldQty ? number_format($gTotalAvgPrice / $grandTotalSoldQty) : 0;
           
            $grandTotalSoldQtyUp += $totalSoldQtyUp;
            $grandTotalAvgPriceUp = $grandTotalSoldQtyUp ? number_format($gTotalAvgPriceUp / $grandTotalSoldQtyUp) : 0;

            ?>

            <tr>
                <td colspan="2"><b>Garden Total</b></td>
                <td></td>
                <td><b><?php echo $totalSoldQty; ?></b></td>
                <td><b><?php echo $totalAvgPrice; ?></b></td>
                <td><b>100</b></td>
                <td></td>
                <td><b><?php echo $totalSoldQtyUp; ?></b></td>
                <td><b><?php echo $totalAvgPriceUp; ?></b></td>
                <td><b>100</b></td>
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
            <td></td>
            <td><b><?php echo $grandTotalSoldQtyUp; ?></b></td>
            <td><b><?php echo $grandTotalAvgPriceUp; ?></b></td>
            <td></td>
        </tr>

    <?php
    }
    ?>


</tbody>