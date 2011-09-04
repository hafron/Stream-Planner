<!DOCTYPE html>
<html>
<head>
  <title><?php echo __('Narzędzie planowania strumieniowego.'); ?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<table border=1>
<tr>
<th><?php echo __('Niedziela') ?></th>
<th><?php echo __('Poniedziałek') ?></th>
<th><?php echo __('Wtorek') ?></th>
<th><?php echo __('Środa') ?></th>
<th><?php echo __('Czwartek') ?></th>
<th><?php echo __('Piątek') ?></th>
<th><?php echo __('Sobota') ?></th>
</tr>
<tr>
<?php for($week_day=0;$week_day<7;$week_day++): ?>
<?php $schedules = DB::instance()->query('SELECT id, start, finish, action FROM schedule WHERE week_day="'.$week_day.'" ORDER BY start;'); ?>
<td>
<table>
<?php foreach($schedules->fetchAll() as $schedule): ?>
  <tr>
  <form action="?action=correct_schedule&id=<?php echo $schedule['id']; ?>" method="POST">
  <td><?php if(isset($_ERRORS['start']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id']) echo $_ERRORS['start'] ?><input name="start" type="time" value="<?php echo isset($_POST['start']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id'] ? $_POST['start'] : format_time($schedule['start']) ?>"></td>
  <td><?php if(isset($_ERRORS['finish']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id']) echo $_ERRORS['finish'] ?><input name="finish" type="time" value="<?php echo isset($_POST['finish']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id'] ?  $_POST['finish'] : format_time($schedule['finish']) ?>"></td>
  <td><?php if(isset($_ERRORS['action']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id']) echo $_ERRORS['finish'] ?><input name="action" value="<?php echo isset($_POST['action']) && $_GET['action'] == 'correct_schedule' && isset($_GET['id']) && $_GET['id'] == $schedule['id'] ?  $_POST['action'] : $schedule['action'] ?>"></td>
  <td><input type="submit" value="<?php echo __('Popraw') ?>"></td><td><a href="?action=remove_schedule&id=<?php echo $schedule['id']; ?>"><?php echo __('Usuń') ?></a></td>
  </form>
  </tr>
<?php endforeach; ?>
<tr>
<form action="?action=add_schedule&week_day=<?php echo $week_day; ?>" method="POST">
  <td><?php if(isset($_ERRORS['start']) && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_ERRORS['start'] ?><input name="start" type="time" value="<?php if(isset($_POST['start']) && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_POST['start']; ?>"></td>
  
  <td><?php if(isset($_ERRORS['finish'])  && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_ERRORS['finish'] ?><input name="finish" type="time" value="<?php if(isset($_POST['finish']) && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_POST['finish']; ?>"></td>
  
  <td><?php if(isset($_ERRORS['action'])  && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_ERRORS['action'] ?><input name="action" value="<?php if(isset($_POST['action']) && $_GET['action'] == 'add_schedule' && isset($_GET['week_day']) && $_GET['week_day'] == $week_day) echo $_POST['action']; ?>"></td>
  
  <td><input type="submit" value="<?php echo __('Dodaj zajęcie') ?>"></td>
  <td></td>
</form>
</tr>

</table>
</td>
<? endfor; ?>
</tr>
</table>
</body>
<ul>
  <li><a href="index.php"><?php echo __('Zadania') ?></a></li>
  <li><a href="schedule.php"><?php echo __('Plan dnia') ?></a></li>
</ul>
</body>
</html>

