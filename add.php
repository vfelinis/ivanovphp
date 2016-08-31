<?php
include 'header.php';
$data = $_POST;
if (isset($data['add_doc'])) {
	if ($_SESSION['logged_user']) {
		$file = 'doc_file';
		$con = new MongoClient();
		$db = $con->ivanovphp;
		$gridFS = $db->getGridFS();
		$file_id = $gridFS->storeUpload($file);
		$doc = array( 
	    	"user" => $_SESSION['logged_user']->id,
	    	"file" => $file_id,
	    	"text" => $data['doc_text'],
	    	"date" => $data['doc_date']
	    );
		$collection = $db->docs;
		$collection->insert($doc);
		$con->close();
		echo "<script>document.location.replace('/');</script>";
		exit();
	}
}
?>
<div class="container">
	<h1>Новый документ</h1>
	<form action="/add.php" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="form-group">
	    <label for="doc_file" class="col-sm-2 control-label">Файл</label>
	    <div class="col-sm-10">
	      <input type="file" class="form-control" id="doc_file" name="doc_file">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="doc_text" class="col-sm-2 control-label">Описание</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="doc_text" name="doc_text">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="doc_date" class="col-sm-2 control-label">Дата</label>
	    <div class="col-sm-3">
	      <input type="date" class="form-control" id="doc_date" name="doc_date">
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default" name="add_doc">Создать</button>
	    </div>
	  </div>
	</form>
</div>
<?php include 'footer.php'; ?>