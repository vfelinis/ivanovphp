<?php
require 'db.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'DELETE') { 
	$id = file_get_contents('php://input'); 
	$con = new MongoClient();
	$db = $con->ivanovphp;
	$collection = $db->docs;
	try{
		$doc = $collection->findOne(['_id' => new MongoId($id)]);
	}
	catch(Exception $e){
		$con->close();
	}
	if ($doc['user'] == $_SESSION['logged_user']->id) {
		$gridFS = $db->getGridFS();
		$file_id = $doc['file'];
		$gridFS->delete(new MongoId($file_id));
		$collection->remove($doc);
	}
	$con->close();
}