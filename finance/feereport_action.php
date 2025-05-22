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
    
    $from=$_POST['from'];
    $to=$_POST['to'];    
   
    $search = $_POST['search'];
    if($search == ''){        
       $search="";
    }else{
       $search=" and (s.Name like '%{$search}%')";
    }

    $sql="select f.*,s.Name from tblfee f,tblearstudent es,tblstudentprofile s 
    where s.AID=es.StudentID and es.AID=f.EARStudentID and 
     Date(f.Date)>='{$from}' and Date(f.Date)<='{$to}' ".$search. "
     order by f.AID desc limit {$offset},{$limit_per_page}";
     
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>VNO</th>
            <th>Student Name</th>
            <th>Total</th>
            <th>Total Pay</th>
            <th>Remain</th> 
            <th>Date</th>        
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        $total=0;
        $totalpay=0;
        $totalremain=0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $total+=$row["Total"];
            $totalpay+=$row["TotalPay"];
            $totalremain+=$row["Remain"];
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["VNO"]}</td>   
                <td>{$row["Name"]}</td>  
                <td>".number_format($row["Total"])."</td>  
                <td>".number_format($row["TotalPay"])."</td> 
                <td>".number_format($row["Remain"])."</td>
                <td>".enDate($row["Date"])."</td>                             
              
            </tr>";
        }
        $out.="<tr>
                <td colspan='3' class='text-center'>Total</td> 
                <td>".number_format($total)."</td>  
                <td>".number_format($totalpay)."</td> 
                <td>".number_format($totalremain)."</td>
                <td></td>                             
              
            </tr>";
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="";
        $sql_total="select f.AID from tblfee f,tblearstudent es,tblstudentprofile s 
        where s.AID=es.StudentID and es.AID=f.EARStudentID and 
         Date(f.Date)>='{$from}' and Date(f.Date)<='{$to}' ".$search. "
         order by f.AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>'.$lang['staff_totalrecord'] .' -  ';
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
                if($next_id >= $total_links){
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
            <th width="7%;">'.$lang['no'].'</th>
            <th>VNO</th>
            <th>Student Name</th>
            <th>Total</th>
            <th>Total Pay</th>
            <th>Remain</th> 
            <th>Date</th>            
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


if($_POST["action"] == 'excel')
{
    $search = $_POST['ser'];
    
    $from=$_POST['dtfrom'];
    $to=$_POST['dtto'];
   
    if($search == ''){        
        $search="";
     }else{
        $search=" and (s.Name like '%{$search}%')";
     }
 
     $sql="select f.*,s.Name from tblfee f,tblearstudent es,tblstudentprofile s 
     where s.AID=es.StudentID and es.AID=f.EARStudentID and 
      Date(f.Date)>='{$from}' and Date(f.Date)<='{$to}' ".$search. "
      order by f.AID desc";


    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentFeeReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Student Fee Reports</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Student Name</th>  
                <th style="border: 1px solid ;">Total</th>  
                <th style="border: 1px solid ;">Total Pay</th> 
                <th style="border: 1px solid ;">Remain</th> 
                <th style="border: 1px solid ;">Date</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>
                    <td style="border: 1px solid ;">'.$row["Total"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalPay"].'</td>   
                    <td style="border: 1px solid ;">'.$row["Remain"].'</td>    
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                         
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

if($_POST["action"] == 'pdf')
{
    $search = $_POST['ser'];
    
    $from=$_POST['dtfrom'];
    $to=$_POST['dtto'];
   
    if($search == ''){        
        $search="";
     }else{
        $search=" and (s.Name like '%{$search}%')";
     }
 
     $sql="select f.*,s.Name from tblfee f,tblearstudent es,tblstudentprofile s 
     where s.AID=es.StudentID and es.AID=f.EARStudentID and 
      Date(f.Date)>='{$from}' and Date(f.Date)<='{$to}' ".$search. "
      order by f.AID desc";


    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentFeeReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="7" align="center"><h3>Student Fee Reports</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Student Name</th>  
                <th style="border: 1px solid ;">Total</th>  
                <th style="border: 1px solid ;">Total Pay</th> 
                <th style="border: 1px solid ;">Remain</th> 
                <th style="border: 1px solid ;">Date</th> 
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>
                    <td style="border: 1px solid ;">'.$row["Total"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalPay"].'</td>   
                    <td style="border: 1px solid ;">'.$row["Remain"].'</td>    
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
        $file = 'StudentFeePDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        echo "No Record Found.";
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
    }
    else{
        $page=1;                      
    }

    $offset = ($page-1) * $limit_per_page;   
    
    $from=$_POST['from'];
    $to=$_POST['to'];
    
   
    $search = $_POST['search'];
    if($search == ''){        
        $search="";
    }else{
       $search=" and (s.Name like '%{$search}%' or d.VNO like '%{$search}%' ) ";
    }

    $sql="select d.*,s.Name from tblfeedetail d, tblfee f,
    tblearstudent es,tblstudentprofile s 
    where s.AID=es.StudentID and d.FeeVNO=f.VNO and es.AID=f.EARStudentID 
    and d.Date>='{$from}' and d.Date<='{$to}' " . $search . "
    order by AID desc limit {$offset},{$limit_per_page}
    ";
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table id="example" class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>VNO</th>
            <th>Student Name</th>
            <th>Cash</th>
            <th>Mobile</th>     
            <th>Total Pay</th>  
            <th>Pay Name</th> 
            <th>Receive Name</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        ';
        $no = (($page - 1) * $limit_per_page);
        $totalcash=0;
        $totalmobile=0;
        $total=0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $totalcash+=$row["Cash"];
            $totalmobile+=$row["Mobile"];
            $total+=$row["TotalAmt"];
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["VNO"]}</td>   
                <td>{$row["Name"]}</td>  
                <td>".number_format($row["Cash"])."</td>  
                <td>".number_format($row["Mobile"])."</td> 
                <td>".number_format($row["TotalAmt"])."</td> 
                <td>{$row["PayName"]}</td> 
                <td>{$row["ReceiveName"]}</td>   
                <td>".enDate($row["Date"])."</td>                    
              
            </tr>";
        }
        $out.="<tr>
            <td colspan='3' class='text-center'>Total</td> 
            <td>".number_format($totalcash)."</td>  
            <td>".number_format($totalmobile)."</td> 
            <td>".number_format($total)."</td> 
            <td></td> 
            <td></td>   
            <td></td>                    
        
        </tr>";
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="";
        $sql_total="select d.AID from tblfeedetail d, tblfee f,
        tblearstudent es,tblstudentprofile s 
        where s.AID=es.StudentID and d.FeeVNO=f.VNO and es.AID=f.EARStudentID 
        and d.Date>='{$from}' and d.Date<='{$to}' " . $search . "
        order by AID desc
        ";
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
                                    <a class="pagin2 page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="pagin2 page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id >= $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="pagin2 page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="pagin2 page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
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
            <th>VNO</th>
            <th>Student Name</th>
            <th>Cash</th>
            <th>Mobile</th>     
            <th>Total Pay</th>  
            <th>Pay Name</th> 
            <th>Receive Name</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="9" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}


if($_POST["action"] == 'excel1')
{
    $search = $_POST['ser1'];
    $from=$_POST['dtfrom1'];
    $to=$_POST['dtto1'];
    if($search == ''){        
        $search="";
    }else{
       $search=" and (s.Name like '%{$search}%' or d.VNO like '%{$search}%' ) ";
    }

    $sql="select d.*,s.Name from tblfeedetail d, tblfee f,
    tblearstudent es,tblstudentprofile s 
    where s.AID=es.StudentID and d.FeeVNO=f.VNO and es.AID=f.EARStudentID 
    and d.Date>='{$from}' and d.Date<='{$to}' " . $search . "
    order by AID desc 
    ";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentFeeDetailReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="9" align="center"><h3>Student Fee Detail Reports</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Student Name</th>  
                <th style="border: 1px solid ;">Cash</th>  
                <th style="border: 1px solid ;">Mobile</th> 
                <th style="border: 1px solid ;">Total Pay</th>
                <th style="border: 1px solid ;">Pay Name</th>
                <th style="border: 1px solid ;">Receive Name</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>
                    <td style="border: 1px solid ;">'.$row["Cash"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Mobile"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalAmt"].'</td>   
                    <td style="border: 1px solid ;">'.$row["PayName"].'</td>
                    <td style="border: 1px solid ;">'.$row["ReceiveName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["Date"].'</td>                        
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

if($_POST["action"] == 'pdf1')
{
    $search = $_POST['ser1'];
    $from=$_POST['dtfrom1'];
    $to=$_POST['dtto1'];
    if($search == ''){        
        $search="";
    }else{
       $search=" and (s.Name like '%{$search}%' or d.VNO like '%{$search}%' ) ";
    }

    $sql="select d.*,s.Name from tblfeedetail d, tblfee f,
    tblearstudent es,tblstudentprofile s 
    where s.AID=es.StudentID and d.FeeVNO=f.VNO and es.AID=f.EARStudentID 
    and d.Date>='{$from}' and d.Date<='{$to}' " . $search . "
    order by AID desc 
    ";

    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "StudentFeeDetailReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                    <td colspan="9" align="center"><h3>Student Fee Detail Reports</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>  
                <th style="border: 1px solid ;">Student Name</th>  
                <th style="border: 1px solid ;">Cash</th>  
                <th style="border: 1px solid ;">Mobile</th> 
                <th style="border: 1px solid ;">Total Pay</th>
                <th style="border: 1px solid ;">Pay Name</th>
                <th style="border: 1px solid ;">Receive Name</th>
                <th style="border: 1px solid ;">Date</th>
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["VNO"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Name"].'</td>
                    <td style="border: 1px solid ;">'.$row["Cash"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Mobile"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalAmt"].'</td>   
                    <td style="border: 1px solid ;">'.$row["PayName"].'</td>
                    <td style="border: 1px solid ;">'.$row["ReceiveName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["Date"].'</td>                        
                </tr>';
        }
        $out .= '</table>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentFeeDetailPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        echo "No Record Found.";
    }   
    
}





?>