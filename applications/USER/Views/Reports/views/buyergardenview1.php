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
    // print_r($response_data);exit;
    if (isset($response_data[0])) : ?>

        <?php foreach ($response_data as $value) : ?>
            <?php if (is_array($value)) : ?>

                <?php if (isset($value['grades']) && is_array($value['grades'])) : ?>
                    <?php foreach ($value['grades'] as $grades) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($value['garden_name']); ?></td>
                            <td><?php echo htmlspecialchars($grades['grade_name']); ?></td>
                            <td><?php echo number_format($grades['sold_quantity'] + $grades['unsold_quantity'], 2); ?></td>
                            <td><?php echo number_format($grades['sold_quantity'], 2); ?></td>
                            <td><?php echo number_format($grades['avg_sold_price'] ?? 0, 2); ?></td>
                            <td><?php echo number_format($grades['percentage'], 2); ?></td>
                            <td><?php echo number_format($grades['upsale_sold_quantity'] + $grades['upsale_unsold_quantity'], 2); ?></td>
                            <td><?php echo number_format($grades['upsale_sold_quantity'], 2); ?></td>
                            <td><?php echo number_format($grades['upsale_avg_sold_price'] ?? 0, 2); ?></td>
                            <td><?php echo number_format($grades['upsale_percentage'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Garden Total row -->
                <tr>
                    <td colspan="2"><b>Garden Total</b></td>
                    <td></td>
                    <td><b><?php echo number_format($value['total_sold_quantity'] ?? 0, 2); ?></b></td>
                    <td><b><?php echo number_format($value['total_avg_sold_price'] ?? 0, 2); ?></b></td>
                    <td><strong><?php echo (isset($value['total_sold_quantity']) && $value['total_sold_quantity'] > 0) ? '100.00%' : '0.00%'; ?></strong></td>
                    <td></td>
                    <td><b><?php echo number_format($value['total_upsale_sold_quantity'] ?? 0, 2); ?></b></td>
                    <td><b><?php echo number_format($value['total_upsale_avg_sold_price'] ?? 0, 2); ?></b></td>
                    <td><strong><?php echo (isset($value['total_upsale_sold_quantity']) && $value['total_upsale_sold_quantity'] > 0) ? '100.00%' : '0.00%'; ?></strong></td>
                </tr>

            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Grand Total row -->
        <tr>
            <td colspan="2"><b>Grand Total</b></td>
            <td></td>
            <td><b><?php echo number_format($response_data['grand_sold_quantity'] ?? 0, 2); ?></b></td>
            <td><b><?php echo number_format($response_data['grand_avg_sold_price'] ?? 0, 2); ?></b></td>
            <td></td>
            <td></td>
            <td><b><?php echo number_format($response_data['grand_upsale_sold_quantity'] ?? 0, 2); ?></b></td>
            <td><b><?php echo number_format($response_data['grand_avg_sold_price'] ?? 0, 2); ?></b></td>
            <td></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="10" style="text-align: center;"><b>No Data Found</b></td>
        </tr>
    <?php endif; ?>


</tbody>