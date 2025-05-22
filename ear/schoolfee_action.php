<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

// show year
if($action == 'show_earyear'){  
    unset($_SESSION["go_schoolfee_aid"]);
    unset($_SESSION["go_schoolfee_name"]);
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
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
        $a = " where Name like '%$search%' ";
    }     
    $sql="select * from tblearyear ".$a." order by AID desc 
    limit {$offset},{$limit_per_page}";  
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Academic Year</th>
            <th class="text-center">Go Detail</th>          
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td> 
                <td>{$row["Name"]}</td>    
                <td class='text-center'>
                    <a href='#' id='btnaddassignjuty' 
                        data-aid='{$row['AID']}' 
                        data-name='{$row['Name']}' >
                        <i class='fas fa-arrow-right'></i> Go Detail</a>
                </td>  
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="select * from tblearyear ".$a." order by AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang['staff_totalrecord'].' -  ';
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
                if($next_id >= $total_links){
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
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Academic Year</th>
            <th>Go Assigned</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

// go assign juty
if($action == "go_assignjuty"){
    $aid = $_POST["aid"];
    $name = $_POST["name"];
    $_SESSION["go_schoolfee_aid"] = $aid;
    $_SESSION["go_schoolfee_name"] = $name;
    echo 1;
}

/////////////////////////////////////////////
if($action == "show_view"){
    $yearid = $_SESSION["yearid"];
    $gradeid_session = $_SESSION['gradeid'];
    $serdt = $_POST["serdt"]; 
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
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
    $search = $_POST["search"];
    $ser = "";
    if($search != ""){
        $ser = " and (p.Name like '%$search%') ";
    }
    $gradeid = $_POST["gradeid"];
    if($gradeid != ""){
        $ser .= " and e.GradeID='{$gradeid}' ";
    }
    $out="";
    $out.='
    <table class="table table-lg table-bordered table-striped table-responsive nowrap">
        <thead>
            <tr class="t1">
                <th class="t1" rowspan="2">No</th>
                <th class="t1" rowspan="2">Name</th>
                <th class="t1" rowspan="2">Year</th>
                <th class="t1" rowspan="2">Grade</th>';     
                for($i=0; $i<count($arr_montheng); $i++){
                    $out.='<th class="t1">'.$arr_montheng[$i].'</th>';
                }                    
        $out.='
            </tr>';
        $out.='
            <tr class="t1">';
                     
                for($i=1; $i<=count($arr_montheng); $i++){
                    if($i < 10){
                        $a = 0;
                    }else{
                        $a = "";
                    }
                    $out.='<th class="t1">'.$a.$i.'</th>';
                }
        $out.='
            </tr>';
        $out.='
        </thead>
        <tbody>
        ';
        $sql_student = "select e.*,p.Name as pname,y.Name as yname,g.Name as gname   
        from tblearstudent e,tblstudentprofile p,tblearyear y,tblgrade g   
        where e.StudentID=p.AID and e.EARYearID=y.AID and e.GradeID=g.AID 
        and e.EARYearID='{$yearid}' and e.GradeID='{$gradeid_session}' ".$ser." order by AID desc 
        limit {$offset},{$limit_per_page}";
        $res_student = mysqli_query($con,$sql_student);
        $no = (($page - 1) * $limit_per_page);
        if(mysqli_num_rows($res_student) > 0){
            while($row_student = mysqli_fetch_array($res_student)){
                $no = $no + 1;
                $out.='
                <tr>
                    <td class="t1">'.$no.'</td>
                    <td>'.$row_student["pname"].'</td>
                    <td class="t1">'.$row_student["yname"].'</td>
                    <td class="t1">'.$row_student["gname"].'</td>
                ';
                for($i=0; $i<count($arr_montheng); $i++){
                    $sql_fee = "select f.* 
                    from tblstudentfee f 
                    where f.MonthName='{$arr_montheng[$i]}' and f.EARStudentID='{$row_student['AID']}' 
                    and Year(f.PayDate)='{$serdt}'";
                    $res_fee = mysqli_query($con,$sql_fee);
                    if(mysqli_num_rows($res_fee) > 0){
                        $row_fee = mysqli_fetch_array($res_fee);
                        $color = "bg-success";
                        if($row_fee["Remain"] > 0){
                            $color = "bg-warning";
                        }
                        $out.='
                        <td class="t1 '.$color.'" style="cursor:pointer;" 
                            id="btnfeeedit" 
                            data-yid="'.$row_student["EARYearID"].'" 
                            data-yname="'.$row_student["yname"].'" 
                            data-gid="'.$row_student["GradeID"].'" 
                            data-gname="'.$row_student["gname"].'" 
                            data-earid="'.$row_student["AID"].'" 
                            data-stuname="'.$row_student["pname"].'" 
                            data-monthname="'.$arr_montheng[$i].'" 
                            data-vno="'.$row_fee["VNO"].'" >
                        </td>';
                    }else{
                        $out.='<td class="t1" style="cursor:pointer;" 
                            id="btnfeepay" 
                            data-yid="'.$row_student["EARYearID"].'" 
                            data-yname="'.$row_student["yname"].'" 
                            data-gid="'.$row_student["GradeID"].'" 
                            data-gname="'.$row_student["gname"].'" 
                            data-earid="'.$row_student["AID"].'" 
                            data-stuname="'.$row_student["pname"].'" 
                            data-monthname="'.$arr_montheng[$i].'" >
                            </td>';
                    }
                   
                }                
                $out.='
                </tr>
                ';
            }
        }
    $out.='
        </tbody>
    </table><br><br>';

    $sql_total="select e.AID   
    from tblearstudent e,tblstudentprofile p,tblearyear y,tblgrade g   
    where e.StudentID=p.AID and e.EARYearID=y.AID and e.GradeID=g.AID 
    and e.EARYearID='{$yearid}' and e.GradeID='{$gradeid_session}' ".$ser." order by AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang['staff_totalrecord'].' -  ';
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
                if($next_id >= $total_links){
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

if($action == 'todaydt'){
    $data = date("Y");
    echo $data;
}

if($action == 'leftdt'){
    $dt = $_POST["dt"];
    $current = $dt."-".date("m-d");
    $data = date('Y', strtotime($current. ' - 1 year')); 
    echo $data;
}

if($action == 'rightdt'){
    $dt = $_POST["dt"];
    $current = $dt."-".date("m-d");
    $data = date('Y', strtotime($current. ' + 1 year')); 
    // $data = date("Y", strtotime(date("Y-m-d", strtotime($current)) . " + 1 year"));
    echo $data;
}

// prepare for save
if($action == "prepare_save"){
    $out = "";
    $yid = $_POST["yid"];
    $yname = $_POST["yname"];
    $gid = $_POST["gid"];
    $gname = $_POST["gname"];
    $earid = $_POST["earid"];
    $stuname = $_POST["stuname"];
    $monthname = $_POST["monthname"];
    $feeamt = GetString("select FeeAmount from tblgrade where AID='{$gid}'");
    $out.='
    <input type="hidden" name="action" value="save_feepay" />
    <input type="hidden" name="yid" value="'.$yid.'" />
    <input type="hidden" name="gid" value="'.$gid.'" />
    <input type="hidden" name="earid" value="'.$earid.'" />
    <div class="modal-body" data-spy="scroll" data-offset="50">
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="usr">Name</label>
                <input type="text" class="form-control border-success" name="stuname" value="'.$stuname.'" readonly>
            </div>
            <div class="form-group col-sm-6">
                <label for="usr">Year</label>
                <input type="text" class="form-control border-success" name="yname" value="'.$yname.'" readonly>
            </div>
            <div class="form-group col-sm-6">
                <label for="usr">Grade</label>
                <input type="text" class="form-control border-success" name="gname" value="'.$gname.'" readonly>
            </div>
            <div class="form-group col-sm-6">
                <label for="usr">Month Name</label>
                <input type="text" class="form-control border-success" name="monthname" value="'.$monthname.'" readonly>
            </div>
            <div class="form-group col-sm-3">
                <label for="usr">Fee Amount</label>
                <input type="text" class="form-control border-success" name="feeamt" value="'.$feeamt.'" readonly>
            </div>
            <div class="form-group col-sm-3">
                <label for="usr">Ferry Fees</label>
                <input type="number" class="form-control border-success" name="ferryamt" id="ferryamt" value="0" >
            </div>
            <div class="form-group col-sm-3">
                <label for="usr">Meal Fees</label>
                <input type="number" class="form-control border-success" name="foodamt" id="foodamt" value="0" >
            </div>
            <div class="form-group col-sm-3">
                <label for="usr">Monthly Lunch Fees</label>
                <input type="number" class="form-control border-success" name="otheramt" id="otheramt" value="0" >
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Register Fees</label>
                <input type="number" class="form-control border-success" name="registeramt" id="registeramt" value="0" >
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Uniform Fees</label>
                <input type="number" class="form-control border-success" name="uniformamt" id="uniformamt" value="0" >
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Material Fees</label>
                <input type="number" class="form-control border-success" name="materialamt" id="materialamt" value="0" >
            </div>
            <div class="form-group col-sm-6">
                <label for="usr">Discount(%)</label>
                <input type="number" class="form-control border-success" name="disc" id="disc" value="0" >
            </div>
            <div class="form-group col-sm-6">
                <label for="usr">Total Amount</label>
                <input type="number" class="form-control border-success" name="totalamt" value="'.$feeamt.'" readonly >
            </div>
            <div class="form-group col-sm-12">
                <label for="usr">Remark(For Discount)</label>
                <input type="text" class="form-control border-success" name="remark" value="">
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Cash</label>
                <input type="number" class="form-control border-success" name="cash" id="cash" value="0" >
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Mobile</label>
                <input type="number" class="form-control border-success" name="mobile" id="mobile" value="0" >
            </div>
            <div class="form-group col-sm-4">
                <label for="usr">Mobile Rmk</label>
                <select class="form-control border-success" name="mobilermk">
                    <option value="">Select Rmk</option>
                    '.load_pay().'
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label for="usr"> '.$lang['feepay_totalpay'].' </label>
                <input type="number" class="form-control border-success" readonly
                    name="totalpay" value="0">
            </div>
            <div class="form-group col-sm-4">
                <label for="usr"> '.$lang['feepay_remain'].' </label>
                <input type="number" class="form-control border-success" readonly name="remain"
                    value="0">
            </div>
            <div class="form-group col-sm-4">
                <label for="usr"> '.$lang['feepay_paydt'].' </label>
                <input type="date" class="form-control border-success"
                    value="'.date('Y-m-d').'" name="paydt">
            </div>
            <div class="form-group col-sm-6">
                <label for="usr"> '.$lang['feepay_payname'].'</label>
                <input type="text" class="form-control border-success" name="payname">
            </div>
            <div class="form-group col-sm-6">
                <label for="usr"> '.$lang['feepay_receivename'].'</label>
                <input type="text" class="form-control border-success" name="receivename">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="btnsave" class="btn btn-'.$color.'"><i class="fas fa-save"></i>
            '.$lang['staff_save'].'</button>
    </div>
    ';
    echo $out;
}

// save fee pay
if($action == "save_feepay"){
    $out = "";
    $yid = $_POST["yid"];
    $yname = $_POST["yname"];
    $gid = $_POST["gid"];
    $gname = $_POST["gname"];
    $earid = $_POST["earid"];
    $stuname = $_POST["stuname"];
    $monthname = $_POST["monthname"];
    $feeamt = $_POST['feeamt']; 
    $ferryamt = $_POST['ferryamt']; 
    $foodamt = $_POST['foodamt']; 
    $otheramt = $_POST['otheramt'];
    $registeramt = $_POST['registeramt'];
    $uniformamt = $_POST['uniformamt'];
    $materialamt = $_POST['materialamt'];
    $remark = $_POST['remark'];
    $disc = $_POST['disc'];
    $totalamt = $_POST['totalamt'];
    $cash = $_POST['cash'];  
    $mobile = $_POST['mobile'];    
    $mobilermk = $_POST['mobilermk'];  
    $totalpay = $_POST['totalpay'];  
    $remain = $_POST['remain'];  
    $paydt = $_POST['paydt'];  
    $payname = $_POST['payname'];
    $receivename = $_POST['receivename'];
    $vno = date("Ymd-His");
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $sql = "insert into tblstudentfee (MonthName,EARYearID,GradeID,EARStudentID,FeeAmount,
    FerryAmount,FoodAmount,OtherAmount,RegisterFee,UniformFee,MaterialFee,Disc,Total,Cash,
    Mobile,MobileRmk,TotalPay,PayDate,PayName,ReceiveName,Remain,VNO,Remark,LoginID) 
    values ('{$monthname}','{$yid}','{$gid}','{$earid}','{$feeamt}','{$ferryamt}','{$foodamt}','{$otheramt}',
    '{$registeramt}','{$uniformamt}','{$materialamt}','{$disc}',
    '{$totalamt}','{$cash}','{$mobile}','{$mobilermk}','{$totalpay}','{$paydt}','{$payname}',
    '{$receivename}','{$remain}','{$vno}','{$remark}','{$userid}')";
    if(mysqli_query($con,$sql)){
        savecms($vno,$cash,$mobile,$mobilermk,'Student Fee',0);
        save_log($_SESSION["username"]." သည် EAR Student Fee အားအသစ်သွင်းသွားသည်။");
        //////////slip////
        $out .= "
        <div id='printdata'>
            <p  class='text-center' align='center'>
                <img src=".$_SESSION["shoplogo"]." style='width:180px;height:80px;' >
            </p>
            <h5 class='text-center'>
                <span style='font-weight:bold;color:darkblue;text-align:center;font-size:20px;'>".$_SESSION["shopname"]."</span><br>
                <span style='font-size:17px;'>".$_SESSION["shopaddress"]."</span><br>
                <span style='font-size:17px;'>Student Fee Pay Slip</span>
            </h5>
            <div style='display: flex; justify-content: space-between;'>
                <p class='txtl fs'>
                    Date: {$todaydt}<br>
                    Time: {$todaytt}
                </p>
                <p class='txtl fs'>
                    PhNo: 09-767755051<br>
                    Email: betterchange.office@gmail.com
                </p>
            </div>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl'>Student Name</td>
                        <td class='txtl'>{$stuname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Year</td>
                        <td class='txtl'>{$yname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Grade</td>
                        <td class='txtl'>{$gname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee VNO</td>
                        <td class='txtl'>{$vno}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee Amount</td>
                        <td class='txtl'>".number_format($feeamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Ferry Fees</td>
                        <td class='txtl'>".number_format($ferryamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Food Fees</td>
                        <td class='txtl'>".number_format($foodamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Other Fees</td>
                        <td class='txtl'>".number_format($otheramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Register Fees</td>
                        <td class='txtl'>".number_format($registeramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Uniform Fees</td>
                        <td class='txtl'>".number_format($uniformamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Material Fees</td>
                        <td class='txtl'>".number_format($materialamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Discount(%)</td>
                        <td class='txtl'>".number_format($disc)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Remark</td>
                        <td class='txtl'>{$remark}</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Total Amount</td>
                        <td class='txtl'>".number_format($totalamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Pay Amount</td>
                        <td class='txtl'>".number_format($totalpay)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Remain Amount</td>
                        <td class='txtl'>".number_format($remain)."</td>
                    </tr>
                <tbody>
            </table>
            <table class='table table\-bordered text\-sm' rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl text-center' width='33%'>Paid By</td>
                        <td class='txtl text-center' width='33%'>Received By</td>
                        <td class='txtl text-center' width='33%'>Approved By</td>
                    </tr>
                    <tr>
                        <td class='txtl text-center' width='33%'>{$payname}</td>
                        <td class='txtl text-center' width='33%'>{$receivename}</td>
                        <td class='txtl text-center' width='33%'></td>
                    </tr>
                </tbody>
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

// prepare for edit
if($action == "prepare_edit"){
    $out = "";
    $yid = $_POST["yid"];
    $yname = $_POST["yname"];
    $gid = $_POST["gid"];
    $gname = $_POST["gname"];
    $earid = $_POST["earid"];
    $stuname = $_POST["stuname"];
    $monthname = $_POST["monthname"];
    $vno = $_POST["vno"];
    $sql = "select * from tblstudentfee where VNO='{$vno}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $feeamt = $row["FeeAmount"];
        $foodamt = $row["FoodAmount"];
        $ferryamt = $row["FerryAmount"];
        $otheramt = $row["OtherAmount"];
        $registeramt = $row["RegisterFee"];
        $uniformamt = $row["UniformFee"];
        $materialamt = $row["MaterialFee"];
        $disc = $row["Disc"];
        $totalamt = $row["Total"];
        $cash = $row["Cash"];
        $mobile = $row["Mobile"];
        $mobilermk = $row["MobileRmk"];
        $totalpay = $row["TotalPay"];
        $paydt = $row["PayDate"];
        $payname = $row["PayName"];
        $receivename = $row["ReceiveName"];
        $remain = $row["Remain"];
        $remark = $row["Remark"];
        $out.='
        <input type="hidden" name="action" value="edit_feepay" />
        <input type="hidden" name="yid" value="'.$yid.'" />
        <input type="hidden" name="gid" value="'.$gid.'" />
        <input type="hidden" name="earid" value="'.$earid.'" />
        <input type="hidden" name="vno" value="'.$vno.'" />
        <div class="modal-body" data-spy="scroll" data-offset="50">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="usr">Name</label>
                    <input type="text" class="form-control border-success" name="stuname" value="'.$stuname.'" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr">Year</label>
                    <input type="text" class="form-control border-success" name="yname" value="'.$yname.'" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr">Grade</label>
                    <input type="text" class="form-control border-success" name="gname" value="'.$gname.'" readonly>
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr">Month Name</label>
                    <input type="text" class="form-control border-success" name="monthname" value="'.$monthname.'" readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label for="usr">Fee Amount</label>
                    <input type="text" class="form-control border-success" name="feeamt" value="'.$feeamt.'" readonly>
                </div>
                <div class="form-group col-sm-3">
                    <label for="usr">Ferry Fees</label>
                    <input type="number" class="form-control border-success" name="ferryamt" id="ferryamt" value="'.$ferryamt.'" >
                </div>
                <div class="form-group col-sm-3">
                    <label for="usr">Meal Fees</label>
                    <input type="number" class="form-control border-success" name="foodamt" id="foodamt" value="'.$foodamt.'" >
                </div>
                <div class="form-group col-sm-3">
                    <label for="usr">Monthly Lunch Fees</label>
                    <input type="number" class="form-control border-success" name="otheramt" id="otheramt" value="'.$otheramt.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Register Fees</label>
                    <input type="number" class="form-control border-success" name="registeramt" id="registeramt" value="'.$registeramt.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Uniform Fees</label>
                    <input type="number" class="form-control border-success" name="uniformamt" id="uniformamt" value="'.$uniformamt.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Material Fees</label>
                    <input type="number" class="form-control border-success" name="materialamt" id="materialamt" value="'.$materialamt.'" >
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr">Disc(%)</label>
                    <input type="number" class="form-control border-success" name="disc" id="disc" value="'.$disc.'" >
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr">Total Amount</label>
                    <input type="number" class="form-control border-success" name="totalamt" value="'.$totalamt.'" readonly >
                </div>
                <div class="form-group col-sm-12">
                    <label for="usr">Remark(For Discount)</label>
                    <input type="text" class="form-control border-success" name="remark" value="'.$remark.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Cash</label>
                    <input type="number" class="form-control border-success" name="cash" id="cash" value="'.$cash.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Mobile</label>
                    <input type="number" class="form-control border-success" name="mobile" id="mobile" value="'.$mobile.'" >
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr">Mobile Rmk</label>
                    <select class="form-control border-success" name="mobilermk">
                        <option value="'.$mobilermk.'">'.$mobilermk.'</option>
                        '.load_pay().'
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr"> '.$lang['feepay_totalpay'].' </label>
                    <input type="number" class="form-control border-success" readonly
                        name="totalpay" value="'.$totalpay.'">
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr"> '.$lang['feepay_remain'].' </label>
                    <input type="number" class="form-control border-success" readonly name="remain"
                    value="'.$remain.'">
                </div>
                <div class="form-group col-sm-4">
                    <label for="usr"> '.$lang['feepay_paydt'].' </label>
                    <input type="date" class="form-control border-success"
                        value="'.$paydt.'" name="paydt">
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr"> '.$lang['feepay_payname'].'</label>
                    <input type="text" class="form-control border-success" name="payname" value="'.$payname.'">
                </div>
                <div class="form-group col-sm-6">
                    <label for="usr"> '.$lang['feepay_receivename'].'</label>
                    <input type="text" class="form-control border-success" name="receivename" value="'.$receivename.'">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnvoucherfeepay" 
                data-vno="'.$vno.'" 
                data-monthname="'.$monthname.'" 
                data-yname="'.$yname.'" 
                data-gname="'.$gname.'" 
                data-stuname="'.$stuname.'" 
                class="btn btn-info"><i class="fas fa-print"></i>
                Print</button>
            <button type="button" id="btndeletefeepay" 
                data-vno="'.$vno.'" 
                class="btn btn-danger"><i class="fas fa-trash"></i>
                '.$lang['staff_delete'].'</button>
            <button type="submit" id="btnedit" class="btn btn-'.$color.'"><i class="fas fa-edit"></i>
                '.$lang['staff_edit'].'</button>
        </div>
        ';
    }
    echo $out;
}

// edit fee pay
if($action == "edit_feepay"){
    $out = "";
    $vno = $_POST["vno"];
    $yid = $_POST["yid"];
    $yname = $_POST["yname"];
    $gid = $_POST["gid"];
    $gname = $_POST["gname"];
    $earid = $_POST["earid"];
    $stuname = $_POST["stuname"];
    $monthname = $_POST["monthname"];
    $feeamt = $_POST['feeamt']; 
    $ferryamt = $_POST['ferryamt']; 
    $foodamt = $_POST['foodamt']; 
    $otheramt = $_POST['otheramt'];
    $registeramt = $_POST['registeramt'];
    $uniformamt = $_POST['uniformamt'];
    $materialamt = $_POST['materialamt'];
    $disc = $_POST['disc'];
    $totalamt = $_POST['totalamt'];
    $remark = $_POST['remark'];
    $cash = $_POST['cash'];  
    $mobile = $_POST['mobile'];    
    $mobilermk = $_POST['mobilermk'];  
    $totalpay = $_POST['totalpay'];  
    $remain = $_POST['remain'];  
    $paydt = $_POST['paydt'];  
    $payname = $_POST['payname'];
    $receivename = $_POST['receivename'];
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $sql = "update tblstudentfee set FeeAmount='{$feeamt}',
    FerryAmount='{$ferryamt}',FoodAmount='{$foodamt}',OtherAmount='{$otheramt}',
    RegisterFee='{$registeramt}',UniformFee='{$uniformamt}',MaterialFee='{$materialamt}',Disc='{$disc}',
    Total='{$totalamt}',Cash='{$cash}',Mobile='{$mobile}',MobileRmk='{$mobilermk}',
    TotalPay='{$totalpay}',PayDate='{$paydt}',PayName='{$payname}',
    ReceiveName='{$receivename}',Remain='{$remain}',Remark='{$remark}',LoginID='{$userid}' 
    where VNO='{$vno}'";
    // echo $sql;
    if(mysqli_query($con,$sql)){
        // delete cms
        deletecms($vno);
        // save cms
        savecms($vno,$cash,$mobile,$mobilermk,'Student Fee',0);
        save_log($_SESSION["username"]." သည် EAR Student Fee အား edit သွားသည်။");
        //////////slip////
        $out .= "
        <div id='printdata'>
            <p  class='text-center' align='center'>
                <img src=".$_SESSION["shoplogo"]." style='width:180px;height:80px;' >
            </p>
            <h5 class='text-center'>
                <span style='font-weight:bold;color:darkblue;text-align:center;font-size:20px;'>".$_SESSION["shopname"]."</span><br>
                <span style='font-size:17px;'>".$_SESSION["shopaddress"]."</span><br>
                <span style='font-size:17px;'>Student Fee Pay Slip</span>
            </h5>
            <div style='display: flex; justify-content: space-between;'>
                <p class='txtl fs'>
                    Date: {$todaydt}<br>
                    Time: {$todaytt}
                </p>
                <p class='txtl fs'>
                    PhNo: 09-767755051<br>
                    Email: betterchange.office@gmail.com
                </p>
            </div>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl'>Student Name</td>
                        <td class='txtl'>{$stuname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Year</td>
                        <td class='txtl'>{$yname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Grade</td>
                        <td class='txtl'>{$gname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee VNO</td>
                        <td class='txtl'>{$vno}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee Amount</td>
                        <td class='txtl'>".number_format($feeamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Ferry Fees</td>
                        <td class='txtl'>".number_format($ferryamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Food Fees</td>
                        <td class='txtl'>".number_format($foodamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Other Fees</td>
                        <td class='txtl'>".number_format($otheramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Register Fees</td>
                        <td class='txtl'>".number_format($registeramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Uniform Fees</td>
                        <td class='txtl'>".number_format($uniformamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Material Fees</td>
                        <td class='txtl'>".number_format($materialamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Discount(%)</td>
                        <td class='txtl'>".number_format($disc)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Total Amount</td>
                        <td class='txtl'>".number_format($totalamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Remark</td>
                        <td class='txtl'>{$remark}</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Pay Amount</td>
                        <td class='txtl'>".number_format($totalpay)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Remain Amount</td>
                        <td class='txtl'>".number_format($remain)."</td>
                    </tr>
                <tbody>
            </table>
            <table class='table table\-bordered text\-sm' rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl text-center' width='33%'>Paid By</td>
                        <td class='txtl text-center' width='33%'>Received By</td>
                        <td class='txtl text-center' width='33%'>Approved By</td>
                    </tr>
                    <tr>
                        <td class='txtl text-center' width='33%'>{$payname}</td>
                        <td class='txtl text-center' width='33%'>{$receivename}</td>
                        <td class='txtl text-center' width='33%'></td>
                    </tr>
                </tbody>
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

// delete fee pay
if($action == "delete_feepay"){
    $vno = $_POST["vno"];
    $sql = "delete from tblstudentfee where VNO='{$vno}'";
    if(mysqli_query($con,$sql)){
        // delete cms
        deletecms($vno);
        save_log($_SESSION["username"]." သည် EAR Student Fee အား delete သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

// voucher fee pay
if($action == "voucher_feepay"){
    $out = "";
    $vno = $_POST["vno"];
    $yname = $_POST["yname"];
    $gname = $_POST["gname"];
    $stuname = $_POST["stuname"];
    $monthname = $_POST["monthname"];
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $sql = "select * from tblstudentfee 
    where VNO='{$vno}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $feeamt = $row['FeeAmount']; 
        $ferryamt = $row['FerryAmount']; 
        $foodamt = $row['FoodAmount']; 
        $otheramt = $row['OtherAmount'];
        $registeramt = $row["RegisterFee"];
        $uniformamt = $row["UniformFee"];
        $materialamt = $row["MaterialFee"];
        $disc = $row["Disc"];
        $totalamt = $row['Total'];
        $cash = $row['Cash'];  
        $mobile = $row['Mobile'];    
        $mobilermk = $row['MobileRmk'];  
        $totalpay = $row['TotalPay'];  
        $remain = $row['Remain'];  
        $paydt = $row['PayDate'];  
        $payname = $row['PayName'];
        $receivename = $row['ReceiveName'];
        $remark = $row['Remark'];
        //////////slip////
        $out .= "
        <div id='printdata'>
            <p  class='text-center' align='center'>
                <img src=".$_SESSION["shoplogo"]." style='width:180px;height:80px;' >
            </p>
            <h5 class='text-center'>
                <span style='font-weight:bold;color:darkblue;text-align:center;font-size:20px;'>".$_SESSION["shopname"]."</span><br>
                <span style='font-size:17px;'>".$_SESSION["shopaddress"]."</span><br>
                <span style='font-size:17px;'>Student Fee Pay Slip</span>
            </h5>
            <div style='display: flex; justify-content: space-between;'>
                <p class='txtl fs'>
                    Date: {$todaydt}<br>
                    Time: {$todaytt}
                </p>
                <p class='txtl fs'>
                    PhNo: 09-767755051<br>
                    Email: betterchange.office@gmail.com
                </p>
            </div>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl'>Student Name</td>
                        <td class='txtl'>{$stuname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Year</td>
                        <td class='txtl'>{$yname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Grade</td>
                        <td class='txtl'>{$gname}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee VNO</td>
                        <td class='txtl'>{$vno}</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Fee Amount</td>
                        <td class='txtl'>".number_format($feeamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Ferry Fees</td>
                        <td class='txtl'>".number_format($ferryamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Food Fees</td>
                        <td class='txtl'>".number_format($foodamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Other Fees</td>
                        <td class='txtl'>".number_format($otheramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Register Fees</td>
                        <td class='txtl'>".number_format($registeramt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Uniform Fees</td>
                        <td class='txtl'>".number_format($uniformamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Material Fees</td>
                        <td class='txtl'>".number_format($materialamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl'>Discount(%)</td>
                        <td class='txtl'>".number_format($disc)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Total Amount</td>
                        <td class='txtl'>".number_format($totalamt)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Remark</td>
                        <td class='txtl'>{$remark}</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Pay Amount</td>
                        <td class='txtl'>".number_format($totalpay)."</td>
                    </tr>
                    <tr>
                        <td class='txtl text-right'>Remain Amount</td>
                        <td class='txtl'>".number_format($remain)."</td>
                    </tr>
                <tbody>
            </table>
            <table class='table table\-bordered text\-sm' rules=rows width='100%'>
                <tbody>
                    <tr>
                        <td class='txtl text-center' width='33%'>Paid By</td>
                        <td class='txtl text-center' width='33%'>Received By</td>
                        <td class='txtl text-center' width='33%'>Approved By</td>
                    </tr>
                    <tr>
                        <td class='txtl text-center' width='33%'>{$payname}</td>
                        <td class='txtl text-center' width='33%'>{$receivename}</td>
                        <td class='txtl text-center' width='33%'></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <button class='btn btn-{$color}' id='btnprint' >Print</button>
        ";      
        /////////////
    }
    echo $out;
}

if($action == "excel"){
    $yearid = $_SESSION["go_schoolfee_aid"];
    $serdt = $_POST["serdt"];    
    $search = $_POST["ser"];
    $ser = "";
    if($search != ""){
        $ser = " and (p.Name like '%$search%') ";
    }
    $gradeid = $_POST["h_gid"];
    if($gradeid != ""){
        $ser .= " and e.GradeID='{$gradeid}' ";
    }
    $out="";
    $fileName = "StudentFeeReports-".date('d-m-Y').".xls";
    $out.='<head><meta charset="UTF-8"></head>
    <table class="table table-sm table-bordered table-striped table-responsive nowrap">
        <thead>
            <tr>
                <td colspan="16" align="center"><h3>Student Fee Pay Lists</h3></td>
            </tr>
            <tr><td colspan="16"><td></tr>
            <tr class="t1">
                <th style="border: 1px solid ;"></th>
                <th style="border: 1px solid ;"></th>
                <th style="border: 1px solid ;"></th>
                <th  style="border: 1px solid ;"></th>';     
                for($i=0; $i<count($arr_montheng); $i++){
                    $out.='<th class="t1" style="border: 1px solid ;">'.$arr_montheng[$i].'</th>';
                }                    
        $out.='
            </tr>';
        $out.='
            <tr class="t1">
                <th style="border: 1px solid ;">No</th>
                <th  style="border: 1px solid ;">Name</th>
                <th  style="border: 1px solid ;">Year</th>
                <th  style="border: 1px solid ;">Grade</th>';     
                for($i=1; $i<=count($arr_montheng); $i++){
                    if($i < 10){
                        $a = 0;
                    }else{
                        $a = "";
                    }
                    $out.='<th style="border: 1px solid ;">'.$a.$i.'</th>';
                }
        $out.='
            </tr>';
        $out.='
        </thead>
        <tbody>
        ';
        $sql_student = "select e.*,p.Name as pname,y.Name as yname,g.Name as gname   
        from tblearstudent e,tblstudentprofile p,tblearyear y,tblgrade g   
        where e.StudentID=p.AID and e.EARYearID=y.AID and e.GradeID=g.AID 
        and e.EARYearID='{$yearid}' ".$ser;
        $res_student = mysqli_query($con,$sql_student);
        $no = 0;
        if(mysqli_num_rows($res_student) > 0){
            while($row_student = mysqli_fetch_array($res_student)){
                $no = $no + 1;
                $out.='
                <tr>
                    <td style="border: 1px solid ;">'.$no.'</td>
                    <td style="border: 1px solid ;">'.$row_student["pname"].'</td>
                    <td style="border: 1px solid ;">'.$row_student["yname"].'</td>
                    <td style="border: 1px solid ;">'.$row_student["gname"].'</td>
                ';
                for($i=0; $i<count($arr_montheng); $i++){
                    $sql_fee = "select f.* 
                    from tblstudentfee f 
                    where f.MonthName='{$arr_montheng[$i]}' and f.EARStudentID='{$row_student['AID']}' 
                    and Year(f.PayDate)='{$serdt}'";
                    $res_fee = mysqli_query($con,$sql_fee);
                    if(mysqli_num_rows($res_fee) > 0){
                        $row_fee = mysqli_fetch_array($res_fee);
                        $color = "background-color:#00ff95;";
                        if($row_fee["Remain"] > 0){
                            $color = "background-color:#ff1247;";
                        }
                        $out.='
                        <td  style="border: 1px solid ;background-color:#00ff95;" ></td>';
                    }else{
                        $out.='<td style="border: 1px solid ;" ></td>';
                    }
                   
                }                
                $out.='
                </tr>
                ';
            }
        }
    $out.='
        </tbody>
    </table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

?>