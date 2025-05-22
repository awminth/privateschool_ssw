<?php

include("config.php");


$action=$data["action"];

if($action=='showexam'){
	$eyid=$data["eyid"];
	$loginid=$data["loginid"];
	$studentid=$data["studentid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select ex.*,sp.name as studentname,sp.AID, count(ex.aid) as countexam,sum(ex.GetMark) as summark, et.Name examname ,
	(select Name from tblgrade where AID=es.GradeID) as gname 
	from tblexam ex, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et  where ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and 
	ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and es.EARYearID=ey.AID and ey.AID='{$eyid}' group by ex.ExamTypeID
    limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"examtypeid"=>$row["ExamTypeID"],
				"subjectid"=>$row["SubjectID"],
				"paymark"=>$row["PayMark"],
				"getmark"=>$row["GetMark"],
				"result"=>$row["Result"],
				"d"=>$row["D"],
				"date"=>$row["Date"],
				"countexam"=>$row["countexam"],
				"summark"=>$row["summark"],
				"examname"=>$row["examname"],
				"gname"=>$row["gname"],
				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select ex.AID
	from tblexam ex, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et  where ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and 
	ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and es.EARYearID=ey.AID and ey.AID='{$eyid}' group by ex.ExamTypeID";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showexam1'){
	
    $sql_arr=array(); 
	$eyid=$data["eyid"];
    $sql="select ex.*,sp.name as studentname,sp.AID, count(ex.aid) as countexam,sum(ex.GetMark) as summark, et.Name examname 
	from tblexam ex, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et  where ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and 
	ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and es.EARYearID=ey.AID and ey.AID='{$eyid}' group by ex.ExamTypeID";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"examtypeid"=>$row["ExamTypeID"],
				"subjectid"=>$row["SubjectID"],
				"paymark"=>$row["PayMark"],
				"getmark"=>$row["GetMark"],
				"result"=>$row["Result"],
				"d"=>$row["D"],
				"date"=>$row["Date"],
				"countexam"=>$row["countexam"],
				"summark"=>$row["summark"],
				"examname"=>$row["examname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

if($action=='showexamdetail'){
	$eyid=$data["eyid"];
	$loginid=$data["loginid"];
	$studentid=$data["studentid"];
	$examtypeid=$data["examtypeid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select ex.*,sp.name as studentname,sp.AID, et.Name examname, sb.Name as subjectname from tblexam ex, 
	tblsubject sb, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et where ex.SubjectID=sb.AID 
	and ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and ex.ExamTypeID='{$examtypeid}'
	and es.EARYearID=ey.AID and ey.AID='{$eyid}'
    order by ex.AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"examtypeid"=>$row["ExamTypeID"],
				"subjectid"=>$row["SubjectID"],
				"paymark"=>$row["PayMark"],
				"getmark"=>$row["GetMark"],
				"result"=>$row["Result"],
				"d"=>$row["D"],
				"date"=>$row["Date"],
				"subjectname"=>$row["subjectname"],
				"studentname"=>$row["studentname"],
				"examname"=>$row["examname"],
				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select ex.AID from tblexam ex, 
	tblsubject sb, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et where ex.SubjectID=sb.AID 
	and ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and es.EARYearID=ey.AID and ey.AID='{$eyid}'
    and ex.ExamTypeID='{$examtypeid}' order by AID";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showexamdetail1'){
	
    $sql_arr=array(); 
	$eyid=$data["eyid"];
	$loginid=$data["loginid"];
	$studentid=$data["studentid"];
	$examtypeid=$data["examtypeid"];
    $sql="select ex.*,sp.name as studentname,sp.AID, count(ex.aid) as countexam,sum(ex.GetMark) as summark, et.Name examname 
	from tblexam ex, tblearstudent es, tblstudentprofile sp, tblearyear ey, tblexamtype et  where ex.ExamTypeID=et.AID and ex.LoginID='{$loginid}' and 
	ex.EARStudentID=es.AID and es.StudentID=sp.AID and sp.AID='{$studentid}' and es.EARYearID=ey.AID and ey.AID='{$eyid}' and ex.ExamTypeID='{$examtypeid}' order by ex.AID";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"examtypeid"=>$row["ExamTypeID"],
				"subjectid"=>$row["SubjectID"],
				"paymark"=>$row["PayMark"],
				"getmark"=>$row["GetMark"],
				"result"=>$row["Result"],
				"d"=>$row["D"],
				"date"=>$row["Date"],
				"countexam"=>$row["countexam"],
				"summark"=>$row["summark"],
				"examname"=>$row["examname"],
				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}



?>