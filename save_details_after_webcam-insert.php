<?
mysql_connect("localhost","root","975008");
mysql_select_db("db_faces");
$max_crop_counter=$_POST['max_crop_counter'];
for ($crop_counter=1; $crop_counter<$max_crop_counter;$crop_counter++){
  $face_details=$_POST["face_details_$crop_counter"];
  $file_id=$_POST["face_file_id_$crop_counter"];
  $query="update faces set details='$face_details' where face_file_id=$file_id";
  $result=mysql_query($query);
  echo "saved face details for face #$file_id <br>\n";
}


?>
