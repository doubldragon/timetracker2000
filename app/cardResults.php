<?php 
  foreach (getActiveTasks(getDb()) as $tasklist) {
  $db = $tasklist['time_start'];
  $startDay = date("M d", date(strtotime($db)));
  $startTime = date("h:ia", date(strtotime($db)));
  $editWindowStartTime = date("H:i", date(strtotime($db)));
  $editWindowStartDate = date("Y-m-d", date(strtotime($db)));
  if ($tasklist['time_end'] == NULL){
    $end= strtotime(date("Y-m-d H:i:s"));
  }else {
    $end = strtotime($tasklist['time_end']);
  }
 
  $duration = round(($end-strtotime($tasklist['time_start']))/3600, 1);
  ?>


<div class="card mb-3 ml-3">
  

  <div class='card-header'>
    <h5><?=$startDay;?> - </h5>
    <h5 class='mb-0 mt-2'><?=$tasklist['task'];?></h5>
    <span class='pull-right' >
    <span class='categoryName mr-3'><h6><em><?=$tasklist['cat_name'];?></em></h6></span>
    <form  class='checkmark' method="get" action="">
        <input name="completeTask" value="<?=$tasklist['task_id'];?>" type="hidden">
        <button class='btn btn-outline-success mr-1' type="submit button" id='completeTask' class="close" aria-label="Complete">
          <span aria-hidden="true" ><i class="fa fa-2x fa-flag-checkered" aria-hidden="true"></i></span>
        </button>
      </form>
      <?php include 'editButton.php'; ?>
      </span>
  </div>
  <div class="card-block ">
      Comments: <?=$tasklist['comment'];?>
    <span class='pull-right' >
      <?=$duration;?> hours
    </span>
  </div>
  
</div>





 



<?php
}
?>