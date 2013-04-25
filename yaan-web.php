<?php
header("Content-type: text/html; charset=utf-8");

$name= $_GET["name"];

$url = "http://opendata.baidu.com/api.php?resource_id=6109&format=json&ie=utf-8&oe=utf-8&query=".urlencode($name)."&from_mid=1&cb=bd__cbs__v29lzb";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
$data = str_replace(array('bd__cbs__v29lzb(',');'),array(''), $data);
// var_dump($data);
$a = json_decode($data,true);
// $a = json_decode($data);
foreach ($a[data][0][disp_data] as $value) {
 echo $value[name]."\n".$value[age]."岁\n".$value[sex]."\n".$value[desc]."。电话：".$value[phone]."\n".$value[remarks]."\n\n";
}
?>
