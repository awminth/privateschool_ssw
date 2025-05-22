<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];
$gradeidsession=$_SESSION['gradeid'];
$subjectidsession=$_SESSION['subjectid'];


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
        $sql="select t.*,s.Name as sname from 
        tblteachingcategory t,tblsubject s where 
        t.SubjectID=s.AID group by t.SubjectID limit {$offset},{$limit_per_page}";
    }else{
        $sql="select t.*,s.Name as sname from 
        tblteachingcategory t,tblsubject s where 
        t.SubjectID=s.AID and t.Description like '%$search%' or t.Type like '%$search%'
        group by t.SubjectID limit {$offset},{$limit_per_page}";        
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
            <th>Type</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Description"]}</td>
                <td>{$row["Type"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                    <div class='dropdown-menu'>
                        <a href='#' id='btnedit' class='dropdown-item'
                        data-aid='{$row['AID']}'>
                        <i class='fas fa-edit text-primary'
                        style='font-size:13px;'></i>
                        {$lang['staff_edit']}</a>
                        <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                            data-aid='{$row['AID']}'><i
                            class='fas fa-trash text-danger'
                            style='font-size:13px;'></i>
                            {$lang['staff_delete']}</a> 
                        <div class='dropdown-divider'></div>
                        <a href='#' id='btnsaverecord' class='dropdown-item'
                            data-aid='{$row['AID']}'><i
                            class='fas fa-trash text-success'
                            style='font-size:13px;'></i>
                            Save Contents</a>                 
                        </div>
                    </div>
                </td>                 
              
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="";
        if($search == ''){        
            $sql_total="select t.*,s.Name as sname from 
            tblteachingcategory t,tblsubject s where 
            t.SubjectID=s.AID group by t.SubjectID";
        }else{
            $sql_total="select t.*,s.Name as sname from 
            tblteachingcategory t,tblsubject s where 
            t.SubjectID=s.AID and t.Description like '%$search%' or t.Type like '%$search%'
            group by t.SubjectID";        
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
            <th>Subject Name</th>
            <th>Description</th>
            <th>Type</th>
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
    $type = $_POST["type"];
    $subjectid = $_POST["subjectid"];
    $sql = "insert into tblteachingcategory (Description,Type,GradeID,SubjectID) 
    values ('{$description}','{$type}','{$gradeidsession}','{$subjectidsession}')";
    if(mysqli_query($con,$sql)){
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select t.*,s.Name as sname 
    from tblteachingcategory t,tblsubject s 
    where t.SubjectID=s.AID and t.AID='{$aid}'";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                    <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>
                    <div class='form-group' style='display:none;'>
                        <label for='usr'> Subject Name :</label>
                        <select class='form-control border-success' name='subjectidone'>
                            <option value='{$row['SubjectID']}'>".$row['sname']."</option>
                            ".load_subjectgrade($gradeidsession)."
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Description :</label>
                        <input type='text' class='form-control border-success' name='descriptionone' value=".$row["Description"].">
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Type :</label>
                        <input type='text' class='form-control border-success' name='typeone' value=".$row["Type"].">
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
    $type = $_POST["type"];
    $subjectid = $_POST["subjectid"];
    $sql = "update tblteachingcategory set Description='{$description}',
    Type='{$type}',SubjectID='{$subjectidsession}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Setup အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblteachingcategory where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Setup အားဖျက်သွားသည်။");
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


?>