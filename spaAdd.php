<?php
require 'db.php';
session_start();
$data = $_POST;
if($_SERVER['REQUEST_METHOD'] == 'POST') { 
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
		$doc += ['fileName' => $_FILES['doc_file']['name']];
		echo json_encode($doc);
	}
}