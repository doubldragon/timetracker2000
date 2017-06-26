I'm from Functions
<?php

if (isset($_GET['taskName']) && isset($_GET['cat_id'])) {
  $safeTask = htmlentities($_GET['taskName']);
  $safeId = htmlentities($_GET['cat_id']);
  addTask(getDb(), $safeTask, $safeId);
}

if (isset($_GET['editWinTask']) && isset($_GET['editWinId']) && isset($_GET['editWinStartDate']) 
  && isset($_GET['editWinStartTime']) //&& isset($_GET['editWinEndDate']) && isset($_GET['editWinEndTime']) 
  && isset($_GET['editWinComments']) && isset($_GET['editWinCatId'])) {
  $safeTask = htmlentities($_GET['editWinTask']);
  $safeId = htmlentities($_GET['editWinId']);
  $safeCatId = htmlentities($_GET['editWinCatId']);
  $safeStartDate = htmlentities($_GET['editWinStartDate']);
  $safeStartTime = htmlentities($_GET['editWinStartTime']);
  $safeEndDate = htmlentities($_GET['editWinEndDate']);
  $safeEndTime = htmlentities($_GET['editWinEndTime']);
  $safeComments = (string)htmlentities($_GET['editWinComments']);
  $startTime = date('Y-m-d h:i:s',strtotime($safeStartDate . $safeStartTime));
  if ($_GET['editWinEndDate'] = null && $_GET['editWinEndTime'] = null) {
  	$endTime = 'Null';
  } else {
  $endTime = date('Y-m-d h:i:s',strtotime($safeEndDate . $safeEndTime));
	};
	var_dump($endTime);
  // var_dump(date('y-m-d h:i:s', $startTime));
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
  $stmt = 'UPDATE taskList SET time_end= \''.$timestamp.'\' WHERE id='.$id;
  $result = pg_query($stmt);
}

function editExistingTask($db, $task, $id, $cat_id, $start, $end, $comments) { //$end,
  //$timestamp = date("Y-m-d H:i:s");
  // $duration = Add Duration of activity to table
  //UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', time_end = \''.$end.'\',
  //comments = \''.$comments.'\' WHERE id=' .$id;
  if ($end = 'Null'){
  	$stmt = 'UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', comment = \''.$comments.'\' WHERE id=' .$id;
  } else {
  	$stmt = 'UPDATE taskList SET task = \''.$task.'\', cat_id = \''.$cat_id.'\', time_start = \''.$start.'\', time_end = \''.$end.'\', comment = \''.$comments.'\' WHERE id=' .$id; //
  };
  var_dump($stmt);
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