<?php 
  foreach (getActiveTasks(getDb()) as $tasklist) {
  $db = $tasklist['time_start'];
  $startDay = date("M d", date(strtotime($db)));
  $startTime = date("h:ia", date(strtotime($db)));
  $editWindowStartTime = date("H:i", date(strtotime($db)));
  $editWindowStartDate = date("Y-m-d", date(strtotime($db)));
  

  ?>
  <tr >
  <td rowspan="2">
    <?=$startDay;?>
  </td>
  <td class='upperRow'>
    <strong><?=$tasklist['task'];?></strong>
  </td>
  <td>
    <?=$startTime;?>
  </td>
  <td class='align-middle' rowspan="2">
    <form method="get" action="">
        <input name="completeTask" value="<?=$tasklist['id'];?>" type="hidden">
        <button class='btn btn-outline-success' type="submit button" id='completeTask' class="close" aria-label="Complete">
          <span aria-hidden="true" >&#x2714;</span>
        </button>
      </form>
  </td>
  <td class='buttons' rowspan="2">
    <?php include 'editButton.php'; ?>
  </td>
</tr>
<tr>
  <td class='innerRow' colspan="2">tags go here</td>
</tr>
  <?php
}
?>