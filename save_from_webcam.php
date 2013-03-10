<?
$jpeg_data = file_get_contents('php://input');

$filename = "image_from_webcam.jpg";
$result = file_put_contents( $filename, $jpeg_data );
//response variable for javascript
echo "?webcam=$filename";

?>
