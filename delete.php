<?php
require 'db.php';
session_start();
if (isset($_GET['id']) && $_GET['id'] != '') {
	$id = $_GET['id'];
	$con = new MongoClient();
	$db = $con->ivanovphp;
	$collection = $db->docs;
	try{
		$doc = $collection->findOne(['_id' => new MongoId($id)]);
	}
	catch(Exception $e){
		$con->close();
		header('Location: /');
		exit();
	}
	if ($doc['user'] == $_SESSION['logged_user']->id) {
		$gridFS = $db->getGridFS();
		$file_id = $doc['file'];
		$gridFS->delete(new MongoId($file_id));
		$collection->remove($doc);
		$con->close();
		header('Location: /');
		exit();
	}
	$con->close();
	echo '<div style="color: red;">Документ не найден</div>
			  <a href="/">Вернуться на главную</a>';
}