<?php
$conn = mysqli_connect("localhost","root","","test") or die("connection Faild"); 

$run_query = "SELECT * FROM data  ORDER BY date DESC LIMIT 5";
$data= mysqli_query($conn,$run_query) or die("select Faild");

$array_data = mysqli_fetch_all($data,MYSQLI_ASSOC);
$json = json_encode($array_data,true);  
// print_r($json);
echo $json;
?>
