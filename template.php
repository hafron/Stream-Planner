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

