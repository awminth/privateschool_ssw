<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid=$_SESSION['userid']; 
$dt=date('Y-m-d H:i:s');

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
    
    $dtmonth=$_POST['dtmonth'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['teacherid'];
    $a=" ";
    if($teacherid!=""){
        $a=" and b.StaffID={$teacherid} ";
    }
   
    $search = $_POST['search'];
    $b = "";
    if($search != ''){  
        $b = " and (s.Name like '%$search%' or c.Name like '%$search%' or bo.Name like '%$search%') ";
    }      
    $sql="select b.*,bo.Name as boname,c.Name as cname,s.Name as sname,
    Date(b.Date) as bdate from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblbonus bo on bo.AID=b.BCID 
    left join tblcut c on c.AID=b.BCID 
    where s.Status=1 and Month(b.Date)='{$month}' 
    and Year(b.Date)='{$year}' ".$a.$b." 
    order by b.AID desc limit {$offset},{$limit_per_page}";
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["staff_staffname"].'</th>
            <th>'.$lang["staff_description"].'</th>
            <th>'.$lang["staff_status"].'</th>
            <th>'.$lang["staff_amount"].'</th>
            <th>'.$lang["staff_date"].'</th>   
            <th>Action</th>     
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $desc=$row['boname'];
            $status="get";
            if($row["Status"]==0){
                $desc=$row['cname'];
                $status='cut';
            }
           
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["sname"]}</td>   
                <td>{$desc}</td>  
                <td>{$status}</td>  
                <td>{$row["Amt"]}</td>  
                <td>{$row["bdate"]}</td>  
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                        <a href='#' id='btnedit' class='dropdown-item'
                        data-aid='{$row['AID']}' 
                        data-status='{$status}' data-toggle='modal'
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
        $out.="</table><br><br>";

        $sql_total="select b.AID 
        from tblbcsalary b 
        inner join tblstaff s on b.StaffID=s.AID 
        left join tblbonus bo on bo.AID=b.BCID 
        left join tblcut c on c.AID=b.BCID 
        where s.Status=1 and Month(b.Date)='{$month}' 
        and Year(b.Date)='{$year}' ".$a.$b." 
        order by b.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang["staff_totalrecord"].' -  ';
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
                                    <a class="pagin1 page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="pagin1 page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="pagin1 page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="pagin1 page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
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
            <th>'.$lang["staff_staffname"].'</th>
            <th>'.$lang["staff_description"].'</th>
            <th>'.$lang["staff_status"].'</th>
            <th>'.$lang["staff_amount"].'</th>
            <th>'.$lang["staff_date"].'</th>   
            <th>Action</th>     
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">No record</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'excel'){
    $dtmonth=$_POST['hid_dt'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['hid_teacher'];
    $a=" ";
    if($teacherid!=""){
        $a=" and b.StaffID={$teacherid} ";
    }
   
    $search = $_POST['ser'];
    $b = "";
    if($search != ''){  
        $b = " and (s.Name like '%$search%' or c.Name like '%$search%' or bo.Name like '%$search%') ";
    }      
    $sql="select b.*,bo.Name as boname,c.Name as cname,s.Name as sname,
    Date(b.Date) as bdate from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblbonus bo on bo.AID=b.BCID 
    left join tblcut c on c.AID=b.BCID 
    where s.Status=1 and Month(b.Date)='{$month}' 
    and Year(b.Date)='{$year}' ".$a.$b." 
    order by b.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StaffBonus/CutReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Salary Bonus / Cut</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_description"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_status"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th> 
                <th style="border: 1px solid ;">'.$lang["staff_date"].'</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $desc=$row['boname'];
            $status="get";
            if($row["Status"]==0){
                $desc=$row['cname'];
                $status='cut';
            }
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$desc.'</td>
                    <td style="border: 1px solid ;">'.$status.'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["bdate"]).'</td>                          
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
                <td colspan="6" align="center"><h3>Salary Bonus / Cut</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_description"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_status"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th> 
                <th style="border: 1px solid ;">'.$lang["staff_date"].'</th> 
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid ;" align="center">No record</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == 'pdf'){
    $dtmonth=$_POST['hid_dt'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['hid_teacher'];
    $a=" ";
    if($teacherid!=""){
        $a=" and b.StaffID={$teacherid} ";
    }
   
    $search = $_POST['ser'];
    $b = "";
    if($search != ''){  
        $b = " and (s.Name like '%$search%' or c.Name like '%$search%' or bo.Name like '%$search%') ";
    }      
    $sql="select b.*,bo.Name as boname,c.Name as cname,s.Name as sname,
    Date(b.Date) as bdate from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblbonus bo on bo.AID=b.BCID 
    left join tblcut c on c.AID=b.BCID 
    where s.Status=1 and Month(b.Date)='{$month}' 
    and Year(b.Date)='{$year}' ".$a.$b." 
    order by b.AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StaffBonus/CutReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Salary Bonus / Cut</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_description"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_status"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th> 
                <th style="border: 1px solid ;">'.$lang["staff_date"].'</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $desc=$row['boname'];
            $status="get";
            if($row["Status"]==0){
                $desc=$row['cname'];
                $status='cut';
            }
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["sname"].'</td>  
                    <td style="border: 1px solid ;">'.$desc.'</td>
                    <td style="border: 1px solid ;">'.$status.'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>  
                    <td style="border: 1px solid ;">'.enDate($row["bdate"]).'</td>                          
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StaffBonus/CutPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>Salary Bonus / Cut</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_description"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_status"].'</th>  
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th> 
                <th style="border: 1px solid ;">'.$lang["staff_date"].'</th> 
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid ;" align="center">No record</td>
            </tr>';
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StaffBonus/CutPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action == 'save'){
    $teacher = $_POST["teacher"];
    $status = $_POST["status"];
    $amt = $_POST["amt"];
    $date = $_POST["date"];
    $bonus = $_POST["bonus"];
    $cut = $_POST["cut"];
    $bcid="";

    if($status=='Bonus'){
        $bcid=$bonus;
        $status=1;
    }else{
        $bcid=$cut;
        $status=0;
    }
    
    $sql = "insert into tblbcsalary (StaffID,BCID,Status,Amt,Date,LoginID) values 
    ({$teacher},{$bcid},{$status},{$amt},'{$date}',{$userid})";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် staff Bonus အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action=='editprepare'){
    $aid = $_POST["aid"];
    $sql = "select b.*,bo.Name as boname,c.Name as cname,s.Name as sname,Date(b.Date) as bdate 
    from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblbonus bo on bo.AID=b.BCID 
    left join tblcut c on c.AID=b.BCID 
     where  b.AID=$aid";

    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $desc=$row['boname']; 
            $status='Bonus';          
            if($row["Status"]==0){
                $desc=$row['cname'];
                $status='Cut';
            }

            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='action' name='action' value='editsave' />                              
                    <div class='form-group'>
                        <label for='usr'> {$lang["staff_staffname"]} :</label>
                        <select class='form-control border-success' name='teacher1'>
                            <option value='{$row['StaffID']}'>{$row['sname']}</option>
                            ".load_staff()."
                        </select>
                    </div>  
                    <div class='form-group'>
                        <label for='usr'> {$lang["staff_status"]} :</label>
                        <select class='form-control border-success' id='status1' name='status1'>
                            <option value='{$status}'>{$status}</option>
                            ".load_status()."
                        </select>
                    </div>                    
                    <div class='form-group' id='bonus1' style='display:none'>
                        <label for='usr'> Bonus :</label>
                        <select class='form-control border-success' name='bonus1'>
                            <option value='{$row['BCID']}'>{$row['boname']}</option>
                            ".load_bonus()."
                        </select>
                    </div> 
                    <div class='form-group' id='cut1' style='display:none'>
                        <label for='usr'> Bonus :</label>
                        <select class='form-control border-success' name='cut1'>
                            <option value='{$row['BCID']}'>{$row['cname']}</option>
                            ".load_cut()."
                        </select>
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> {$lang["staff_amount"]} :</label>
                        <input type='number' class='form-control border-success' name='amt1' value='{$row['Amt']}' />
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> {$lang["staff_date"]} :</label>
                        <input type='date' class='form-control border-success' name='date1' value='{$row['bdate']}' />
                    </div>                              
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  {$lang["staff_edit"]}</button>
                </div>";
        }
        echo $out;
    }
}

if($action == 'update'){
    $aid = $_POST["aid"];
    $teacher = $_POST["teacher"];
    $status = $_POST["status"];
    $amt = $_POST["amt"];
    $date = $_POST["date"];
    $bonus = $_POST["bonus"];
    $cut = $_POST["cut"];
    $bcid="";

    if($status=='Bonus'){
        $bcid=$bonus;
        $status=1;
    }else{
        $bcid=$cut;
        $status=0;
    }
    
    $sql = "update tblbcsalary set StaffID='{$teacher}',BCID={$bcid},Status={$status},
    Amt={$amt},Date='{$date}' where AID=$aid";

   
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Staff Salary Bonus/Cut အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblbcsalary where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Staff Salary Bonus/Cut အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($action == 'show1'){
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
    }else{
        $page=1;
    }

    $offset = ($page-1) * $limit_per_page;
    
    $dtmonth=$_POST['dtmonth'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['teacherid'];
    $a=" ";
    if($teacherid!=""){
        $a=" and p.StaffID={$teacherid} ";
    }
    $search = $_POST['search'];
    $b = "";
    if($search != ''){
        $b = " and (s.Name like '%$search%') ";
    }
    $sql="select p.*,s.AID as said,s.Name as sname,s.StaffID as sid,s.Salary 
    from tblpaysalary p,tblstaff s 
    where s.AID=p.StaffID and  s.Status=1  
    and Month(p.Date)='{$month}' and Year(p.Date)='{$year}' ".$a.$b."  
    order by p.AID desc 
    limit {$offset},{$limit_per_page}";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">'.$lang["no"].'</th>
                    <th>'.$lang["staff_staffname"].'</th>
                    <th>'.$lang["staff_amount"].'</th>
                    <th>'.$lang["staff_date"].'</th>
                    <th>Action</th>
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
            <td>{$row["Amt"]}</td>
            <td>{$row["Date"]}</td>
            <td class='text-center'>
                <div class='dropdown dropleft'>
                <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                </a>
                    <div class='dropdown-menu'>
                    <a href='#' id='btneditsalary' class='dropdown-item'
                    data-aid='{$row['said']}' 
                    data-date='{$row['Date']}' 
                    data-staffid='{$row['sid']}'  
                    data-name='{$row['sname']}' 
                    data-salary='{$row['Salary']}'  
                    data-rmk='{$row['Rmk']}' data-toggle='modal'
                    data-target='#salarymodal'><i class='fas fa-print text-primary'
                    style='font-size:13px;'></i>
                    {$lang['staff_detail']}</a>
                        <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete1' class='dropdown-item'
                            data-aid='{$row['AID']}' 
                            data-vno='{$row['VNO']}'><i
                            class='fas fa-trash text-danger'
                            style='font-size:13px;'></i>
                            {$lang['staff_delete']}</a>                    
                    </div>
                </div>
            </td>  
            </tr>";
        }
    $out.="</tbody>";
    $out.="</table><br><br>";

    $sql_total="select p.AID from tblpaysalary p,tblstaff s 
    where s.AID=p.StaffID and  s.Status=1 
    and Month(p.Date)='{$month}' and Year(p.Date)='{$year}' ".$a.$b."  
    order by p.AID desc";
    $record = mysqli_query($con,$sql_total) or die("fail query");
    $total_record = mysqli_num_rows($record);
    $total_links = ceil($total_record/$limit_per_page);

    $out.='<div class="float-left">
        <p>'.$lang["staff_totalrecord"].' - ';
            $out.=$total_record;
            $out.='</p>
    </div>';

    $out.='<div class="float-right">
        <ul class="pagination">
    ';

    $previous_link = '';
    $next_link = '';
    $page_link = '';

    if($total_links > 4){
        if($page < 5){ 
            for($count=1; $count <=5; $count++) { 
                $page_array[]=$count; 
            } 
            $page_array[]='...' ;
            $page_array[]=$total_links; 
        }else{ 
            $end_limit=$total_links - 5; 
            if($page> $end_limit){
                $page_array[] = 1;
                $page_array[] = '...';
                for($count = $end_limit; $count <= $total_links; $count++) { 
                    $page_array[]=$count; 
                } 
            }else{ 
                $page_array[]=1;
                $page_array[]='...' ; 
                for($count=$page - 1; $count <=$page + 1; $count++) { 
                    $page_array[]=$count; 
                }
                $page_array[]='...' ; 
                $page_array[]=$total_links; 
            } 
        } 
    }else{ 
        for($count=1; $count <=$total_links;$count++) { 
            $page_array[]=$count; 
        } 
    } 
    for($count=0; $count < count($page_array); $count++){
        if($page==$page_array[$count]){ 
            $page_link .='<li class="page-item active">
                                <a class="pagin2 page-link" href="#">' .$page_array[$count].' <span class="sr-only">
            (current)</span></a>
            </li>';

            $previous_id = $page_array[$count] - 1;
            if($previous_id > 0){
                $previous_link = '<li class="page-item">
                <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                </li>';
            }else{
                $previous_link = '<li class="page-item disabled">
                    <a class="pagin2 page-link" href="#">Previous</a>
                </li>';
            }

            $next_id = $page_array[$count] + 1;
            if($next_id > $total_links){
                $next_link = '<li class="page-item disabled">
                    <a class="pagin2 page-link" href="#">Next</a>
                </li>';
            }else{
                $next_link = '<li class="page-item">
                    <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                </li>';
            }
        }else{
            if($page_array[$count] == '...'){
                $page_link .= '<li class="page-item disabled">
                    <a class="pagin2 page-link" href="#">...</a>
                </li> ';
            }else{
                $page_link .= '<li class="page-item">
                    <a class="pagin2 page-link" href="javascript:void(0)"
                        data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                </li> ';
            }
        }
    }

    $out .= $previous_link . $page_link . $next_link;
    $out .= '</ul>
    </div>';
    echo $out;
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">'.$lang["no"].'</th>
                    <th>'.$lang["staff_staffname"].'</th>
                    <th>'.$lang["staff_amount"].'</th>
                    <th>'.$lang["staff_date"].'</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">No record</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action == 'delete1'){

    $aid = $_POST["aid"];
    $vno=$_POST['vno'];
    $sql = "delete from tblpaysalary where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Staff Salary Pay အားဖျက်သွားသည်။");
        deletecms($vno);
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($action == 'excel1'){
    $dtmonth=$_POST['hid_dt1'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['hid_teacher1'];
    $a=" ";
    if($teacherid!=""){
        $a=" and p.StaffID={$teacherid} ";
    }
    $search = $_POST['ser'];
    $b = "";
    if($search != ''){
        $b = " and (s.Name like '%$search%') ";
    }
    $sql="select p.*,s.AID as said,s.Name as sname,s.StaffID as sid,s.Salary 
    from tblpaysalary p,tblstaff s 
    where s.AID=p.StaffID and  s.Status=1  
    and Month(p.Date)='{$month}' and Year(p.Date)='{$year}' ".$a.$b."  
    order by p.AID desc ";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StaffSalaryPayReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table>
            <tr>
                <td colspan="4" align="center">
                    <h3>Salary Pay</h3>
                </td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>';
            $no=0;
            while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
            <tr>
                <td style="border: 1px solid ;">'.$no.'</td>
                <td style="border: 1px solid ;">'.$row["sname"].'</td>
                <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>
                <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>
            </tr>';
            }
            $out .= '
        </table>';

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table>
            <tr>
                <td colspan="4" align="center">
                    <h3>Staff Salary Pay</h3>
                </td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid ;" align="center">No record</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }

}

if($action == 'pdf1'){
    $dtmonth=$_POST['hid_dt1'];
    $yrdata= strtotime($dtmonth);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata);
    
    $teacherid=$_POST['hid_teacher1'];
    $a=" ";
    if($teacherid!=""){
        $a=" and p.StaffID={$teacherid} ";
    }
    $search = $_POST['ser'];
    $b = "";
    if($search != ''){
        $b = " and (s.Name like '%$search%') ";
    }
    $sql="select p.*,s.AID as said,s.Name as sname,s.StaffID as sid,s.Salary 
    from tblpaysalary p,tblstaff s 
    where s.AID=p.StaffID and  s.Status=1  
    and Month(p.Date)='{$month}' and Year(p.Date)='{$year}' ".$a.$b."  
    order by p.AID desc ";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StaffSalaryPayReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table>
            <tr>
                <td colspan="4" align="center">
                    <h3>Salary Pay</h3>
                </td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>';
            $no=0;
            while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
            <tr>
                <td style="border: 1px solid ;">'.$no.'</td>
                <td style="border: 1px solid ;">'.$row["sname"].'</td>
                <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>
                <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>
            </tr>';
            }
            $out .= '
        </table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StaffSalaryPayPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table>
            <tr>
                <td colspan="4" align="center">
                    <h3>Staff Salary Pay</h3>
                </td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_staffname"].'</th>
                <th style="border: 1px solid ;">'.$lang["staff_amount"].'</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid ;" align="center">No record</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StaffSalaryPayPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }

}

if($action=='slipsalary'){   
    $date = $_POST["date"];
    $aid=$_POST['aid'];
    $staffid = $_POST['staffid'];
    $staffname=$_POST['name'];
    $salary=$_POST['salary'];   
    $rmk=$_POST['rmk'];   
    $out = "";
    $yrdata= strtotime($date);

    $month=date('m', $yrdata);
    $year=date('Y', $yrdata); 
    $totalget=0;
    $totalcut=0;
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $no = 1;
    $out.="
        <div id='printdata'>
            <h5 class='text-center'>
                ".$_SESSION["shopname"]."<br>
                ".$_SESSION["shopaddress"]."<br>
                Teacher Salary Pay Slip
            </h5>
            <p class='txtl fs'>
                {$lang["staff_staffid"]} : {$staffid} <br>
                {$lang["staff_staffname"]} : {$staffname} <br>
                {$lang["staff_salarymonth"]} : ".enDate2($date)."<br>
                {$lang["staff_datetime"]} : {$todaydt} / {$todaytt}
            </p>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <thead>
                    <tr>
                        <th class='text-center txtc'>{$lang["no"]}</th>      
                        <th class='txtl'>{$lang["staff_description"]}</th>  
                        <th class='text-right txtr'>{$lang["staff_amount"]}</th>  
                        <th class='text-center txtc'>{$lang["staff_date"]}</th>           
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center txtc'>{$no}</td>
                        <td class='txtl'>{$lang["staff_basicsalary"]}</td>
                        <td class='text-right txtr'>".number_format($salary)."</td>
                        <td class='text-center txtc'>Today</td>
                    </tr>    
                ";
                $sql_bonus = "select b.*,bo.Name as boname,s.Name as sname,Date(b.Date) as bdate 
                from tblbcsalary b 
                inner join tblstaff s on b.StaffID=s.AID 
                left join tblbonus bo on bo.AID=b.BCID 
                where Month(b.Date)='{$month}' and Year(b.Date)='{$year}' and
                b.Status=1 and  b.StaffID={$aid}";
                $result_bonus = mysqli_query($con,$sql_bonus) or die("SQL a Query");
                if(mysqli_num_rows($result_bonus) > 0){
                    // $no=0;
                    while($row = mysqli_fetch_array($result_bonus)){
                        $no = $no + 1;
                        $totalget+=$row['Amt'];
                        $out.="<tr>
                            <td class='text-center txtc'>{$no}</td>
                            <td class='txtl'>{$row["boname"]}</td>
                            <td class='text-right txtr'>".number_format($row["Amt"])."</td> 
                            <td class='text-center txtc'>".enDate($row["bdate"])."</td>                    
                        </tr>";
                    }        
                }
                // total bonus
                $totalget += $salary;
                $out.="
                <tr>
                    <td colspan='2' class='text-center txtc'><b>Total Get</b></td>  
                    <td class='text-right txtr'><b>".number_format($totalget)."</b></td> 
                    <td>             
                </tr>";

                $sql_cut = "select b.*,c.Name as cname,s.Name as sname,Date(b.Date) as bdate 
                from tblbcsalary b 
                inner join tblstaff s on b.StaffID=s.AID 
                left join tblcut c on c.AID=b.BCID 
                where Month(b.Date)='{$month}' and Year(b.Date)='{$year}' and
                b.Status=0  and b.StaffID={$aid}";
                $result_cut = mysqli_query($con,$sql_cut) or die("SQL a Query");
                if(mysqli_num_rows($result_cut) > 0){
                    // $no1=0;
                    while($row = mysqli_fetch_array($result_cut)){
                        $no = $no + 1;
                        $totalcut+=$row['Amt'];
                        $out.="<tr>
                            <td class='text-center txtc'>{$no}</td>
                            <td class='txtl'>{$row["cname"]}</td>
                            <td class='text-right txtr'>".number_format($row["Amt"])."</td> 
                            <td class='text-center txtc'>".enDate($row["bdate"])."</td>                    
                        </tr>";
                    }    
                }

                $remain = $totalget - $totalcut;
                // total cut
                $out.="
                <tr>
                    <td colspan='2' class='text-center txtc'><b>Total Cut</b></td>  
                    <td class='text-right txtr'><b>".number_format($totalcut)."</b></td> 
                    <td>             
                </tr>";
                // total remain
                $out.="
                <tr>
                    <td colspan='2' class='text-center txtc'><b>Total Remain</b></td>  
                    <td class='text-right txtr'><b>".number_format($remain)."</b></td> 
                    <td>             
                </tr>
                <tr>
                    <td colspan='2' class='text-center txtc'><b>Remark</b></td>  
                    <td class='text-right'>".$rmk."</td> 
                    <td>             
                </tr>
                ";
    $out .= "
            </tbody>
        </table>
    </div>
    <br>
    <button class='btn btn-{$color}' id='btnprint' >Print</button>
    ";
    echo $out; 
}





?>