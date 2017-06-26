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
    <?php include 'editButton.php'; ?>

    </td>
  </tr>
  <?php
}
?>