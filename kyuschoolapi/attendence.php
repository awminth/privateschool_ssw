<?php

include("config.php");


$action=$data["action"];

if($action=='showattendance'){
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
	$month=$data["month"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select sa.*,g.Name as gradename, sp.Name as studentname from tblstudentattendance sa, 
	tblearstudent es, tblearyear ey, tblgrade g, tblstudentprofile sp where 
	sa.LoginID='{$loginid}' and sp.AID='{$studentid}' and ey.AID='{$eyid}' and Month(sa.AttendanceDate)='{$month}' 
	and sa.EARStudentID=es.AID and sa.EARYearID=ey.AID and es.StudentID=sp.AID and 
	sa.GradeID=g.AID
    order by AID desc limit $offset,$limit_per_page";



    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"attendancedate"=>$row["AttendanceDate"],
				"date"=>$row["Date"],
				"status"=>$row["Status"],
				"rmk"=>$row["StatusRmk"],
				"gradename"=>$row["gradename"],
				"studentname"=>$row["studentname"],
				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select sa.AID from tblstudentattendance sa, 
	tblearstudent es, tblearyear ey, tblgrade g, tblstudentprofile sp where sa.LoginID='{$loginid}' and sp.AID='{$studentid}'
	 and ey.AID='{$eyid}' and Month(sa.AttendanceDate)='{$month}' 
	and sa.EARStudentID=es.AID and sa.EARYearID=ey.AID and es.StudentID=sp.AID and 
	sa.GradeID=g.AID  order by AID desc";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showattendance1'){
	
    $sql_arr=array(); 
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
	$month=$data['month'];
    $sql="select sa.*,g.Name as gradename, sp.Name as studentname from tblstudentattendance sa, 
	tblearstudent es, tblearyear ey, tblgrade g, tblstudentprofile sp where sa.LoginID='{$loginid}' and sp.AID='{$studentid}' and ey.AID='{$eyid}'
	 and Month(sa.AttendanceDate)='{$month}'  and sa.EARStudentID=es.AID and sa.EARYearID=ey.AID and es.StudentID=sp.AID and sa.GradeID=g.AID
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"attendancedate"=>$row["AttendanceDate"],
				"date"=>$row["Date"],
				"status"=>$row["Status"],
				"rmk"=>$row["StatusRmk"],
				"gradename"=>$row["gradename"],
				"studentname"=>$row["studentname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

if($action=='showpresent'){
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
	$month=$data["month"];
	
	$present="0";
		
    $sql="select count(s.Status) as count,sp.Name,es.StudentID,s.*  
	from tblstudentattendance s,tblearstudent es,tblstudentprofile sp 
	 where s.EARStudentID=es.AID and es.StudentID=sp.AID and 
	 Month(s.AttendanceDate)='{$month}' and 
	 s.EARYearID={$eyid} and sp.AID={$studentid} and s.Status=1 group by sp.AID";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
           $present=$row['count'];
        }
    }
	echo $present; 
   
	
}

if($action=='showabsent'){
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
	$month=$data["month"];
	
	$absent="0";
		
    $sql="select count(s.Status) as count,sp.Name,es.StudentID,s.*  
	from tblstudentattendance s,tblearstudent es,tblstudentprofile sp 
	 where s.EARStudentID=es.AID and es.StudentID=sp.AID and 
	 Month(s.AttendanceDate)='{$month}' and 
	 s.EARYearID={$eyid} and sp.AID={$studentid} and s.Status=0 group by sp.AID";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
           $absent=$row['count'];
        }
    }
	echo $absent; 
   
	
}



?>