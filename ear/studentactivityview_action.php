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
    $aid = $_POST['aid'];
    $a = "";
    if($search != ''){ 
        $a = " and (e.Description like '%$search%') ";
    }       
    $sql="select e.*   
    from tblstudentactivity e 
    where e.EARStudentID={$aid} ".$a."   
    order by AID desc limit {$offset},{$limit_per_page}";
        
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['activity_description'].'</th>
            <th>'.$lang['nc_date'].'</th>
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
                <td>".enDate($row["Date"])."</td>   
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>                       
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
                                class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                            {$lang['staff_edit']}</a>  
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
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

        $sql_total="select e.AID    
        from tblstudentactivity e 
        where e.EARStudentID={$aid} ".$a."   
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
            <th>'.$lang['activity_description'].'</th>
            <th>'.$lang['nc_date'].'</th>
            <th width="10%;">Action</th>           
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

if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select e.*,p.Name as pname   
    from tblstudentactivity e,tblearstudent d,tblstudentprofile p 
    where e.EARStudentID=d.AID and d.StudentID=p.AID  
    and e.AID={$aid}";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $out .= '<div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="eaid" value="'.$row["AID"].'"/>
                        <label for="usr"> '.$lang['activity_studentname'].' :</label>
                        <input type="text" class="form-control border-success" id="stuname"
                            name="estuname" value="'.$row["pname"].'">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="usr"> '.$lang['activity_description'].' :</label>
                        <textarea class="form-control border-success"
                        name="edesc">'.$row["Description"].'</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="usr"> '.$lang['nc_date'].' :</label>
                        <input type="date" class="form-control border-success"
                        value="'.$row["Date"].'" name="edt">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btnupdate" class="btn btn-'.$color.'"><i class="fas fa-edit"></i> '.$lang['staff_edit'].'<button>
        </div>
        ';
        echo $out;
    }
}

if($action == "edit"){
    $aid = $_POST['aid'];
    $desc = $_POST['desc'];
    $dt = $_POST['dt'];
    $sql="update tblstudentactivity set Description='{$desc}',Date='{$dt}' 
    where AID={$aid}";  
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် EAR Student Activity အား update သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'delete'){
    $aid = $_POST["aid"];
    $sql = "delete from tblstudentactivity where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် EAR Student Activity အားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }    
}


if($action == 'excel')
{
    $search = $_POST['ser'];
    $aid = $_POST['aid'];
    $a = "";
    if($search != ''){ 
        $a = " and (e.Description like '%$search%') ";
    }       
    $sql="select e.*,p.Name as pname   
    from tblstudentactivity e,tblearstudent d,tblstudentprofile p 
    where  e.EARStudentID={$aid} and e.EARStudentID=d.AID and d.StudentID=p.AID ".$a."   
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "EARStudentActivityReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="4" align="center"><h3>EAR Student Activity</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_description'].'</th>    
                <th style="border: 1px solid ;">'.$lang['nc_date'].'</th>      
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["pname"].'</td>     
                    <td style="border: 1px solid ;">'.$row["Description"].'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>           
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
                    <td colspan="4" align="center"><h3>EAR Student Activity</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_description'].'</th>   
                <th style="border: 1px solid ;">'.$lang['nc_date'].'</th>      
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
            $out .= '</table>';

            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename='.$fileName);
            echo $out;

    }     
    
}


if($action == 'pdf')
{
    $search = $_POST['ser'];
    $aid = $_POST['aid'];
    $a = "";
    if($search != ''){ 
        $a = " and (e.Description like '%$search%') ";
    }       
    $sql="select e.*,p.Name as pname   
    from tblstudentactivity e,tblearstudent d,tblstudentprofile p 
    where  e.EARStudentID={$aid} and e.EARStudentID=d.AID and d.StudentID=p.AID ".$a."   
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "EARStudentActivityReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="4" align="center"><h3>EAR Student Activity</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_description'].'</th>    
                <th style="border: 1px solid ;">'.$lang['nc_date'].'</th>      
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["pname"].'</td>     
                    <td style="border: 1px solid ;">'.$row["Description"].'</td> 
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>           
                </tr>';
        }
        $out .= '</table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'EARStudentActivityPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');

    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="4" align="center"><h3>EAR Student Activity</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['activity_description'].'</th>   
                <th style="border: 1px solid ;">'.$lang['nc_date'].'</th>      
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
            $out .= '</table>';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont   = true;  
            $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
            $mpdf->WriteHTML($stylesheet,1);  
            $mpdf->WriteHTML($out,2);
            $file = 'EARStudentActivityPDF'.date("d_m_Y").'.pdf';
            $mpdf->output($file,'D');

    }     
    
}


?>