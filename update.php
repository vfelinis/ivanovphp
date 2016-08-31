<?php
include 'header.php';
if (isset($_POST['up_doc'])){
	$data = $_POST;
	$con = new MongoClient();
	$collection = $con->ivanovphp->docs;
	try{
		$doc = $collection->findOne(['_id' => new MongoId($data['doc_id'])]);
	}
	catch(Exception $e){
		$con->close();
		echo "<script>document.location.replace('/');</script>";
		exit();
	}
	if ($doc['user'] == $_SESSION['logged_user']->id) {
		$new_doc = array ('$set' => array("text" => $data['doc_text'], "date" => $data['doc_date']));
		$collection->update($doc, $new_doc);
	}
	$con->close();
	echo "<script>document.location.replace('/');</script>";
	exit();
}
if (isset($_GET['id']) && $_GET['id'] != '') {
	$id = $_GET['id'];
	$con = new MongoClient();
	$collection = $con->ivanovphp->docs;
	try{
		$doc = $collection->findOne(['_id' => new MongoId($id)]);
	}
	catch(Exception $e){
		$con->close();
		echo "<script>document.location.replace('/');</script>";
		exit();
	}
	if ($doc['user'] == $_SESSION['logged_user']->id) {
		$text = $doc['text'];
		$date = $doc['date'];
	}
	$con->close();
}
?>
<div class="container">
	<h1>Изменение информации к файлу</h1>
	<form action="/update.php" method="POST" class="form-horizontal" role="form">
		<input type="hidden" name="doc_id" value="<?= $id ?>">
		<div class="form-group">
			<label for="doc_text" class="col-sm-2 control-label">Описание</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="doc_text" name="doc_text" value="<?= $text ?>">
			</div>
		</div>
		<div class="form-group">
		    <label for="doc_date" class="col-sm-2 control-label">Дата</label>
		    <div class="col-sm-3">
		    	<input type="date" class="form-control" id="doc_date" name="doc_date" value="<?= $date ?>">
		    </div>
	    </div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default" name="up_doc">Сохранить</button>
			</div>
		</div>
	</form>
</div>
<?php include 'footer.php'; ?>