
<?php 

include "config.php";

$id = $_POST["id"];
$name = $_POST["name"];
$college = $_POST["college"];
$city = $_POST["city"];
$state = $_POST["state"];


if($id){
    $update = "UPDATE `login2` SET `Name`='$name',`college`='$college',`city`='$city',`state`='$state' WHERE id=$id";
    $result = mysqli_query($conn,$update) or die("update faild");
}
$run_query = "SELECT * FROM login2";
$data= mysqli_query($conn,$run_query) or die("select Faild");

$array_data = mysqli_fetch_all($data,MYSQLI_ASSOC);
$json = json_encode($array_data,true);  


echo $json;




?>

