<?php

include "config.php";

$name = $_POST["name"];
$college = $_POST["college"];
$city = $_POST["city"];
$state = $_POST["state"];

$query = "INSERT INTO `login2` (`id`, `Name`, `college`, `city`, `state`) VALUES (NULL, '$name', '$college', '$city', '$state')";

$result = mysqli_query($conn,$query) or die("query faild");


$run_query = "SELECT * FROM login2";
$data= mysqli_query($conn,$run_query) or die("select Faild");

$array_data = mysqli_fetch_all($data,MYSQLI_ASSOC);
$json = json_encode($array_data,true);  


echo $json;





?>