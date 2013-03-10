<?

$pwd=`pwd`;
$pwd=trim($pwd);
if ($_GET['webcam']=='image_from_webcam.jpg'){
    $converted_to_jpg=$pwd."/image_from_webcam.jpg";
    $arr=explode('.',"image_from_webcam.jpg");
}
else{



    if ($_FILES["myfile"]["error"] > 0)
      {
      echo "Error: " . $_FILES["myfile"]["error"] . "<br />";
      }

      
      
    move_uploaded_file($_FILES["myfile"]["tmp_name"],"tmp/".$_FILES["myfile"]["name"]);


    $original_picture=$pwd."/tmp/".$_FILES["myfile"]["name"];

    //making a name for the $converted_to_jpg  by replacing the extension of $original_picture with jpg
    //$arr will be an array holding the two parts of the filename "name.ext"
    $arr=explode('.',$_FILES["myfile"]["name"]);
    $converted_to_jpg=$pwd."/tmp/".$arr[0].".jpg";

    echo "<br>converted_to_jpg: ".$converted_to_jpg;
    $convert_to_jpeg=`convert $original_picture $converted_to_jpg`;
    echo "<br>converted_to_jpg: ".$converted_to_jpg;
    $convert_to_jpeg=`convert $original_picture $converted_to_jpg`;
}
echo $converted_to_jpg;
$face_location_in_original_picture=`./detect_face.py $converted_to_jpg `;
$face_location_in_original_picture=trim($face_location_in_original_picture);
$face_location_in_original_picture=rtrim($face_location_in_original_picture,';');
if (strlen($face_location_in_original_picture)==0) {
echo "<br>face detection result:no faces found!!<br>";
die();
}

echo "<br>face locations:".$face_location_in_original_picture;

$list_faces_location_in_original_picture=explode(';',$face_location_in_original_picture);
//to count the number of faces to crop

$crop_counter=1;
$x=0;
foreach ( $list_faces_location_in_original_picture as $face_location){
    $face_location=trim($face_location);
    
    $cropped_face_picture=$pwd."/tmp/cropped_".$arr[0]."_$crop_counter.jpg";
    $cropped_face=`convert $converted_to_jpg -crop $face_location $cropped_face_picture`;

    $resized_cropped_face_picture=$pwd."/tmp/resized_".$arr[0]."_$crop_counter.jpg";
    $resized_cropped_face_picture_with_host="tmp/resized_".$arr[0]."_$crop_counter.jpg";
    $cropped_face_picture_after_resize=`convert $cropped_face_picture -resize 100x100 $resized_cropped_face_picture `;
    #faces/face_124.jpeg
    mysql_connect("localhost","root","975008");
    mysql_select_db("db_faces");
    $query="select face_file_id,details from faces";
    $result=mysql_query($query);
    $table_row="";
    //libpuzzle
    $cvec1 = puzzle_fill_cvec_from_file($resized_cropped_face_picture);
    //libpuzzle
    while($row=mysql_fetch_assoc($result)){

    $db_face_filename=$pwd."/faces/face_".$row['face_file_id'].".jpeg";
    if (!file_exists($db_face_filename)){
      mysql_query("delete from faces where face_file_id=".$row['face_file_id']);
      continue;
    }
    $db_face_filename_with_host="faces/face_".$row['face_file_id'].".jpeg";
    $details=$row['details'];

    $cvec2 = puzzle_fill_cvec_from_file($db_face_filename);

    # Compute the distance between both signatures
    $d = puzzle_vector_normalized_distance($cvec1, $cvec2);
     
    //if ($x==0) { echo " distance=".$d; $x=1;}
    # Are pictures similar?
    if ($d <= 0.50 ) {
      $table_row.="<tr><td><img src='$db_face_filename_with_host'></td><td> <b>distance=$d</b>;;$details</td></tr>\n";
    } 
}

?>
<table border=1>
<tr><td><img src="<?=$resized_cropped_face_picture_with_host;?>"></td><td>THIS FACE IS SIMILAR TO:<br> smaller distance means more similar</td></tr>
<?=$table_row;?>
</table>
<hr>
<?
} //end of foreach $list_faces_location_in_original_picture
?>