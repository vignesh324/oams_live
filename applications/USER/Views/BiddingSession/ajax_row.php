<table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Garden Name</th>
                                <th>Grade</th>
                                <th>No Of bags</th>
                                <th>Base Price</th>
                                <th>Reserve Price</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              //$auction_data = session('auction_data');
                              if (isset($auction_data))
                              {
                              foreach(@$auction_data as $key => $auction_items)
                              {
                              ?>
                              <tr>
                                <td><?php echo $key+1;?></td>
                                <td><?=$auction_items['garden_name'];?></td>
                                <td><?=$auction_items['grade_name'];?></td>
                                <td><?=$auction_items['auction_quantity'];?></td>
                                <td><?=$auction_items['base_price'];?></td>
                                <td><?=$auction_items['reverse_price'];?></td>
                                <td>
                                    <input type="hidden" class="form-control" name="unique_id" id="unique_id<?php echo $key;?>" value="<?=$auction_items['unique_id'];?>"  placeholder="High Price">
                                    <button type="button" class="btn btn-danger delete_btn" id="ajax_auction_quantity_<?php echo $key;?>"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                              <?php 
                              }
                            } 
                              ?>
                            </tbody>
                          </table>