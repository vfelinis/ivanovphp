<h1>Список ваших документов</h1>
<a href="add.php" class="btn btn-default btn-lg">Добавить новый документ</a>
<form action="/" method="GET" class="navbar-form navbar-right" role="search">
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Поиск" name="search" value="<?= $_GET['search'] ?>">
	</div>
	<button type="submit" class="btn btn-default">Найти</button>
</form>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Файл</th>
			<th>Описание</th>
			<th>Дата</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$con = new MongoClient();
	$db = $con->ivanovphp;
	$collection = $db->docs;
	$filter = array("user" => $_SESSION['logged_user']->id);
	if (isset($_GET['search']) && $_GET['search'] != '') {
		$search = $_GET['search'];
		$filter['text'] = new MongoRegex('/'.$search.'/i');
	}
	$list = $collection->find($filter);
	$param=array("date"=>-1);
	$list->sort($param);
	$count = $list->count();
	$pages = ceil($count / 10);
	if (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) {
		$page = round($_GET['page']);
		if ($page > 0 && $page <= $pages) {
			$list->skip($page*10-10);
		}
	}
	$list->limit(10);
	while ($document = $list->getNext()) {
		$gridFS = $db->getGridFS();
		$file_id = $document['file'];
		$file = $gridFS->findOne(['_id' => new MongoId($file_id)]);
		$file_name = $file->getFilename();
		echo '<tr>
				  <td><a href="/getFile.php?file_id='.$file_id.'">'
				  .$file_name.
				  '</a></td>
			      <td>'.$document['text'].'</td>
			      <td>'.$document['date'].'</td>
			      <td><a href="/update.php?id='.$document['_id'].'" class="btn btn-default">Изменить</a></td>
			      <td><a href="/delete.php?id='.$document['_id'].'" onclick="return confirmDelete();" class="btn btn-default">Удалить</a></td>
			  </tr>';
	}
	$con->close();
	?>
	</tbody>
</table>
<div style="text-align: center;">
	<ul class="pagination">
		<?php
			$i = 0;
			while ($pages - $i) {
				$i++;
				if (isset($_GET['search']) && $_GET['search'] != '') {
					if ($i == $page || $i == 1 && !isset($page)) {
						echo '<li class="active"><a href="/?search='.$_GET['search'].'&page='.$i.'">'.$i.'</a></li>';
					}
					else{
						echo '<li><a href="/?search='.$_GET['search'].'&page='.$i.'">'.$i.'</a></li>';
					}
				}
				else{
					if ($i == $page || $i == 1 && !isset($page)) {
						echo '<li class="active"><a href="/?page='.$i.'">'.$i.'</a></li>';
					}
					else{
						echo '<li><a href="/?page='.$i.'">'.$i.'</a></li>';
					}
				}
			}
		?>
	</ul>
</div>
<script>
	function confirmDelete(){
		if (confirm("Вы подтверждаете удаление?")) {
			return true;
		}
		else{
			return false;
		}
	}
</script>