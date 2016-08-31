<?php
require 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
	<div class="container">
		<ul class="nav navbar-nav">
			<li><a href="/">Главная</a></li>
			<li><a href="/signup.php">Регистрация</a></li>
			<?php if(isset($_SESSION['logged_user'])) : ?>
				<?php
					if ($_SESSION['logged_user']->admin) {
						echo '<li><a href="/admin.php">Администрирование</a></li>';
					}
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Пользователь, <?= $_SESSION['logged_user']->email ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
            			<li><a href="/logout.php">Выйти</a></li>
            		</ul>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</nav>