<?php
require 'db.php';
session_start();
$data = $_POST;
if (isset($data['do_login'])) {
	// авторизуем
	$errors = array();
	$user = R::findOne('users', 'email = ?', array($data['email']));
	if ($user) {
		// пользователь существует
		if (password_verify($data['password'], $user->password)) {
			// пароли совпадают, можно авторизовывать
			$_SESSION['logged_user'] = $user;
			header('Location: /');
			exit();
		}
		else{
			$errors[] = "Неверный логин или пароль";
		}
	}
	else{
		$errors[] = "Неверный логин или пароль";
	}
	if (!empty($errors)) {
		echo '<div style="color: red;">'.$errors[0].'</div>
			  <a href="/">Вернуться на главную</a>';
	}
}