<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

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
        $a = " and Name like '%$search%' ";
    }   
    $sql="select *,(select count(AID) from tblsubject where 
    GradeID=g.AID) as count
    from tblgrade g where AID is not null ".$a."  order by AID desc 
    limit {$offset},{$limit_per_page}";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["eargrade_title"].'</th>
            <th>Subject Count</th>
            <th width="20%;" class="text-center">Go Subject</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>";
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
                <td>{$row["count"]}</td>   
                <td class='text-center'>               
                       
                    <a href='#' id='btngograde' class='dropdown-item'
                    data-aid='{$row['AID']}' 
                    data-name='{$row['Name']}'
                    data-count='{$row['count']}' ><i class='fas fa-edit text-primary'
                    style='font-size:13px;'></i>
                    Go Subject</a>
                           
                </td> 
                
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";

        $sql_total="
        select *,(select count(AID) from tblsubject where 
        GradeID=g.AID) as count
        from tblgrade g where AID is not null ".$a."  order by AID desc";
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
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["eargrade_title"].'</th>
            <th>Subject Count</th>
            <th width="20%;" class="text-center">Add Contents</th>           
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

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){     
        $a = " and Name like '%$search%' ";
    }   
    $sql="select *,(select count(AID) from tblsubject where 
    GradeID=g.AID) as count
    from tblgrade g where AID is not null ".$a."  order by AID desc";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "EARGradeReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="3" align="center"><h3>'.$lang["eargrade_list"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["eargrade_title"].'</th> 
                <th style="border: 1px solid ;">Add Contents</th>       
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
                    <td style="border: 1px solid ;">'.$row["count"].'</td>               
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
                <td colspan="3" align="center"><h3>'.$lang["eargrade_list"].'</h3></td>
            </tr>
            <tr><td colspan="3"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["eargrade_title"].'</th> 
                <th style="border: 1px solid ;">'.$lang["eargrade_stucount"].'</th>       
            </tr>
            <tr>
                <td colspan="3" align="center" style="border: 1px solid ;">No data</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == 'gograde'){
    $gradeid = $_POST['aid'];
    $gradename = $_POST['name'];
    $count = $_POST['count'];
    $_SESSION['gradeid'] = $gradeid;
    $_SESSION['gradename'] = $gradename;
    $_SESSION['subjectcount'] = $count;
    echo 1;
}



?>