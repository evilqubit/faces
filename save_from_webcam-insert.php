<?
$jpeg_data = file_get_contents('php://input');

$filename = "image_from_webcam-insert.jpg";
$result = file_put_contents( $filename, $jpeg_data );
echo "?webcam=$filename";

?>