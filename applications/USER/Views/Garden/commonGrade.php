<style>
    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    #sortable li {
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 1.2em;
        width: 90%;
    }

    #sortable li span {
        cursor: move;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title">Reorder Grade</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">

        <div class="menu-container">
            <ul id="sortable">

                <?php
                $count = 0;
                // print_r($grade_list);exit;

                foreach ($grade_list as $key => $grade) :
                ?>
                    <li class="item btn btn-success mb-2" style="width:100%">
                        <input type="hidden" class="sequence" value="<?php echo $count + 1; ?>" readonly>
                        <input type="hidden" class="id" value="<?php echo $grade['grade_id']; ?>">
                        <span><?php echo $grade['grade_name']; ?></span>
                    </li>
                <?php
                    $count++;
                endforeach;
                ?>

            </ul>
        </div>
        <div class="timeline">
            <!-- Jquery -->
        </div>
        <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id; ?>">
    </div>
    <!-- /.card-body -->
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" id="save-order" class="btn btn-primary">Save changes</button>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        // Initialize sortable functionality
        $("#sortable").sortable({
            axis: 'y',
            items: 'li',
            stop: function(event, ui) {
                $("#sortable li").each(function(index) {
                    $(this).find(".sequence").val(index + 1);
                });
            }
        });

        // Ensure the click event for saving the order is only bound once
        $(document).off("click", "#save-order").on("click", "#save-order", function(event) {
            event.preventDefault();

            // Immediately disable the button to prevent multiple clicks
            $("#save-order").attr("disabled", true);

            var dataToSend = [];
            $("#sortable li").each(function(index) {
                var sequence = $(this).find(".sequence").val();
                var id = $(this).find(".id").val();
                var category_id = $("#category_id").val();
                dataToSend.push({
                    sequence: sequence,
                    grade_id: id,
                    category_id: category_id
                });
            });

            console.log("Data to send:", dataToSend);

            // Make AJAX call to save the new order
            $.ajax({
                url: '<?= @basePath ?>USER/Garden/reOrderCategoryGrade',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(dataToSend),
                success: function(_response) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Success!',
                    //     text: 'Form submitted successfully',
                    // }).then((result) => {
                    //     if (result.isConfirmed || result.isDismissed) {
                    //         window.location.reload(); // Reload the page on success
                    //     }
                    // });
                    console.log('hii');
                },
                error: function(xhr, status, error) {
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Error!',
                    //     text: 'Internal Server Error',
                    // });
                    console.log('bye');

                },
                complete: function() {
                    // Re-enable the submit button after the request is complete
                    $("#save-order").attr("disabled", false);
                }
            });
        });
    });
</script>