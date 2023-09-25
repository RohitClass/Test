
<?php

include "config.php";

$id = $_POST["id"];
// echo $id;
if($id){

    $run_query = "SELECT * FROM login2 WHERE id = $id";
    $data= mysqli_query($conn,$run_query) or die("select Faild");
    
    $array_data = mysqli_fetch_all($data,MYSQLI_ASSOC);
    $json = json_encode($array_data,true);  
    
    
    echo $json;
}



?>