<?php

include("config.php");


$action=$data["action"];

if($action=='showfee'){
	$eyid=$data["eyid"];
	$loginid=$data["loginid"];
	$studentid=$data["studentid"];
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="SELECT f.*,p.Name as studentname,es.EARYearID,es.GradeID,
	(select Name from tblgrade where AID=es.GradeID) as gname FROM tblfee f, 
	tblstudentprofile p ,tblearstudent es , tblearyear ey where es.EARYearID=ey.AID and f.EARStudentID=es.AID 
	and es.StudentID=p.AID and f.LoginID='{$loginid}' and p.AID='{$studentid}' and ey.AID='{$eyid}'
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"paytypeid"=>$row["PayTypeID"],
				"amt"=>$row["Amt"],
				"disc"=>$row["Disc"],
				"tax"=>$row["Tax"],
				"total"=>$row["Total"],
				"cash"=>$row["Cash"],
				"mobile"=>$row["Mobile"],
                "mobilermk"=>$row["MobileRmk"],
				"totalpay"=>$row["TotalPay"],
				"remain"=>$row["Remain"],
				"date"=>$row["Date"],
				"vno"=>$row["VNO"],
				"studentname"=>$row["studentname"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"gname"=>$row['gname'],
            ));
        }
    }
    
    $sql_total="";
	$sql_total="SELECT f.AID FROM tblfee f, 
	tblstudentprofile p ,tblearstudent es , tblearyear ey where es.EARYearID=ey.AID and f.EARStudentID=es.AID 
	and es.StudentID=p.AID and f.LoginID='{$loginid}' and p.AID='{$studentid}' and ey.AID='{$eyid}'
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

if($action=='showfee1'){
	
    $sql_arr=array(); 
	$eyid=$data["eyid"];
	$loginid=$data["loginid"];
	$studentid=$data["studentid"];
    $sql="SELECT f.*,p.Name as studentname,es.EARYearID,es.GradeID,
	(select Name from tblgrade where AID=es.GradeID) as gname FROM tblfee f, 
	tblstudentprofile p ,tblearstudent es , tblearyear ey where es.EARYearID=ey.AID and f.EARStudentID=es.AID 
	and es.StudentID=p.AID and f.LoginID='{$loginid}' and p.AID='{$studentid}' and ey.AID='{$eyid}'
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"earstudentid"=>$row["EARStudentID"],
				"paytypeid"=>$row["PayTypeID"],
				"amt"=>$row["Amt"],
				"disc"=>$row["Disc"],
				"tax"=>$row["Tax"],
				"total"=>$row["Total"],
				"cash"=>$row["Cash"],
				"mobile"=>$row["Mobile"],
                "mobilermk"=>$row["MobileRmk"],
				"totalpay"=>$row["TotalPay"],
				"remain"=>$row["Remain"],
				"date"=>$row["Date"],
				"vno"=>$row["VNO"],
				"studentname"=>$row["studentname"],
				"earyearid"=>$row["EARYearID"],
				"gradeid"=>$row["GradeID"],
				"gname"=>$row["gname"],
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

if($action=='showfeedetail'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;
	$feevno=$data["feevno"];
    $sql="SELECT * FROM tblfeedetail where FeeVNO='{$feevno}'
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "vno"=>$row["VNO"],
				"cash"=>$row["Cash"],
				"mobile"=>$row["Mobile"],
				"mobilermk"=>$row["MobileRmk"],
				"totalamt"=>$row["TotalAmt"],
				"payuser"=>$row["PayUser"],
				"paydate"=>$row["PayDate"],
				"date"=>$row["Date"],
				"loginid"=>$row["LoginID"],
                "feevno"=>$row["FeeVNO"],
				
            ));
        }
    }
    
    $sql_total="";
	$sql_total="SELECT AID FROM tblfeedetail where FeeVNO='{$feevno}'
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

if($action=='showfeedetail1'){
	
    $sql_arr=array(); 
	$feevno=$data["feevno"];
    $sql="SELECT * FROM tblfeedetail where FeeVNO='{$feevno}'
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "vno"=>$row["VNO"],
				"cash"=>$row["Cash"],
				"mobile"=>$row["Mobile"],
				"mobilermk"=>$row["MobileRmk"],
				"totalamt"=>$row["TotalAmt"],
				"payuser"=>$row["PayUser"],
				"paydate"=>$row["PayDate"],
				"date"=>$row["Date"],
				"loginid"=>$row["LoginID"],
                "feevno"=>$row["FeeVNO"],
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}

?>