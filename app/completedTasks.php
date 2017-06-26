<?php foreach (getCompletedTasks(getDb()) as $tasklist) {
  $db = $tasklist['time_start'];
  $startDay = date("M d", date(strtotime($db)));
  $startTime = date("h:ia", date(strtotime($db)));
  $editWindowStartTime = date("H:i", date(strtotime($db)));
  $editWindowStartDate = date("Y-m-d", date(strtotime($db)));
  $db = $tasklist['time_end'];
  $editWindowEndTime = date("H:i", date(strtotime($db)));
  $editWindowEndDate = date("Y-m-d", date(strtotime($db)));
  $endTime = date("h:ia", date(strtotime($db)));
  ?>
  <tr>
  <td class='align-middle'>
    <?=$startDay;?>
  </td>
  <td class='align-middle'>
    <?=$tasklist['task'];?>
  </td>
  <td class='align-middle'>
    <?=$startTime;?>
  </td >
  <td class='align-middle'>
    <?=$endTime;?>
  </td>
  <td class='buttons'>
    <div class="btn-group" role="group">
      <!-- Edit task button  -->
     <form method="get" action="">
        <input name="editTask" value="<?=$tasklist['id'];?>" type="hidden">
        <button class='btn btn-outline-warning' type="button" id='editTask' class="close" aria-label="Complete" data-toggle="modal" data-target="#modal<?=$tasklist['id'];?>">
          <span aria-hidden="true">&#x270E;</span>
        </button>
      <!-- Modal -->
  <div class="modal fade" id="modal<?=$tasklist['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <input type='text' value='<?=$tasklist['task'];?>' />
          </div>
          <div class="input-group editWindow">
        <div class="input-group-addon">Start Date</div>
          <input type="date" value='<?=$editWindowStartDate;?>'>
          </div>
          <div class="input-group editWindow">
        <div class="input-group-addon">Start Time</div>
          <input type="time" value="<?=$editWindowStartTime?>" />
          </div>
          <div class="input-group editWindow">
      <div class="input-group-addon">End Date</div>
          <input type="date" value='<?=$editWindowEndDate;?>'>
          </div>
          <div class="input-group editWindow">
      <div class="input-group-addon">End Time</div>
          <input type="time" name="usr_time" value='<?=$editWindowEndTime;?>' />
          </div>
          <div class="input-group editWindow">
        <div class="input-group-addon">Comment:</div>
          <textarea rows='4' style='width: 100%;' placeholder="Fam, what are you doin?"></textarea>
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
      <form method="get" action="">
        <input name="removeTask" value="<?=$tasklist['id'];?>" type="hidden">
        <button class = 'btn btn-outline-danger' type="submit " id='removeTask' class="close" aria-label="Remove">
          <span aria-hidden="true" >&times;</span>
        </button>
      </form>

    </div>

    </td>
  </tr>
  <?php
}
?>