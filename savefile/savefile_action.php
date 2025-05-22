<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];

if($action == 'show'){  

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
    if($search == ''){        
        $sql="select * from tblsavefile 
        order by AID desc limit {$offset},{$limit_per_page}";
    }else{
        $sql="select * from tblsavefile 
        where Description like '%$search%' 
        order by AID desc limit {$offset},{$limit_per_page}";        
    }
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Description</th>
            <th>File</th>
            <th>Date</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $url = roothtml.'upload/savefile/'.$row["File"];
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Description"]}</td>
                <td>{$row["File"]}</td>
                <td>{$row["Date"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                    <div class='dropdown-menu'>
                        <a href='#' id='btnedit' class='dropdown-item'
                        data-aid='{$row['AID']}' data-toggle='modal'
                        data-target='#editmodal'><i class='fas fa-edit text-primary'
                        style='font-size:13px;'></i>
                        {$lang['staff_edit']}</a>
                    <div class='dropdown-divider'></div>
                        <a href='#' id='btnupdatefile' class='dropdown-item'
                        data-aid='{$row['AID']}' data-toggle='modal'
                        ><i class='fas fa-file-alt text-primary'
                        style='font-size:13px;'></i>
                        Update File</a>
                    <div class='dropdown-divider'></div>
                        <a href='{$url}' class='dropdown-item'
                        ><i class='fas fa-file-download text-primary'
                        style='font-size:13px;'></i>
                        Download</a>
                    <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                            data-aid='{$row['AID']}'
                            data-path='{$row['File']}'><i
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

        $sql_total="";
        if($search == ''){        
            $sql_total="select * from tblsavefile 
            order by AID desc";
        }else{
            $sql_total="select * from tblsavefile 
            where Description like '%$search%'
            order by AID desc";        
        }
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
            <th>Description</th>
            <th>File</th>
            <th>Date</th>
            <th width="10%;">Action</th>            
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

if($action == 'save'){
    $description = $_POST["description"];
    $dt = $_POST["dt"];
    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("pdf","PDF");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/savefile/". $new_filename;
            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblsavefile (Description,File,Date)  
                values ('{$description}','{$new_filename}','{$dt}')";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် File အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }
    else{
        $sql = "insert into tblsavefile (Description,Date)  
        values ('{$description}','{$dt}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် File အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}


if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select * from tblsavefile where AID='{$aid}'";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='action' name='action' value='editsave' /> 
                    <div class='form-group'>
                        <label for='usr'> Description :</label>
                        <input type='text' class='form-control border-success' name='description1' value='{$row['Description']}'>
                    </div>                            
                    <div class='form-group'>
                        <label for='usr'> Date :</label>
                        <input type='date' class='form-control border-success' name='dt1' value='{$row['Date']}'>
                    </div>                               
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  {$lang['staff_edit']}</button>
                </div>";
        }
        echo $out;
    }
}


if($action == 'update'){
    $aid = $_POST["aid"];
    $description = $_POST["description"];
    $dt = $_POST["dt"];
    
    $sql = "update tblsavefile set Description='{$description}',
    Date='{$dt}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် File အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'editpreparefile'){
    $aid = $_POST["aid"];
    $sql = "select File,AID from tblsavefile where AID='{$aid}'";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='action' name='action' value='editfilesave' />
                <input type='hidden' id='path' name='path' value='{$row['File']}'/> 
                    <div class='form-group'>
                        <label for='usr'> Old File :</label>
                        <input type='text' class='form-control border-success' name='description1' value='{$row['File']}'>
                    </div>  
                    <div class='form-group'>
                        <label for='usr'>Upload File:</label>
                        <input type='file' name='file1' id='qfile1'
                            class='form-control border-success'
                            accept='.pdf,.PDF'>
                    </div>                             
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdatefilesave' class='btn btn-{$color}'><i class='fas fa-edit'></i>  {$lang['staff_edit']}</button>
                </div>";
        }
    }
    echo $out;
}

if($action == 'editfilesave'){   
    $aid = $_POST["aid"];
    $path = $_POST["path"];

    if($_FILES['file1']['name'] != ''){
        $filename = $_FILES['file1']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file1']['tmp_name'];
        $valid_extension = array("pdf","PDF");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/savefile/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != "" || $path != NULL){
                    unlink(root."upload/savefile/".$path);
                    $sql = "update tblsavefile set File='{$new_filename}' where AID={$aid}";
                    if(mysqli_query($con,$sql)){
                        save_log($_SESSION["username"]." သည် File အား update လုပ်သွားသည်။");
                        echo 1;
                    }
                    else{
                        echo 0;
                    }
                }
            }
        }
    }
}


if($action == 'delete'){

    $aid = $_POST["aid"];
    $path = $_POST["path"];
    $sql = "delete from tblsavefile where AID=$aid";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/savefile/'.$path);
        }
        save_log($_SESSION["username"]." သည် File အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}


if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    if($search == ''){        
        $sql="select s.*,g.Name as gname from tblsubject s,tblgrade g where 
        s.GradeID=g.AID ";
    }else{
        $sql="select s.*,g.Name as gname from tblsubject s,tblgrade g where 
        s.GradeID=g.AID and (s.Name like '%$search%' or g.Name like '%$search%')";        
    }

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SubjectReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="3" align="center"><h3>Subject</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['nc_name'].'</th>    
                <th style="border: 1px solid ;">'.$lang['gradename'].'</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>   
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>                
                </tr>';
        }
        $out .= '</table>';

            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename='.$fileName);
            echo $out;
    }else{
        echo "No Record Found.";
    }   
    
}


if($_POST["action"] == 'pdf'){
    $search = $_POST['ser'];
    if($search == ''){        
        $sql="select s.*,g.Name as gname from tblsubject s,tblgrade g where 
        s.GradeID=g.AID ";
    }else{
        $sql="select s.*,g.Name as gname from tblsubject s,tblgrade g where 
        s.GradeID=g.AID and (s.Name like '%$search%' or g.Name like '%$search%')";        
    }

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SubjectReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="3" align="center"><h3>Subject</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['nc_name'].'</th>    
                <th style="border: 1px solid ;">'.$lang['gradename'].'</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>   
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>                
                </tr>';
        }
        $out .= '</table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'SubjectPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        echo "No Record Found.";
    }   
    
}


?>