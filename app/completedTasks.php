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
 <tr id='mainRow'>
  <td >
    <?=$startDay;?>
  </td>
  <td class='upperRow'>
    <?=$tasklist['task'];?>
  </td>
  <td>
    <?=$startTime;?>
  </td>
  <td class='align-middle' >
   <?=$endTime;?>
  </td>
  <td class='buttons' rowspan="2">
    <?php include 'editButton.php'; ?>
  </td>
</tr>
<tr>
  <td id='commentRow' class='upperRow border-top-0' colspan="4">Comments: <?=$tasklist['comment'];?></td>
</tr>
  <?php
}
?>