<?php
include('../config.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];
$dt=date('Y-m-d');

if($action == 'showteacher'){ 
    $dtmonth = $_POST['dtmonth'];
    $yrdata = strtotime($dtmonth);

    $month = date('m', $yrdata);
    $year = date('Y', $yrdata); 

    $sql = "select * from tblstaff  
    where Status=0 and 
    AID not in (select StaffID from tblpaysalary where Month(Date)='{$month}' and Year(Date)='{$year}') 
    order by AID desc";
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped table-responsive">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["tea_teacherid"].'</th>
            <th>'.$lang["tea_name"].'</th>            
            <th width="8%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["StaffID"]}</td>
                <td>{$row["Name"]}</td> 
                <td class='text-center'>
                    <a href='#' id='btnedit' class='dropdown-item'
                    data-aid='{$row['AID']}'  data-name='{$row['Name']}' 
                    data-salary='{$row['Salary']}' data-staffid='{$row['StaffID']}'>
                    <i class='fas fa-edit text-primary' style='font-size:13px;'></i>
                    Show</a>
                </td>     
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";  
    }else{
        $out.='
        <table class="table table-bordered table-striped table-responsive">
            <thead>
            <tr>
                <th width="7%;">'.$lang["no"].'</th>
                <th>'.$lang["tea_teacherid"].'</th>
                <th>'.$lang["tea_name"].'</th>               
                <th width="8%;">Action</th>           
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">No record</td>
                </tr>
            </tbody>
        </table>
        ';
    }

    echo $out;


}

if($action == 'editprepare'){ 
    $aid = $_POST['aid'];
    $staffid = $_POST['staffid'];
    $staffname = $_POST['name'];
    $salary = $_POST['salary'];   

    $dtmonth = $_POST['dtmonth'];
    $yrdata = strtotime($dtmonth);

    $month = date('m', $yrdata);
    $year = date('Y', $yrdata); 
    
    $out = "";

    $totalget=0;
    $totalcut=0;

    $out.="
    <h3 class='text-center'>{$lang["tea_teachername"]} : {$staffname}</h3>
    <br>        
    <table class='table table-bordered table-striped responsive' width='100%'>
        <thead>
        <tr>
            <td colspan='4'>{$lang["tea_basicsalary"]}</td>               
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>{$lang["tea_basicsalary"]}</td>
            <td class='text-right'>".number_format($salary)."</td>
            <td class='text-center'>Today</td>
        </tr>
        <tr>
            <td colspan='4'>Bonus</td>               
        </tr>
        ";
    $sql = "select b.*,bo.Name as boname,s.Name as sname,Date(b.Date) as bdate from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblbonus bo on bo.AID=b.BCID 
    where b.Status=1 and b.StaffID={$aid} 
    and Month(b.Date)='{$month}' and Year(b.Date)='{$year}'";

    $result = mysqli_query($con,$sql) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){

        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $totalget+=$row['Amt'];
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["boname"]}</td>
                <td class='text-right'>".number_format($row["Amt"])."</td> 
                <td class='text-center'>".enDate($row["bdate"])."</td>                    
            </tr>";
        }        
    }
    $totalget+=$salary;
    $out.="
        <tr>
            <td colspan='2'><b>Total Get</b></td>  
            <td class='text-right'><b>".number_format($totalget)."</b></td> 
            <td>             
        </tr>
        <tr>
            <td colspan='4'>Cuts</td>               
        </tr>
        ";

    $sql1 = "select b.*,c.Name as cname,s.Name as sname,Date(b.Date) as bdate from tblbcsalary b 
    inner join tblstaff s on b.StaffID=s.AID 
    left join tblcut c on c.AID=b.BCID 
    where b.Status=0 and b.StaffID={$aid}  
    and Month(b.Date)='{$month}' and Year(b.Date)='{$year}'";
    $result = mysqli_query($con,$sql1) or die("SQL a Query");
    if(mysqli_num_rows($result) > 0){

        $no1=0;
        while($row = mysqli_fetch_array($result)){
            $no1 = $no1 + 1;
            $totalcut+=$row['Amt'];
            $out.="<tr>
                <td>{$no1}</td>
                <td>{$row["cname"]}</td>
                <td class='text-right'>".number_format($row["Amt"])."</td> 
                <td class='text-center'>".enDate($row["bdate"])."</td>                    
            </tr>";
        }    

    }

    $remain=$totalget-$totalcut;
    $out.="
        <tr>
            <td colspan='2'><b>Total Cut</b></td>  
            <td class='text-right'><b>".number_format($totalcut)."</b></td> 
            <td>             
        </tr>
        <tr>
            <td colspan='2'><b>Total Remain</b></td>  
            <td class='text-right'><b>".number_format($remain)."</b></td> 
            <td>             
        </tr>
        ";

    $out.="</tbody>";
    $out.="</table>
        <div class='row'>
            <div class='col-2'>
                <label>Remark</label>
            </div>
            <div class='col-10'>
                <input type='text' class='form-control' name='rmk' />
            </div>

        </div>
        <div class='text-center pt-2'>
            <button class='btn btn-{$color}' 
                id='btnpay' 
                data-aid='{$aid}' 
                data-staffid='{$staffid}' 
                data-staffname='{$staffname}' 
                data-remain='{$remain}' 
                data-salary='{$salary}' >Pay</button>
        </div>
    ";
    echo $out;
}

if($action == 'pay'){
    $aid = $_POST['aid'];
    $staffid = $_POST['staffid'];
    $staffname = $_POST['staffname'];
    $remain = $_POST['remain'];
    $salary = $_POST['salary'];
    $rmk1 = $_POST['rmk'];
    $vno = "sal_".date('YmdHis');
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $cash = $remain;
    $mobile = 0;
    $rmk = "";
    $out = "";
    $totalget = 0;
    $totalcut = 0;

    $dtmonth = $_POST['dtmonth'];
    $yrdata = strtotime($dtmonth);

    $month = date('m', $yrdata);
    $year = date('Y', $yrdata);
    $dtt=$year."-".$month."-"."01"; 

    $no = 1;
    $sql = "insert into tblpaysalary (LoginID,StaffID,Amt,Date,VNO,Rmk) 
    values ({$userid},{$aid},{$remain},'{$dtt}','{$vno}','{$rmk1}')";
    
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Teacher Salary Pay အားအသစ်သွင်းသွားသည်။");
        savecms($vno,$cash,$mobile,$rmk,'Salary Pay',1);
        ////////// slip view
        $out .= "
        <div id='printdata'>
            <h5 class='text-center'>
                ".$_SESSION["shopname"]."<br>
                ".$_SESSION["shopaddress"]."<br>
                Teacher Salary Pay Slip
            </h5>
            <p class='txtl fs'>
            {$lang["tea_teacherid"]} : {$staffid} <br>
            {$lang["tea_teachername"]} : {$staffname} <br>
            {$lang["tea_salarymonth"]} : ".enDate2($dt)."<br>
            {$lang["tea_datetime"]} : {$todaydt} / {$todaytt}
            </p>
            <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
                <thead>
                    <tr>
                        <th class='text-center txtc'>{$lang["no"]}</th>      
                        <th class='txtl'>{$lang["tea_description"]}</th>  
                        <th class='text-right txtr'>{$lang["tea_amount"]}</th>  
                        <th class='text-center txtc'>{$lang["tea_date"]}</th>           
                    </tr> 
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center txtc'>{$no}</td>
                        <td class='txtl'>{$lang["tea_basicsalary"]}</td>
                        <td class='text-right txtr'>{$salary}</td>
                        <td class='text-center txtc'>Today</td>
                    </tr>
                ";
                // bonus
                $sql_bonus = "select b.*,bo.Name as boname,s.Name as sname,Date(b.Date) as bdate 
                from tblbcsalary b 
                inner join tblstaff s on b.StaffID=s.AID 
                left join tblbonus bo on bo.AID=b.BCID 
                where b.Status=1 and b.StaffID={$aid} 
                and Month(b.Date)='{$month}' and Year(b.Date)='{$year}'";
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
                // cut
                $sql_cut = "select b.*,c.Name as cname,s.Name as sname,Date(b.Date) as bdate 
                from tblbcsalary b 
                inner join tblstaff s on b.StaffID=s.AID 
                left join tblcut c on c.AID=b.BCID 
                where b.Status=0 and b.StaffID={$aid} 
                and Month(b.Date)='{$month}' and Year(b.Date)='{$year}'";
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
                    <td class='text-right'>".$rmk1."</td> 
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
        ///////////
        echo $out;
    }else{
        echo 0;
    }

}

?>