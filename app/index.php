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
  if (isset($_GET['completeTask'])) {
    $safeTask = htmlentities($_GET['completeTask']);
    completeTask(getDb(), $safeTask);
  }
	function getDb() {
    $db = pg_connect("host=localhost port=5432 dbname=timetracking user=tracker password=track");
    return $db;
  }

  function addTask($db, $task){
  	$date = date('M-d-y');
  	$time = date(' H:i');
  	$stmt = 'INSERT INTO time (task, date_in,time_in) VALUES 
  		(\''.$task.'\',\''.$date.'\',\''.$time.'\');';
  	$result = pg_query($stmt);
  }

  function completeTask($db, $id){
  	$date = date('M-d-y');
  	$time = date(' H:i');
  	$stmt = 'UPDATE time SET date_out= \''.$date.'\', time_out=\''.$time.'\' WHERE id='.$id;
  	$result = pg_query($stmt);
  }

  function getActiveTasks($db) {
    $request = pg_query(getDb(), "SELECT * FROM time WHERE date_out is null ORDER BY date_in DESC, time_in DESC");
    return pg_fetch_all($request);
  }

  function getCompletedTasks($db) {
    $request = pg_query(getDb(), "SELECT * FROM time WHERE date_out is not null ORDER BY date_out DESC, time_out DESC");
    return pg_fetch_all($request);
  }

  function removeTask($db, $id) {
    $stmt = "DELETE FROM time WHERE id=" . $id;
    $result = pg_query($stmt);
  }
$stmt = 'UPDATE time SET date_out= '.$date.'\', time_out=\''.$time.'\' WHERE id='.$id;
?>


<div class ='container'>
<h1>Time Tracker 2000</h1>
<div>
<form class="form-inline align-middle" method="get" action="">

  <label class="sr-only" for="task">Task</label>
  <div class="input-group">
    <div class="input-group-addon">Task</div>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="task" name="task" placeholder="What are you working on?">
	</div>
  <button type="submit" class="btn btn-primary">Punch the Clock</button>
</form>
</div>
<table class='table table-responsive table-striped align-middle'>
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
<?php foreach (getActiveTasks(getDb()) as $tasklist) { ?>
<tr>
	<td>
		<?=$tasklist['date_in'];?>
	</td>
	<td>
		<?=$tasklist['task'];?>
	</td>
	<td>
		<?=$tasklist['time_in'];?>
	</td>
	<td>
		<?=$tasklist['time_out'];?>
	</td>
	<td class='buttons'>
	<div class="btn-group" role="group">	
    <form method="get" action="">
      <input name="completeTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class='btn btn-success' type="submit button" id='completeTask' class="close" aria-label="Complete">
        <span aria-hidden="true" >&#x2714;</span>
      </button>
    </form>
    <form method="get" action="">
      <input name="editTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class='btn btn-warning' type="submit button" id='editTask' class="close" aria-label="Complete">
        <span aria-hidden="true">&#x270E;</span>
      </button>
    </form>
    <form method="get" action="">      
      <input name="removeTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class = 'btn btn-danger' type="submit " id='removeTask' class="close" aria-label="Remove">
        <span aria-hidden="true" >&times;</span>
      </button>
    </form>
    
 	</div>

	</td>
</tr>	
  <?php 
  }
?>

<?php foreach (getCompletedTasks(getDb()) as $tasklist) { ?>
<tr>
	<td>
		<?=$tasklist['date_in'];?>
	</td>
	<td>
		<?=$tasklist['task'];?>
	</td>
	<td>
		<?=$tasklist['time_in'];?>
	</td>
	<td>
		<?=$tasklist['time_out'];?>
	</td>
	<td class='buttons'>
	<div class="btn-group" role="group">	
    <form method="get" action="">
      <input name="editTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class='btn btn-warning' type="submit button" id='editTask' class="close" aria-label="Complete">
        <span aria-hidden="true">&#x270E;</span>
      </button>
    </form>
    <form method="get" action="">      
      <input name="removeTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class = 'btn btn-danger' type="submit " id='removeTask' class="close" aria-label="Remove">
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

<!-- <div class="row mx-auto" style="padding: 10px 0;">
    
        <div class="col taskItem">
      <div class="align-middle">
        <=$tasklist['task'];?>
        
      </div> 	
    </div>
    
    

</div> -->






</div>
</body>
</html>

<!-- <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="000000">
  </div> -->