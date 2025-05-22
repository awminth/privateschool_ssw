<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'show'){  
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
        $a = " and (sp.Name like '%$search%' or p.UserName like '%$search%') ";
    }    
    $sql = "select s.AID as sid,sp.Name as spname,p.*,p.AID as pid from tblparent_student s,
    tblparent p,tblstudentprofile sp where p.AID=s.ParentID and 
    sp.AID=s.StudentID  ". $a." 
    order by s.AID desc limit $offset,$limit_per_page";

   
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['app_studentname'].'</th>
            <th>Username</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["spname"]}</td>
                <td>{$row["UserName"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['sid']}' ><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                {$lang['staff_delete']}</a>                           
                        </div>
                    </div>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select p.AID as pid from tblparent_student s,
        tblparent p,tblstudentprofile sp where p.AID=s.ParentID and 
        sp.AID=s.StudentID  ". $a." 
        order by s.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang['staff_totalrecord'].' - ';
        $out.=$total_record;
        $out.='</p>
        </div>';

        $out.='<div class="float-right">
            <ul class="pagination">
        ';

        $previous_link = '';
        $next_link = '';
        $page_link = '';

        if($total_links > 4){
        if($page < 5){ for($count=1; $count <=5; $count++) { $page_array[]=$count; } $page_array[]='...' ;
            $page_array[]=$total_links; }else{ $end_limit=$total_links - 5; if($page> $end_limit){
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++) { $page_array[]=$count; } }else{ $page_array[]=1;
                $page_array[]='...' ; for($count=$page - 1; $count <=$page + 1; $count++) { $page_array[]=$count; }
                $page_array[]='...' ; $page_array[]=$total_links; } } }else{ for($count=1; $count <=$total_links;
                $count++) { $page_array[]=$count; } } for($count=0; $count < count($page_array); $count++){
                if($page==$page_array[$count]){ $page_link .='<li class="page-item active">
                                    <a class="page-link" href="#">' .$page_array[$count].' <span class="sr-only">
                (current)</span></a>
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
                    <a class="page-link" href="javascript:void(0)"
                        data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                </li> ';
                }
                }
                }

                $out .= $previous_link . $page_link . $next_link;

                $out .= '</ul>
            </div>';

        echo $out;

    }else{
    $out.='
    <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th width="7%;">'.$lang['no'].'</th>
                <th>'.$lang['app_studentname'].'</th>
                <th>Username</th>
                <th width="8%;">Action</th>
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

if($action == "student"){
    $data = $_POST["data"];
    $sql = "select AID from tblparentuser where StudentID='{$data}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'delete'){
    $aid = $_POST["aid"];
    $sql = "delete from tblparent_student where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် app user အားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){
        $a = " and (sp.Name like '%$search%' or p.UserName like '%$search%') ";
    }
    $sql = "select s.*,sp.Name as spname,p.*,p.AID as pid from tblparent_student s,
    tblparent p,tblstudentprofile sp where p.AID=s.ParentID and
    sp.AID=s.StudentID  ". $a."
    order by s.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "AppUserReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>App User Control</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">'.$lang['no'].'</th>
            <th style="border: 1px solid ;">'.$lang['app_studentname'].'</th>
            <th style="border: 1px solid ;">User Name</th>
            <th style="border: 1px solid ;">Password</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
            <tr>
                <td style="border: 1px solid ;">'.$no.'</td>
                <td style="border: 1px solid ;">'.$row["spname"].'</td>
                <td style="border: 1px solid ;">'.$row["UserName"].'</td>
                <td style="border: 1px solid ;">'.$row["Password"].'</td>
            </tr>';
        }
        $out .= '
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '

        <head>
            <meta charset="utf-8">
        </head>
        <table>
            <tr>
                <td colspan="4" align="center">
                    <h3>App User Control</h3>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                <td>
            </tr>
            <tr>
                <th style="border: 1px solid ;">'.$lang['no'].'</th>
                <th style="border: 1px solid ;">'.$lang['app_studentname'].'</th>
                <th style="border: 1px solid ;">User Name</th>
                <th style="border: 1px solid ;">Password</th>
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="4" align="center">No record found.</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }

}

if($_POST["action"] == 'pdf'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){
    $a = " and (sp.Name like '%$search%' or p.UserName like '%$search%') ";
    }
    $sql = "select s.*,sp.Name as spname,p.*,p.AID as pid from tblparent_student s,
    tblparent p,tblstudentprofile sp where p.AID=s.ParentID and
    sp.AID=s.StudentID  ". $a."
    order by s.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "AppUserReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
    $out .= '
    
    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>App User Control</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">'.$lang['no'].'</th>
            <th style="border: 1px solid ;">'.$lang['app_studentname'].'</th>
            <th style="border: 1px solid ;">User Name</th>
            <th style="border: 1px solid ;">Password</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
        $no = $no + 1;
        $out .= '
        <tr>
            <td style="border: 1px solid ;">'.$no.'</td>
            <td style="border: 1px solid ;">'.$row["spname"].'</td>
            <td style="border: 1px solid ;">'.$row["UserName"].'</td>
            <td style="border: 1px solid ;">'.$row["Password"].'</td>
        </tr>';
        }
        $out .= '
    </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'AppUserPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
    $out .= '
    
    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>App User Control</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">'.$lang['no'].'</th>
            <th style="border: 1px solid ;">'.$lang['app_studentname'].'</th>
            <th style="border: 1px solid ;">User Name</th>
            <th style="border: 1px solid ;">Password</th>
        </tr>
        <tr>
            <td style="border: 1px solid ;" colspan="4" align="center">No record found.</td>
        </tr>
    </table>';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = 'AppUserPDF'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
    }
    
}

// for show student
if($action == 'showstudent'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){
        $a = " and (s.Name like '%$search%') ";
    }
    $sql = "select * from tblstudentprofile s where  s.AID
    Not in (select StudentID from tblparent_student) ".$a;
    
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
    $out.='
    <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th width="7%;">'.$lang['no'].'</th>
                <th>'.$lang['app_studentname'].'</th>
                <th width="10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            ';
            $no=0;
            while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Name"]}</td>
                <td class='text-center'>
                    <a href='#' id='btnclickstudent' data-aid='{$row['AID']}' data-name='{$row['Name']}'
                        class='btn btn-sm btn-success'>Add</a>
                </td>
            </tr>";
            }
            $out.="</tbody>";
        $out.="
        </table>";

        echo $out;

    }else{
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">'.$lang['no'].'</th>
                    <th>'.$lang['app_studentname'].'</th>
                    <th width="8%;">Action</th>
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

// add new parent
if($action == 'saveparent'){
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = "select AID from tblparent where Name='{$name}' and UserName='{$username}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        echo 2;
    }else{
        $sql = "insert into tblparent (Name,UserName,Password,LoginID)
        values ('{$name}','{$username}','{$password}','{$userid}')";        
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် Parent အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

// save account for apk
if($action == 'savestudent'){
    $stuid = $_POST["stuid"];
    $parent = $_POST["parent"];
    $date = date('Y-m-d');

    $sql = "insert into tblparent_student (StudentID,ParentID,LoginID,Date)
    values ('{$stuid}','{$parent}','{$userid}','{$date}')";

    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Parent Account အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}




?>