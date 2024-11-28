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
    if (isset($response_data) && is_array($response_data) && count($response_data) > 0) {
        $grandTotalQty = 0;
        $grandTotalPrice = 0;
        $grandTotalOfferedQty = 0;
        $currentGarden = '';
        $currentGardenQty = 0;
        $currentGardenPrice = 0;
        $currentGardenOfferedQty = 0;
        $currentGardenRowCount = 0;

        foreach ($response_data as $index => $value) {
            $offeredQty = $value['sold_quantity'] + $value['unsold_quantity'];
            $gardenTotalQty = $value['sold_quantity'];
            $gardenTotalPrice = $value['sold_quantity'] * (isset($value['avg_sold_price']) ? (float)$value['avg_sold_price'] : 0);
            
            // Update grand totals
            $grandTotalQty += $gardenTotalQty;
            $grandTotalPrice += $gardenTotalPrice;
            $grandTotalOfferedQty += $offeredQty;

            // Check for garden change
            if ($currentGarden !== $value['garden_name']) {
                if ($currentGarden !== '') {
                    // Add previous garden total row
                    $avgGardenTotalPrice = $currentGardenQty ? $currentGardenPrice / $currentGardenQty : 0;
                    ?>
                    <tr>
                        <td><b>GARDEN TOTAL</b></td>
                        <td></td>
                        <td><b><?= number_format($currentGardenOfferedQty, 2); ?></b></td>
                        <td><b><?= number_format($currentGardenQty, 2); ?></b></td>
                        <td><b><?= number_format($avgGardenTotalPrice, 2); ?></b></td>
                        <td><b>100.00</b></td>
                    </tr>
                    <?php
                }
                // Reset garden totals
                $currentGarden = $value['garden_name'];
                $currentGardenQty = 0;
                $currentGardenPrice = 0;
                $currentGardenOfferedQty = 0;
                $currentGardenRowCount = 0;
            }

            $currentGardenQty += $gardenTotalQty;
            $currentGardenPrice += $gardenTotalPrice;
            $currentGardenOfferedQty += $offeredQty;
            $currentGardenRowCount++;
            $percentage = $currentGardenQty ? ($offeredQty / $currentGardenQty) * 100 : 0;
            ?>
            <tr>
                <?php if ($currentGardenRowCount === 1) : ?>
                    <td rowspan="<?= count(array_filter($response_data, function ($item) use ($value) {
                        return $item['garden_name'] === $value['garden_name'];
                    })); ?>"><?= htmlspecialchars($value['garden_name']); ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($value['grade_name']); ?></td>
                <td><?= $offeredQty > 0 ? number_format($offeredQty, 2) : '-'; ?></td>
                <td><?= $gardenTotalQty > 0 ? number_format($gardenTotalQty, 2) : '-'; ?></td>
                <td><?= isset($value['avg_sold_price']) && $value['avg_sold_price'] > 0 ? number_format($value['avg_sold_price'], 2) : '-'; ?></td>
                <td><?= number_format($percentage, 2); ?></td>
            </tr>
        <?php
        }

        // Add last garden total
        if ($currentGarden !== '') {
            $avgGardenTotalPrice = $currentGardenQty ? $currentGardenPrice / $currentGardenQty : 0;
            ?>
            <tr>
                <td><b>GARDEN TOTAL</b></td>
                <td></td>
                <td><b><?= number_format($currentGardenOfferedQty, 2); ?></b></td>
                <td><b><?= number_format($currentGardenQty, 2); ?></b></td>
                <td><b><?= number_format($avgGardenTotalPrice, 2); ?></b></td>
                <td><b>100.00</b></td>
            </tr>
            <?php
        }

        // Calculate and display grand total
        $avgGrandTotalPrice = $grandTotalQty ? $grandTotalPrice / $grandTotalQty : 0;
        ?>
<tfoot>
    <tr>
        <td><b>Grand TOTAL</b></td>
        <td></td>
        <td><b><?= number_format($grandTotalOfferedQty, 2); ?></b></td>
        <td><b><?= number_format($grandTotalQty, 2); ?></b></td>
        <td><b><?= number_format($avgGrandTotalPrice, 2); ?></b></td>
        <td></td>
    </tr>
</tfoot>
<?php
    } else {
?>
    <tr>
        <td colspan="6" class="text-center">No data found</td>
    </tr>
<?php
    }
?>
</tbody>
