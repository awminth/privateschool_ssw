<?php

include("config.php");


$action=$data["action"];

if($action=='showearyear'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select * from tblearyear order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"name"=>$row["Name"],		
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select AID from tblearyear order by AID desc";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showearyear1'){
	
    $sql_arr=array(); 

    $sql="select * from tblearyear order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
				"name"=>$row["Name"],				
            ));
        }
    }    
    
    echo json_encode(
        array(    			
            "data"=>$sql_arr 
        ));
	
}



?>