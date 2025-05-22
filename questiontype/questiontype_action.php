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
        $sql="select q.*,g.Name as gname from tblquestion q,
        tblgrade g where q.GradeID=g.AID 
        order by q.AID desc limit {$offset},{$limit_per_page}";
    }else{
        $sql="select q.*,g.Name as gname from tblquestion q,
        tblgrade g where q.GradeID=g.AID 
        (where g.Name like '%$search%' or q.Description like '%$search%') 
        order by q.AID desc limit {$offset},{$limit_per_page}";        
    }
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Grade Name</th>
            <th>Description</th>
            <th>PDF</th>
            <th>Date</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $url = roothtml.'upload/question/'.$row["PDF"];
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["gname"]}</td>  
                <td>{$row["Description"]}</td>
                <td>{$row["PDF"]}</td>
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
                            data-path='{$row['PDF']}'><i
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
            $sql_total="select q.*,g.Name as gname from tblquestion q,
            tblgrade g where q.GradeID=g.AID 
            order by q.AID desc";
        }else{
            $sql_total="select q.*,g.Name as gname from tblquestion q,
            tblgrade g where q.GradeID=g.AID 
            (where g.Name like '%$search%' or q.Description like '%$search%') 
            order by q.AID desc";        
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
            <th>Grade Name</th>
            <th>Description</th>
            <th>PDF</th>
            <th>Date</th>
            <th width="10%;">Action</th>            
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'save'){
    $gname = $_POST["gname"];
    $description = $_POST["description"];
    $dt = $_POST["dt"];
    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("pdf","PDF");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/question/". $new_filename;
            if(move_uploaded_file($file,$new_path)){
                $sql = "insert into tblquestion (GradeID,Description,PDF,Date)  
                values ('{$gname}','{$description}','{$new_filename}','{$dt}')";
                if(mysqli_query($con,$sql)){
                    save_log($_SESSION["username"]." သည် Question Type အားအသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }
    }
    else{
        $sql = "insert into tblquestion (GradeID,Description,Date)  
        values ('{$gname}','{$description}','{$dt}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် Question Type အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select q.*,g.Name as gname from tblquestion q,
    tblgrade g where q.GradeID=g.AID and q.AID='{$aid}'";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='action' name='action' value='editsave' />    
                    <div class='form-group'>
                        <label for='usr'> Grade Name :</label>
                        <select class='form-control border-success select2' name='gname1'>
                            <option value='{$row['GradeID']}' selected>{$row['gname']}</option>
                            ".load_grade()."
                        </select>
                    </div> 
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
    $gname = $_POST["gname"];
    $description = $_POST["description"];
    $dt = $_POST["dt"];
    
    $sql = "update tblquestion set GradeID={$gname},Description='{$description}',
    Date='{$dt}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Question Type အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'editpreparefile'){
    $aid = $_POST["aid"];
    $sql = "select PDF,AID from tblquestion where AID='{$aid}'";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='action' name='action' value='editfilesave' />
                <input type='hidden' id='path' name='path' value='{$row['PDF']}'/> 
                    <div class='form-group'>
                        <label for='usr'> Old File :</label>
                        <input type='text' class='form-control border-success' name='description1' value='{$row['PDF']}'>
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
            $new_path = root."upload/question/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                if($path != "" || $path != NULL){
                    unlink(root."upload/question/".$path);
                    $sql = "update tblquestion set PDF='{$new_filename} where AID={$aid}";
                    if(mysqli_query($con,$sql)){
                        save_log($_SESSION["username"]." သည် Question Type အား update လုပ်သွားသည်။");
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
    $sql = "delete from tblquestion where AID=$aid";
    if(mysqli_query($con,$sql)){
        if($path != ""){
            unlink(root.'upload/question/'.$path);
        }
        save_log($_SESSION["username"]." သည် Question Type အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    if($search == ''){        
        $sql="select q.*,g.Name as gname from tblquestion q,
        tblgrade g where q.GradeID=g.AID 
        order by q.AID desc";
    }else{
        $sql="select q.*,g.Name as gname from tblquestion q,
        tblgrade g where q.GradeID=g.AID 
        where g.Name like '%$search%' or q.Description like '%$search%' 
        order by q.AID desc";        
    }

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "QuestionTypes-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="4" align="center"><h3>Question Type Reports</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Grade Name</th>    
                <th style="border: 1px solid ;">Description</th>       
                <th style="border: 1px solid ;">Date</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>   
                    <td style="border: 1px solid ;">'.$row["Description"].'</td>                
                    <td style="border: 1px solid ;">'.$row["Date"].'</td>                
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


?>