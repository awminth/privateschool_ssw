<?php
include('../config.php');

$action = $_POST["action"];
$userid=$_SESSION['userid'];

if($action == 'changepermission'){  
    $A1=$_POST["A1"]=='on'?1:0;
    $A2=$_POST["A2"]=='on'?1:0;
    $A3=$_POST["A3"]=='on'?1:0;
    $A4=$_POST["A4"]=='on'?1:0;
    $A5=$_POST["A5"]=='on'?1:0;
    $A6=$_POST["A6"]=='on'?1:0;
    $A7=$_POST["A7"]=='on'?1:0;
    $A8=$_POST["A8"]=='on'?1:0;
    $A9=$_POST["A9"]=='on'?1:0;
    $A10=$_POST["A10"]=='on'?1:0;
    $A11=$_POST["A11"]=='on'?1:0;
    $A12=$_POST["A12"]=='on'?1:0;
    $A13=$_POST["A13"]=='on'?1:0;
    $A14=$_POST["A14"]=='on'?1:0;
    $A15=$_POST["A15"]=='on'?1:0;

    $aid=$_POST['aid'];

    $sql="update tbllogin set A1={$A1},A2={$A2},A3={$A3},A4={$A4},A5={$A5},
    A6={$A6},A7={$A7},A8={$A8},A9={$A9},A10={$A10},
    A11={$A11},A12={$A12},A13={$A13},A14={$A14},A15={$A15} where AID={$aid}" ;

    // $sql="update tbllogin set A1={$A1},A2={$A2},A3={$A3},A4={$A4},A5={$A5},A6={$A6}  
    // where AID={$aid}" ;

   
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
  


}



?>