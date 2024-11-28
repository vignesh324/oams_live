<form id="view-inward-return-form">
    <div class="modal-header">
        <h4 class="modal-title">View Inward Return</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td><strong>Invoice No</strong></td>
                        <td><?php echo $response_data['inward_invoice_no'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Date</strong></td>
                        <td><?php echo $response_data['date'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Grade Name</strong></td>
                        <td><?php echo $response_data['grade_name'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Bag Type</strong></td>
                        <td>
                            <?php if ($response_data['bag_type'] == 1) {
                                echo "Bag";
                            } else {
                                echo "Chest";
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Quantity</strong></td>
                        <td><?php echo $response_data['no_of_bags'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Serial Number From</strong></td>
                        <td><?php echo $response_data['sno_from'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Serial Number To</strong></td>
                        <td><?php echo $response_data['sno_to'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Weight per C/B (Kgs) Nett</strong></td>
                        <td><?php echo $response_data['weight_net'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Weight per C/B (Kgs) Tare</strong></td>
                        <td><?php echo $response_data['weight_tare'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Weight per C/B (Kgs) Gross</strong></td>
                        <td><?php echo $response_data['weight_gross'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Return Quantity</strong></td>
                        <td><?php echo $response_data['return_quantity'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Reason</strong></td>
                        <td><?php echo $response_data['reason'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>