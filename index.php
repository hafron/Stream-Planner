<?php
/**
 * Translating function. 
 */
function __($string)
{
	echo $string;
}
/**
 * Classic PHP htmlspecialchars calling recursively on the array.
 */
function htmlspecialchars_array($array=array()) {
if($array)
{

    foreach($array as $k => $v)
    {
       if(is_array($v)) {
           $array[$k] = htmlspecialchars_array($v);
       } else {
           $array[$k] = htmlspecialchars($v);
       }      
    }
}
return $array;
}
?>
<?php
define('DATABASE_FILE', '/usr/local/stream_planner/stream_planner.db');
?>
<?php
$_POST = htmlspecialchars_array($_POST);
$_GET = htmlspecialchars_array($_GET);
$_SESSION = htmlspecialchars_array($_SESSION);
$_SERVER = htmlspecialchars_array($_SERVER);
$_ERRORS = array();
?>
<?php
if(false === file_exists(DATABASE_FILE))
{
	$db = new PDO('sqlite:'.DATABASE_FILE);
    $db->beginTransaction();
	$db->exec('
	CREATE TABLE streams(
	  id INTEGER PRIMARY KEY,
	  name TEXT,
	  priority INTEGER
	)');
	$db->exec('
	CREATE TABLE targets(
	  id INTEGER PRIMARY KEY,
	  stream INTEGER,
	  name INTEGER,
	  end_date INTEGER,
	  result INTEGER
	)');
	$db->exec('
	CREATE TABLE tasks(
	  id INTEGER PRIMARY KEY,
	  target INTEGER,
	  name INTEGER,
	  time INTEGER,
	  finished INTEGER,
	  time_preference TEXT
	)');
	$db->exec('
	CREATE TABLE times(
	  id INTEGER PRIMARY KEY,
	  name TEXT
	)');
	$db->exec('INSERT INTO times VALUES (1, \'autobusowy\')');
	$db->exec('INSERT INTO times VALUES (2, \'szkolny\')');
	$db->exec('INSERT INTO times VALUES (3, \'domowy\')');
	$db->exec('INSERT INTO times VALUES (4, \'domowy_od_rana\')');
	$db->commit();
}
?>
<?php
switch($_GET['action']) {
	case 'add_stream':
	  if('' === $_POST['name'])
	  {
		  $_ERRORS['name'] = __('Musisz podać nazwę strumienia.');
		  break;
	  }
	  $db = new PDO('sqlite:'.DATABASE_FILE);
      $db->beginTransaction();
      $db->exec('INSERT INTO streams VALUES (NULL, \''.$_POST['name'].'\', (SELECT IFNULL(MAX(priority), 0)+1 FROM streams))');
      $db->commit();
	break;
	case 'remove_stream':
	  $db = new PDO('sqlite:'.DATABASE_FILE);
      $db->beginTransaction();
      $db->exec('DELETE FROM streams WHERE id = \''.$_GET['id'].'\'');
      $db->commit();
	break;
	case 'stream_up':
	  $db = new PDO('sqlite:'.DATABASE_FILE);
	  $stream = $db->query('SELECT id, priority FROM streams WHERE priority <= (SELECT priority FROM streams WHERE id = \''.$_GET['id'].'\') ORDER BY priority DESC');
	  $streams = $stream->fetchAll();
	  $this_stream = $streams[0];
	  
	  if(!isset($streams[1])) break;
	  $stream_upper = $streams[1];
	  $down_record_priority = $this_stream['priority'];
	  
	  $db->beginTransaction();
	  $db->exec('UPDATE streams SET priority = \''.$stream_upper['priority'].'\' WHERE id = \''.$this_stream['id'].'\'');
	  $db->exec('UPDATE streams SET priority = \''.$down_record_priority.'\' WHERE id = \''.$stream_upper['id'].'\'');
	  $db->commit();
	break;
	case 'stream_down':
	  $db = new PDO('sqlite:'.DATABASE_FILE);
	  $stream = $db->query('SELECT id, priority FROM streams WHERE priority >= (SELECT priority FROM streams WHERE id = \''.$_GET['id'].'\') ORDER BY priority');
	  $streams = $stream->fetchAll();
	  $this_stream = $streams[0];
	  
	  if(!isset($streams[1])) break;
	  $stream_lower = $streams[1];
	  $up_record_priority = $this_stream['priority'];
	  
	  $db->beginTransaction();
	  $db->exec('UPDATE streams SET priority = \''.$stream_lower['priority'].'\' WHERE id = \''.$this_stream['id'].'\'');
	  $db->exec('UPDATE streams SET priority = \''.$up_record_priority.'\' WHERE id = \''.$stream_lower['id'].'\'');
	  $db->commit();
	break;
}
?>
<?php
$db = new PDO('sqlite:'.DATABASE_FILE);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo __('Narzędzie planowania strumieniowego.'); ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<form action="?action=add_stream" method="post">
  <fieldset>
  <legend><?php echo __('Dodwawanie nowego strumienia') ?></legend>
    <label for="name"><?php echo __('Nazwa strumienia') ?>:</label><?php if(isset($_ERRORS['name'])) echo $_ERRORS['name']; ?><input id="name" name="name">
    <input type="submit" value="<?php echo __('Dodaj') ?>">
  </fieldset>
</form>
<?php $streams = $db->query('SELECT id, name FROM streams ORDER BY priority;'); ?>
<?php foreach($streams->fetchAll() as $row): ?>
<table border=1>
  <th><?php echo $row['name']; ?><a href="?action=remove_stream&id=<?php echo $row['id']; ?>"><?php __('Usuń') ?></a><a href="?action=stream_up&id=<?php echo $row['id']; ?>"><?php __('W górę') ?></a><a href="?action=stream_down&id=<?php echo $row['id']; ?>"><?php __('W dół') ?></a></th>
  <tr>
	<td></td>
  </tr>
</table>
<?php endforeach; ?>
</body>
</html>

