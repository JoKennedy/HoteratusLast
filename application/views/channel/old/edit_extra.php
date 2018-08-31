             <input type="hidden" name="reservation_id" value="<?php echo $result->reservation_id; ?>">
             <input type="hidden" name="extra_id" value="<?php echo $result->extra_id; ?>">
             <input type="hidden" name="curr_cha_id" value="<?php echo secure($result->channel_id); ?>">

              <input type="hidden" name="old_amount" value="<?php echo $result->amount; ?>">
                <div class="form-group">
                  <label class="control-label col-md-3">Description <span class="errors">*</span></label>
                  <div class="col-md-9">
                    <input class="form-control" name="description" value="<?php echo $result->description; ?>" type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Price <span class="errors">*</span></label>
                  <div class="col-md-9">
                    <input class="form-control" name="price" value="<?php echo $result->amount; ?>" type="text">
                  </div>
                </div>
