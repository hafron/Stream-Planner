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
    <label for="name"><?php echo __('Nazwa strumienia') ?>:</label><?php if(isset($_ERRORS['name']) && $_GET['action'] === 'add_stream') echo $_ERRORS['name']; ?><input id="name" name="name">
    <input type="submit" value="<?php echo __('Dodaj') ?>">
  </fieldset>
</form>
<?php $streams = $db->query('SELECT id, name FROM streams ORDER BY priority;'); ?>
<?php foreach($streams->fetchAll() as $stream): ?>
<table class="stream">
  <caption><form action="?action=correct_stream&id=<?php echo $stream['id']; ?>" method="POST"><?php if(isset($_ERRORS['name']) && $_GET['action'] == 'correct_stream' && isset($_GET['id']) && $_GET['id'] == $stream['id'] ) echo $_ERRORS['name']; ?><input name="name" value="<?php echo isset($_POST['name']) && $_GET['action'] == 'correct_stream' && isset($_GET['id']) && $_GET['id'] == $stream['id'] ? $_POST['name']: $stream['name']; ?>" ><input type="submit" value="<?php echo __('Popraw') ?>"></form><a href="?action=remove_stream&id=<?php echo $stream['id']; ?>"><?php echo __('Usuń') ?></a><a href="?action=stream_up&id=<?php echo $stream['id']; ?>"><?php echo __('W górę') ?></a><a href="?action=stream_down&id=<?php echo $stream['id']; ?>"><?php echo __('W dół') ?></a></caption>
  <tr>
  <th><?php echo __('Cel') ?></th><th><?php echo __('Dada zakończenia') ?></th><th><?php echo __('Wynik') ?></th><th></th>
  </tr>
  <?php $targets = $db->query('SELECT id, name, end_date, result FROM targets WHERE stream = \''.$stream['id'].'\'ORDER BY end_date DESC;'); ?>
  <?php foreach($targets->fetchAll() as $target): ?>
      <tr>
		<form action="?action=correct_target&id=<?php echo $target['id']; ?>" method="POST">
        <td><?php if(isset($_ERRORS['name']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id']) echo $_ERRORS['name'] ?><input name="name" value="<?php echo isset($_POST['name']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id'] ? $_POST['name'] : $target['name'] ?>"></td>
        <td><?php if(isset($_ERRORS['end_date']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id']) echo $_ERRORS['end_date'] ?><input name="end_date" value="<?php echo isset($_POST['end_date']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id'] ?  $_POST['end_date'] : unix_to_formated($target['end_date']) ?>"></td>
        <td><?php if(isset($_ERRORS['result']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id']) echo $_ERRORS['result'] ?><input name="result" value="<?php echo isset($_POST['result']) && $_GET['action'] == 'correct_target' && isset($_GET['id']) && $_GET['id'] == $target['id'] ? $_POST['result'] : $target['result'] ?>"></td>
        <td><input type="submit" value="<?php echo __('Popraw') ?>"><a href="?action=remove_target&id=<?php echo $target['id']; ?>"><?php echo __('Usuń') ?></a></td>
		</form>
      </tr>
  <?php endforeach; ?>
  <tr>
	<form action="?action=add_target&stream=<?php echo $stream['id']; ?>" method="POST">
	  <td><?php if(isset($_ERRORS['name']) && $_GET['action'] == 'add_target' && isset($_GET['stream']) && $_GET['stream'] == $stream['id']) echo $_ERRORS['name']; ?><input name="name" value="<?php if(isset($_POST['name']) && $_GET['action'] == 'add_target' && isset($_GET['stream']) && $_GET['stream'] == $stream['id']) echo $_POST['name']; ?>"></td>
	  <td><?php if(isset($_ERRORS['end_date'])  && $_GET['action'] == 'add_target' && isset($_GET['stream']) && $_GET['stream'] == $stream['id']) echo $_ERRORS['end_date']; ?><input name="end_date" value="<?php if(isset($_POST['end_date']) && $_GET['action'] == 'add_target' && isset($_GET['stream']) && $_GET['stream'] == $stream['id']) echo $_POST['end_date']; ?>"></td>
	  <td><?php echo __('Nie możesz określić wyników w tym korku.') ?></td>
	  <td><input type="submit" value="<?php echo __('Dodaj cel') ?>"></td>
	</form>
  </tr>
</table>
<?php endforeach; ?>
</body>
</html>

