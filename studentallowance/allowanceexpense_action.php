<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid=$_SESSION['userid'];
$incomeyearid=$_SESSION['incomeyearid'];

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
    $a = "";
    if($search != ''){     
        $a = " and (s.Name like '%$search%' or a.Amount like '%$search%') ";
    } 
    $sql="select a.*,s.Name as sname 
        from tblallowanceexpense a,tblstudentprofile s  
        where a.StudentID=s.AID and a.AllowanceYearID={$incomeyearid} ".$a."
        order by a.AID desc limit {$offset},{$limit_per_page}";
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>Student Name</th>
            <th>Amount</th>
            <th>Remark</th>
            <th>Date</th>
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
                <td>{$row["sname"]}</td>
                <td>{$row["Amount"]}</td>
                <td>{$row["Rmk"]}</td>
                <td>{$row["Date"]}</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                        <a href='#' id='btnedit' class='dropdown-item'
                        data-aid='{$row['AID']}'
                        data-stuid='{$row['StudentID']}'
                        data-sname='{$row['sname']}'
                        data-amount='{$row['Amount']}'
                        data-rmk='{$row['Rmk']}' data-toggle='modal'
                        data-target='#editmodal'><i class='fas fa-edit text-primary'
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

        $sql_total="";
        $sql_total="select a.*,s.Name as sname 
            from tblallowanceexpense a,tblstudentprofile s  
            where a.StudentID=s.AID and a.AllowanceYearID={$incomeyearid} ".$a." 
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
            <th>Student Name</th>
            <th>Amount</th>
            <th>Remark</th>
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
    $stuid = $_POST["stuid"];
    $rmk = $_POST["rmk"];
    $amount = $_POST["amount"];
    $dt = $_POST["date"];
    $totalAmount = 0;
    $chk_amount = GetInt("select sum(Amount) from tblallowanceexpense where StudentID='{$stuid}'");
    $totalAmount = $chk_amount + $amount;
    $allowanceincomeAmount = GetInt("select sum(Amount) from tblallowanceincome where StudentID='{$stuid}'");
    if($totalAmount <= $allowanceincomeAmount){
        $sql = "insert into tblallowanceexpense (StudentID,Amount,Rmk,Date,AllowanceYearID) 
        values ('{$stuid}',{$amount},'{$rmk}','{$dt}',{$incomeyearid})";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် Student Allowance Expense အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
    else{
        echo 2;
    }
    
}

if($action == 'update'){
    $aid = $_POST["aid"];
    $stuid = $_POST["stuid"];
    $rmk = $_POST["rmk"];
    $amount = $_POST["amount"];
    $dt = date("Y-m-d");
    $sql = "update tblallowanceexpense set StudentID={$stuid},Rmk='{$rmk}',
    Amount={$amount},Date='{$dt}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Student Allowance Expense အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblallowanceexpense where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Student Allowance Expense အားဖျက်သွားသည်။");
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