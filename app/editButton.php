<div class="btn-group" role="group">



   <!-- Edit task button  -->
   <form method="post" action="">
      <input name="editWinId" value="<?=$tasklist['task_id'];?>" type="hidden">
      <input name="editWinCatId" value="<?=$tasklist['cat_id'];?>" type="hidden">
      <button class='btn btn-outline-warning mr-2' type="button" id='editTask' class="close" aria-label="Complete" data-toggle="modal" data-target="#modal<?=$tasklist['task_id'];?>">
        <span aria-hidden="true"><i class="fa fa-2x fa-pencil-square-o" aria-hidden="true"></i></span>
      </button>
    <!-- Modal -->
      <div class="modal fade" id="modal<?=$tasklist['task_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Time</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="input-group editWindow">
                <div class="input-group-addon">Task</div>
                <input type='text' name='editWinTask' value='<?=$tasklist['task'];?>' />
              </div>
              <div class="input-group editWindow">
            <div class="input-group-addon">Start Date</div>
              <input type="date" name='editWinStartDate' value='<?=$editWindowStartDate;?>'>
              </div>
              <div class="input-group editWindow">
            <div class="input-group-addon">Start Time</div>
              <input type="time" name='editWinStartTime' value="<?=$editWindowStartTime;?>" />
              </div>
              <div class="input-group editWindow">
          <div class="input-group-addon">End Date</div>
              <input type="date" name='editWinEndDate' value="<?php if ($complete) {echo($editWindowEndDate);} ?>" >
              </div>
              <div class="input-group editWindow">
          <div class="input-group-addon">End Time</div>
              <input type="time" name='editWinEndTime' value="<?php if ($complete) {echo($editWindowEndTime);} ?>" >
              </div>
              <div class="input-group editWindow">
            <div class="input-group-addon">Comment:</div>
              <textarea rows='4' name='editWinComments' style='width: 100%;' ><?=$tasklist['comment'];?></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </form>


<!-- Remove Button     -->
    <form method="post" action="">
      <input name="removeTask" value="<?=$tasklist['task_id'];?>" type="hidden">
      <button class = 'btn btn-outline-danger' type="submit " id='removeTask' class="close" aria-label="Remove">
        <span aria-hidden="true" ><i class="fa fa-2x fa-times-circle-o" aria-hidden="true"></i></span>
      </button>
    </form>

  </div>