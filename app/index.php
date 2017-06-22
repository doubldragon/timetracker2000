<!DOCTYPE html>
<html>
<head>
	<title>Task Tracker 2000</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<?php
	
	if (isset($_GET['task'])) {
    $safeTask = htmlentities($_GET['task']);
    addTask(getDb(), $safeTask);
}
	if (isset($_GET['removeTask'])) {
    $safeTask = htmlentities($_GET['removeTask']);
    removeTask(getDb(), $safeTask);
  }
	function getDb() {
    $db = pg_connect("host=localhost port=5432 dbname=timetracking user=tracker password=track");
    return $db;
  }

  function addTask($db, $task){
  	$date = date('M-d-y');
  	$time = date(' H:i:s');
  	$stmt = 'INSERT INTO time (task, date_in,time_in) VALUES 
  		(\''.$task.'\',\''.$date.'\',\''.$time.'\');';
  	$result = pg_query($stmt);
  }

  function getTasks($db) {
    $request = pg_query(getDb(), "SELECT * FROM time ORDER BY date_in DESC, time_in DESC");
    return pg_fetch_all($request);
  }

  function removeTask($db, $id) {
    $stmt = "DELETE FROM time WHERE id=" . $id;
    $result = pg_query($stmt);
  }

?>


<div class ='container'>
<h1>Time Tracker 2000</h1>
<div>
<form class="form-inline align-middle" method="get" action="">

  <label class="sr-only" for="task">Task</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="task" name="task" placeholder="What are you working on?">
  <!-- <input type='hidden' id='timePunch' name='timePunch'> -->
<!-- <input type="date" class="form-control mb-2 mr-sm-2 mb-sm-0" id="dateOut" name="dateOut" >
<input type="time" class="form-control mb-2 mr-sm-2 mb-sm-0" id="timeOut" name="timeOut" > -->
  

  <button type="submit" class="btn btn-primary">Punch the Clock</button>
</form>
</div>
<?php
  foreach (getTasks(getDb()) as $tasklist) {
?>
<div class="row mx-auto" style="padding: 10px 0;">
    
        <div class="col taskItem">
      <div class="align-middle">
        <?=$tasklist['task'];?>
        
      </div> 	
    </div>
    <form method="get" action="">
      <input name="removeTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button type="submit" class="close" aria-label="Remove">
        <span aria-hidden="true" style="padding-right: 10px;">&times;</span>
      </button>
    </form>
  </div>
  <?php 
  }
?>
</div>






</div>
</body>
</html>

<!-- <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="000000">
  </div> -->