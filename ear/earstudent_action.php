<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];
$yearid= $_SESSION['yearid'];
$yearname= $_SESSION['yearname'];
$gradeid= $_SESSION['gradeid'];
$gradename= $_SESSION['gradename'];


if($action == 'show'){  
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=30; 
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
        $a = " and (s.Name like '%$search%') ";
    }     
    $sql="select e.*,s.Name,g.Name as gname 
    from tblearstudent e,tblstudentprofile s,tblgrade g 
    where e.StudentID=s.AID and e.GradeID=g.AID and e.EARYearID={$yearid} and e.GradeID={$gradeid} 
     ".$a." 
     order by e.AID desc limit {$offset},{$limit_per_page}";     
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["earstu_stuname"].'</th>
            <th>'.$lang["earstu_gradename"].'</th>  
            <th class="text-center">'.$lang["earstu_assessment"].'</th> 
            <th class="text-center">Action</th>              
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="
            <tr>";
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
                <td>{$row["gname"]}</td>
                <td class='text-center'>                   
                    <a href='#' id='btnmonthly' class='dropdown-item'
                        data-earid='{$row['AID']}' 
                        data-sid='{$row['StudentID']}' 
                        data-sname='{$row['Name']}' >
                        <i class='fas fa-edit text-".$color."'
                        style='font-size:13px;'></i>
                        ".$lang["earstu_put"]."</a>
                </td> 
                <td class='text-center'>                   
                    <a href='#' id='btnreport' class='dropdown-item'
                        data-earid='{$row['AID']}' 
                        data-sid='{$row['StudentID']}' 
                        data-sname='{$row['Name']}' >
                        <i class='fas fa-eye text-".$color."'
                        style='font-size:13px;'></i>
                        ".$lang["earstu_rpt"]."</a>
                </td>             
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="select e.AID from tblearstudent e,tblstudentprofile s,tblgrade g 
        where e.StudentID=s.AID and e.GradeID=g.AID and e.EARYearID={$yearid} and e.GradeID={$gradeid} 
         ".$a." 
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
        
    }
    else{
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["earstu_stuname"].'</th>
            <th>'.$lang["earstu_gradename"].'</th>  
            <th class="text-center">'.$lang["earstu_assessment"].'</th>  
            <th class="text-center">Action</th>              
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

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){   
        $a = " and (s.Name like '%$search%') ";
    }     
    $sql="select e.*,s.Name,g.Name as gname 
    from tblearstudent e,tblstudentprofile s,tblgrade g 
    where e.StudentID=s.AID and e.GradeID=g.AID and e.EARYearID={$yearid} and e.GradeID={$gradeid} 
     ".$a." 
     order by e.AID desc"; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "EARStudentReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="3" align="center"><h3>'.$lang["earstu_stulist"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>   
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["earstu_stuname"].'</th> 
                <th style="border: 1px solid ;">'.$lang["earstu_gradename"].'</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>';
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.='<td style="border: 1px solid ;">'.toMyanmar($no).'</td> ';
                    }else{
                        $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                    }
                }else{
                    $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                }  
                $out.='  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td> 
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>                
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
                <td colspan="3" align="center"><h3>'.$lang["earstu_stulist"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>   
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["earstu_stuname"].'</th> 
                <th style="border: 1px solid ;">'.$lang["earstu_gradename"].'</th>       
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid ;">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == 'pdf'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){   
        $a = " and (s.Name like '%$search%') ";
    }     
    $sql="select e.*,s.Name,g.Name as gname 
    from tblearstudent e,tblstudentprofile s,tblgrade g 
    where e.StudentID=s.AID and e.GradeID=g.AID and e.EARYearID={$yearid} and e.GradeID={$gradeid} 
     ".$a." 
     order by e.AID desc"; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "EARStudentReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="3" align="center"><h3>'.$lang["earstu_stulist"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>   
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["earstu_stuname"].'</th> 
                <th style="border: 1px solid ;">'.$lang["earstu_gradename"].'</th>       
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>';
                if(isset($_SESSION['la'])){
                    if($_SESSION['la']=='my'){
                        $out.='<td style="border: 1px solid ;">'.toMyanmar($no).'</td> ';
                    }else{
                        $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                    }
                }else{
                    $out.='<td style="border: 1px solid ;">'.$no.'</td> ';
                }  
                $out.='  
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
        $file = 'EARStudentPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="3" align="center"><h3>'.$lang["earstu_stulist"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>   
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["earstu_stuname"].'</th> 
                <th style="border: 1px solid ;">'.$lang["earstu_gradename"].'</th>       
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid ;">No data</td>
            </tr>';
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'EARStudentPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action=='gograde'){
    $gradeid=$_POST['aid'];
    $_SESSION['gradeid']=$gradeid;
    echo 1;
}

if($action == "go_detail"){
    $earid = $_POST["earid"];
    $sid = $_POST["sid"];
    $sname = $_POST["sname"];
    $_SESSION['earid'] = $earid;
    $_SESSION['sid'] = $sid;
    $_SESSION['sname'] = $sname;
    echo 1;
}


?>