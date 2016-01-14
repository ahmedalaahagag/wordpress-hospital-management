<?php
// Check download token
if (empty($_GET['mime']) OR empty($_GET['token']))
{
	exit('Invalid download token 8{');
}
$path = $_REQUEST['token'];
$known_mime_types=array(
		"htm" => "text/html",
		"exe" => "application/octet-stream",
		"zip" => "application/zip",
		"doc" => "application/msword",
		"jpg" => "image/jpg",
		"php" => "text/plain",
		"xls" => "application/vnd.ms-excel",
		"ppt" => "application/vnd.ms-powerpoint",
		"gif" => "image/gif",
		"pdf" => "application/pdf",
		"txt" => "text/plain",
		"html"=> "text/html",
		"png" => "image/png",
		"jpeg"=> "image/jpg"
);
$mime_type = '';
if($mime_type==''){
	$file_extension = strtolower(substr(strrchr($path,"."),1));
	if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	} else {
		$mime_type="application/force-download";
	};
};

// Set operation params
//$mime = filter_var($_GET['mime']);
//$ext  = str_replace(array('/', 'x-'), '', strstr($mime, '/'));
//$url  = base64_decode(filter_var($_GET['token']));
//$name = urldecode($_GET['title']). '.' .$ext;
//echo $path = $_REQUEST['token'];
// Fetch and serve


	$size=filesize ($path);
	if (headers_sent()) { exit("Sorry but the headers have already been sent."); }

	ob_end_clean();
	$path = $_REQUEST['token'];
	if (is_readable($path)) {
	header('Content-Type:'.$mime_type);
	header('Content-Disposition: attachment; filename='.basename($path));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length:'.$size);
	header('HTTP/1.0 200 OK', true, 200);
	readfile($path);
	}

?>
