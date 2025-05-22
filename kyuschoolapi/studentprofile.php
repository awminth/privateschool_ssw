<?php

include("config.php");


$action=$data["action"];

if($action=='showstudent')
{
	$parentid=$data["parentid"];
	$loginid=$data['loginid'];
	 $sql="SELECT s.* FROM tblparent_student p, tblstudentprofile s where 
	 s.AID=p.StudentID and  p.ParentID='{$parentid}'";
	$result=mysqli_query($con,$sql);
	$sql_arr=array(); 
	if(mysqli_num_rows($result)>0)	  
		
	{
		while($row = mysqli_fetch_array($result)){

			array_push($sql_arr,array(
				"aid"=>$row["AID"],
					"loginid"=>$row["LoginID"],
					"name"=>$row["Name"],
					"studentid"=>$row["StudentID"],
					"dob"=>$row["DOB"],
					"age"=>$row["Age"],
					"nationality"=>$row["Nationality"],
					"religion"=>$row["Religion"],
					"fathername"=>$row["FatherName"],
					"fatherwork"=>$row["FatherWork"],
					"mothername"=>$row["MotherName"],
					"motherwork"=>$row["MotherWork"],
					"img"=>$row["Img"],
					"gender"=>$row["Gender"],)

			);

		}	
				
	}
	echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
}

if($action=='showprofile1'){
	
    $sql_arr=array(); 
	$studentid=$data["studentid"];
    $sql="SELECT * FROM tblstudentprofile where AID='{$studentid}'";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"name"=>$row["Name"],
				"studentid"=>$row["StudentID"],
				"dob"=>$row["DOB"],
				"age"=>$row["Age"],
				"nationality"=>$row["Nationality"],
				"religion"=>$row["Religion"],
				"fathername"=>$row["FatherName"],
				"fatherwork"=>$row["FatherWork"],
				"mothername"=>$row["MotherName"],
				"motherwork"=>$row["MotherWork"],
				"img"=>$row["Img"],
				"gender"=>$row["Gender"],
            ));
        }
    }    
    
    echo json_encode(
        array(    	
            "data"=>$sql_arr 
        ));
	
}

if($action=='showprofile')
{
	$studentid=$data["studentid"];
	 $sql="SELECT * FROM tblstudentprofile where AID='{$studentid}'";
	$result=mysqli_query($con,$sql);
	$output=array();
	if(mysqli_num_rows($result)>0)
		  
		
	{
		$row = mysqli_fetch_array($result);
		
				echo json_encode(
			array(
				'data'=>[
					"aid"=>$row["AID"],
					"loginid"=>$row["LoginID"],
					"name"=>$row["Name"],
					"studentid"=>$row["StudentID"],
					"dob"=>$row["DOB"],
					"age"=>$row["Age"],
					"nationality"=>$row["Nationality"],
					"religion"=>$row["Religion"],
					"fathername"=>$row["FatherName"],
					"fatherwork"=>$row["FatherWork"],
					"mothername"=>$row["MotherName"],
					"motherwork"=>$row["MotherWork"],
					"img"=>$row["Img"],
					"gender"=>$row["Gender"],
					]
						
			));
		
				
			
		
			
	}
}

?>