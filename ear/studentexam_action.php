<?php
include('../config.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];
$dt=date('Y-m-d');

if($action == "show"){
    unset($_SESSION["exam_earstudent_aid"]);
    unset($_SESSION["exam_earstudent_vno"]);
    unset($_SESSION["exam_earstudent_name"]);
    unset($_SESSION["exam_earstudent_examtypeid"]);
    unset($_SESSION["exam_earstudent_examtypename"]);

    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    }else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page = "";
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];                
    }else{
        $page=1;                      
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){ 
        $a = " and (t.Name like '%$search%' or v.EARStudentName like '%$search%') ";
    }       
    $sql = "select v.*,t.Name as tname  
    from tblexam_voucher v,tblexamtype t    
    where  v.EARYearID={$yearid} 
    and v.GradeID={$gradeid} and v.ExamTypeID=t.AID ".$a."   
    order by v.AID desc limit {$offset},{$limit_per_page}";
    
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    $no = 0;
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['exam_stuname'].'</th>
            <th>'.$lang['exam_examtype'].'</th>            
            <th>'.$lang['exam_totalpaymark'].'</th>
            <th>'.$lang['exam_totalgetmark'].'</th>
            <th>'.$lang['exam_dt'].'</th>
            <th width="10%;" class="text-center">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
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
                <td>{$row["EARStudentName"]}</td>  
                <td>{$row["tname"]}</td> 
                <td>{$row["TotalPayMark"]}</td> 
                <td>{$row["TotalGetMark"]}</td> 
                <td>".enDate($row["Date"])."</td>   
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>                       
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-vno='{$row['VNO']}'
                                data-stuid='{$row['EARStudentID']}'
                                data-stuname='{$row['EARStudentName']}'
                                data-examtypeid='{$row['ExamTypeID']}'
                                data-examtypename='{$row['tname']}'><i
                                class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                                ".$lang['btnedit']."</a>  
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-vno='{$row['VNO']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                ".$lang['btndelete']."</a>   
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btnview' class='dropdown-item'
                                data-vno='{$row['VNO']}'><i
                                class='fas fa-eye text-info'
                                style='font-size:13px;'></i>
                                View</a>                  
                        </div>
                    </div>
                </td>            
              
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="select v.*,t.Name as tname  
        from tblexam_voucher v,tblexamtype t    
        where v.EARYearID={$yearid} 
        and v.GradeID={$gradeid} and v.ExamTypeID=t.AID ".$a."   
        order by v.AID desc";
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
        
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['exam_stuname'].'</th>
            <th>'.$lang['exam_examtype'].'</th>            
            <th>'.$lang['exam_totalpaymark'].'</th>
            <th>'.$lang['exam_totalgetmark'].'</th>
            <th>'.$lang['exam_dt'].'</th>
            <th width="10%;" class="text-center">Action</th>           
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == 'show_earstudent'){ 
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=30; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page = "";
    $no = 0;
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];                
    }else{
        $page=1;                      
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){  
        $a = " and s.Name like '%$search%' ";  
    }       
    $sql="select e.*,s.Name from tblearstudent e,tblstudentprofile s where 
    s.AID=e.StudentID and e.EARYearID={$yearid} and 
    e.GradeID={$gradeid} ".$a."
    order by e.AID desc limit {$offset},{$limit_per_page}";  

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th width="7%;">'.$lang['no'].'</th>
                <th>'.$lang['exam_stuname'].'</th>
                <th width="20%;" class="text-center">Action</th>           
            </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
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
                <td class='text-center'>  
                    <a href='#' id='btnaddexam' class='dropdown-item'
                        data-aid='{$row['AID']}' 
                        data-name='{$row['Name']}' ><i class='fas fa-edit text-primary'
                        style='font-size:13px;'></i>
                        ".$lang['exam_put']."</a>                           
                </td>                 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="select e.AID 
        from tblearstudent e,tblstudentprofile s 
        where s.AID=e.StudentID and  
        e.EARYearID={$yearid} and e.GradeID={$gradeid} ".$a."
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
        
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th width="7%;">'.$lang['no'].'</th>
                <th>'.$lang['exam_stuname'].'</th>
                <th width="20%;" class="text-center">Action</th>           
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

if($action == "go_addexam"){
    $aid = $_POST["aid"];
    $name = $_POST["name"];
    $_SESSION["exam_earstudent_aid"] = $aid;
    $_SESSION["exam_earstudent_name"] = $name;
    echo 1;
}

if($action == "edit_exam"){
    $vno = $_POST["vno"];
    $stuid = $_POST["stuid"];
    $stuname = $_POST["stuname"];
    $examtypeid = $_POST["examtypeid"];
    $examtypename = $_POST["examtypename"];
    $_SESSION["exam_earstudent_vno"] = $vno;
    $_SESSION["exam_earstudent_aid"] = $stuid;
    $_SESSION["exam_earstudent_name"] = $stuname;
    $_SESSION["exam_earstudent_examtypeid"] = $examtypeid;
    $_SESSION["exam_earstudent_examtypename"] = $examtypename;
    echo 1;
}

if($action == "delete"){
    $vno = $_POST["vno"];
    $sql_v = "delete from tblexam_voucher where VNO='{$vno}'";
    if(mysqli_query($con,$sql_v)){
        $sql_e = "delete from tblexam where VNO='{$vno}'";
        if(mysqli_query($con,$sql_e)){
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
}

if($action == "view"){
    $vno = $_POST["vno"];
    $out = "";
    $student = "";
    $grade = "";
    $year = "";  
    $dt = "";  
    $sql_v = "select * from tblexam_voucher where VNO='{$vno}'";
    $res_v = mysqli_query($con,$sql_v);
    if(mysqli_num_rows($res_v) > 0){
        $row_v = mysqli_fetch_array($res_v);
        $student = $row_v["EARStudentName"];
        $grade = $row_v["GradeName"];
        $year = $row_v["EARYearName"];
        $dt = enDate($row_v["Date"]);
    }
    $out .= "
    <div id='printdata'>
        <h5 class='text-center'>
            <img src=".$_SESSION["examlogo"]." style='width:120px;height:80px;'>
            <span style='font-weight:bold;color:red;'>ကိုယ်ပိုင်အထက်တန်းကျောင်း</span><br>   
            လစဉ်ပညာရည်တိုးတက်မှုမှတ်တမ်း<span>(Monthly Report Card)</span><br>
            ".$row_v["EARYearName"]."
        </h5>
        <div style='display:flex;justify-content: space-between;'>
            <p class='txtl fs'>
                အမည် - {$student}
            </p>
            <p class='txtl fs'>
                အတန်း - {$gradename}
            </p>
        </div>
        <table class='table table-bordered table-responsive table-striped' frame=hsides rules=rows width='100%'>
            <tr>
                <th rowspan='2' class='text-center txtc'>လအမည်</th>";
        $sql_sub = "select * from tblsubject where GradeID='{$gradeid}'";
        $res_sub = mysqli_query($con,$sql_sub);
        $count_sub = array();
        while($row_sub=mysqli_fetch_array($res_sub)){
            $out.="<th rowspan='2' class='text-center txtc'>{$row_sub["Name"]}</th>";
            array_push($count_sub,$row_sub["AID"]);
        }
        $a=1;
        $out.=" <th rowspan='2' class='text-center txtc'>စုစုပေါင်းရမှတ်</th>
                <th rowspan='2' class='text-center txtc'>အဆင့်</th>
                <th colspan='2' class='text-center txtc'>ဘာသာစကား</th>
                <th rowspan='2' class='text-center txtc'>ကွန်ပျူတာဘာသာ</th>
                <th rowspan='2' class='text-center txtc'>အတန်းပိုင်လက်မှတ်နှင့်နေ့စွဲ</th>
                <th rowspan='2' class='text-center txtc'>မိဘအုပ်ထိန်းသူလက်မှတ်နှင့်နေ့စွဲ</th>
                <th rowspan='2' class='text-center txtc'>ကျောင်းအုပ်ကြီးလက်မှတ်နှင့်နေ့စွဲ</th>
            </tr>
            <tr>
                <th class='text-center txtc'>Japan/Korea</th>
                <th class='text-center txtc'>Eng 4 skills</th>
            </tr>
            <tr>
                <th class='text-center txtc'>({$a})</th>";
            for($i=1;$i<count($count_sub);$i++){
                $a = $i+1;
                $out.="<th class='text-center txtc'>({$a})</th>";
            }
            $a1=count($count_sub)+1;
            $a2=count($count_sub)+2;
            $a3=count($count_sub)+3;
            $a4=count($count_sub)+4;
            $a5=count($count_sub)+5;
            $a6=count($count_sub)+6;
            $a7=count($count_sub)+7;
            $a8=count($count_sub)+8;
            $a9=count($count_sub)+9;
            $out.="
                <th class='text-center txtc'>({$a1})</th>
                <th class='text-center txtc'>({$a2})</th>
                <th class='text-center txtc'>({$a3})</th>
                <th class='text-center txtc'>({$a4})</th>
                <th class='text-center txtc'>({$a5})</th>
                <th class='text-center txtc'>({$a6})</th>
                <th class='text-center txtc'>({$a7})</th>
                <th class='text-center txtc'>({$a8})</th>
                <th class='text-center txtc'>({$a9})</th>
            </tr>
            ";
        for($i=0;$i<count($arr_month);$i++){
            $out.="<tr><td class='text-center txtc'>{$arr_month[$i]}</td>";
            $sql_chksub = "select GetMark from tblexam where ExamTypeName='{$arr_month[$i]}'";
            $res_chksub = mysqli_query($con,$sql_chksub);
            $total_getmark=0;
            if(mysqli_num_rows($res_chksub)>0){
                while($row_chksub=mysqli_fetch_array($res_chksub)){
                    $out.="<td class='text-center txtc'>{$row_chksub["GetMark"]}</td>";
                    $total_getmark+=$row_chksub["GetMark"];
                }
                $out.="<td class='text-center txtc'>{$total_getmark}</td>";
            }
            else{
                for($j=0;$j<count($count_sub);$j++){
                    $out.="<td class='text-center txtc'></td>";
                }
                $out.="<td class='text-center txtc'></td>";
            }
            for($k=0;$k<7;$k++){
                $out.="<td class='text-center txtc'></td>";
            }
            $out.="</tr>";
        }
        $out.=" <tr><td class='text-center txtc'>တစ်နှစ်တာစုစုပေါင်းရမှတ်(ပျှမ်းမျှ)</td>";
        for($m=0;$m<count($count_sub);$m++){
            $out.="<td class='text-center txtc'></td>";
        }
        for($n=0;$n<8;$n++){
            $out.="<td class='text-center txtc'></td>";
        }
        $out.="</tr>";
        $out.="<tr><td class='text-center txtc'>ပျှမ်းမျှရမှတ်/အဆင့်</td>";
        for($m=0;$m<count($count_sub);$m++){
            $out.="<td class='text-center txtc'></td>";
        }
        for($n=0;$n<8;$n++){
            $out.="<td class='text-center txtc'></td>";
        }
        $out.="</tr>
            </table>
        ";
        $out.="
        <br><br><br>
        <div style='display:flex;justify-content: space-between;'>
            <p class='txtl fs'>
                အတန်းပိုင်ဆရာ၏မှတ်ချက်။  ------------------------------
            </p>
            <p class='txtl fs'>
                ကျောင်းအုပ်ကြီး မှတ်ချက်။  ------------------------------
            </p>
        </div>
        <div style='display:flex;justify-content: space-between;'>
            <p class='txtl fs'>
                -----------------------------------------------------------------------
            </p>
            <p class='txtl fs'>
                ----------------------------------------------------
            </p>
            <p class='txtl fs'>
                ------------------------------------------------------------------------
            </p>
        </div>
        <div style='display:flex;justify-content: space-between;'>
            <p class='txtl fs'>
                အတန်းပိုင်ဆရာ၏ လက်မှတ်။  ------------------------------
            </p>
            <p class='txtl fs'>
                ဘော်ဒါအုပ်လက်မှတ်
            </p>
            <p class='txtl fs'>
                ကျောင်းအုပ်ကြီး လက်မှတ်။  ------------------------------
            </p>
        </div>
        ";
        $out.="
    </div>
    <br>
    <button class='btn btn-{$color}' id='btnprint' >Print</button>
    ";  
    /////////
    echo $out;
}




?>