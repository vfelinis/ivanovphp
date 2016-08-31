<?php include 'header.php'; ?>
<div class="container">
	<?php if($_SESSION['logged_user']->admin == 1) : ?>
	<h1>Список пользователь</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Пользователь</th>
				<th>Количество файлов</th>
				<th>Права</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$con = new MongoClient();
				$collection = $con->ivanovphp->docs;
				$users = R::find('users');
				foreach ($users as $user) {
					$admin = $user->admin == 1 ? 'Администратор' : 'Обычный пользователь';
					$action = $user->admin == 0 ? 'Дать админку' : 'Забрать админку';
					$filter = array("user" => $user->id);
					$list = $collection->find($filter);
					$count = $list->count();
					echo '<tr>
							<td>'.$user->email.'</td>
							<td>'.$count.'</td>
							<td>'.$admin.'</td>
							<td><a href="/action.php?user_id='.$user->id.'" class="btn btn-default">'.$action.'</a></td>
							<td><a href="/detail.php?user_id='.$user->id.'&user_email='.$user->email.'" class="btn btn-default">Подробнее</a></td>
						  </tr>';
				}
				$con->close();
			?>
		</tbody>
	</table>
	<?php else : ?>
		<h1 style="color: red;">В доступе отказано!</h1>
	<?php endif; ?>
</div>
<?php include 'footer.php'; ?>