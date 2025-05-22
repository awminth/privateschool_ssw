<?php

include("config.php");



$action=$data['action'];


if($action=='login')
{
	
	$username=$data["username"];
    $password=$data["password"];

    $sql="select * from tblparent where UserName='{$username}'";
    $result=mysqli_query($con,$sql);
	
	$arr_sql=array();
	$arr_amt=array();
    if(mysqli_num_rows($result)>0){
        $data=mysqli_fetch_array($result);
        if($password==$data['Password']) {
						
				
			echo json_encode(
                    array(
                        "status"=>true,
                        "aid"=>$data['AID'],
						"username"=>$data['UserName'],
						"password"=>$data['Password'],
						"loginid"=>$data["LoginID"],
						"studentid"=>"0",
                        "message" => "Successful"
                    ));
			
			
              
        }else{

          echo json_encode(
                array(
                    "status"=>false,
                    "message" => "Incorrect password"                     
                ));

        }
            
        
    }else{
        echo json_encode(
            array(
                "status"=>false,
                "message" => "UserName Incorrect"                     
            ));
    }
	
}

if($action=='showlog'){
	
    $sql_arr=array(); 
	$aid=$data["aid"];	
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select l.*,u.UserName as username from tbllog l, tblparent u 
    where l.UserAID=u.AID and u.AID='{$aid}' order by l.AID desc 
    limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "description"=>$row["Description"],
				"date"=>$row["Date"],
				"username"=>$row["username"],
				
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select l.AID from tbllog l, tbluser u 
    where l.UserAID=u.AID and u.AID='{$aid}' order by l.AID desc";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showlog1'){
	
    $sql_arr=array(); 
	$aid=$data["aid"];			
    $sql="select l.*,u.UserName as username from tbllog l, tbluser u 
    where l.UserAID=u.AID and u.AID='{$aid}' order by l.AID desc 
   ";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "description"=>$row["Description"],
				"date"=>$row["Date"],
				"username"=>$row["username"],
				
            ));
        }
    }
  
    echo json_encode(
        array(    
			"total"=>0,   		
            "data"=>$sql_arr 
        ));
	
}


?>