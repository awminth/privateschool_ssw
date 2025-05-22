<?php

include("config.php");


$action=$data["action"];

if($action=='showpurchase'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblitem r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "code"=>$row["Code"],
				"name"=>$row["Name"],
				"categoryid"=>$row["CategoryID"],
				"unitid"=>$row["UnitID"],
				"codeid"=>$row["CodeID"],
				"supplierid"=>$row["SupplierID"],
				"locationid"=>$row["LocationID"],
				"bqty"=>$row["BQty"],
				"cqty"=>$row["CQty"],
                "lqty"=>$row["LQty"],
				"purprice"=>$row["PurchasePrice"],
				"bsellprice"=>$row["BSellPrice"],
				"csellprice"=>$row["CSellPrice"],
				"lsellprice"=>$row["LSellPrice"],
				"totalbqty"=>$row["TotalBQty"],
				"totalcqty"=>$row["TotalCQty"],
				"totall"=>$row["TotalL"],
				"totallqty"=>$row["TotalLQty"],
				"sellqty"=>$row["SellQty"],
				"expiredate"=>$row["ExpireDate"],
				"date"=>$row["Date"],
				"sellLone"=>$row["SellLone"],
				"unit1"=>$row["Unit1"],
				"unit2"=>$row["Unit2"],
				"unit3"=>$row["Unit3"],
				"cname"=>$row["cname"],
				"uname"=>$row["uname"],
				"code1"=>$row["code1"],
				"sname"=>$row["sname"],
				"lname"=>$row["lname"],
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select r.AID from tblitem r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
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

if($action=='showpurchase1'){
	
    $sql_arr=array(); 

    $sql="select r.*, c.Name as cname, u.Name as uname, co.Code as code1,
	 s.Name as sname, l.Name as lname from tblitem r, tblcategory c, tblunit u,
	  tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "code"=>$row["Code"],
				"name"=>$row["Name"],
				"categoryid"=>$row["CategoryID"],
				"unitid"=>$row["UnitID"],
				"codeid"=>$row["CodeID"],
				"supplierid"=>$row["SupplierID"],
				"locationid"=>$row["LocationID"],
				"bqty"=>$row["BQty"],
				"cqty"=>$row["CQty"],
                "lqty"=>$row["LQty"],
				"purprice"=>$row["PurchasePrice"],
				"bsellprice"=>$row["BSellPrice"],
				"csellprice"=>$row["CSellPrice"],
				"lsellprice"=>$row["LSellPrice"],
				"totalbqty"=>$row["TotalBQty"],
				"totalcqty"=>$row["TotalCQty"],
				"totall"=>$row["TotalL"],
				"totallqty"=>$row["TotalLQty"],
				"sellqty"=>$row["SellQty"],
				"expiredate"=>$row["ExpireDate"],
				"date"=>$row["Date"],
				"sellLone"=>$row["SellLone"],
				"unit1"=>$row["Unit1"],
				"unit2"=>$row["Unit2"],
				"unit3"=>$row["Unit3"],
				"cname"=>$row["cname"],
				"uname"=>$row["uname"],
				"code1"=>$row["code1"],
				"sname"=>$row["sname"],
				"lname"=>$row["lname"],
            ));
        }
    }    
    
    echo json_encode(
        array(    
			"total"=>0,   		
            "data"=>$sql_arr 
        ));
	
}

if($action=='showremain'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblremain r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "code"=>$row["Code"],
				"name"=>$row["Name"],
				"categoryid"=>$row["CategoryID"],
				"unitid"=>$row["UnitID"],
				"codeid"=>$row["CodeID"],
				"supplierid"=>$row["SupplierID"],
				"locationid"=>$row["LocationID"],
				"bqty"=>$row["BQty"],
				"cqty"=>$row["CQty"],
                "lqty"=>$row["LQty"],
				"purprice"=>$row["PurchasePrice"],
				"bsellprice"=>$row["BSellPrice"],
				"csellprice"=>$row["CSellPrice"],
				"lsellprice"=>$row["LSellPrice"],
				"totalbqty"=>$row["TotalBQty"],
				"totalcqty"=>$row["TotalCQty"],
				"totall"=>$row["TotalL"],
				"totallqty"=>$row["TotalLQty"],
				"sellqty"=>$row["SellQty"],
				"expiredate"=>$row["ExpireDate"],
				"date"=>$row["Date"],
				"sellLone"=>$row["SellLone"],
				"unit1"=>$row["Unit1"],
				"unit2"=>$row["Unit2"],
				"unit3"=>$row["Unit3"],
				"cname"=>$row["cname"],
				"uname"=>$row["uname"],
				"code1"=>$row["code1"],
				"sname"=>$row["sname"],
				"lname"=>$row["lname"],
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblremain r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
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

if($action=='showremainlone'){
	
    $sql_arr=array();  
	$limit_per_page=$data['pagesize'];
	$pagenumber=$data['pagenumber'];	
	$offset=($pagenumber-1)*$limit_per_page;	
    $sql="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblremain r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
    order by AID desc limit $offset,$limit_per_page";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "code"=>$row["Code"],
				"name"=>$row["Name"],
				"categoryid"=>$row["CategoryID"],
				"unitid"=>$row["UnitID"],
				"codeid"=>$row["CodeID"],
				"supplierid"=>$row["SupplierID"],
				"locationid"=>$row["LocationID"],
				"bqty"=>$row["BQty"],
				"cqty"=>$row["CQty"],
                "lqty"=>$row["LQty"],
				"purprice"=>$row["PurchasePrice"],
				"bsellprice"=>$row["BSellPrice"],
				"csellprice"=>$row["CSellPrice"],
				"lsellprice"=>$row["LSellPrice"],
				"totalbqty"=>$row["TotalBQty"],
				"totalcqty"=>$row["TotalCQty"],
				"totall"=>$row["TotalL"],
				"totallqty"=>$row["TotalLQty"],
				"sellqty"=>$row["SellQty"],
				"expiredate"=>$row["ExpireDate"],
				"date"=>$row["Date"],
				"sellLone"=>$row["SellLone"],
				"unit1"=>$row["Unit1"],
				"unit2"=>$row["Unit2"],
				"unit3"=>$row["Unit3"],
				"cname"=>$row["cname"],
				"uname"=>$row["uname"],
				"code1"=>$row["code1"],
				"sname"=>$row["sname"],
				"lname"=>$row["lname"],
            ));
        }
    }
    
    $sql_total="";
	$sql_total="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblremain r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
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

if($action=='showremain1'){
	
    $sql_arr=array();  	
    $sql="select r.*, c.Name as cname, u.Name as uname, co.Code as code1, s.Name as sname, l.Name as lname from tblremain r, tblcategory c, tblunit u, tblcode co, tblsupplier s, tbllocation l where r.CategoryID=c.AID and 
	r.UnitID=u.AID and r.CodeID=co.AID and r.SupplierID=s.AID and r.LocationID=l.AID
    order by AID desc";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            array_push($sql_arr,array(
				"aid"=>$row["AID"],
                "code"=>$row["Code"],
				"name"=>$row["Name"],
				"categoryid"=>$row["CategoryID"],
				"unitid"=>$row["UnitID"],
				"codeid"=>$row["CodeID"],
				"supplierid"=>$row["SupplierID"],
				"locationid"=>$row["LocationID"],
				"bqty"=>$row["BQty"],
				"cqty"=>$row["CQty"],
                "lqty"=>$row["LQty"],
				"purprice"=>$row["PurchasePrice"],
				"bsellprice"=>$row["BSellPrice"],
				"csellprice"=>$row["CSellPrice"],
				"lsellprice"=>$row["LSellPrice"],
				"totalbqty"=>$row["TotalBQty"],
				"totalcqty"=>$row["TotalCQty"],
				"totall"=>$row["TotalL"],
				"totallqty"=>$row["TotalLQty"],
				"sellqty"=>$row["SellQty"],
				"expiredate"=>$row["ExpireDate"],
				"date"=>$row["Date"],
				"sellLone"=>$row["SellLone"],
				"unit1"=>$row["Unit1"],
				"unit2"=>$row["Unit2"],
				"unit3"=>$row["Unit3"],
				"cname"=>$row["cname"],
				"uname"=>$row["uname"],
				"code1"=>$row["code1"],
				"sname"=>$row["sname"],
				"lname"=>$row["lname"],
            ));
        }
    }   
    
    echo json_encode(
        array(      		
            "data"=>$sql_arr 
        ));
	
}

if($action=='save'){
	$codeno=$data["codeno"];	
	$itemname=$data["itemname"];
	$qty=$data["qty"];	
	$purprice=$data["purprice"];
	$sellprice=$data["sellprice"];	
	$date=$data["date"];
	$categoryid=$data["categoryid"];
	$companyid=$data["companyid"];	
	$rmk=$data["rmk"];
          
    $sql="INSERT INTO tblpurchase (txtCodeNo, txtName,txtQty,txtPurPrice,txtSellPrice,cboDate,txtRemark,CategoryID,IsCredit,CompanyAID) VALUES 
	('{$codeno}', '{$itemname}', '{$qty}', '{$purprice}', '{$sellprice}', '{$date}', '{$rmk}', '{$categoryid}','0','{$companyid}')";
    if(mysqli_query($con,$sql)){

        $sqlchk="select * from tblremain where txtCodeNo='{$codeno}'";
        $sqlchkres=mysqli_query($con,$sqlchk);
        if(mysqli_num_rows($sqlchkres) > 0){
            $rowremain= mysqli_fetch_array($sqlchkres);
            $oldqty=$rowremain["txtQty"]+$qty;
            $sqlupd="update tblremain set txtSellPrice={$sellprice},txtPurPrice={$purprice},
            txtQty={$oldqty} where txtCodeNo='{$codeno}'";
            mysqli_query($con,$sqlupd);

        }else{

            $sqlins="INSERT INTO tblremain (txtCodeNo, txtName,txtQty,txtPurPrice,txtSellPrice,cboDate,txtRemark,CategoryID,IsCredit,CompanyAID) VALUES 
            ('{$codeno}', '{$itemname}', '{$qty}', '{$purprice}', '{$sellprice}', '{$date}', '{$rmk}', '{$categoryid}','0','{$companyid}')";
            mysqli_query($con,$sqlins);
            
        }
            echo 'success';
        }else{
            echo 'error';
        }
		
}

if($action=='update'){
	$aid=$data["aid"]; 
	$codeno=$data["codeno"];	
	$itemname=$data["itemname"];
	$qty=$data["qty"];	
	$purprice=$data["purprice"];
	$sellprice=$data["sellprice"];	
	$date=$data["date"];
	$categoryid=$data["categoryid"];
	$companyid=$data["companyid"];	
	$rmk=$data["rmk"];
    $sql="UPDATE tblpurchase SET txtCodeNo='{$codeno}', txtName='{$itemname}', txtQty = '{$qty}',  txtPurPrice = '{$purprice}',  txtSellPrice = '{$sellprice}',
	cboDate='{$date}', txtRemark= '{$rmk}',CategoryID= '{$categoryid}', CompanyAID= '{$companyid}' WHERE txtAutoNo='$aid'";
    if(mysqli_query($con,$sql)){
        $sqlchk="select * from tblremain where txtCodeNo='{$codeno}'";
        $sqlchkres=mysqli_query($con,$sqlchk);
        if(mysqli_num_rows($sqlchkres) > 0){
            $rowremain= mysqli_fetch_array($sqlchkres);
            $oldqty=$rowremain["txtQty"]+$qty;
            $sqlupd="update tblremain set txtSellPrice={$sellprice},txtPurPrice={$purprice},
            txtQty={$oldqty} where txtCodeNo='{$codeno}'";
            mysqli_query($con,$sqlupd);

        }else{

            $sqlins="INSERT INTO tblremain (txtCodeNo, txtName,txtQty,txtPurPrice,txtSellPrice,cboDate,txtRemark,CategoryID,IsCredit,CompanyAID) VALUES 
            ('{$codeno}', '{$itemname}', '{$qty}', '{$purprice}', '{$sellprice}', '{$date}', '{$rmk}', '{$categoryid}','0','{$companyid}')";
            mysqli_query($con,$sqlins);
            
        }
            echo 'success';
        }else{
            echo 'error';
        }
	
	
}

if($action=='delete'){
	$aid=$data["aid"]; 	
    $code=$data["code"]; 
    $qty=$data["qty"]; 
    $sql="DELETE FROM tblpurchase WHERE txtAutoNo='$aid'";
    if(mysqli_query($con,$sql)){
        $sqlchk="select txtAutoNo from tblpurchase where txtCodeNo='{$code}'";
        $sqlchkres=mysqli_query($con,$sqlchk);
        if(mysqli_num_rows($sqlchkres) > 0){
            $sqlremain="select * from tblremain where txtCodeNo='{$code}'";
            $sqlremainres=mysqli_query($con,$sqlremain);
            if(mysqli_num_rows($sqlremainres)>0){
                $rowremain= mysqli_fetch_array($sqlremainres);
                $oldqty=$rowremain["txtQty"]-$qty;
                $sqlupd="update tblremain set txtQty={$oldqty} where txtCodeNo='{$code}'";
                mysqli_query($con,$sqlupd);
            }
        }

            echo 'success';
        }else{
            echo 'error';
        }
	
	
}


if($action=="categorylist"){      

      $sql="select * from tblcategory order by AID";
      $result=mysqli_query($con,$sql) or die("SQL Query");
      $list = array();

      if(mysqli_num_rows($result) > 0){

        while ($rowdata= mysqli_fetch_array($result)) {
            $list[] = $rowdata;
		
			
        }      

      }
      echo json_encode($list);


}

if($action=="companylist"){      

      $sql="select * from tblsupplier order by AID";
      $result=mysqli_query($con,$sql) or die("SQL Query");
      $list = array();

      if(mysqli_num_rows($result) > 0){

        while ($rowdata= mysqli_fetch_array($result)) {
            $list[] = $rowdata;
		
			
        }      

      }
      echo json_encode($list);


}

if($action=='deleteremain'){
	$aid=$data["aid"]; 	
    $code=$data["code"]; 
    $sql="DELETE FROM tblremain WHERE txtCodeNo='{$code}'";
    if(mysqli_query($con,$sql)){
        echo 'success';
    }else{
        echo 'error';
    }
	
	
}


if($action=='updateremain'){
	$aid=$data["aid"]; 
	$codeno=$data["codeno"];	
	$itemname=$data["itemname"];
	$qty=$data["qty"];	
	$purprice=$data["purprice"];
	$sellprice=$data["sellprice"];	
	$categoryid=$data["categoryid"];
	$companyid=$data["companyid"];	
    $sql="UPDATE tblremain SET txtCodeNo='{$codeno}', txtName='{$itemname}', txtQty = '{$qty}',  txtPurPrice = '{$purprice}',  txtSellPrice = '{$sellprice}',
	CategoryID= '{$categoryid}', CompanyAID= '{$companyid}' WHERE txtAutoNo='$aid'";
  
   if(mysqli_query($con,$sql)){        
        echo 'success';
    }else{
        echo 'error';
    }
	
	
}

?>