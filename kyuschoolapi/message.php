<?php

include("config.php");


$action=$data["action"];

if($action=='show'){

    $userid=$data['userid'];
    $loginid=$data['loginid'];	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
   // $sql="select * from tblchat where ToUser={$userid} and LoginID={$loginid} order by Date 
      //limit $offset,$limit_per_page";
	  
	  $sql = "select * from (select * from tblchat 
    where LoginID={$loginid} and (FromUser='1' and ToUser='{$userid}') or 
    (FromUser='{$userid}' and ToUser='1') order by AID Desc limit {$limit_per_page}) var1 
    order by AID";
	

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "loginid"=>$row["LoginID"],
                "fromuser"=>$row["FromUser"],
                "touser"=>$row["ToUser"],
                "message"=>$row["Message"],
                "date"=>$row["Date"],
                "view"=>$row["View"],
                "messagetype"=>$row["MessageType"],				
               
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select AID from tblchat where ToUser={$userid} and LoginID={$loginid}";	
	$record = mysqli_query($con,$sql_total) or die("fail query");
	$total_record = mysqli_num_rows($record);
	$total_links = ceil($total_record/$limit_per_page);
    echo json_encode(
        array(    
			"total"=>$total_record,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='save'){

    $fromuser=1;
    $userid=$data['userid'];
    $loginid=$data['loginid'];
    $message=$data['message'];
    $date=date('Y-m-d h:i:s');

    $sql="insert into tblchat (FromUser,ToUser,Message,Date,View,LoginID,MessageType) 
    values ({$fromuser},{$userid},'{$message}','{$date}','No Seen',{$loginid},'sender')";
    if(mysqli_query($con,$sql)){
        echo 'success';
    }else{
        echo 'error'; 
    }


}


if($action=='alldelete'){

    $userid=$data['userid'];
    $loginid=$data['loginid'];	
    
	$sql="delete from tblchat where FromUser={$userid}";
	if(mysqli_query($con,$sql)){
		echo 'success';
	}else{
		echo 'error';
	}
	
	
}

if($action=='onedelete'){

    $userid=$data['userid'];
    $loginid=$data['loginid'];	
	 $aid=$data['aid'];	
    
	$sql="delete from tblchat where AID={$aid}";
	if(mysqli_query($con,$sql)){
		echo 'success';
	}else{
		echo 'error';
	}
	
	
}


if($action=='active'){

    $userid=$data['userid'];
    $loginid=$data['loginid'];	
	 $status=$data['status'];	
    
	$sql="update tblparent set Status={$status} where AID={$userid}";
	if(mysqli_query($con,$sql)){
		echo 'success';
	}else{
		echo 'error';
	}
	
	
}

?>