<?php

include("config.php");
$action=$data["action"];

if($action=='showannounce'){
	$loginid=$data["loginid"];
	$parentuserid=$data["parentuserid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="SELECT a.*,p.Name as pname FROM tblaccoumentparent a,tblstudentprofile p  where
     p.AID=a.StudentID and a.LoginID='{$loginid}' and a.ParentUserID='{$parentuserid}' 
    order by a.AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],
				"file"=>$row["File"]==null ? '' : $row['File'],
				"date"=>$row["Date"],
				"parentuserid"=>$row["ParentUserID"],
                "studentname"=>$row["pname"],
				
            ));
        }
    }
    
    $sql_total="";
	$sql_total="SELECT a.AID FROM tblaccoumentparent a,tblstudentprofile p  where
     p.AID=a.StudentID and a.LoginID='{$loginid}' and a.ParentUserID='{$parentuserid}' 
    order by a.AID desc ";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showannounce1'){
    $sql_arr=array(); 
	$loginid=$data["loginid"];
	$parentuserid=$data["parentuserid"];
    $sql="SELECT * FROM tblaccoumentparent where LoginID='{$loginid}' and ParentUserID='{$parentuserid}' order by AID desc";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],
				"file"=>$row["File"],
				"date"=>$row["Date"],
				"parentuserid"=>$row["ParentUserID"],
            ));
        }
    }    
    
    echo json_encode(
        array( 	
            "data"=>$sql_arr 
        ));
	
}

if($action=='showparentannouncewithdate'){
    $sql_arr=array(); 
	$loginid=$data["loginid"];
	$parentuserid=$data["parentuserid"];
	$startdate=$data["startdate"];
	$enddate=$data["enddate"];
    $sql="SELECT * FROM tblaccoumentparent where LoginID='{$loginid}' and ParentUserID='{$parentuserid}' 
	and Date>='{$startdate}' and Date<='{$enddate}' order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],
				"file"=>$row["File"],
				"date"=>$row["Date"],
				"parentuserid"=>$row["ParentUserID"],
            ));
        }
    }    
    
    echo json_encode(
        array( 	
            "data"=>$sql_arr 
        ));
	
}

if($action=='showannouncewithdate'){
    $sql_arr=array(); 
	$loginid=$data["loginid"];
	$stdate=$data["startdate"];
	$enddate=$data["enddate"];
    $sql="SELECT * FROM tblaccoumentall where LoginID='{$loginid}' and Date>='{$stdate}' and Date<='{$enddate}' order by AID desc";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],				
				"date"=>$row["Date"],
            ));
        }
    }    
    
    echo json_encode(
        array( 	
            "data"=>$sql_arr 
        ));
	
}

if($action=='showannounceall'){
	$loginid=$data["loginid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="SELECT * FROM tblaccoumentall where LoginID='{$loginid}' order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],				
				"date"=>$row["Date"],
				
            ));
        }
    }
    
    $sql_total="";
	$sql_total="SELECT AID FROM tblaccoumentall where LoginID='{$loginid}' order by AID desc ";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showannounceall1'){
    $sql_arr=array(); 
	$loginid=$data["loginid"];
    $sql="SELECT * FROM tblaccoumentall where LoginID='{$loginid}' order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"desc"=>$row["Description"],				
				"date"=>$row["Date"],
				
            ));
        }
    }    
    
    echo json_encode(
        array( 	
            "data"=>$sql_arr 
        ));
	
}


?>