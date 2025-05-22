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
        $a = " and (a.Description like '%$search%' or pa.UserName like '%$search%' or s.Name like '%$search%') ";
    }    

    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom != '' || $dtto != ''){
        $b = " and a.Date>='{$dtfrom}' and a.Date<='{$dtto}' ";
    }

    $sql = "select a.*,pa.UserName,s.Name as sname,s.AID as said from tblaccoumentparent a,
    tblstudentprofile s ,tblparent pa where a.ParentUserID=pa.AID and
       s.AID=a.StudentID ".$a.$b." 
    order by AID desc limit $offset,$limit_per_page";

   

    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['ann_studentname'].'</th>
            <th>'.$lang['ann_parentaccount'].'</th>
            <th>'.$lang['ann_description'].'</th>
            <th>'.$lang['ann_date'].'</th>
            <th>'.$lang['ann_file'].'</th>
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
                <td>{$row["sname"]}</td>
                <td>{$row["UserName"]}</td>
                <td>{$row["Description"]}</td>
                <td>".enDate($row["Date"])."</td>
                <td>{$row["File"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-description='{$row['Description']}' 
                                data-dt='{$row['Date']}' 
                                data-pid='{$row['ParentUserID']}' 
                                data-pname='{$row['UserName']}'
                                data-sid='{$row['said']}'  
                                data-sname='{$row['sname']}'
                                data-path='{$row['File']}'  >
                                <i class='fas fa-edit text-primary' style='font-size:13px;'></i>
                                {$lang['staff_edit']}</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btnfile' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-path='{$row['File']}' ><i
                                class='fas fa-edit text-success'
                                style='font-size:13px;'></i>
                                {$lang['ann_changefile']}</a> 
                                <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-path='{$row['File']}' ><i
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

        $sql_total="select a.AID from tblaccoumentparent a,
        tblstudentprofile s ,tblparent pa where a.ParentUserID=pa.AID and
          s.AID=a.StudentID ".$a.$b." 
        order by a.AID desc";
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
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['ann_studentname'].'</th>
            <th>'.$lang['ann_parentaccount'].'</th>
            <th>'.$lang['ann_description'].'</th>
            <th>'.$lang['ann_date'].'</th>
            <th>'.$lang['ann_file'].'</th>
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

if($action == "parent"){
    $data = $_POST["data"];
    $sql = "select p.AID,p.UserName from tblparent_student s,tblparent p  where 
    p.AID=s.ParentID and s.StudentID={$data}";
    $res = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $out .= '
        <div class="form-group">
            <input type="hidden" name="parentid" value="'.$row['AID'].'" />
            <label for="usr">'.$lang['ann_parentname'].':</label>
            <input type="text" class="form-control border-primary" name="parent" value="'.$row['UserName'].'" 
                readonly >
        </div>
        ';
        echo $out;
    }
}

if($action == "e_parent"){
    $data = $_POST["data"];
    $sql = "select p.AID,p.UserName from tblparent_student s,tblparent p  where 
    p.AID=s.ParentID and s.StudentID={$data}";
    $res = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $out .= '
        <div class="form-group">
            <input type="hidden" name="eparentid" value="'.$row['AID'].'" />
            <label for="usr">'.$lang['ann_parentname'].':</label>
            <input type="text" class="form-control border-primary" name="eparent" value="'.$row['UserName'].'" 
                readonly >
        </div>
        ';
        echo $out;
    }
}

if($action == 'save'){    
    $description = $_POST["description"];
    $dt = $_POST["dt"];
    $parentid = $_POST["parentid"];
    $student = $_POST["student"];
    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("xls","pdf","zip","rar");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/announcement/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblaccoumentparent (StudentID,Description,Date,LoginID,ParentUserID,File)  
                values ({$student},'{$description}','{$dt}','{$userid}','{$parentid}','{$new_filename}')";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် parent announcement အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }else{
        $sql = "insert into tblaccoumentparent (StudentID,Description,Date,LoginID,ParentUserID)  
        values ({$student},'{$description}','{$dt}','{$userid}','{$parentid}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် parent announcement အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == "prepare"){
    $aid = $_POST["aid"];
    $description = $_POST["description"];
    $sid = $_POST["sid"];
    $sname = $_POST["sname"];
    $pid = $_POST["pid"];
    $pname = $_POST["pname"];
    $path = $_POST["path"];
    $dt = $_POST["dt"];
    $out = "";
    $out .= '
    <input type="hidden" name="action" value="edit" />
    <input type="hidden" name="eaid" value="'.$aid.'" />
    <div class="form-group ">
        <label for="usr">'.$lang['ann_studentname'].':</label>
        <select class="form-control border-primary" name="estudent" id="e_student">
            <option value="'.$sid.'">'.$sname.'</option>
            '.load_student().'
        </select>
    </div>
    <div id="e_show_parent">
        <input type="hidden" name="eparentid" value="'.$pid.'" />
        <label for="usr">'.$lang['ann_parentname'].':</label>
        <input type="text" class="form-control border-primary" name="eparent" value="'.$pname.'" readonly>
    </div>
    <div class="form-group">
        <label for="usr">'.$lang['ann_description'].':</label>
        <textarea class="form-control border-primary" name="edescription" placeholder="Enter description"
            rows="5" required>'.$description.'</textarea>
    </div>
    <div class="form-group">
        <label for="usr">'.$lang['ann_date'].' :</label>
        <input type="date" class="form-control border-primary" name="edt" placeholder="Enter date"
            required value="'.$dt.'">
    </div>
    <div class="modal-footer">
        <button type="submit" id="btneditsave" class="btn btn-'.$color.'"><i class="fas fa-edit"></i>
        '.$lang['staff_save'].'</button>
    </div>
    ';
    echo $out;
}

if($action == 'edit'){   
    $aid = $_POST["eaid"]; 
    $description = $_POST["edescription"];
    $dt = $_POST["edt"];
    $parentid = $_POST["eparentid"];
    $student = $_POST["estudent"];
    $sql = "update tblaccoumentparent set Description='{$description}',Date='{$dt}',
    ParentUserID='{$parentid}',StudentID={$student} where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် parent announcement အား update သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'delete'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblaccoumentparent where AID={$aid}";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/announcement/'.$path);
        }
        save_log($_SESSION["username"]." သည် parent announcement အားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }    
}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and (Description like '%$search%') ";
    }    

    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom != '' || $dtto != ''){
        $b = " and Date>='{$dtfrom}' and Date<='{$dtto}' ";
    }

    $sql = "select a.*,pa.UserName,s.Name as sname,s.AID as said      
    from tblaccoumentparent a,tblparent_student u,tblstudentprofile s ,tblparent pa     
    where pa.AID=u.ParentID  and a.ParentUserID=u.ParentID 
    and u.StudentID=s.AID ".$a.$b." 
    order by a.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "ParentAnnouncementReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Parent Announcements</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_studentname'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_parentaccount'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_description'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_date'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_file'].'</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$row["UserName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Description"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td> 
                    <td style="border: 1px solid ;">'.$row["File"].'</td>  
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Parent Announcements</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_studentname'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_parentaccount'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_description'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_date'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_file'].'</th>  
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="6" align="center">No record found.</td>
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
        $a = " and (Description like '%$search%') ";
    }    

    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom != '' || $dtto != ''){
        $b = " and Date>='{$dtfrom}' and Date<='{$dtto}' ";
    }

    $sql = "select a.*,pa.UserName,s.Name as sname,s.AID as said      
    from tblaccoumentparent a,tblparent_student u,tblstudentprofile s ,tblparent pa     
    where pa.AID=u.ParentID  and a.ParentUserID=u.ParentID 
    and u.StudentID=s.AID ".$a.$b." 
    order by a.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "ParentAnnouncementReport_".date('d_m_Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Parent Announcements</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_studentname'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_parentaccount'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_description'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_date'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_file'].'</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$row["UserName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Description"].'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td> 
                    <td style="border: 1px solid ;">'.$row["File"].'</td>  
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'ParentAnnouncementPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Parent Announcements</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_studentname'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_parentaccount'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_description'].'</th>
                <th style="border: 1px solid ;">'.$lang['ann_date'].'</th> 
                <th style="border: 1px solid ;">'.$lang['ann_file'].'</th>  
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="6" align="center">No record found.</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'ParentAnnouncementPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action == "change_file"){
    $aid = $_POST["faid"];
    $path = $_POST["path"];
    if($_FILES['cfile']['name'] != ''){
        $filename = $_FILES['cfile']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['cfile']['tmp_name'];
        $valid_extension = array("xls","pdf","zip","rar");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/announcement/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != ""){
                    unlink(root.'upload/announcement/'.$path);
                }
                $sql = "update tblaccoumentparent set File='{$new_filename}' 
                where AID={$aid}";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် parent announcement file အား update သွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }
}


?>