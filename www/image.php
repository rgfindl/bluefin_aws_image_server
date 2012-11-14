<?php 

function noCacheError() {
	header('Cache-Control: max-age=0'); 
	exit;
}

function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	$str = "";
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

function getParam($name, $default) {
	if (!isset($_GET[$name]) || $_GET[$name] === "")
		return $default;
	else 
		return $_GET[$name];
}

function validateS3Path($s3Path) {
	$ini_array = parse_ini_file("valid-s3-paths.ini");
	if (!in_array($s3Path, $ini_array['s3path'])) 
		noCacheError();
}

//
// Get the params.
//
$size = getParam("size", "200");
$square = getParam("square", "false"); 
$image = getParam("image", ""); // Should never be null due to the rewrite rule.
$s3Path = getParam("s3path", "");

$IMAGE_QUALITY = "75";

try {

	//
	// Validate the s3 path
	//
	validateS3Path($s3Path);
	$s3 = "https://s3.amazonaws.com"+$s3Path;

	//
	// Make temp dir.
	//
	$tempDir = rand_string(5)."_".$image;
	exec("mkdir /tmp/".$tempDir);

	//
	// Setup in/out image names
	//
	$inImage = "/tmp/".$tempDir."/in.".get_file_extension($image);
	$outImage = "/tmp/".$tempDir."/out.jpg";
	
	//
	// Fetch the image from s3 and place it in the temp dir.
	//
	if (!file_put_contents($inImage, file_get_contents($s3.$image))) 
		noCacheError();
	
	// 
	// Resize image
	//
	if ($square == "true") {
		//-gravity center -crop 25x25+0+0 +repage
		exec("/usr/bin/convert -resize '".$size."x".$size."^' -gravity center -crop ".$size."x".$size."+0+0 +repage -quality ".$IMAGE_QUALITY." \"".$inImage."\" \"".$outImage."\"");
	} else {
		exec("/usr/bin/convert -resize ".$size."x".$size."\> -quality ".$IMAGE_QUALITY." \"".$inImage."\" \"".$outImage."\"");
	}
	
	//
	// Send the image to the http response
	//
	header('Content-Type: image/jpeg');
	header('Cache-Control: max-age=31536000');  // Cache for 1 year.
	readfile($outImage);
	
	//
	// Remove temp dir.
	//
	exec("rm -fR /tmp/".$tempDir);
} catch (Exception $e) {
	//error_log($e->getMessage());
	exec("rm -fR /tmp/".$tempDir);
	noCacheError();
}

exit;
?>