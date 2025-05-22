<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];

if($action == 'show'){  
    unset($_SESSION["chk_dt"]);

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


    $dtfrom =date('Y-m') ;
    if($_POST["dtfrom"]!=""){
        $dtfrom=$_POST["dtfrom"];
    }
    $dtto = $_POST["dtto"];

    $yrdata= strtotime($dtfrom);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);

    $tomonth=date('m');
    $toyear=date('Y');

    $b = " and Month(AttendanceDate)='{$tomonth}' and Year(AttendanceDate)='{$toyear}'";
    if($dtfrom != ""){
        $b = " and Month(AttendanceDate)='{$month}' and Year(AttendanceDate)='{$year}' ";
    }

    $sql="select *,
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=1 ".$b.") as tin,
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=0 ".$b.") as tout
    from tblstudentattendance a where EARYearID={$yearid} and 
    GradeID={$gradeid}  ".$b." group by EARStudentID
    limit {$offset},{$limit_per_page}";

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>Student Name</th>
            <th>Present</th>
            <th>Absent</th> 
            <th>Percent(%)</th>  
            <th class="text-center">ခစာရင်း</th>       
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $pname = GetString("select p.Name from tblstudentprofile p,tblearstudent e,tblstudentattendance s 
            where p.AID=e.StudentID and e.AID=s.EARStudentID and s.AID='{$row['AID']}' 
            group by s.EARStudentID");
            $sum=$row["tin"]+$row["tout"];
            $percent=round(($row["tin"]/$sum)*100);
            $out.="<tr>
                <td>{$no}</td>
                <td>{$pname}</td>   
                <td>{$row["tin"]}</td>
                <td>{$row["tout"]}</td>
                <td>{$percent} %</td>
                <td class='text-center'>
                    <a href='#' id='btnkhalist' 
                        data-stuname='{$pname}' 
                        data-earstuid='{$row["EARStudentID"]}' 
                        data-serdt='{$dtfrom}' >
                        <i class='fas fa-eye'></i>&nbsp;View
                    </a>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br><br>";

        $sql_total="select AID from tblstudentattendance a where EARYearID={$yearid} and 
        GradeID={$gradeid}  ".$b." group by EARStudentID ";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>Total Records -  ';
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
            <th width="7%;">No</th>
            <th>Student Name</th>
            <th>Present</th>
            <th>Absent</th>  
            <th>ခစာရင်း</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action== 'excel'){
    $dtfrom = $_POST["dtfrom"];
    $dtto = $_POST["dtto"];
    $yrdata= strtotime($dtfrom);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $tomonth=date('m');
    $toyear=date('Y');

    $b = " and Month(AttendanceDate)='{$tomonth}' and Year(AttendanceDate)='{$toyear}'";
    if($dtfrom != ""){
        $b = " and Month(AttendanceDate)='{$month}' and Year(AttendanceDate)='{$year}' ";
    }

    $sql="select *,
    (select p.Name from tblstudentprofile p,tblearstudent e,tblstudentattendance s 
    where p.AID=e.StudentID and e.StudentID=s.EARStudentID and s.AID=a.AID 
    group by s.EARStudentID) as sname, 
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=1 ".$b.") as tin,
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=0 ".$b.") as tout
    from tblstudentattendance a where EARYearID={$yearid} and 
    GradeID={$gradeid} ".$b." group by EARStudentID";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentAttendanceReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Student Attendance Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Present</th>
                <th style="border: 1px solid ;">Absent</th>  
                <th style="border: 1px solid ;">Percent</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $sum=$row["tin"]+$row["tout"];
            $percent=round(($row["tin"]/$sum)*100);
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$row["tin"].'</td>  
                    <td style="border: 1px solid ;">'.$row["tout"].'</td> 
                    <td style="border: 1px solid ;">'.$percent.' %</td>               
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Student Attendance Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Present</th>
                <th style="border: 1px solid ;">Absent</th>  
                <th style="border: 1px solid ;">Percent</th>      
            </tr>
            <tr>
                <td colspan="5" align="center" style="border: 1px solid ;">No data</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action== 'pdf'){
    $dtfrom = $_POST["dtfrom"];
    $dtto = $_POST["dtto"];
    $yrdata= strtotime($dtfrom);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $tomonth=date('m');
    $toyear=date('Y');

    $b = " and Month(AttendanceDate)='{$tomonth}' and Year(AttendanceDate)='{$toyear}'";
    if($dtfrom != ""){
        $b = " and Month(AttendanceDate)='{$month}' and Year(AttendanceDate)='{$year}' ";
    }

    $sql="select *,
    (select p.Name from tblstudentprofile p,tblearstudent e,tblstudentattendance s 
    where p.AID=e.StudentID and e.StudentID=s.EARStudentID and s.AID=a.AID 
    group by s.EARStudentID) as sname, 
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=1 ".$b.") as tin,
    (select count(AID) from tblstudentattendance where EARYearID={$yearid} and 
    GradeID={$gradeid} and EARStudentID=a.EARStudentID and Status=0 ".$b.") as tout
    from tblstudentattendance a where EARYearID={$yearid} and 
    GradeID={$gradeid} ".$b." group by EARStudentID";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentAttendanceReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Student Attendance Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Present</th>
                <th style="border: 1px solid ;">Absent</th>  
                <th style="border: 1px solid ;">Percent</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $sum=$row["tin"]+$row["tout"];
            $percent=round(($row["tin"]/$sum)*100);
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$row["tin"].'</td>  
                    <td style="border: 1px solid ;">'.$row["tout"].'</td> 
                    <td style="border: 1px solid ;">'.$percent.' %</td>               
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentAttendancePDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Student Attendance Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Present</th>
                <th style="border: 1px solid ;">Absent</th>  
                <th style="border: 1px solid ;">Percent</th>      
            </tr>
            <tr>
                <td colspan="5" align="center" style="border: 1px solid ;">No data</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentAttendancePDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action == "chk_save"){
    $dtsave = $_POST["dtsave"];
    $sql = "select AID from tblstudentattendance 
    where AttendanceDate='{$dtsave}' and EARYearID={$yearid} and 
    GradeID={$gradeid} ";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        echo 2;
    }else{
        $sql_in = "insert into tblstudentattendance (LoginID,EARYearID,GradeID,EARStudentID,
        AttendanceDate) select LoginID,EARYearID,GradeID,AID,'{$dtsave}' 
        from tblearstudent 
        where EARYearID={$yearid} and GradeID={$gradeid} and LoginID={$userid}";
        if(mysqli_query($con,$sql_in)){
            $_SESSION["chk_dt"] = $dtsave;
            echo 1;
        }else{
            echo 0;
        }        
    }
}

if($action == "show_student"){
    $chkdt = $_SESSION['chk_dt'];
    $search = $_POST["search"];
    $a = "";
    if($search != ""){
        $a = " and p.Name like '%$search%' ";
    }
    $sql = "select a.*,p.Name as pname   
    from tblstudentattendance a,tblearstudent e,tblstudentprofile p   
    where a.EARStudentID=e.AID and e.StudentID=p.AID  
    and a.EARYearID={$yearid} and a.GradeID={$gradeid} and a.AttendanceDate='{$chkdt}' ".$a." 
    group by a.EARStudentID order by a.AID desc";
    
    $res = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    $no = 0;
    if(mysqli_num_rows($res) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>Student Name</th>
            <th>Attendance Date</th>
            <th>Absent Reason</th>  
            <th></th>       
        </tr>
        </thead>
        <tbody>
        ';
        while($row = mysqli_fetch_array($res)){
            $no = $no + 1;
            $ck = "";
            if($row["Status"] == 0){
                $ck = "checked";
            }
            $out.="
            <tr>
                <td>{$no}</td>
                <td>{$row["pname"]}</td>
                <td>".enDate($row["AttendanceDate"])."</td>
                <td>{$row["StatusRmk"]}</td>
                <td class='text-center'>
                    <input type='checkbox' {$ck} class='checkItem' 
                        id='checkItem".$row['AID']."' value='{$row['AID']}' 
                        name='code[]'/>
                </td>
            </tr>
            ";
        }
        $out.="</tbody>
        </table><hr>
        <div class='text-right'>
            <button type='submit' id='btnfinal' data-dt='{$chkdt}' class='btn btn-{$color}'><i class='fas fa-save'></i>
                Save Attendance</button>
        </div>
        ";
        echo $out;
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>Student Name</th>
            <th>Attendance Date</th>
            <th>Remark</th>  
            <th></th>       
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == 'save_absent'){
    $aid = $_POST["aid"];
    $rmk = $_POST["rmk"];
    $sql = "update tblstudentattendance  set Status=0,StatusRmk='{$rmk}' 
    where AID={$aid}";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'delete_absent'){
    $aid = $_POST["aid"];
    $sql = "update tblstudentattendance  set Status=1,StatusRmk=NULL 
    where AID={$aid}";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "final_save"){
    $dt = $_POST["dt"];
    $today = date("Y-m-d");
    $sql = "update tblstudentattendance  set Date='{$today}' where AttendanceDate='{$dt}'";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် ".$dt." attendance အား save သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action== 'absent_excel'){
    $search = $_POST['ser'];
    $chkdt = $_SESSION['chk_dt'];
    $a = "";
    if($search != ""){
        $a = " and p.Name like '%$search%' ";
    }
    $sql = "select a.*,p.Name as pname   
    from tblstudentattendance a,tblearstudent e,tblstudentprofile p   
    where a.EARStudentID=e.AID and e.StudentID=p.AID  
    and a.EARYearID={$yearid} and a.GradeID={$gradeid} and a.AttendanceDate='{$chkdt}' ".$a." 
    order by a.AID desc";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentAbsentReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="4" align="center"><h3>Student Absent Report</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Attendance Date</th>
                <th style="border: 1px solid ;">Absent Reason</th>      
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["pname"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["AttendanceDate"]).'</td>  
                    <td style="border: 1px solid ;">'.$row["StatusRmk"].'</td>               
                </tr>';
        }
        $out .= '</table>';

            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename='.$fileName);
            echo $out;
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="4" align="center"><h3>Student Absent Report</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Attendance Date</th>
                <th style="border: 1px solid ;">Absent Reason</th>      
            </tr>
            <tr>
                <td colspan="4" align="center" style="border: 1px solid ;">No data</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == "chk_prepare"){
    $dtsave = $_POST["dtsave"];
    $sql = "select AID from tblstudentattendance 
    where AttendanceDate='{$dtsave}' and EARYearID={$yearid} and 
    GradeID={$gradeid} ";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $_SESSION["chk_dt"] = $dtsave;
        echo 1;
    }else{
        echo 0;       
    }
}

if($action == "delete"){
    $dtsave = $_POST["dtsave"];
    $sql = "select AID from tblstudentattendance 
    where AttendanceDate='{$dtsave}' and EARYearID={$yearid} and 
    GradeID={$gradeid} ";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $sql = "delete from tblstudentattendance where AttendanceDate='{$dtsave}'";
        if(mysqli_query($con,$sql)){
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 2;       
    }
}

/////////////////////////////////////////////
// for studentatt_view ka list ကစာရင်း
if($action == "ka_list"){
    $serdt = $_POST["serdt"];
    $yrdata = strtotime($serdt);
    $month = date('m', $yrdata);
    $year = date('Y', $yrdata);
    $search = $_POST["search"];
    $ser = "";
    if($search != ""){
        $ser = " and (s.Name like '%$search%') ";
    }
    $out="";
    $dt = strtotime( $serdt);
    $day = date("t",$dt);
    $out.="
    <table style='max-width:100%' class='t1 t2'>
        <thead>
            <tr class='t1'>
                <th class='t1'>No</th>
                <th class='t1'>Day</th>";                
                for($i=1; $i<=$day; $i++){
                    if($i<10){
                        $a = 0;
                    }else{
                        $a = "";
                    }
                    $out.="<th class='t1'>{$a}{$i}</th>";
                }  
                $out.="
                <th class='t1'>%</th>
                ";                   
        $out.="
            </tr>
        </thead>
        <tbody>
        ";
        $sql = "select a.*,s.Name as sname  
        from tblstudentattendance a,tblearstudent e,tblstudentprofile s   
        where a.EARStudentID=e.AID and e.StudentID=s.AID and a.EARYearID={$yearid} and 
        a.GradeID={$gradeid} and   
        Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}' ".$ser." 
        group by a.EARStudentID";        
        $res = mysqli_query($con,$sql);
        $no = 0;
        
        if(mysqli_num_rows($res) > 0){            
            while($row = mysqli_fetch_array($res)){
                $no = $no + 1;
                $out.="
                <tr class='t1'>
                    <td class='t1'>{$no}</td>
                    <td class='t1'>{$row['sname']}</td>
                ";

                $absent=0;
                $present=0;
                $sum=0;
                for($i=1; $i<=$day; $i++){
                    $sql_d = "select a.*,Day(a.AttendanceDate) as dd   
                    from tblstudentattendance a    
                    where a.EARStudentID={$row['EARStudentID']} and Day(a.AttendanceDate)='{$i}' 
                    and Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}'";
                    $res_d = mysqli_query($con,$sql_d);
                    if(mysqli_num_rows($res_d) > 0){
                        $row_d = mysqli_fetch_array($res_d);
                       
                        if($row_d["Status"] == 0){
                            $txt = "ပျက်";
                            $col = "text-danger";
                            $absent+=1;
                        }else{
                            $txt = "တက်";
                            $col = "text-success";
                            $present+=1;
                        }
                        $out.="<td class='t1 {$col}'>{$txt}</td>";
                    }else{
                        $out.="<td class='t1'>-</td>";
                    }
                }
                $sum=$present+$absent;
                $percent=round(($present/$sum)*100);
                $out.="<td class='t1'>{$percent} %</td>
                </tr>
                ";
            }
        }else{
            $out.="
            <tr class='t1'>
                <td  class='t1'></td>
                <td  class='t1'></td>
                <td  class='t1' colspan='{$day}'>No record</td>
                <td  class='t1'></td>
            </tr>
            ";
        }
    $out.="
        </tbody>
    </table><br><br>";
    echo $out;
}

if($action == 'todaydt'){
    $data = date("Y-m-d");
    echo $data;
}

if($action == 'leftdt'){
    $dt = $_POST["dt"];
    $data = date('Y-m-d', strtotime($dt. ' - 1 months')); 
    echo $data;
}

if($action == 'rightdt'){
    $dt = $_POST["dt"];
    $data = date('Y-m-d', strtotime($dt. ' + 1 months')); 
    echo $data;
}

if($action == "att_excel"){
    $serdt = $_POST["serdt"];
    $yrdata = strtotime($serdt);
    $month = date('m', $yrdata);
    $year = date('Y', $yrdata);
    $search = $_POST["ser"];
    $ser = "";
    if($search != ""){
        $ser = " and (s.Name like '%$search%') ";
    }
    $out="";
    $dt = strtotime($serdt);
    $day = date("t",$dt);
    $fileName = "ကစာရင်းReports-".date('d-m-Y').".xls";
    $out.='<head><meta charset="UTF-8"></head>
    <table >  
        <tr>
            <td colspan="31" align="center"><h3>Attendance က စာရင်း Report</h3></td>
        </tr>
        <tr><td colspan="31"><td></tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">Day</th>';                
            for($i=1; $i<=$day; $i++){
                if($i<10){
                    $a = 0;
                }else{
                    $a = "";
                }
                $out.='<th style="border: 1px solid ;">'.$a.$i.'</th>';
            }  
        $out.='
            <th style="border: 1px solid ;">%</th>
        </tr>
        '; 
        $sql = "select a.*,s.Name as sname  
        from tblstudentattendance a,tblearstudent e,tblstudentprofile s   
        where a.EARStudentID=e.StudentID and e.StudentID=s.AID and a.EARYearID={$yearid} and 
        a.GradeID={$gradeid} and   
        Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}' ".$ser." 
        group by a.EARStudentID";        
        $res = mysqli_query($con,$sql);
        $no = 0;
        if(mysqli_num_rows($res) > 0){            
            while($row = mysqli_fetch_array($res)){
                $no = $no + 1;
                $out.='
                <tr>
                    <td style="border: 1px solid ;">'.$no.'</td>
                    <td style="border: 1px solid ;">'.$row['sname'].'</td>
                ';
                $present=0;
                $absent=0;
                $sum=0;
                for($i=1; $i<=$day; $i++){
                    $sql_d = "select a.*,Day(a.AttendanceDate) as dd   
                    from tblstudentattendance a    
                    where a.EARStudentID={$row['EARStudentID']} and Day(a.AttendanceDate)='{$i}' 
                    and Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}'";
                    $res_d = mysqli_query($con,$sql_d);
                    if(mysqli_num_rows($res_d) > 0){
                        $row_d = mysqli_fetch_array($res_d);
                        
                        if($row_d["Status"] == 0){
                            $txt = "ပျက်";
                            $col = "color:red;";
                            $absent+=1;
                        }else{
                            $txt = "တက်";
                            $col = "color:green;";
                            $present+=1;
                        }
                        $out.='<td style="border: 1px solid ;'.$col.'">'.$txt.'</td>';
                    }else{
                        $out.='<td style="border: 1px solid ;">-</td>';
                    }
                }
                $sum=$present+$absent;
                $percent=round(($present/$sum)*100);
                $out.='<td style="border: 1px solid ;">'.$percent.' %</td>
                </tr>
                ';
            }
        }else{
            $out.='
            <tr>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;" align="center" colspan="'.$day.'">No record</td>
                <td style="border: 1px solid ;"></td>
            </tr>
            ';
        }
    $out.='
    </table>
    ';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

if($action == "att_pdf"){
    $serdt = $_POST["serdt"];
    $yrdata = strtotime($serdt);
    $month = date('m', $yrdata);
    $year = date('Y', $yrdata);
    $search = $_POST["ser"];
    $ser = "";
    if($search != ""){
        $ser = " and (s.Name like '%$search%') ";
    }
    $out="";
    $dt = strtotime($serdt);
    $day = date("t",$dt);
    $fileName = "ကစာရင်းReports-".date('d-m-Y').".xls";
    $out.='<head><meta charset="UTF-8"></head>
    <table >  
        <tr>
            <td colspan="31" align="center"><h3>Attendance က စာရင်း Report</h3></td>
        </tr>
        <tr><td colspan="31"><td></tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">Day</th>';                
            for($i=1; $i<=$day; $i++){
                if($i<10){
                    $a = 0;
                }else{
                    $a = "";
                }
                $out.='<th style="border: 1px solid ;">'.$a.$i.'</th>';
            }  
        $out.='
            <th style="border: 1px solid ;">%</th>
        </tr>
        '; 
        $sql = "select a.*,s.Name as sname  
        from tblstudentattendance a,tblearstudent e,tblstudentprofile s   
        where a.EARStudentID=e.StudentID and e.StudentID=s.AID and a.EARYearID={$yearid} and 
        a.GradeID={$gradeid} and   
        Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}' ".$ser." 
        group by a.EARStudentID";        
        $res = mysqli_query($con,$sql);
        $no = 0;
        if(mysqli_num_rows($res) > 0){            
            while($row = mysqli_fetch_array($res)){
                $no = $no + 1;
                $out.='
                <tr>
                    <td style="border: 1px solid ;">'.$no.'</td>
                    <td style="border: 1px solid ;">'.$row['sname'].'</td>
                ';
                $present=0;
                $absent=0;
                $sum=0;
                for($i=1; $i<=$day; $i++){
                    $sql_d = "select a.*,Day(a.AttendanceDate) as dd   
                    from tblstudentattendance a    
                    where a.EARStudentID={$row['EARStudentID']} and Day(a.AttendanceDate)='{$i}' 
                    and Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}'";
                    $res_d = mysqli_query($con,$sql_d);
                    if(mysqli_num_rows($res_d) > 0){
                        $row_d = mysqli_fetch_array($res_d);
                        
                        if($row_d["Status"] == 0){
                            $txt = "ပျက်";
                            $col = "color:red;";
                            $absent+=1;
                        }else{
                            $txt = "တက်";
                            $col = "color:green;";
                            $present+=1;
                        }
                        $out.='<td style="border: 1px solid ;'.$col.'">'.$txt.'</td>';
                    }else{
                        $out.='<td style="border: 1px solid ;">-</td>';
                    }
                }
                $sum=$present+$absent;
                $percent=round(($present/$sum)*100);
                $out.='<td style="border: 1px solid ;">'.$percent.' %</td>
                </tr>
                ';
            }
        }else{
            $out.='
            <tr>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;" align="center" colspan="'.$day.'">No record</td>
                <td style="border: 1px solid ;"></td>
            </tr>
            ';
        }
    $out.='
    </table>
    ';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = 'ကစာရင်းPDF'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
}


////////////////////////////////////////////////
//// for student show kha list ခစာရင်း
if($action == "kha_list"){
    $stuname = $_POST["stuname"];
    $earstuid = $_POST["earstuid"];
    $serdt = $_POST["serdt"];
    $_SESSION["kha_studentname"] = $stuname;
    $_SESSION["kha_studentearstuid"] = $earstuid;
    $_SESSION["kha_studentserdt"] = $serdt;
    echo 1;
}

if($action == "cal_count"){
    $earstuid = $_SESSION["kha_studentearstuid"];
    $dt = $_POST["dt"];
    $out = "";
    $yrdata = strtotime($dt);
    $month = date('m', $yrdata);
    $year = date('Y', $yrdata);
    $sql_present = "select count(a.AID) as cnt   
    from tblstudentattendance a    
    where a.EARStudentID={$earstuid} and a.EARYearID={$yearid} and 
    a.GradeID={$gradeid} and  a.Status=1 and  
    Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}'";
    $present = GetInt($sql_present);

    $sql_absent = "select count(a.AID) as cnt   
    from tblstudentattendance a    
    where a.EARStudentID={$earstuid} and a.EARYearID={$yearid} and 
    a.GradeID={$gradeid} and a.Status=0 and  
    Year(a.AttendanceDate)='{$year}' and Month(a.AttendanceDate)='{$month}'";
    $absent = GetInt($sql_absent);

    $out .= "<span class='text-success'>တက် : {$present}</span> / <span class='text-danger'>ပျက် : {$absent}</span>";
    echo $out;
}

if($action == 'kha_todaydt'){
    $data = date("Y-m");
    echo $data;
}

if($action == 'kha_leftdt'){
    $dt = $_POST["dt"];
    $data = date('Y-m', strtotime($dt. ' - 1 months')); 
    echo $data;
}

if($action == 'kha_rightdt'){
    $dt = $_POST["dt"];
    $data = date('Y-m', strtotime($dt. ' + 1 months')); 
    echo $data;
}

if($action == "show_calendar"){
    $earstuid = $_SESSION["kha_studentearstuid"];
    $dt = $_POST["serdt"];
    $ym = date('Y-m');
    if($dt != ""){
        $ym = $dt;
    }

    // Check format
    $timestamp = strtotime($ym . '-01');
    if ($timestamp === false) {
        $ym = date('Y-m');
        $timestamp = strtotime($ym . '-01');
    }

    // Today
    $today = date('Y-m-j', time());  

    // Number of days in the month
    $day_count = date('t', $timestamp);
 
    // 0:Sun 1:Mon 2:Tue ...
    $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
    //$str = date('w', $timestamp);

    // Create Calendar!!
    $weeks = array();
    $week = '';

    // create Add empty cell
    $week .= str_repeat('<td class="td-height"></td>', $str);

    for ( $day = 1; $day <= $day_count; $day++, $str++) {     
        $date = $ym . '-' . $day;
        // search event count from tbltodolist 
        $txt = '';
        $sql = "select count(AID) as cnt from tbltodolist 
        where  Date(StartEvent)='{$date}'";
        $sql_d = "select a.Status     
        from tblstudentattendance a    
        where a.EARStudentID={$earstuid} and a.EARYearID={$yearid} and 
        a.GradeID={$gradeid} and 
        Date(a.AttendanceDate)='{$date}'";
        $res = mysqli_query($con,$sql_d);
        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            if($row["Status"] == 1){
                $col = "text-success";
                $title = "တက်";
            }else{
                $col = "text-danger";
                $title = "ပျက်";
            }
            $txt = '<br><br><b><span class="text-right '.$col.'">'.$title.'</span></b>';
        }
     
        if ($today == $date) {
            $week .= '<td class="td-height today">'.$day.$txt.'</td>';
        } else {
            $week .= '<td class="td-height">'.$day.$txt.'</td>';
        }
     
        // End of the week OR End of the month
        if ($str % 7 == 6 || $day == $day_count) {

            if ($day == $day_count) {
                // Add empty cell
                $week .= str_repeat('<td class="td-height"></td>', 6 - ($str % 7));
            }

            $weeks[] = '<tr>'.$week.'</tr>';

            // Prepare for new week
            $week = '';
        }
    }

    // show data
    foreach ($weeks as $week) {
        echo $week;
    }
}

?>