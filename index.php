<?php
try {//default Exceptions behaviour


include 'bootstrap.php';

switch($_GET['action']) {
	case 'add_stream':
	  if('' === $_POST['name'])
	  {
		  $_ERRORS['name'] = __('Musisz podać nazwę strumienia.');
		  break;
	  }
      DB::instance()->exec('INSERT INTO streams VALUES (NULL, "'.$_POST['name'].'", (SELECT IFNULL(MAX(priority), 0)+1 FROM streams))');
	break;
	case 'remove_stream':
	 try {
	    DB::instance()->beginTransaction();
        DB::instance()->exec('DELETE FROM streams WHERE id = "'.$_GET['id'].'"');
        $targets = DB::instance()->query('SELECT id FROM targets WHERE stream = "'.$_GET['id'].'"');
        foreach($targets->fetchAll() as $target)
        {
			DB::instance()->exec('DELETE FROM tasks WHERE target = "'.$target['id'].'"');
		}
		DB::instance()->exec('DELETE FROM targets WHERE stream = "'.$_GET['id'].'"');
        DB::instance()->commit();
	  } catch(Exception $e) {
		DB::instance()->rollBack();
		error($e);
	  }
	  
	break;
	case 'stream_left':
	  
	  $stream = DB::instance()->query('SELECT id, priority FROM streams WHERE priority <= (SELECT priority FROM streams WHERE id = "'.$_GET['id'].'") ORDER BY priority DESC');
	  $streams = $stream->fetchAll();
	  $this_stream = $streams[0];
	  
	  if(!isset($streams[1])) break;
	  $stream_upper = $streams[1];
	  $down_record_priority = $this_stream['priority'];
	  
	  DB::instance()->beginTransaction();
	  DB::instance()->exec('UPDATE streams SET priority = "'.$stream_upper['priority'].'" WHERE id = "'.$this_stream['id'].'"');
	  DB::instance()->exec('UPDATE streams SET priority = "'.$down_record_priority.'" WHERE id = "'.$stream_upper['id'].'"');
	  DB::instance()->commit();
	break;
	case 'stream_right':
	  
	  $stream = DB::instance()->query('SELECT id, priority FROM streams WHERE priority >= (SELECT priority FROM streams WHERE id = "'.$_GET['id'].'") ORDER BY priority');
	  $streams = $stream->fetchAll();
	  $this_stream = $streams[0];
	  
	  if(!isset($streams[1])) break;
	  $stream_lower = $streams[1];
	  $up_record_priority = $this_stream['priority'];
	  
	  DB::instance()->beginTransaction();
	  DB::instance()->exec('UPDATE streams SET priority = "'.$stream_lower['priority'].'" WHERE id = "'.$this_stream['id'].'"');
	  DB::instance()->exec('UPDATE streams SET priority = "'.$up_record_priority.'" WHERE id = "'.$stream_lower['id'].'"');
	  DB::instance()->commit();
	break;
	case 'correct_stream':
	   if('' === $_POST['name'])
	   {
	     $_ERRORS['name'] = __('Musisz podać nazwę strumienia.');
	     break;
	   }
	   
	   DB::instance()->exec('UPDATE streams SET name = "'.$_POST['name'].'" WHERE id = "'.$_GET['id'].'"');
	break;
	case 'add_target':
	  if('' === $_POST['name'])
	  {
		  $_ERRORS['name'] = __('Musisz podać cel.');
	  }
	  if('' === $_POST['end_date'])
	  {
		  $_ERRORS['end_date'] = __('Musisz podać datę zakończenia.');
	  } else
	  {
	    $date = formated_to_unix($_POST['end_date']);
	    if(false === $date)
	    {
		    $_ERRORS['end_date'] = __('Podaj datę w formacie: '.DATE_FORMAT.'.');
	    }
	  }

	  if(count($_ERRORS) == 0) {
	    
        DB::instance()->exec('INSERT INTO targets VALUES (NULL, "'.$_GET['stream'].'", "'.$_POST['name'].'", "'.$date.'", NULL)');
        $_POST = array();
	  }
	 break;
	 case 'remove_target':
	 try {
	    DB::instance()->beginTransaction();
        DB::instance()->exec('DELETE FROM targets WHERE id = "'.$_GET['id'].'"');
        DB::instance()->exec('DELETE FROM tasks WHERE target = "'.$_GET['id'].'"');
        DB::instance()->commit();
	  } catch(Exception $e) {
		DB::instance()->rollBack();
		error($e);
	  }
	 break;
	 case 'correct_target':
		if('' === $_POST['name'])
		{
		  $_ERRORS['name'] = __('Musisz podać cel.');
		}
		if('' === $_POST['end_date'])
		{
		  $_ERRORS['end_date'] = __('Musisz podać datę zakończenia.');
		} else
		{
			$date = formated_to_unix($_POST['end_date']);
			if(false === $date)
			{
				$_ERRORS['end_date'] = __('Podaj datę w formacie: '.DATE_FORMAT.'.');
			}
		}
		if('' !== $_POST['result'] && !is_numeric($_POST['result']))
		{
			$_ERRORS['result'] = __('Wynik musi byś liczbą całkowitą określającą wynik wykonanego zadania w procentach.');
		} elseif('' !== $_POST['result'] && time() < $date)
		{
		  $_ERRORS['result'] = __('Nie możesz wpisywać rezultatów danego celu jeżeli nie zostałą przekroczona data zakończenia.');
		}
	  if(count($_ERRORS) == 0) {
	    
        DB::instance()->exec('UPDATE targets SET name="'.$_POST['name'].'", end_date="'.$date.'", result = "'.$_POST['result'].'" WHERE id="'.$_GET['id'].'"');
        $_POST = array();
	  }
	 break;
	 case 'add_task':
	  if('' === $_POST['name'])
	  {
		  $_ERRORS['name'] = __('Musisz podać zakres zadania.');
	  }
	  if('' === $_POST['time'])
	  {
		  $_ERRORS['time'] = __('Musisz podać ilość czasu potrzebnego na zadanie.');
	  } else if(!is_numeric($_POST['time']))
	  {
	      $_ERRORS['time'] = __('Czas musi być liczbą naturalną określającą ilość minut potrzebnych na zadanie.');
	  }

	  if(count($_ERRORS) == 0) {
	    
        DB::instance()->exec('INSERT INTO tasks VALUES (NULL, "'.$_GET['target'].'", "'.$_POST['name'].'", "'.$_POST['time'].'", NULL)');
        $_POST = array();
	  }
	 break;
	 case 'remove_task':
	  
      DB::instance()->exec('DELETE FROM tasks WHERE id = "'.$_GET['id'].'"');
	 break;
	 case 'correct_task':
	
	  if(!isset($_POST['finished']) || '1' != $_POST['finished'])
	  {
		 $_POST['finished'] = '0';
	  }
	  if('' === $_POST['name'])
	  {
		  $_ERRORS['name'] = __('Musisz podać zakres zadania.');
	  }
	  if('' === $_POST['time'])
	  {
		  $_ERRORS['time'] = __('Musisz podać ilość czasu potrzebnego na zadanie.');
	  } else if(!is_numeric($_POST['time']))
	  {
	      $_ERRORS['time'] = __('Czas musi być liczbą naturalną określającą ilość minut potrzebnych na zadanie.');
	  }

	  if(count($_ERRORS) == 0) {
	    
        DB::instance()->exec('UPDATE tasks SET name="'.$_POST['name'].'", time="'.$_POST['time'].'", finished="'.$_POST['finished'].'" WHERE id="'.$_GET['id'].'"');
        $_POST = array();
	  }
	 break;
}


include 'templates/index.php';

} catch(Exception $e) {
	error($e);
}
