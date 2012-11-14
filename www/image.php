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

$size = $_GET['size'];
$square = $_GET['square']; 
$image = $_GET['image']; 
if (array_key_exists('temp', $_GET)) {
	$s3 = "https://s3.amazonaws.com/burpee/uploads/";
} else {
	$s3 = "https://s3.amazonaws.com/burpee/media/images/";
}
$IMAGE_QUALITY = "75";

try {
	if (strlen($image) <= 0)
		noCacheError();
	
	$tempDir = rand_string(5)."_".$image;
	
	// 
	// Make temp dir.
	//
	exec("mkdir /php_scripts/tmp/".$tempDir);
	//chmod 777 /php_scripts/tmp/$dir

	$inImage = "/php_scripts/tmp/".$tempDir."/in.".get_file_extension($image);
	$outImage = "/php_scripts/tmp/".$tempDir."/out.jpg";
	
	//
	// Fetch the image from s3 and place it in the temp dir.
	//
	if (!file_put_contents($inImage, file_get_contents($s3.$image))) 
		noCacheError();
	
	// Resize image
	if ($square == "true") {
		//-gravity center -crop 25x25+0+0 +repage
		exec("/usr/bin/convert -resize '".$size."x".$size."^' -gravity center -crop ".$size."x".$size."+0+0 +repage -quality ".$IMAGE_QUALITY." \"".$inImage."\" \"".$outImage."\"");
	} else {
		exec("/usr/bin/convert -resize ".$size."x".$size."\> -quality ".$IMAGE_QUALITY." \"".$inImage."\" \"".$outImage."\"");
	}
	
	// Send the imat to the http response
	header('Content-Type: image/jpeg');
	header('Cache-Control: max-age=31536000');  // Cache for 1 year.
	readfile($outImage);
	
	// Remove temp dir.
	exec("rm -fR /php_scripts/tmp/".$tempDir);
} catch (Exception $e) {
	//error_log($e->getMessage());
	exec("rm -fR /php_scripts/tmp/".$tempDir);
	noCacheError();
}

exit;
?>