<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$stuid = $_SESSION['stuid'];
$stuname = $_SESSION['stuname'];
$stuvno = $_SESSION['stuvno'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];

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
        $a = " and VNO like '%$search%' ";
    }       
    $sql="select * from tblfeedetail where 
    FeeVNO='{$stuvno}' ".$a." 
    order by AID desc limit {$offset},{$limit_per_page}";
        
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["feeview_vno"].'</th>
            <th>'.$lang["feeview_cash"].'</th>
            <th>'.$lang["feeview_mobile"].'</th>
            <th>'.$lang["feeview_mobilermk"].'</th>
            <th>'.$lang["feeview_totalpay"].'</th>
            <th>'.$lang["feeview_paydt"].'</th>
            <th>'.$lang["feeview_payname"].'</th>
            <th>'.$lang["feeview_receivename"].'</th>
            <th width="10%;">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        $totalcash=0;
        $totalmobile=0;
        $totalpay=0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $totalcash+=$row["Cash"];
            $totalmobile+=$row["Mobile"];
            $totalpay+=$row["TotalAmt"];
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
                <td>{$row["VNO"]}</td>  
                <td>".number_format($row["Cash"])."</td>  
                <td>".number_format($row["Mobile"])."</td>  
                <td>{$row["MobileRmk"]}</td>  
                <td>".number_format($row["TotalAmt"])."</td> 
                <td>".enDate($row["PayDate"])."</td>   
                <td>{$row["PayName"]}</td>  
                <td>{$row["ReceiveName"]}</td>    
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'> 
                            <a href='#' id='btnview' class='dropdown-item'  
                                data-feevno='{$row['FeeVNO']}' 
                                data-totalpay='{$row['TotalAmt']}'
                                data-paydt='{$row['PayDate']}'
                                data-payname='{$row['PayName']}'
                                data-receivename='{$row['ReceiveName']}'><i
                                class='fas fa-eye text-primary'
                                style='font-size:13px;'></i>
                                ".$lang["feeview_view"]."</a> 
                            <div class='dropdown-divider'></div>                      
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-vno='{$row['VNO']}' 
                                data-feevno='{$row['FeeVNO']}' 
                                data-totalpay='{$row['TotalAmt']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                ".$lang["btndelete"]."</a>                   
                        </div>
                    </div>
                </td>            
              
            </tr>";
        }

        $out.="
        <tr>
            <td colspan='2' class='text-center'>Total</td>  
            <td>".number_format($totalcash)."</td>  
            <td>".number_format($totalmobile)."</td>  
            <td></td>  
            <td>".number_format($totalpay)."</td> 
            <td></td>   
            <td></td>  
            <td></td>    
            <td class='text-center'>
               
            </td>            
        
        </tr>";
        
        $out.="</tbody>";
        $out.="</table><br><br>";

        $sql_total="select AID from tblfeedetail where 
        FeeVNO='{$stuvno}' ".$a." 
        order by AID desc";
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
                if($next_id > $total_links){
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
            <th>'.$lang["feeview_vno"].'</th>
            <th>'.$lang["feeview_cash"].'</th>
            <th>'.$lang["feeview_mobile"].'</th>
            <th>'.$lang["feeview_mobilermk"].'</th>
            <th>'.$lang["feeview_totalpay"].'</th>
            <th>'.$lang["feeview_paydt"].'</th>
            <th>'.$lang["feeview_payname"].'</th>
            <th>'.$lang["feeview_receivename"].'</th>
            <th width="10%;">Action</th>           
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

if($action == 'delete'){
    $aid = $_POST["aid"];
    $vno = $_POST["vno"];
    $feevno = $_POST["feevno"];
    $totalpay = $_POST["totalpay"];   
   
    $sql = "delete from tblfeedetail where AID=$aid";
    if(mysqli_query($con,$sql)){

        $sqlchkdel="select AID from tblfeedetail where FeeVNO='{$feevno}'";
        $result1=mysqli_query($con,$sqlchkdel);
        if(mysqli_num_rows($result1)>0){           
            $sqlchk="select * from tblfee where LoginID={$userid} and 
            EARStudentID={$stuid}";
            $result=mysqli_query($con,$sqlchk);
            if(mysqli_num_rows($result)>0){
                $row = mysqli_fetch_array($result);
                $ototalpay=$row['TotalPay'];
                $oremain=$row['Remain'];
                $a=$ototalpay-$totalpay;
                $b=$oremain-$a;
                $sqlupd="update tblfee set TotalPay={$a},Remain={$b} where LoginID={$userid} and 
                EARStudentID={$stuid}";
                mysqli_query($con,$sqlupd);        
            }

        }else{

            $sqldelfee="delete from tblfee where VNO='{$feevno}'";
            mysqli_query($con,$sqldelfee);

        }  
        deletecms($vno);
        save_log($_SESSION["username"]." သည် Student Fee အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " and VNO like '%$search%' ";
    }       
    $sql="select * from tblfeedetail where 
    FeeVNO='{$stuvno}' ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "FeeTransactionReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>'.$lang["feeview_title"].' ('.$_SESSION['stuname'].')</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_vno"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_cash"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobile"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobilermk"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_totalpay"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_paydt"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_payname"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_receivename"].'</th>
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
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
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Cash"].'</td> 
                    <td style="border: 1px solid ;">'.$row["Mobile"].'</td> 
                    <td style="border: 1px solid ;">'.$row["MobileRmk"].'</td> 
                    <td style="border: 1px solid ;">'.$row["TotalAmt"].'</td> 
                    <td style="border: 1px solid ;">'.$row["PayDate"].'</td> 
                    <td style="border: 1px solid ;">'.$row["PayName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["ReceiveName"].'</td>              
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
                <td colspan="9" align="center"><h3>'.$lang["feeview_title"].' ('.$_SESSION['stuname'].')</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_vno"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_cash"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobile"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobilermk"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_totalpay"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_paydt"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_payname"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_receivename"].'</th>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid ;" colspan="9">No data</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action == 'pdf'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " and VNO like '%$search%' ";
    }       
    $sql="select * from tblfeedetail where 
    FeeVNO='{$stuvno}' ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "FeeTransactionReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>'.$lang["feeview_title"].' ('.$_SESSION['stuname'].')</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_vno"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_cash"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobile"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobilermk"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_totalpay"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_paydt"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_payname"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_receivename"].'</th>
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
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
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Cash"].'</td> 
                    <td style="border: 1px solid ;">'.$row["Mobile"].'</td> 
                    <td style="border: 1px solid ;">'.$row["MobileRmk"].'</td> 
                    <td style="border: 1px solid ;">'.$row["TotalAmt"].'</td> 
                    <td style="border: 1px solid ;">'.$row["PayDate"].'</td> 
                    <td style="border: 1px solid ;">'.$row["PayName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["ReceiveName"].'</td>              
                </tr>';
        }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'FeeTransactionPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>'.$lang["feeview_title"].' ('.$_SESSION['stuname'].')</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang["no"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_vno"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_cash"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobile"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_mobilermk"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_totalpay"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_paydt"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_payname"].'</th>
                <th style="border: 1px solid ;">'.$lang["feeview_receivename"].'</th>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid ;" colspan="9">No data</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'FeeTransactionPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }   
    
}

if($action == "view"){
    $feevno = $_POST['feevno'];
    $totalpay = $_POST['totalpay'];
    $paydt = $_POST['paydt'];
    $payname = $_POST['payname'];
    $receivename = $_POST['receivename'];
    $studentname = $stuname;
    $amt = 0;
    $remain = 0;
    $studentID =  GetString("select s.StudentID from tblearstudent e,tblstudentprofile s 
    where e.StudentID=s.AID and e.AID={$stuid}");
    $sql = "select f.Total,f.Remain from tblfee f,tblfeedetail d 
    where f.VNO=d.FeeVNO and d.FeeVNO='{$feevno}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $amt = $row["Total"];
        $remain = $row["Remain"];
    }    
    $out = "";
    $todaydt = date("d-F-Y");
    $todaytt = date("H:i");
    $out .= "
    <div id='printdata'>
        <h5 class='text-center'>
            ".$_SESSION["shopname"]."<br>
            ".$_SESSION["shopaddress"]."<br>
            Student Fee Pay Slip
        </h5>
        <p class='txtl fs'>
            Date : {$todaydt}<br>
            Time : {$todaytt}
        </p>
        <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
            <tbody>
                <tr>
                    <td class='txtl'>Student ID</td>
                    <td class='txtl'>{$studentID}</td>
                </tr>
                <tr>
                    <td class='txtl'>Student Name</td>
                    <td class='txtl'>{$studentname}</td>
                </tr>
                <tr>
                    <td class='txtl'>Grade</td>
                    <td class='txtl'>{$gradename}</td>
                </tr>
                <tr>
                    <td class='txtl'>Fee VNO</td>
                    <td class='txtl'>{$feevno}</td>
                </tr>
                <tr>
                    <td class='txtl'>Total Amount</td>
                    <td class='txtl'>".number_format($amt)."</td>
                </tr>
                <tr>
                    <td class='txtl'>Pay Amount</td>
                    <td class='txtl'>".number_format($totalpay)."</td>
                </tr>
                <tr>
                    <td class='txtl'>Remain Amount</td>
                    <td class='txtl'>".number_format($remain)."</td>
                </tr>
                <tr>
                    <td class='txtl'>Pay Name</td>
                    <td class='txtl'>{$payname}</td>
                </tr>
                <tr>
                    <td class='txtl'>Receive Name</td>
                    <td class='txtl'>{$receivename}</td>
                </tr>
                <tr>
                    <td class='txtl'>Pay Date</td>
                    <td class='txtl'>".enDate($paydt)."</td>
                </tr>
            <tbody>
        </table>
    </div>
    <br>
    <button class='btn btn-{$color}' id='btnprint' >Print</button>
    ";      
    echo $out;
}


?>