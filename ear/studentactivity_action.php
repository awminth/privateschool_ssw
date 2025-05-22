<?php
include('../config.php');

$action = $_POST["action"];
$userid=$_SESSION['userid'];
$gradeid= $_SESSION['gradeid'];
$gradename= $_SESSION['gradename'];
$yearid= $_SESSION['yearid'];
$yearname= $_SESSION['yearname'];
$dt=date('Y-m-d');
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
        $a = " and s.Name like '%$search%' ";
    }        
    $sql="select e.*,s.Name from tblearstudent e,tblstudentprofile s where 
    s.AID=e.StudentID and  e.EARYearID={$yearid} and 
    e.GradeID={$gradeid} ".$a." 
    order by e.AID desc limit {$offset},{$limit_per_page}";  
  
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['activity_studentname'].'</th>
            <th width="20%;" class="text-center">View</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td>
                <td id='btnaddstudent' 
                data-aid='{$row['AID']}' 
                data-name='{$row['Name']}' ><a href='#'>{$row["Name"]}</a></td> 
               
                <td class='text-center'>  
                    <a href='#' id='btnview' class='dropdown-item'
                    data-aid='{$row['AID']}' 
                    data-name='{$row['Name']}' ><i class='fas fa-list text-primary'
                    style='font-size:13px;'></i>
                View</a>
                           
                </td> 
                
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select e.*,s.Name from tblearstudent e,tblstudentprofile s where 
        s.AID=e.StudentID and  e.EARYearID={$yearid} and 
        e.GradeID={$gradeid} ".$a." 
        order by e.AID desc";
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
        echo"<h3>No Record Found</h3>";
    }

}

if($action=='save'){
    $stuid = $_POST['stuid'];
    $desc = $_POST['desc'];
    $dt = $_POST['dt'];

    $sql="insert into tblstudentactivity (LoginID,EARStudentID,Description,Date) 
    values ({$userid},'{$stuid}','{$desc}','{$dt}')";  
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Student Activity အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
    
    
}



?>