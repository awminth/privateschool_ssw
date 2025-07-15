<?php
include('../config.php');

$action = $_POST["action"];
$userid=$_SESSION['userid'];
$gradeid= $_SESSION['gradeid'];
$gradename= $_SESSION['gradename'];
$yearid= $_SESSION['yearid'];
$yearname= $_SESSION['yearname'];
$dt=date('Y-m-d');

if($action == 'show'){  

    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=30; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    $no=0;
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];                
    }
    else{
        $page=1;                      
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){   
        $a = " and (s.Name like '%$search%' or s.NameMM like '%$search%')";
    }        
    $sql="select e.*,s.*,s.StudentID as sID,
    (select FeeAmt from tblfee where  EARStudentID=e.StudentID) as feeamt,
    (select FerryAmt from tblfee where  EARStudentID=e.StudentID) as ferryamt,
    (select FoodAmt from tblfee where  EARStudentID=e.StudentID) as foodamt,
    (select OtherAmt from tblfee where  EARStudentID=e.StudentID) as otheramt,
    (select NightStudyAmt from tblfee where  EARStudentID=e.StudentID) as nightstudyamt,
    (select Amt from tblfee where  EARStudentID=e.StudentID) as amt,
    (select TotalPay from tblfee where  EARStudentID=e.StudentID) as stotalpay,
    (select Remain from tblfee where  EARStudentID=e.StudentID) as sremain,
    (select VNO from tblfee where  EARStudentID=e.StudentID) as svno,
    (select PayTypeID from tblfee where  EARStudentID=e.StudentID) as paytypeid  
    from tblearstudent e,tblstudentprofile s where 
    s.AID=e.StudentID and  e.EARYearID={$yearid} and 
    e.GradeID={$gradeid}  ".$a." 
    order by e.AID desc limit {$offset},{$limit_per_page}";  
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-sm table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="5%;">'.$lang["no"].'</th>
            <th>'.$lang["fee_stuname"].'</th>
            <th>Name(MM)</th>
            <th>Grade</th>
            <th class="text-right">'.$lang["fee_feeamt"].'</th>
            <th class="text-right">'.$lang["fee_ferryamt"].'</th>
            <th class="text-right">'.$lang["fee_foodamt"].'</th>
            <th class="text-right">'.$lang["fee_otheramt"].'</th>
            <th class="text-right">Night Study Amount</th>
            <th class="text-right">'.$lang["fee_totalamt"].'</th>
            <th class="text-right">'.$lang["fee_totalpay"].'</th>
            <th class="text-right">'.$lang["fee_remain"].'</th>
            <th width="10%;" class="text-center">'.$lang["fee_view"].'</th>  
            <th width="10%;" class="text-center">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        $totalfeeamt=0;
        $totalferryamt=0;
        $totalfoodamt=0;
        $totalotheramt=0;
        $totalnightstudyamt=0;
        $totalamt=0;
        $totalpay=0;
        $totalremain=0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $totalfeeamt+=$row['feeamt'];
            $totalferryamt+=$row['ferryamt'];
            $totalfoodamt+=$row['foodamt'];
            $totalotheramt+=$row['otheramt'];
            $totalnightstudyamt+=$row['nightstudyamt'];
            $totalamt+=$row['amt'];
            $totalpay+=$row['stotalpay'];
            $totalremain+=$row['sremain'];
            $out.="<tr>";
            if(isset($_SESSION['la'])){
                if($_SESSION['la']=='my'){
                    $out.="<td class='text-center'>".toMyanmar($no)."</td>";
                }else{
                    $out.="<td class='text-center'>{$no}</td>";
                }
            }else{
                $out.="<td class='text-center'>{$no}</td>";
            }
            $out.="
                <td>{$row["Name"]}</td>
                <td>{$row['NameMM']}</td> 
                <td>{$row['RealGrade']}</td> 
                <td class='text-right'>".number_format($row['feeamt'])."</td>
                <td class='text-right'>".number_format($row['ferryamt'])."</td>
                <td class='text-right'>".number_format($row['foodamt'])."</td>
                <td class='text-right'>".number_format($row['otheramt'])."</td>
                <td class='text-right'>".number_format($row['nightstudyamt'])."</td>
                <td class='text-right'>".number_format($row['amt'])."</td>
                <td class='text-right'>".number_format($row['stotalpay'])."</td>  
                <td class='text-right'>".number_format($row['sremain'])."</td>
                <td class='text-center'>  
                    <a href='#' id='btnview' class='dropdown-item'
                        data-aid='{$row['AID']}' 
                        data-name='{$row['Name']}' 
                        data-vno='{$row['svno']}'  ><i class='fas fa-list text-primary'
                        style='font-size:13px;'></i>
                        ".$lang["fee_view"]."</a>                           
                </td> 
                <td class='text-center'>  
                    <a href='#' id='btnpay' class='dropdown-item text-primary' 
                        data-vno='{$row['svno']}' 
                        data-aid='{$row['AID']}' 
                        data-earstuid='{$row['sID']}'
                        data-stuname='{$row['Name']}' >
                        <i class='fas fa-dollar text-primary'
                        style='font-size:13px;'></i>
                        ".$lang["fee_pay"]."</a>                           
                </td> 
            </tr>";
        }

        $out.="
            <tr>
            <td colspan='4' class='text-center'>Total</td> 
            <td class='text-right'>".number_format($totalfeeamt)."</td>
            <td class='text-right'>".number_format($totalferryamt)."</td>
            <td class='text-right'>".number_format($totalfoodamt)."</td>
            <td class='text-right'>".number_format($totalotheramt)."</td>
            <td class='text-right'>".number_format($totalnightstudyamt)."</td>
            <td class='text-right'>".number_format($totalamt)."</td>
            <td class='text-right'>".number_format($totalpay)."</td>  
            <td class='text-right'>".number_format($totalremain)."</td>
            <td class='text-center'>  
                                        
            </td> 
            <td class='text-center'>  
                                      
            </td> 
        </tr>";
        
        $out.="</tbody>";
        $out.="</table><br><br>";

        $sql_total="select e.AID  
        from tblearstudent e,tblstudentprofile s where 
        s.AID=e.StudentID and e.EARYearID={$yearid} and 
        e.GradeID={$gradeid}  ".$a." 
        order by e.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang["totalrecord"].' -  ';
        $out.=$total_record;
        $out.='</p></div>';

        $out.='<div class="float-right">
                <ul class="pagination">
            ';      
        
        $previous_link = '';
        $next_link = '';
        $page_link = '';

        if($total_links > 4){
            if($page < 5){
                for($count = 1; $count <= 5; $count++)
                {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }else{
                $end_limit = $total_links - 5;
                if($page > $end_limit){
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $end_limit; $count <= $total_links; $count++)
                    {
                        $page_array[] = $count;
                    }
                }else{
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $page - 1; $count <= $page + 1; $count++)
                    {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
            }            

        }else{
            for($count = 1; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }

        for($count = 0; $count < count($page_array); $count++){
            if($page == $page_array[$count]){
                $page_link .= '<li class="page-item active">
                                    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                                    </li> ';
                }
            }
        }

        $out .= $previous_link . $page_link . $next_link;

        $out .= '</ul></div>';

        echo $out;
    }
    else{
        $out.='
        <table class="table table-bordered table-striped table-responsive nowrap">
        <thead>
        <tr>
            <th width="5%;">'.$lang["no"].'</th>
            <th>'.$lang["fee_stuname"].'</th>
            <th class="text-right">'.$lang["fee_feeamt"].'</th>
            <th class="text-right">'.$lang["fee_ferryamt"].'</th>
            <th class="text-right">'.$lang["fee_foodamt"].'</th>
            <th class="text-right">'.$lang["fee_otheramt"].'</th>
            <th class="text-right">'.$lang["fee_totalamt"].'</th>
            <th class="text-right">'.$lang["fee_totalpay"].'</th>
            <th class="text-right">'.$lang["fee_remain"].'</th>
            <th width="10%;" class="text-center">'.$lang["fee_view"].'</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="11" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action=='go_pay'){
    $vno = $_POST['vno'];
    $aid = $_POST['aid'];
    $earstuid = $_POST['earstuid'];
    $stuname = $_POST['stuname'];
    $_SESSION['go_pay_vno'] = $vno;
    $_SESSION['go_pay_stuid'] = $aid;
    $_SESSION['go_pay_earstuid'] = $earstuid;
    $_SESSION['go_pay_stuname'] = $stuname;
    echo 1;
}

if($action=='paytypeamt'){
    $aid=$_POST['aid'];   
    $sqlchk="select * from tblpaytype where AID={$aid}";
    $result=mysqli_query($con,$sqlchk);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
        echo $row['Price'];
    }else{
        echo 0;
    }   
    
}

if($action=='savefee'){
    $studentID  =  $_POST['earstuid'];
    $studentid = $_POST['studentid'];
    $paytype = $_POST['paytype'];  
    $feeamt = $_POST['feeamt']; 
    $ferryamt = $_POST['ferryamt']; 
    $foodamt = $_POST['foodamt']; 
    $otheramt = $_POST['otheramt'];  
    $nightstudyamt = $_POST['nightstudyamt'];  
    $amt = $_POST['amt'];  
    $disc = $_POST['disc'];  
    $tax = $_POST['tax']; 
    $total = $_POST['total'];  
    $cash = $_POST['cash'];  
    $mobile = $_POST['mobile'];    
    $rmk = $_POST['rmk'];  
    $totalpay = $_POST['totalpay'];  
    $totalpay1 = $_POST['totalpay1'];  
    $remain = $_POST['remain'];  
    $dt1 = $_POST['dt'];   
    $vno = "fe_".date('YmdHis'); 
    $feevno = $_POST['feevno'];
    $payname = $_POST['payname'];
    $receivename = $_POST['receivename'];
    $paymonth = $_POST['paymonth'];
    $studentname  =  $_POST['earstuname'];   

    if($amt == ""){
        $amt = 0;
    }
    if($disc == ""){
        $disc = 0;
    }
    if($tax == ""){
        $tax = 0;
    }
    if($total==""){
        $total=0;
    }
    if($cash == ""){
        $cash = 0;
    }
    if(empty($mobile)){
        $mobile = 0;
    }
    $out = "";
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $sqlchk = "select * from tblfee where 
    EARStudentID={$studentid}";
    $result = mysqli_query($con,$sqlchk);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        // $ototalpay=$row['TotalPay'];
        $oremain = $row['Remain'];
        $a = $totalpay + $totalpay1;
        $b = $oremain - $totalpay;
        $sqlupd = "update tblfee set TotalPay={$a},Remain={$b} where 
        EARStudentID={$studentid}";
        mysqli_query($con,$sqlupd);
        
    }else{
        $sql="insert into tblfee (LoginID,EARStudentID,PayTypeID,Amt,Disc,Tax,Total,
        Cash,Mobile,MobileRmk,TotalPay,Remain,Date,VNO,FeeAmt,FerryAmt,FoodAmt,OtherAmt,NightStudyAmt) values 
        ({$userid},{$studentid},{$paytype},{$amt},{$disc},{$tax},{$total},{$cash},{$mobile},
        '{$rmk}',{$totalpay},{$remain},'{$dt1}','{$vno}','{$feeamt}','{$ferryamt}','{$foodamt}',
        '{$otheramt}','{$nightstudyamt}')";       
        mysqli_query($con,$sql);
        $feevno = $vno;      
         
    }

    $sql1="insert into tblfeedetail (LoginID,VNO,Cash,Mobile,MobileRmk,TotalAmt,PayUser,PayDate,
    Date,FeeVNO,PayName,ReceiveName,PaymentForMonth) values ({$userid},'{$vno}',{$cash},{$mobile},'{$rmk}',
    {$totalpay},'test','{$dt1}','{$dt}','{$feevno}','{$payname}','{$receivename}','{$paymonth}')"; 
  
    if(mysqli_query($con,$sql1)){
        savecms($vno,$cash,$mobile,$rmk,'Student Fee',0);
        save_log($_SESSION["username"]." သည် EAR Student Fee အားအသစ်သွင်းသွားသည်။");
        //////////slip////
        $out .= "
        <div id='printdata'>
            <h5 class='text-center'>
                ".$_SESSION["shopname"]."<br>
                ".$_SESSION["shopaddress"]."<br>
                Student Fee Pay Slip
            </h5>
            <p class='txtl fs'>
                Date : {$todaydt}<br>
                Time : {$todaytt}
            </p>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl'>Student ID</td>
                        <td class='txtl'>{$studentID}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Student Name</td>
                        <td class='txtl'>{$studentname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Grade</td>
                        <td class='txtl'>{$gradename}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee VNO</td>
                        <td class='txtl'>{$feevno}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Total Amount</td>
                        <td class='txtl'>".number_format($amt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Pay Amount</td>
                        <td class='txtl'>".number_format($totalpay)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Remain Amount</td>
                        <td class='txtl'>".number_format($remain)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Pay Name</td>
                        <td class='txtl'>{$payname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Receive Name</td>
                        <td class='txtl'>{$receivename}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>For Month(Payment)</td>
                        <td class='txtl'>{$paymonth}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Pay Date</td>
                        <td class='txtl'>".enDate($dt)."</td>
                    </tr>
                <tbody>
            </table>
        </div>
        <br>
        <button class='btn btn-{$color}' id='btnprint' >Print</button>
        ";      
        /////////////
        echo $out;
    }else{
        echo 0;
    }
    
    
}

if($action=='gofeeview'){
    $aid=$_POST['aid'];
    $name=$_POST['name'];
    $vno=$_POST['vno'];
    $_SESSION['stuid']=$aid;
    $_SESSION['stuname']=$name;
    $_SESSION['stuvno']=$vno;
    echo 1;
}

if($action == "show_paytype"){
    $paytypeid = $_POST["paytypeid"];
    $aid = $_POST["aid"];
    $out = "";
    $sql = "select p.AID,c.Name from tblfee f,tblpaytypecategory c,tblpaytype p    
    where f.PayTypeID=p.AID and p.PayTypeID=c.AID and f.PayTypeID='{$paytypeid}' 
    and f.EARStudentID='{$aid}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $out.='<option value="'.$row["AID"].'">'.$row["Name"].'</option>';
    }else{
        $out.='<option value="">Select Pay Type</option>'.load_paytypestudent($gradeid);
    }
    echo $out;
}


?>