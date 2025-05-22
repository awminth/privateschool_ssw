<?php
include('../config.php');

$action = $_POST["action"];

if($action=='updatepassword'){
    $aid=$_SESSION['userid'];   
    $newpassword=$_POST["newpassword"];
    $sql="update tbllogin set Password='{$newpassword}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        $_SESSION['userpassword']=$newpassword;
        echo 1;
    }
    else{
        echo 0;
    }
}



?>