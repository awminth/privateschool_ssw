<?php
include('../config.php');
require(root.'lib/excelReader/excel_reader2.php');
require(root.'lib/excelReader/SpreadsheetReader.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'show'){  
    unset($_SESSION["teacher_table_aid"]);
    unset($_SESSION["teacher_table_name"]);
    unset($_SESSION["teacher_table_earyear"]); 
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
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
        $a = " and (Name like '%$search%' or StaffID like '%$search%' or PhoneNo like '%$search%') ";
    }    
    $sql = "select * from tblstaff  
    where Status=0 ".$a." 
    order by AID desc limit $offset,$limit_per_page";
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["tea_teacherid"].'</th>
            <th>'.$lang["tea_name"].'</th>
            <th>'.$lang["tea_dob"].'</th>
            <th>'.$lang["tea_gender"].'</th>
            <th>'.$lang["tea_phoneno"].'</th>
            <th width="8%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["StaffID"]}</td>
                <td>{$row["Name"]}</td>
                <td>".enDate($row["DOB"])."</td>
                <td>{$row["Gender"]}</td> 
                <td>{$row["PhoneNo"]}</td>
                <td class='text-center'>
                    <a href='#' id='btnview' 
                        data-aid='{$row['AID']}' 
                        data-name='{$row['Name']}' >
                        <i class='fas fa-eye'></i></a>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select AID from tblstaff  
        where Status=0 ".$a." 
        order by AID desc";
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
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["tea_teacherid"].'</th>
            <th>'.$lang["tea_name"].'</th>
            <th>'.$lang["tea_dob"].'</th>
            <th>'.$lang["tea_gender"].'</th>
            <th>'.$lang["tea_phoneno"].'</th>
            <th width="8%;">Action</th>          
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

if($action == "go_timetable"){
    $taid = $_POST["taid"];
    $tname = $_POST["tname"];
    $earyear = $_POST["earyear"];
    $_SESSION["teacher_table_aid"] = $taid;
    $_SESSION["teacher_table_name"] = $tname;
    $_SESSION["teacher_table_earyear"] = $earyear;
    echo 1;
}

if($action == 'show_timetable'){  
    $taid = $_SESSION["teacher_table_aid"];
    $tname = $_SESSION["teacher_table_name"];
    $earyear = $_SESSION["teacher_table_earyear"];        
    $sql="select t.* 
    from tbltime t 
    where t.AID is not null"; 
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    $arr_time = [];
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th>Day/Time</th>';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th class="text-center">'.$row["Name"].'</th>';
            }        
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr style="height:100px;">
                    <th class="text-center pb-5">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname,g.Name as gname     
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m,tblgrade g     
                        where t.TeacherID=s.AID and t.SubjectID=b.AID and t.GradeID=g.AID   
                        and t.EARYearID={$earyear} and t.TeacherID={$taid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID"; 
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td class="text-center" >
                                <span style="font-size:16px;"><b>'.$row_t["gname"].'</b></span><br>
                                <span class="text-primary" style="font-size:14px;">'.$row_t["bname"].'</span>
                            </td>';
                        }else{
                            $out.='<td></td>';
                        }
                    }
                    
                    
                $out.='</tr>';
            }

        $out.='</thead>
        <tbody>
        ';
        $out.="</tbody>";
        $out.="</table>"; 
        echo $out;
    }else{
        echo"<h3>No Record Found</h3>";
    }

}

if($action == 'excel'){    
    $taid = $_SESSION["teacher_table_aid"];
    $tname = $_SESSION["teacher_table_name"];
    $earyear = $_SESSION["teacher_table_earyear"];  
    $sql = "select t.* from tbltime t where t.AID is not null"; 
    $result = mysqli_query($con,$sql);
    $out = "";
    $arr_time = [];
    $fileName = "TeacherTimetableReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="8" align="center">
                    <h3>Timetable For <b class="text-primary">'.$tname.'</b></h3>
                </td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;height:50px;">Day/Time</th>  
            ';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th style="border: 1px solid ;height:50px;" align="center">'.$row["Name"].'</th>';
            } 
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr>
                    <th style="border: 1px solid ;height:50px;" align="center">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname,g.Name as gname     
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m,tblgrade g     
                        where t.TeacherID=s.AID and t.SubjectID=b.AID and t.GradeID=g.AID   
                        and t.EARYearID={$earyear} and t.TeacherID={$taid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID";
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td style="border: 1px solid ;height:50px;" align="center">
                                '.$row_t["gname"].' <br>
                                <span style="color:blue;">'.$row_t["bname"].'<span>
                            </td>';
                        }else{
                            $out.='<td style="border: 1px solid ;height:50px;" align="center"></td>';
                        }
                    }

                $out.='</tr>';
            }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        echo "No Record Found.";
    }  
}

if($action == 'pdf'){    
    $taid = $_SESSION["teacher_table_aid"];
    $tname = $_SESSION["teacher_table_name"];
    $earyear = $_SESSION["teacher_table_earyear"];  
    $sql = "select t.* from tbltime t where t.AID is not null"; 
    $result = mysqli_query($con,$sql);
    $out = "";
    $arr_time = [];
    $fileName = "TeacherTimetableReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="8" align="center">
                    <h3>Timetable For <b class="text-primary">'.$tname.'</b></h3>
                </td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;height:50px;">Day/Time</th>  
            ';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th style="border: 1px solid ;height:50px;" align="center">'.$row["Name"].'</th>';
            } 
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr>
                    <th style="border: 1px solid ;height:50px;" align="center">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname,g.Name as gname     
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m,tblgrade g     
                        where t.TeacherID=s.AID and t.SubjectID=b.AID and t.GradeID=g.AID   
                        and t.EARYearID={$earyear} and t.TeacherID={$taid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID";
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td style="border: 1px solid ;height:50px;" align="center">
                                '.$row_t["gname"].' <br>
                                <span style="color:blue;">'.$row_t["bname"].'<span>
                            </td>';
                        }else{
                            $out.='<td style="border: 1px solid ;height:50px;" align="center"></td>';
                        }
                    }

                $out.='</tr>';
            }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'TeacherTimetablePDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        echo "No Record Found.";
    }  
}


?>