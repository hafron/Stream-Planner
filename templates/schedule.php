<!DOCTYPE html>
<html>
<head>
  <title><?php echo __('Narzędzie planowania strumieniowego.'); ?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<h1><?php echo __('Stały plan dnia') ?></h1>
<table>
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
<h1><?php echo __('Zmienny plan dnia') ?></h1>

<table class="stream">
<form action="?action=add_modification_date" method="POST">
  <caption><?php if(isset($_ERRORS['date']) && $_GET['action'] === 'add_modification_date') echo $_ERRORS['date']; ?><input type="date" name="date"><input type="submit" value="<?php echo __('Dodaj datę') ?>"></caption>
</form>

<tr>
  <td><input type="time" disabled></td>
  <td><input type="time" disabled></td>
  <td><input disabled></td>
  <td><input type="submit" disabled value="<?php echo __('Dodaj zajęcie') ?>"></td>
</tr>
</table>

<?php $schedule_modification_dates = DB::instance()->query('SELECT id, date FROM modification_dates ORDER BY date DESC'); ?>
<?php foreach($schedule_modification_dates->fetchAll() as $date): ?>

<table class="stream">
<form action="?action=change_modification_date&id=<?php echo $date['id'] ?>" method="POST">
  <caption><?php if(isset($_ERRORS['date']) && $_GET['action'] == 'change_modification_date' && isset($_GET['id']) && $_GET['id'] == $stream['id'] ) echo $_ERRORS['date']; ?>
  <input type="date" name="date" value="<?php echo isset($_POST['date']) && $_GET['action'] == 'change_modification_date' && isset($_GET['id']) && $_GET['id'] == $date['id'] ? $_POST['date']: $date['date']; ?>">
  <input type="submit" value="<?php echo __('Popraw datę') ?>">
  <a href="?action=remove_modification_date&id=<?php echo $date['id']; ?>"><?php echo __('Usuń') ?></a>
  </caption>
  
<?php $schedule_modifications = DB::instance()->query('SELECT id, start, finish, action FROM schedule_modifications WHERE date="'.$date['id'].'" ORDER BY start'); ?>

<?php foreach($schedule_modifications->fetchAll() as $schedule_modification): ?>

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
<form action="?action=add_schedule_modification&date=<?php echo $date['id'] ?>" method="POST">
  <td><?php if(isset($_ERRORS['start']) && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_ERRORS['start'] ?><input name="start" type="time" value="<?php if(isset($_POST['start']) && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_POST['start']; ?>"></td>
  
  <td><?php if(isset($_ERRORS['finish'])  && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_ERRORS['finish'] ?><input name="finish" type="time" value="<?php if(isset($_POST['finish']) && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_POST['finish']; ?>"></td>
  
  <td><?php if(isset($_ERRORS['action'])  && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_ERRORS['action'] ?><input name="action" value="<?php if(isset($_POST['action']) && $_GET['action'] == 'add_schedule_modification' && isset($_GET['date']) && $_GET['date'] == $date['id']) echo $_POST['action']; ?>"></td>
  
  <td><input type="submit" value="<?php echo __('Dodaj zajęcie') ?>"></td>
</form>
</tr>

</table>
<?php endforeach; ?>

<ul>
  <li><a href="index.php"><?php echo __('Zadania') ?></a></li>
  <li><a href="schedule.php"><?php echo __('Plan dnia') ?></a></li>
</ul>
</body>
</html>

