<?php include 'header.php'; ?>
<div class="container">
	<?php if($_SESSION['logged_user']->admin == 1) : ?>
	<h1>Список документов у <?= $_GET['user_email'] ?></h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Файл</th>
				<th>Описание</th>
				<th>Дата</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$con = new MongoClient();
			$db = $con->ivanovphp;
			$collection = $db->docs;
			$filter = array("user" => $_GET['user_id']);
			$list = $collection->find($filter);
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