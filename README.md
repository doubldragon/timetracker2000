# Task Tracker 2000

## Basic Timekeeping app


### Features
- Ability to add categories to track
- Ability to name task to be tracked
- Simple button to start the task from the current moment
- Tasks can be edited once created, even if still active
- simple interface to list past activity
- Tasks should be ordered with active tasks at the top


### Startup instructions
- Enter psql
- In console enter "CREATE [user] WITH PASSWORD [password];" - add your desired username and password
- In console enter "CREATE DATABASE [database name] OWNER [user];" - name the database and use the username to declare owner
- In app/functions.php, enter the username, password, database name and port (default is 5432) into the getDb() function to allow access to the app
- Create Tables with the following statements:

CREATE TABLE category (
	id SERIAL PRIMARY KEY,
	cat_name VARCHAR(50)
);

CREATE TABLE tasklist (
	task_id SERIAL PRIMARY KEY,
	task VARCHAR(50),
	cat_id INTEGER REFERENCES category(id),
	time_start TIMESTAMP,
	time_end TIMESTAMP,
	duration INTEGER,
	comment TEXT
);

### All Set!

- Host the app locally via the command "$php -S localhost:8000"
- Navigate "localhost:8000" in the browser and you are ready to use the app