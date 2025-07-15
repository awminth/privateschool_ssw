<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$userid = $_SESSION['userid'];

if($_POST["action"] == 'show'){  
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
    $from = $_POST['from'];
    $to = $_POST['to'];
    $a = "";
    if($search != ''){  
        $a = " and (l.VNO like '%$search%' or p.Name like '%$search%' or g.Name like '%$search%') ";
    } 
    $b = "";
    if($from != "" || $to != ""){
        $b = " and Date(l.Date)>='{$from}' and Date(l.Date)<='{$to}' ";
    }     
    $sql="select l.*,g.Name as gname,p.Name as pname  
    from tblvoucheruniform l,tblgrade g,tblstudentprofile p  
    where l.StudentID=p.AID and l.GradeID=g.AID ".$a.$b." order by l.AID desc 
    limit {$offset},{$limit_per_page}";
        
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>VNO</th>
            <th>Student Name</th>
            <th>Grade</th>    
            <th>Total</th>   
            <th>Disc</th>   
            <th>Tax</th>   
            <th>Grand Total</th>        
            <th>Date</th>  
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
                <td>{$row["VNO"]}</td>
                <td>{$row["pname"]}</td>
                <td>{$row["gname"]}</td>
                <td>".number_format($row["Total"])."</td>
                <td>".number_format($row["Disc"])."</td>
                <td>".number_format($row["Tax"])."</td>
                <td>".number_format($row["Amount"])."</td>
                <td>".enDate($row["Date"])."</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnview' class='dropdown-item'
                                data-vno='{$row['VNO']}' ><i class='fas fa-eye text-primary'
                                style='font-size:13px;'></i>
                                View</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-vno='{$row['VNO']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                Delete</a>                        
                        </div>
                    </div>
                </td> 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select l.AID from tblvoucheruniform l,tblgrade g,tblstudentprofile p  
        where Date(l.Date)>='{$from}' and Date(l.Date)<='{$to}' and
        l.StudentID=p.AID and l.GradeID=g.AID ".$a." order by l.AID desc";
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
            <th>VNO</th>
            <th>Student Name</th>
            <th>Total</th>    
            <th>Disc</th>   
            <th>Tax</th>   
            <th>Grand Total</th>        
            <th>Date</th>  
            <th>Action</th>             
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="9" class="text-center">No record</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }

}

if($_POST["action"] == "view"){
    $vno = $_POST["vno"];
    $student = "";
    $disc = "";
    $tax = "";
    $total = "";
    $grandtotal = "";
    $dt = "";
    $grade = "";
    $out = "";
    $sql_v="select l.*,g.Name as gname,p.Name as pname  
    from tblvoucheruniform l,tblgrade g,tblstudentprofile p  
    where l.StudentID=p.AID and l.GradeID=g.AID and l.VNO='{$vno}'";
    $res_v = mysqli_query($con,$sql_v);
    if(mysqli_num_rows($res_v) > 0){
        $row_v = mysqli_fetch_array($res_v);
        $student = $row_v["pname"];
        $disc = $row_v["Disc"];
        $tax = $row_v["Tax"];
        $total = $row_v["Total"];
        $grandtotal = $row_v["Amount"];
        $dt = date($row_v["Date"]);
        $grade = $row_v["gname"];
    }
    $out .= "
    <div id='printdata'>
        <h5 class='text-center'>
            ".$_SESSION["shopname"]."<br>
            ".$_SESSION["shopaddress"]."
        </h5>
        <p class='txtl fs'>
            VNO : {$vno} <br>
            Student : {$student} <br>
            Grade : {$grade} <br>
            Date : ".enDate($dt)."
        </p>
        <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='text-center txtc'>No</th>
                <th class='txtl'>Name</th>
                <th class='text-right txtr'>Price</th>
                <th class='text-center txtc'>Size</th>
                <th class='text-center txtc'>Qty</th>
                <th class='text-right txtr'>Total</th>
            </tr>
        ";
        $sql_item = "select * from tblselluniform where VNO='{$vno}'";
        $res_item = mysqli_query($con,$sql_item);
        $no = 0;
        if(mysqli_num_rows($res_item) > 0){
            while($row_item = mysqli_fetch_array($res_item)){
                $no = $no + 1;
                $out.="                   
                <tr>
                    <td class='text-center txtc'>{$no}</td>
                    <td class='txtl'>{$row_item['Name']}</td>
                    <td class='text-right txtr'>".number_format($row_item['Price'])."</td>
                    <td class='text-center txtc'>{$row_item['Size']}</td>
                    <td class='text-center txtc'>{$row_item['Qty']}</td>
                    <td class='text-right txtr'>".number_format($row_item['Price']*$row_item['Qty'])."</td>
                </tr>                    
                ";
            }
        }
        $out.="<tr>
                <td colspan='5' class='text-right txtr'>
                    Total :<br>
                    Disc(%) :<br>
                    Tax(%) :<br>
                    Grand Total :<br>
                </td>
                <td class='text-right txtr'>
                    ".number_format($total)."<br>
                    ".number_format($disc)."<br>
                    ".number_format($tax)."<br>
                    ".number_format($grandtotal)."<br>
                </td>
            </tr>                             
            <tr class='text-center txtc'>
                <td colspan='6'>----Thank You----</td>   
            </tr>
        </table>
        <br>
        ";
        $out.="
    </div>
    <br>
    <button class='btn btn-{$color}' id='btnprint' >Print</button>
    ";  
    /////////
    echo $out;
}

if($_POST["action"] == 'delete'){
    $vno = $_POST["vno"];
    $sql = "delete from tblvoucheruniform where VNO='{$vno}'";
    if(mysqli_query($con,$sql)){
        $sql_s = "delete from tblselluniform where VNO='{$vno}'";
        if(mysqli_query($con,$sql_s)){
            save_log($_SESSION["username"]." သည် uniform sale အားဖျက်သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
    
}

if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $from = $_POST['dtfrom'];
    $to = $_POST['dtto'];
    $a = "";
    if($search != ''){  
        $a = " and (l.VNO like '%$search%' or p.Name like '%$search%' or g.Name like '%$search%') ";
    } 
    $b = "";
    if($from != "" || $to != ""){
        $b = " and Date(l.Date)>='{$from}' and Date(l.Date)<='{$to}' ";
    }     
    $sql="select l.*,g.Name as gname,p.Name as pname  
    from tblvoucheruniform l,tblgrade g,tblstudentprofile p  
    where l.StudentID=p.AID and l.GradeID=g.AID ".$a.$b." order by l.AID desc ";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SaleUniform-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>Sale Uniform</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Grade</th>    
                <th style="border: 1px solid ;">Total</th>   
                <th style="border: 1px solid ;">Disc</th>   
                <th style="border: 1px solid ;">Tax</th>   
                <th style="border: 1px solid ;">Grand Total</th>        
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
                    <td style="border: 1px solid ;">'.$row["pname"].'</td>
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Total"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Disc"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Tax"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Amount"]).'</td>
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>
                
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;            
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>Sale Uniform</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Grade</th>    
                <th style="border: 1px solid ;">Total</th>   
                <th style="border: 1px solid ;">Disc</th>   
                <th style="border: 1px solid ;">Tax</th>   
                <th style="border: 1px solid ;">Grand Total</th>        
                <th style="border: 1px solid ;">Date</th>    
       
            </tr>
            <tr>
                <td style="border: 1px solid ;" align="center">No record</td>
            </tr>
        </table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out; 
    }
    
}

if($_POST["action"] == 'pdf'){
    $search = $_POST['ser'];
    $from = $_POST['dtfrom'];
    $to = $_POST['dtto'];
    $a = "";
    if($search != ''){  
        $a = " and (l.VNO like '%$search%' or p.Name like '%$search%' or g.Name like '%$search%') ";
    } 
    $b = "";
    if($from != "" || $to != ""){
        $b = " and Date(l.Date)>='{$from}' and Date(l.Date)<='{$to}' ";
    }     
    $sql="select l.*,g.Name as gname,p.Name as pname  
    from tblvoucheruniform l,tblgrade g,tblstudentprofile p  
    where l.StudentID=p.AID and l.GradeID=g.AID ".$a.$b." order by l.AID desc ";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SaleUniform-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>Sale Uniform</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Grade</th>    
                <th style="border: 1px solid ;">Total</th>   
                <th style="border: 1px solid ;">Disc</th>   
                <th style="border: 1px solid ;">Tax</th>   
                <th style="border: 1px solid ;">Grand Total</th>        
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
                    <td style="border: 1px solid ;">'.$row["pname"].'</td>
                    <td style="border: 1px solid ;">'.$row["gname"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Total"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Disc"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Tax"]).'</td>
                    <td style="border: 1px solid ;">'.number_format($row["Amount"]).'</td>
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
        $file = 'SaleUniformPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');           
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="9" align="center"><h3>Sale Uniform</h3></td>
            </tr>
            <tr><td colspan="9"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">VNO</th>
                <th style="border: 1px solid ;">Student Name</th>
                <th style="border: 1px solid ;">Grade</th>    
                <th style="border: 1px solid ;">Total</th>   
                <th style="border: 1px solid ;">Disc</th>   
                <th style="border: 1px solid ;">Tax</th>   
                <th style="border: 1px solid ;">Grand Total</th>        
                <th style="border: 1px solid ;">Date</th>    
       
            </tr>
            <tr>
                <td style="border: 1px solid ;" align="center">No record</td>
            </tr>
        </table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'SaleUniformPDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }
    
}



?>