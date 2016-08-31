<?php
require 'db.php';
session_start();
$con = new MongoClient();
$db = $con->ivanovphp;
$collection = $db->docs;
$filter = array("user" => $_SESSION['logged_user']->id);
$list = $collection->find($filter);
$gridFS = $db->getGridFS();
$list = iterator_to_array($list);
foreach ($list as $key => $doc){
	$file_id = $doc['file'];
	$file = $gridFS->findOne(['_id' => new MongoId($file_id)]);
	$file_name = $file->getFilename();
	$list[$key] += ['fileName' => $file_name];
}
echo json_encode($list);
$con->close();
?>