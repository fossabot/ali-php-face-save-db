<?php
// DB Import : Ali Can Gönüllü
// xxx.php?pic=pics/file_name.jpg OR xxx.php?pic=file_name.jpg

include "class/yuz_tanilama.php";
$get = $_GET["pic"];

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $_GET["pic"]);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($ch, CURLOPT_USERPWD, "user:pass");
//$result = curl_exec($ch);
//curl_close($ch);

if(empty($get)) {
	echo 'Yüz Tanımlanamadı!';
} else {
$detector = new alicangonullu\YuzTanila('class/algila.dat');
$detector->faceDetect($get);
// $detector->faceDetect($result);
$tojson = $detector->toJson();
include ("conn.php");

$type = pathinfo($get, PATHINFO_EXTENSION);
$data = file_get_contents($get);
$base64 = base64_encode($data);

$update = $db->prepare("INSERT INTO face_table(face_title, face_json, face_data) VALUES (:title, :json, :data) ");
$update->bindValue(':title', $get);
$update->bindValue(':json', $tojson);
$update->bindValue(':data', $base64);
$db->beginTransaction();
$update->execute();
$db->commit();
if($update){
echo '<script>
alert("Başarılı | Success");
</script>';
}
}
?>
