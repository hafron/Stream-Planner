<!DOCTYPE html>
<html>
<head>
  <title><?php echo __('Błąd aplikacji'); ?></title>
  <meta charset="utf-8">
</head>
<body>
	<p><?php echo __('W aplikacji pojawił się błąd i nie może fukcjonować poprawnie. Sprawdź następujące rzeczy:') ?></p>
	<ul>
	<li><?php echo __('czy plik z bazą danych'); echo '('.DATABASE_FILE.') '; echo ('ma uprawnienia do odczytu i zapisu,') ?></li>
	<li><?php echo __('czy posiadasz PHP w wersji 5.3 lub wyższej,') ?></li>
	<li><?php echo __('czy posiadasz zainstalowane rozszerzenie do obsługi SQLite.') ?></li>
	</ul>
	<p><?php echo __('Jeżeli wszystko się zgadza, a pomimo to nadal nie możesz korzystać z aplikacji, jest to najprawdopodobniej błąd jej twórcy. Spróbuj zaktualizować aplikację do najnowszej wersji. Jeżeli i to nie pomoże zgłoś błąd aplikacji poprzez stronę projektu:') ?> <a href="https://github.com/freesz/Stream-Planner">github.com/freesz/Stream-Planner</a>.</p>
	<p><?php echo __('Przepraszamy za niedogodności i postaramy rozwiązać ten problem jak najszybciej.') ?></p>
</body>
</html>
