<?php
require 'db.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
	$data = file_get_contents('php://input');
	$document = json_decode($data);
	$con = new MongoClient();
	$collection = $con->ivanovphp->docs;
	$doc = $collection->findOne(['_id' => new MongoId($document->_id->{'$id'})]);
	if ($doc['user'] == $_SESSION['logged_user']->id) {
		$new_doc = array ('$set' => array("text" => $document->text, "date" => $document->date));
		$collection->update($doc, $new_doc);
	}
	$con->close();
	echo $data;
}