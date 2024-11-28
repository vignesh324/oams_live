<style>
    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 60%;
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

    .sequence {
        width: 30px;
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
                // echo '<pre>';print_r($grade_list);exit;
                foreach ($grade_list as $key => $grade) :
                ?>
                    <li class="item bg-green">
                        <input type="hidden" class="sequence" value="<?php echo $key + 1; ?>" readonly>
                        <input type="hidden" class="id" value="<?php echo $grade['grade_id']; ?>">
                        <span><?php echo $grade['grade_name']; ?></span>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
        <div class="timeline">
            <!-- Jquery -->
        </div>
        <input type="hidden" id="garden_id" name="garden_id" value="<?php echo $garden_id; ?>">
        <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id; ?>">
    </div>
    <!-- /.card-body -->
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

</div>




<script>
    $(function() {
        $("#sortable").sortable({
            axis: 'y',
            items: 'li',
            stop: function(event, ui) {
                $("#sortable li").each(function(index) {
                    $(this).find(".sequence").val(index + 1);
                });
                var dataToSend = [];
                $("#sortable li").each(function(index) {
                    var sequence = $(this).find(".sequence").val();
                    var id = $(this).find(".id").val();
                    var text = $(this).find("span").text();
                    var garden_id = $("#garden_id").val();
                    var category_id = $("#category_id").val();
                    dataToSend.push({
                        sequence: sequence,
                        grade_id: id,
                        garden_id: garden_id,
                        category_id: category_id
                    });
                });


                $.ajax({
                    url: '<?= @basePath ?>USER/Garden/reOrder',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(dataToSend),
                    success: function(response) {
                        console.log('AJAX request successful');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request error:', error);
                    }
                });
            }
        });
        $("#sortable").disableSelection();
    });
</script>