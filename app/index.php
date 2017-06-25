<!DOCTYPE html>
<html>
<head>
  <title>Task Tracker 2000</title>
  <link href="https://fonts.googleapis.com/css?family=Share+Tech" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<?php

if (isset($_GET['taskName']) && isset($_GET['cat_id'])) {
  $safeTask = htmlentities($_GET['taskName']);
  $safeId = htmlentities($_GET['cat_id']);
  addTask(getDb(), $safeTask, $safeId);
}

if (isset($_GET['editWinTask']) && isset($_GET['editWinId']) && isset($_GET['editWinStartDate']) 
  && isset($_GET['editWinStartTime']) && isset($_GET['editWinEndDate']) && isset($_GET['editWinEndTime']) 
  && isset($_GET['editWinComments']) && isset($_GET['editWinCatId'])) {
  $safeTask = htmlentities($_GET['editWinTask']);
  $safeId = htmlentities($_GET['editWinId']);
  $safeCatId = htmlentities($_GET['editWinCatId']);
  $safeStartDate = htmlentities($_GET['editWinStartDate']);
  $safeStartTime = htmlentities($_GET['editWinStartTime']);
  $safeEndDate = htmlentities($_GET['editWinEndDate']);
  $safeEndTime = htmlentities($_GET['editWinEndTime']);
  $safeComments = htmlentities($_GET['editWinComments']);
  $startTime = $safeStartDate . $safeStartTime;
  $endTime = $safeEndDate . $safeEndTime;
  editExistingTask(getDb(), $safeTask, $safeId, $safeCatId, $startTime, $endTime, $safeComments);
}

if (isset($_GET['newCategory'])) {
  $safeCategory = htmlentities($_GET['newCategory']);
  addCategory(getDb(), $safeCategory);
}

if (isset($_GET['removeTask'])) {
  $safeTask = htmlentities($_GET['removeTask']);
  removeTask(getDb(), $safeTask);
}
if (isset($_GET['completeTask'])) {
  $safeTask = htmlentities($_GET['completeTask']);
  completeTask(getDb(), $safeTask);
}

function getDb() {
  $db = pg_connect("host=localhost port=5432 dbname=timetracking user=tracker password=track");
  return $db;
}

function addTask($db, $taskName, $cat_id) {
  
  $timestamp = date("Y-m-d H:i:s");
  $stmt = 'INSERT INTO taskList (task, cat_id, time_start) VALUES
      (\''.$taskName.'\',\''.$cat_id.'\',\''.$timestamp.'\');';
  $result = pg_query($stmt);
}

function addCategory($db, $cat_name) {
  $stmt = 'INSERT INTO category (cat_name) VALUES
      (\''.$cat_name.'\');';
  $result = pg_query($stmt);
}

function getCategory($db) {
  $request = pg_query(getDb(), "SELECT * FROM category ORDER BY cat_name");
  return pg_fetch_all($request);
}
function completeTask($db, $id) {
  $timestamp = date("Y-m-d H:i:s");
  // $duration = Add Duration of activity to table
  $stmt   = 'UPDATE taskList SET time_end= \''.$timestamp.'\' WHERE id='.$id;
  $result = pg_query($stmt);
}

function editExistingTask($db, $task, $id, $cat_id, $start, $end, $comments) {
  //$timestamp = date("Y-m-d H:i:s");
  // $duration = Add Duration of activity to table
  //UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', time_end = \''.$end.'\',
  //comments = \''.$comments.'\' WHERE id=' .$id;

  $stmt   = 'UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', time_end = \''.$end.'\', comments = \''.$comments.'\' WHERE id=' .$id;
  $result = pg_query($stmt);
}

function getActiveTasks($db) {
  $request = pg_query(getDb(), "SELECT * FROM taskList WHERE time_end is null ORDER BY time_start DESC");
  return pg_fetch_all($request);
}

function getCompletedTasks($db) {
  $request = pg_query(getDb(), "SELECT * FROM taskList WHERE time_end is not null ORDER BY time_end DESC");
  return pg_fetch_all($request);
}

function removeTask($db, $id) {
  $stmt   = "DELETE FROM taskList WHERE id=".$id;
  $result = pg_query($stmt);
}

?>
<div class='container col-md-8'>

<h1><em>Time Tracker 2000</em></h1>

<div class='mx-auto center-block text-center'>

<!-- Choose a category to begin tracking -->

<form class="form-inline mx-auto" method="get" action="">

  <label class="sr-only" for="task">Select Task</label>
  <div class="input-group input-group-lg mx-auto">
    <!-- <div class="input-group-addon">Task</div> -->
  <input type="text" class="form-control mb-2 mb-sm-0" id="taskName" name="taskName" placeholder="What are you working on?">
  <select class="form-control mb-2 mb-sm-0" id="cat_id" name="cat_id">

    <?php foreach (getCategory(getDb()) as $catList) {?>
    
    <option value="<?=$catList['id'];?>"><?=$catList['cat_name'];?></option>

      <?php } ?>
    </select>
  <span class='input-group-btn'>
   <button type="submit" class="btn btn-primary">Punch the Clock</button>
   </span>
  </div>
  <!-- <button type="submit" class="btn btn-primary">Punch the Clock</button> -->
</form>


<!-- Add new tasks to the dropdown -->

<form class="form-inline mx-auto" method="get" action="">

  <label class="sr-only" for="task">Add New Category</label>
  <div class="input-group mx-auto">

  <input type="text" class="form-control mb-2  mb-sm-0" id="newCategory" name="newCategory" placeholder="Add new Category...">
  <span class="input-group-btn">
        <button class="btn btn-secondary" type="submit">Go!</button>
      </span>
  </div>


</form>
</div>


<!-- Active Tasks Being Tracked -->


<table class='table'>
<thead>
<tr>
  <th>Date</th>
  <th>Project Task</th>
  <th>Start</th>
  <th>Stop</th>
  <th class='buttons'></th>
</tr>
</thead>

<tbody>
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
  <div class="btn-group" role="group">



   <!-- Edit task button  -->
   <form method="get" action="">
      <input name="editWinId" value="<?=$tasklist['id'];?>" type="hidden">
      <input name="editWinCatId" value="<?=$tasklist['cat_id'];?>" type="hidden">
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
              <input type="date" name='editWinEndDate'>
              </div>
              <div class="input-group editWindow">
          <div class="input-group-addon">End Time</div>
              <input type="time" name='editWinEndDate'  />
              </div>
              <div class="input-group editWindow">
            <div class="input-group-addon">Comment:</div>
              <textarea rows='4' name='editWinComments' style='width: 100%;' placeholder="Fam, what are you doin?"></textarea>
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
<tr>
  <td class='innerRow' colspan="2">tags go here</td>
</tr>
  <?php
}
?>
<!-- Populate table with completed tasks -->

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
</tbody>



</table>

</div>


</body>
</html>