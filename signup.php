<?php include 'header.php';

$data = $_POST;
if (isset($data['do_signup'])) {
	// регистрируем
	$errors = array();
	if (trim($data['email']) == '') {
		$errors[] = 'Введите Email';
	}
	if (R::count('users', 'email = ?', array($data['email'])) > 0) {
		$errors[] = 'Такой Email уже используется';
	}
	if (trim($data['password']) == '') {
		$errors[] = 'Введите пароль';
	}
	if ($data['confirm_password'] != $data['password']) {
		$errors[] = 'Повторный пароль введен не верно';
	}
	if (empty($errors)) {
		// ошибок нет, можно регистрировать
		$user = R::dispense('users');
		$user->email = $data['email'];
		$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
		$user->admin = 0;
		R::store($user);
		echo '<div class="container"><div style="color: green;">Вы зарегистрированы!</div></div><hr />';
	}
	else{
		echo '<div class="container"><div style="color: red;">'.$errors[0].'</div></div><hr />';
	}
}

?>

<div class="container">
	<h1>Регистрация</h1>
	<form action="/signup.php" method="POST" class="form-horizontal" role="form">
	  <div class="form-group">
	    <label for="email" class="col-sm-2 control-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="email" name="email">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="password" class="col-sm-2 control-label">Пароль</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="password" name="password">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="confirm_password" class="col-sm-2 control-label">Повторите пароль</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="confirm_password" name="confirm_password">
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default" name="do_signup">Отправить</button>
	    </div>
	  </div>
	</form>
</div>

<?php include 'footer.php'; ?>