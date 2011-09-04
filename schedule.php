<?php
try {//default Exceptions behaviour
include 'bootstrap.php';
switch($_GET['action']) {
	case 'add_schedule':
	$no_time_errors=true;
	if('' === $_POST['action'])
	  {
		  $_ERRORS['action'] = __('Musisz tytuł wykonywanego zadania.');
	  }
	  if('' === $_POST['start'])
	  {
		  $_ERRORS['start'] = __('Musisz podać godzinę rozpoczęcia.');
		  $no_time_errors=false;
	  } elseif(!valid_time($_POST['start'], $start)) {
		  $_ERRORS['start'] = __('Podaj godzinę w formacie hh:mm.');
		  $no_time_errors=false;
	  }
	  if('' === $_POST['finish'])
	  {
		  $_ERRORS['finish'] = __('Musisz podać godzinę zakończenia.');
		  $no_time_errors=false;
	  } elseif(!valid_time($_POST['finish'], $finish)) {
		  $_ERRORS['finish'] = __('Podaj godzinę w formacie hh:mm.');
		  $no_time_errors=false;
	  }
	  if($no_time_errors === true) {  
		$start_sec = $start[0]*3600+$start[1]*60;
		$finish_sec = $finish[0]*3600+$finish[1]*60;
		if($start_sec > $finish_sec) {
			 $_ERRORS['finish'] = __('Godzina zakończenia musi być późniejsza niż godzina rozpoczęcia.');
		} else
		{
			$schedules = DB::instance()->query('SELECT id, start, finish, action FROM schedule WHERE week_day="'.$_GET['week_day'].'" ORDER BY start');
			$schedules_array = $schedules->fetchAll();
			$in_time_interval = false;
			for($i=0;$i<=86400;$i+=60)
			{
				if($finish_sec == $i && $in_time_interval)
				{
					 $_ERRORS['finish'] = __('Godzina zakończenia nachodzi na inny termin.');
				}
				foreach($schedules_array as $schedule)
				{
				    if($schedule['start'] == $i)
				    {
						$in_time_interval=true;
					}
					if($schedule['finish'] == $i)
					{
						$in_time_interval=false; 
					}
				}

				if($start_sec == $i && $in_time_interval)
				{
					 $_ERRORS['start'] = __('Godzina rozpoczęcia nachodzi na inny termin.');
				}
			}
			if(count($_ERRORS) == 0) {  
			  DB::instance()->exec('INSERT INTO schedule VALUES (NULL, "'.$_GET['week_day'].'", "'.$start_sec.'", "'.$finish_sec.'", "'.$_POST['action'].'")');
              $_POST = array();
		    }
		}
	  }
	break;
	case 'remove_schedule':
	   DB::instance()->exec('DELETE FROM schedule WHERE id = "'.$_GET['id'].'"');
	break;
	case 'correct_schedule':
	$no_time_errors=true;
	if('' === $_POST['action'])
	  {
		  $_ERRORS['action'] = __('Musisz tytuł wykonywanego zadania.');
	  }
	  if('' === $_POST['start'])
	  {
		  $_ERRORS['start'] = __('Musisz podać godzinę rozpoczęcia.');
		  $no_time_errors=false;
	  } elseif(!valid_time($_POST['start'], $start)) {
		  $_ERRORS['start'] = __('Podaj godzinę w formacie hh:mm.');
		  $no_time_errors=false;
	  }
	  if('' === $_POST['finish'])
	  {
		  $_ERRORS['finish'] = __('Musisz podać godzinę zakończenia.');
		  $no_time_errors=false;
	  } elseif(!valid_time($_POST['finish'], $finish)) {
		  $_ERRORS['finish'] = __('Podaj godzinę w formacie hh:mm.');
		  $no_time_errors=false;
	  }
	  if($no_time_errors === true) {  
		$start_sec = $start[0]*3600+$start[1]*60;
		$finish_sec = $finish[0]*3600+$finish[1]*60;
		if($start_sec > $finish_sec) {
			 $_ERRORS['finish'] = __('Godzina zakończenia musi być późniejsza niż godzina rozpoczęcia.');
		} else
		{
			$this_schedule = DB::instance()->query('SELECT week_day FROM schedule WHERE id = "'.$_GET['id'].'"')->fetchAll();
			$schedules = DB::instance()->query('SELECT id, start, finish, action FROM schedule WHERE week_day="'.$this_schedule[0]['week_day'].'" AND id != "'.$_GET['id'].'" ORDER BY start');
			$schedules_array = $schedules->fetchAll();
			$in_time_interval = false;
						for($i=0;$i<=86400;$i+=60)
			{
				if($finish_sec == $i && $in_time_interval)
				{
					 $_ERRORS['finish'] = __('Godzina zakończenia nachodzi na inny termin.');
				}
				foreach($schedules_array as $schedule)
				{
				    if($schedule['start'] == $i)
				    {
						$in_time_interval=true;
					}
					if($schedule['finish'] == $i)
					{
						$in_time_interval=false; 
					}
				}

				if($start_sec == $i && $in_time_interval)
				{
					 $_ERRORS['start'] = __('Godzina rozpoczęcia nachodzi na inny termin.');
				}
			}
			if(count($_ERRORS) == 0) {  
			  DB::instance()->exec('UPDATE schedule SET start="'.$start_sec.'", finish="'.$finish_sec.'", action="'.$_POST['action'].'" WHERE id="'.$_GET['id'].'"');
              $_POST = array();
		    }
		}
	  }
	break;
}


include 'templates/schedule.php';

} catch(Exception $e) {
	error($e);
}
