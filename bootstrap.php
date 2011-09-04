<?php
include('config.php');
include('functions.inc.php');

class DB {
	private static $instance=NULL;
	private function __construct() {}
	static public function instance()
	{
		if(self::$instance == NULL) {
		  try {
		    self::$instance = new PDO('sqlite:'.DATABASE_FILE);
		  } catch(Exception $e) {
		    error($e);
		  }	
	    }
	    return self::$instance;
	}
}

$_POST = htmlspecialchars_array($_POST);
$_GET = htmlspecialchars_array($_GET);
$_SERVER = htmlspecialchars_array($_SERVER);
$_ERRORS = array();
if(!isset($_GET['action'])) $_GET['action'] = '';
if(isset($_GET['id']) && !is_numeric($_GET['id'])) exit('Possible SQL Injection attack. Exiting.');

if(false === file_exists(DATABASE_FILE))
{
try {
    DB::instance()->beginTransaction();
	DB::instance()->exec('
	CREATE TABLE streams(
	  id INTEGER PRIMARY KEY,
	  name TEXT,
	  priority INTEGER
	)');
	DB::instance()->exec('
	CREATE TABLE targets(
	  id INTEGER PRIMARY KEY,
	  stream INTEGER,
	  name INTEGER,
	  end_date INTEGER,
	  result INTEGER
	)');
	DB::instance()->exec('
	CREATE TABLE tasks(
	  id INTEGER PRIMARY KEY,
	  target INTEGER,
	  name INTEGER,
	  time INTEGER,
	  finished INTEGER
	)');
	DB::instance()->exec('
	CREATE TABLE times(
	  id INTEGER PRIMARY KEY,
	  name TEXT
	)');
	//In tables schedule and schedule_modifications fileds start and finish means number of seconds for 0:00 of the day.
	//Action in schedule means what are you are doing at that moment. If you put "#{free}" to your action filed it means that application can put your tasks to it. 
	DB::instance()->exec('
	CREATE TABLE schedule(
	  id INTEGER PRIMARY KEY,
	  week_day INTEGER,
	  start INTEGER,
	  finish INTEGER,
	  action TEXT
	)');
	DB::instance()->exec('
	CREATE TABLE schedule_modifications(
	  id INTEGER PRIMARY KEY,
	  date INTEGER,
	  start INTEGER,
	  finish INTEGER,
	  action TEXT
	)');
	DB::instance()->commit();
} catch(Exception $e) {
	DB::instance()->rollBack();
	error($e);
}
}
