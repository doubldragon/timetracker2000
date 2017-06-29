<!DOCTYPE html>
<html>
  <head>
    <?php include 'links.html'; ?>
  </head>
  <body>

  <?php include 'functions.php'; ?>
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
          <button type="submit" class="btn btn-info">Punch the Clock</button>
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
    <?php include 'cardResults.php'; ?>
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
      <!-- <php include 'activeTasks.php'; ?> -->

      <!-- Populate table with completed tasks -->
      <?php include 'completedTasks.php'; ?>

      </tbody>
    </table>

      

    </div>


  </body>
</html>