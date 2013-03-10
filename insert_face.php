<?
$pwd=`pwd`;
$pwd=trim($pwd);
@$face_details=$_REQUEST['face_details'];
if ($_GET['webcam']=='image_from_webcam-insert.jpg'){
    $converted_to_jpg=$pwd."/image_from_webcam-insert.jpg";
    $arr[0]="image_from_webcam-insert";
}
else{
      if ($_FILES["myfile"]["error"] > 0)
	{
	echo "Error: " . $_FILES["myfile"]["error"] . "<br />";
	}

	
	
      move_uploaded_file($_FILES["myfile"]["tmp_name"],"tmp/".$_FILES["myfile"]["name"]);

      

      echo $pwd;
      $original_picture=$pwd."/tmp/".$_FILES["myfile"]["name"];
      echo "<br>original_picture: ".$original_picture;
      //making a name for the $converted_to_jpg  by replacing the extension of $original_picture with jpg
      //$arr will be an array holding the two parts of the filename "name.ext"
      $arr=explode('.',$_FILES["myfile"]["name"]);
      $converted_to_jpg=$pwd."/tmp/".$arr[0].".jpg";

      echo "<br>converted_to_jpg: ".$converted_to_jpg;
      $convert_to_jpeg=`convert $original_picture $converted_to_jpg`;

}
$face_location_in_original_picture=`./detect_face.py $converted_to_jpg`;
$face_location_in_original_picture=trim($face_location_in_original_picture);
//detect_face.py is giving an extra semicolon. this will make an extra enrty with explode() below
$face_location_in_original_picture=rtrim($face_location_in_original_picture,';');
if (strlen($face_location_in_original_picture)==0) {
echo "<br>face detection result:no faces found!!<br>";
die();
}
else "FACES FOunD!!<br>";
$list_faces_location_in_original_picture=explode(';',$face_location_in_original_picture);
//to count the number of faces to crop
$crop_counter=1;
foreach ( $list_faces_location_in_original_picture as $face_location){
$face_location=trim($face_location);
echo "<br>".$face_location;
$cropped_face_picture=$pwd."/tmp/cropped_".$arr[0]."_$crop_counter.jpg";

$cropped_face=`convert $converted_to_jpg -crop $face_location $cropped_face_picture`;
echo "<br>convert $converted_to_jpg -crop $face_location  $cropped_face_picture";
mysql_connect("localhost","root","975008");
mysql_select_db("db_faces");
$query="insert into faces(details) values('$face_details')";
$result=mysql_query($query);
$last_insert_id=mysql_insert_id();
//used in the form at the bottom to set details for every cropped face
$files_to_set_details_for["insert_id_$crop_counter"]=$last_insert_id;
$crop_counter++;
#save into faces folder
$db_face_filename=$pwd."/faces/face_".$last_insert_id.".jpeg";
echo "<br>".$db_face_filename;
$cropped_face_picture_after_resize=`convert $cropped_face_picture -resize 100x100 $db_face_filename `;
}
$max_crop_counter=$crop_counter;
?>

<?
if ($_GET['webcam']=='image_from_webcam-insert.jpg'){

?>
<Form action="save_details_after_webcam-insert.php" method="POST" >
<?
for ($crop_counter=1; $crop_counter<$max_crop_counter;$crop_counter++){
$file_id=$files_to_set_details_for["insert_id_$crop_counter"];
echo "<br><img src='faces/face_$file_id'> face details of face_$file_id: <input type=text name='face_details_$crop_counter'>
<input type=hidden name='face_file_id_$crop_counter' value='$file_id'>";
}
?>
<input type=hidden name='max_crop_counter' value='<?=$max_crop_counter?>'>
<input type=submit name=submit value="submit">
</Form>
<?

}
//var_dump($files_to_set_details_for);
?>