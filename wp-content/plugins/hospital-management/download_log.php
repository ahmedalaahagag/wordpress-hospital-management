<?php 
$path = $_REQUEST['token'];

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($path));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($path));
readfile($path);

?>