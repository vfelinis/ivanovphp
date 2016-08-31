<?php
require 'db.php';
session_start();
if (isset($_GET['file_id']) && $_GET['file_id'] != '') {
	$file_id = $_GET['file_id'];
	$con = new MongoClient();
	$db = $con->ivanovphp;
	$collection = $db->docs;
	$doc = $collection->findOne(['file' => new MongoId($file_id)]);
	if ($doc['user'] == $_SESSION['logged_user']->id || $_SESSION['logged_user']->admin == 1) {
		$gridFS = $db->getGridFS();
		$file = $gridFS->findOne(['_id' => new MongoId($file_id)]);
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$file->getFilename());
		$stream = $file->getResource();
		while (!feof($stream)) {
		    echo fread($stream, 8192);
		}
		$con->close();
	}
	else{
		$con->close();
		echo '<div style="color: red;">Файл не найден</div>
			  <a href="/">Вернуться на главную</a>';
	}	
}