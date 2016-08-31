<?php
require 'db.php';
session_start();
if ($_SESSION['logged_user']->admin == 1) {
	if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
		$user_id = $_GET['user_id'];
		$user = R::findOne('users', 'id = ?', array($user_id));
		if ($user != null) {
			$user->admin = $user->admin == 0 ? 1 : 0;
			R::store($user);
			echo "<script>document.location.replace('/admin.php');</script>";
		}
		else{
			echo '<div style="color: red;">Пользователь не найден</div>
			  <a href="/admin.php">Вернуться назад</a>';
		}
	}
}