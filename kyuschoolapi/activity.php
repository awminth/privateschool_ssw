<?php

include("config.php");


$action=$data["action"];

if($action=='showactivity'){
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select a.*,sp.Name as studentname, ey.Name as earyearname,
    (select Name from tblgrade where AID=es.GradeID) as gname from tblstudentactivity a, tblearstudent es,
	tblstudentprofile sp, tblearyear ey where a.EARStudentID=es.AID and es.StudentID=sp.AID and es.EARYearID=ey.AID and ey.AID='{$eyid}'
	and a.LoginID='{$loginid}' and sp.AID='{$studentid}'
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"desc"=>$row["Description"],
				"date"=>$row["Date"],
				"studentname"=>$row["studentname"],
				"earyearname"=>$row["earyearname"],
                "gname"=>$row["gname"],
				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select a.AID from tblstudentactivity a, tblearstudent es,
	tblstudentprofile sp, tblearyear ey where a.EARStudentID=es.AID and es.StudentID=sp.AID and es.EARYearID=ey.AID and ey.AID='{$eyid}'
	and a.LoginID='{$loginid}' and sp.AID='{$studentid}'
    order by AID desc";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showactivity1'){
	
    $sql_arr=array(); 
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
    $sql="select a.*,sp.Name as studentname, ey.Name as earyearname from tblstudentactivity a, tblearstudent es,
	tblstudentprofile sp, tblearyear ey where a.EARStudentID=es.AID and es.StudentID=sp.AID and es.EARYearID=ey.AID and ey.AID='{$eyid}'
	and a.LoginID='{$loginid}' and sp.AID='{$studentid}'
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"desc"=>$row["Description"],
				"date"=>$row["Date"],
				"studentname"=>$row["studentname"],
				"earyearname"=>$row["earyearname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}



?>