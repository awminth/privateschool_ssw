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
        $sql="select l.*,g.Name as gname,f.Name as fname,s.Status as status,
        s.Count as count,s.Date as sdate from tbllearningtitle l,
        tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
        s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0 
        order by l.AID desc limit {$offset},{$limit_per_page}";
    }else{
        $sql="select l.*,g.Name as gname,f.Name as fname,s.Status as status,
        s.Count as count,s.Date as sdate from tbllearningtitle l,
        tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
        s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0 and 
        l.Description like '%$search%' or l.Type like '%$search%' 
        order by l.AID desc limit {$offset},{$limit_per_page}";        
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
            <th>Grade Name</th>
            <th>Teacher Name</th>
            <th class="text-center">Count</th>
            <th>Status</th>
            <th>Date</th>       
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $color="";
            if($row["status"]==1){
                $color = "bg-success";
            }
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Description"]}</td>  
                <td>{$row["Type"]}</td>
                <td>{$row["gname"]}</td>
                <td>{$row["fname"]}</td>
                <td class='text-center'>{$row["count"]}</td>
                <td class='t1 ".$color."'></td>
                <td>{$row["sdate"]}</td>                               
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="";
        if($search == ''){        
            $sql_total="select l.*,g.Name as gname,f.Name as fname from tbllearningtitle l,
            tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
            s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0 order by l.AID desc";
        }else{
            $sql_total="select l.*,g.Name as gname,f.Name as fname from tbllearningtitle l,
            tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
            s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0  
            and l.Description like '%$search%' or l.Type like '%$search%' 
            order by l.AID desc";        
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
            <th>Type</th>  
            <th>Grade Name</th>  
            <th>Teacher Name</th>  
            <th>Count</th>  
            <th>Status</th>  
            <th>Date</th>           
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    if($search == ''){        
        $sql="select l.*,g.Name as gname,f.Name as fname,s.Status as status,
        s.Count as count,s.Date as sdate from tbllearningtitle l,
        tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
        s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0 
        order by l.AID desc";
    }else{
        $sql="select l.*,g.Name as gname,f.Name as fname,s.Status as status,
        s.Count as count,s.Date as sdate from tbllearningtitle l,
        tblsavequestion s,tblgrade g,tblstaff f where s.LearningTitleID=l.AID and 
        s.GradeID=g.AID and s.TeacherID=f.AID and f.Status=0 and 
        l.Description like '%$search%' or l.Type like '%$search%' 
        order by l.AID desc";        
    }

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "Report Save Titles-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Report Save Titles</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">Description</th>    
                <th style="border: 1px solid ;">Type</th>       
                <th style="border: 1px solid ;">Grade Name</th>       
                <th style="border: 1px solid ;">Teacher Name</th>       
                <th style="border: 1px solid ;">Count</th>       
                <th style="border: 1px solid ;">Date</th>       
                </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Description"].'</td>   
                    <td style="border: 1px solid ;">'.$row["Type"].'</td>                
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>                
                    <td style="border: 1px solid ;">'.$row["fname"].'</td>                
                    <td style="border: 1px solid ;">'.$row["count"].'</td>                
                    <td style="border: 1px solid ;">'.$row["sdate"].'</td>                
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