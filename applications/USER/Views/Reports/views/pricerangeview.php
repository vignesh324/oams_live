<?php

// Define price ranges
$price_ranges = [
    '50-59' => [50, 59],
    '60-69' => [60, 69],
    '70-79' => [70, 79],
    '80-89' => [80, 89],
    '90-99' => [90, 99],
    '100-109' => [100, 109],
    '110-119' => [110, 119],
    '120-129' => [120, 129],
    '130-139' => [130, 139],
    '140-150' => [140, 150],
    '>150' => [151, PHP_INT_MAX],
];

$quantities = [
    'leaf' => array_fill_keys(array_keys($price_ranges), 0),
    'dust' => array_fill_keys(array_keys($price_ranges), 0)
];

// Calculate quantities
foreach ($response_data as $item) {
    $grade = $item['grade_type'] == 1 ? 'leaf' : 'dust';
    foreach ($price_ranges as $range => [$min, $max]) {
        if ($item['bid_price'] >= $min && $item['bid_price'] <= $max) {
            $quantities[$grade][$range] += $item['qty'];
        }
    }
}

// Calculate totals
$totals = [
    'leaf' => array_sum($quantities['leaf']),
    'dust' => array_sum($quantities['dust']),
    'combined' => array_sum($quantities['leaf']) + array_sum($quantities['dust']),
];

?>

<thead>
    <tr>
        <th>Price Range</th>
        <th>Dust Quantity</th>
        <th>Dust Sold %</th>
        <th>Leaf Quantity</th>
        <th>Leaf Sold %</th>
        <th>Total Sold Qty</th>
        <th>Total Sold %</th>
    </tr>
</thead>
<tbody>
    <?php 
    
    foreach ($price_ranges as $range => [$min, $max]) : ?>
        <?php
        $dust_qty = $quantities['dust'][$range];
        $leaf_qty = $quantities['leaf'][$range];
        $total_qty = $dust_qty + $leaf_qty;
        $dust_percent = $totals['dust'] > 0 ? ($dust_qty / $totals['dust']) * 100 : 0;
        $leaf_percent = $totals['leaf'] > 0 ? ($leaf_qty / $totals['leaf']) * 100 : 0;
        $total_sold_percent = (isset($totals['combined']) && $totals['combined'] > 0) ? ($total_qty / $totals['combined']) * 100 : 0;
        ?>
        <tr>
            <td><?php echo $range; ?></td>
            <td><?php echo number_format($dust_qty, 2); ?></td>
            <td><?php echo number_format($dust_percent, 2); ?>%</td>
            <td><?php echo number_format($leaf_qty, 2); ?></td>
            <td><?php echo number_format($leaf_percent, 2); ?>%</td>
            <td><?php echo number_format($total_qty, 2); ?></td>
            <td><?php echo number_format($total_sold_percent, 2); ?>%</td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><strong>Grand Total</strong></td>
        <td><strong><?php echo number_format($totals['dust'], 2); ?></strong></td>
        <td><strong><?php echo (isset($dust_percent) && $dust_percent > 0) ? '100.00%' : '0.00%'; ?></strong></td>
        <td><strong><?php echo number_format($totals['leaf'], 2); ?></strong></td>
        <td><strong><?php echo (isset($leaf_percent) && $leaf_percent > 0) ? '100.00%' : '0.00%'; ?></strong></td>
        <td><strong><?php echo number_format($totals['combined'], 2); ?></strong></td>
        <td><strong><?php echo (isset($total_sold_percent) && $total_sold_percent > 0) ? '100.00%' : '0.00%'; ?></strong></td>
    </tr>
</tbody>