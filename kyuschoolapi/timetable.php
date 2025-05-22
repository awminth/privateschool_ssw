<?php

include("config.php");


$action=$data["action"];

if($action=='showactivity'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="SELECT st.*,ey.Name as earyearname,g.Name as gradename,t.Name as timename, s.Name as teachername,sb.Name as subjectname 
	FROM tblstudenttimetable st,tblearyear ey,tblgrade g, tbltime t, tblstaff s, tblsubject sb where st.EARYearID=ey.AID 
	and st.GradeID=g.AID and st.TimeID=t.AID and st.TeacherID=s.AID and st.SubjectID=sb.AID
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"timeid"=>$row["TimeID"],
				"teacherid"=>$row["TeacherID"],
				"subjectid"=>$row["SubjectID"],
				"date"=>$row["Date"],
				"dname"=>$row["DName"],
				"earyearname"=>$row["earyearname"],
				"gradename"=>$row["gradename"],
				"timename"=>$row["timename"],
				"teachername"=>$row["teachername"],
				"subjectname"=>$row["subjectname"],
				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="SELECT st.*
	FROM tblstudenttimetable st,tblearyear ey,tblgrade g, tbltime t, tblstaff s, tblsubject sb where st.EARYearID=ey.AID 
	and st.GradeID=g.AID and st.TimeID=t.AID and st.TeacherID=s.AID and st.SubjectID=sb.AID
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

    $sql="SELECT st.*,ey.Name as earyearname,g.Name as gradename,t.Name as timename, s.Name as teachername,sb.Name as subjectname 
	FROM tblstudenttimetable st,tblearyear ey,tblgrade g, tbltime t, tblstaff s, tblsubject sb where st.EARYearID=ey.AID 
	and st.GradeID=g.AID and st.TimeID=t.AID and st.TeacherID=s.AID and st.SubjectID=sb.AID
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"timeid"=>$row["TimeID"],
				"teacherid"=>$row["TeacherID"],
				"subjectid"=>$row["SubjectID"],
				"date"=>$row["Date"],
				"dname"=>$row["DName"],
				"earyearname"=>$row["earyearname"],
				"gradename"=>$row["gradename"],
				"timename"=>$row["timename"],
				"teachername"=>$row["teachername"],
				"subjectname"=>$row["subjectname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

if($action=='showtimetable'){
	$sql_arr=array(); 
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];

    $sql="select s.*,sb.Name as sbname,g.Name as gname,t.Name as tname,ey.Name as eyname,ti.Name as tiname from tblstudenttimetable s,
	tblsubject sb,tblgrade g,tblstaff t,tbltime ti ,tblearyear ey 
	where s.EARYearID=ey.AID and s.TimeID=ti.AID and s.TeacherID=t.AID and s.SubjectID=sb.AID and s.GradeID=g.AID and 
	 s.EARYearID={$eyid} and s.LoginID={$loginid} and 
   s.GradeID= (select GradeID from  tblearstudent where StudentID={$studentid} and EARYearID={$eyid});";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"timeid"=>$row["TimeID"],
				"teacherid"=>$row["TeacherID"],
				"subjectid"=>$row["SubjectID"],
				"date"=>$row["Date"],
				"dname"=>$row["DName"],
				"earyearname"=>$row["eyname"],
				"gradename"=>$row["gname"],
				"timename"=>$row["tiname"],
				"teachername"=>$row["tname"],
				"subjectname"=>$row["sbname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

if($action=='showday'){
	$sql_arr=array(); 
	$eyid=$data["eyid"];
	$studentid=$data["studentid"];
	$loginid=$data["loginid"];
	$day=$data["day"];

    $sql="select s.*,sb.Name as sbname,g.Name as gname,t.Name as tname,ey.Name as eyname,ti.Name as tiname from tblstudenttimetable s,
	tblsubject sb,tblgrade g,tblstaff t,tbltime ti ,tblearyear ey 
	where s.EARYearID=ey.AID and s.TimeID=ti.AID and s.TeacherID=t.AID and s.SubjectID=sb.AID and s.GradeID=g.AID and 
	 s.EARYearID={$eyid} and s.LoginID={$loginid} and s.DName='{$day}' and 
   s.GradeID= (select GradeID from  tblearstudent where StudentID={$studentid} and EARYearID={$eyid}) order by s.TimeID;";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"timeid"=>$row["TimeID"],
				"teacherid"=>$row["TeacherID"],
				"subjectid"=>$row["SubjectID"],
				"date"=>$row["Date"],
				"dname"=>$row["DName"],
				"earyearname"=>$row["eyname"],
				"gradename"=>$row["gname"],
				"timename"=>$row["tiname"],
				"teachername"=>$row["tname"],
				"subjectname"=>$row["sbname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}



?>