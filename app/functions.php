
<?php

//////////////////////////
// Basic connection and retrieval functions
//////////////////////////

function getDb() {
  $db = pg_connect("host=localhost port=5432 dbname=timetracking user=tracker password=track");
  return $db;
}

function getCategory($db) {
  $request = pg_query(getDb(), "SELECT * FROM category ORDER BY cat_name");
  return pg_fetch_all($request);
}

function getActiveTasks($db) {
  $request = pg_query(getDb(), "SELECT * from taskList 
  JOIN category ON taskList.cat_id=category.id
  ORDER BY taskList.time_end DESC, taskList.time_start DESC;");
  return pg_fetch_all($request);
}


//////////////////////////
// Add Task to Database
//////////////////////////

if (isset($_POST['taskName']) && isset($_POST['cat_id'])) {
  $safeTask = htmlentities($_POST['taskName']);
  $safeId = htmlentities($_POST['cat_id']);
  addTask(getDb(), $safeTask, $safeId);
}

function addTask($db, $taskName, $cat_id) {
  
  $timestamp = date("Y-m-d H:i:s");
  $stmt = 'INSERT INTO taskList (task, cat_id, time_start) VALUES
      (\''.$taskName.'\',\''.$cat_id.'\',\''.$timestamp.'\');';
  $result = pg_query($stmt);
}

//////////////////////////
// Edit Task in Database
//////////////////////////

if (isset($_POST['editWinTask']) && isset($_POST['editWinId']) && isset($_POST['editWinStartDate']) 
  && isset($_POST['editWinStartTime']) //&& isset($_POST['editWinEndDate']) && isset($_POST['editWinEndTime']) 
  && isset($_POST['editWinComments']) && isset($_POST['editWinCatId'])) {

  $safeTask = htmlentities($_POST['editWinTask']);
  $safeId = htmlentities($_POST['editWinId']);
  $safeCatId = htmlentities($_POST['editWinCatId']);
  $safeStartDate = htmlentities($_POST['editWinStartDate']);
  $safeStartTime = htmlentities($_POST['editWinStartTime']);
  $safeEndDate = htmlentities($_POST['editWinEndDate']);
  $safeEndTime = htmlentities($_POST['editWinEndTime']);
  
  // Checks time/date fields of task to see if it is still active
  if ($safeEndDate == '' || $safeEndTime == '') {
    $endTime = null;
    } else {
        $endTime = date('Y-m-d H:i:s',strtotime($safeEndDate . $safeEndTime));
      };

  $safeComments = htmlentities($_POST['editWinComments']);
  if ($safeComments == '') {
    $safeComments = null;
    };
  $startTime = date('Y-m-d H:i:s',strtotime($safeStartDate . $safeStartTime));
  editExistingTask(getDb(), $safeTask, $safeId, $safeCatId, $startTime, $endTime, $safeComments); 
} 

function editExistingTask($db, $task, $id, $cat_id, $start, $end, $comments) { 
  if ($end == ''){
    $stmt = 'UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', comment = \''.$comments.'\' WHERE task_id=' .$id;
  } else {
    $stmt = 'UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', time_end = \''.$end.'\', comment = \''.$comments.'\' WHERE task_id=' .$id; //
  };
  $result = pg_query($stmt);
}

//////////////////////////
// Add new Category of work to be tracked
//////////////////////////

if (isset($_POST['newCategory'])) {
  $safeCategory = htmlentities($_POST['newCategory']);
  addCategory(getDb(), $safeCategory);
}

function addCategory($db, $cat_name) {
  $stmt = 'INSERT INTO category (cat_name) VALUES
      (\''.$cat_name.'\');';
  $result = pg_query($stmt);
}

//////////////////////////
// Remove Task from Database
//////////////////////////

if (isset($_POST['removeTask'])) {
  $safeTask = htmlentities($_POST['removeTask']);
  removeTask(getDb(), $safeTask);
}

function removeTask($db, $id) {
  $stmt   = "DELETE FROM taskList WHERE task_id=".$id;
  $result = pg_query($stmt);
}

//////////////////////////
// Complete task in database
//////////////////////////

if (isset($_POST['completeTask'])) {
  $safeTask = htmlentities($_POST['completeTask']);
  completeTask(getDb(), $safeTask);
}

function completeTask($db, $id) {
  $timestamp = date("Y-m-d H:i:s");
  // $duration = Add Duration of activity to table
  $stmt = 'UPDATE taskList SET time_end= \''.$timestamp.'\' WHERE task_id='.$id;
  
  $result = pg_query($stmt);
}



// function getCompletedTasks($db) {
//   $request = pg_query(getDb(), "SELECT * FROM taskList WHERE time_end is not null ORDER BY time_end DESC");
//   return pg_fetch_all($request);
// }



?>