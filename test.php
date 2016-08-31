<?php
$con = new MongoClient();
$db = $con->ivanovphp;
$gridFS = $db->getGridFS();
//$file_id = $gridFS->storeUpload($file);
$file = $gridFS->findOne(['_id' => new MongoId('57aed0ac7ece43900100002f')]);
//echo $file_id;
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename='".$file->getFilename()."'");
$stream = $file->getResource();
while (!feof($stream)) {
    echo fread($stream, 8192);
}
$con->close();
?>