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


<div class='container col-md-8'>

<h1>Time Tracker 2000</h1>

<div class='mx-auto center-block text-center'>

<!-- Add a task to the list -->

<form class="form-inline mx-auto" method="get" action="">

  <label class="sr-only" for="task">Task</label>
  <div class="input-group input-group-lg mx-auto">
    <!-- <div class="input-group-addon">Task</div> -->
  <input type="text" class="form-control mb-2 mb-sm-0" id="task" name="task" placeholder="What are you working on?">
	 <span class='input-group-btn'> 
	 <button type="submit" class="btn btn-primary">Punch the Clock</button>
	 </span>
	</div>
  <!-- <button type="submit" class="btn btn-primary">Punch the Clock</button> -->
</form>


<!-- Add new tasks to the dropdown -->

<form class="form-inline mx-auto" method="get" action="">

  <label class="sr-only" for="task">Add New Task</label>
  <div class="input-group mx-auto">
    
  <input type="text" class="form-control mb-2  mb-sm-0" id="addNewTask" name="addNewTask" placeholder="Add a new task">
	<span class="input-group-btn">
        <button class="btn btn-secondary" type="submit">Go!</button>
      </span>
	</div>
	
  
</form>
</div>


<!-- Active Tasks Being Tracked -->


<table class='table table-responsive table-striped mx-auto col-md-4'>
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
<tr >
	<td class='align-middle'>
		<?=$tasklist['date_in'];?>
	</td>
	<td class='align-middle'>
		<?=$tasklist['task'];?>
	</td>
	<td class='align-middle'>
		<?=$tasklist['time_in'];?>
	</td>
	<td class='align-middle'>
		<form method="get" action="">
      <input name="completeTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class='btn btn-success' type="submit button" id='completeTask' class="close" aria-label="Complete">
        <span aria-hidden="true" >&#x2714;</span>
      </button>
    </form>
	</td>
	<td class='buttons'>
	<div class="btn-group" role="group">	
    
    <form method="get" action="">
      <input name="editTask" value="<?=$tasklist['id'];?>" type="hidden">
      <button class='btn btn-warning' type="button" id='editTask' class="close" aria-label="Complete" data-toggle="modal" data-target="#modal<?=$tasklist['id'];?>">
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
        <input type="date" value='<?=$tasklist['date_in'];?>'>
        </div>
        <div class="input-group editWindow">
    	<div class="input-group-addon">Start Time</div>	
        <input type="time" value="<?=$tasklist['time_in'];?>" />
        </div>
        <div class="input-group editWindow">
    <div class="input-group-addon">End Date</div>
        <input type="date">
        </div>
        <div class="input-group editWindow">
    <div class="input-group-addon">End Time</div>
        <input type="time" name="usr_time" value="19:47" />
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
	<td class='align-middle'> 
		<?=$tasklist['date_in'];?>
	</td>
	<td class='align-middle'>
		<?=$tasklist['task'];?>
	</td>
	<td class='align-middle'>
		<?=$tasklist['time_in'];?>
	</td >
	<td class='align-middle'>
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

</div>



<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button> -->
<button class='mx-auto btn btn-warning' type="button" data-toggle="modal" data-target="#myModal">
        <span aria-hidden="true">&#x270E;</span>
      </button>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <input type='text' />
        </div>
        <div class="input-group editWindow">
    	<div class="input-group-addon">Start Date</div>
        <input type="date" >
        </div>
        <div class="input-group editWindow">
    	<div class="input-group-addon">Start Time</div>	
        <input type="time" value="14:00" />
        </div>
        <div class="input-group editWindow">
    <div class="input-group-addon">End Date</div>
        <input type="date">
        </div>
        <div class="input-group editWindow">
    <div class="input-group-addon">End ime</div>
        <input type="time" name="usr_time" value="19:47" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>

<!-- <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="000000">
  </div> -->