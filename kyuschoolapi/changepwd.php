<?php

include("config.php");


$action=$data["action"];


if($action=='changepwd')
{
      $oldpassword=$data["oldpassword"];
      $newpassword=$data["newpassword"];
	  $aid=$data["aid"];
	  $sql_check="select * from tblparent where Password='{$oldpassword}'";
	  $result=mysqli_query($con,$sql_check);
	  
		   if(mysqli_num_rows($result) > 0){
			   
			   $sql="UPDATE tblparentuser SET Password='{$newpassword}' WHERE AID='{$aid}'";
				if(mysqli_query($con,$sql)){
					echo "success";
				}
				else{
					echo "fail";
				}	  
			}
}






?>