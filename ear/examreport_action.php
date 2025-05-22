<?php
include('../config.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];
$dt=date('Y-m-d');

if($action == 'show'){        
    $sql = "select v.*,t.Name as tname  
    from tblexam_voucher v,tblexamtype t  
    where v.ExamTypeID=t.AID  and 
    v.EARYearID={$yearid} and v.GradeID={$gradeid} 
    group by v.ExamTypeID";  
  
    $res = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    $cnt = 0;
    if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
            $cnt = $cnt + 1;
            $out .= '
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <table>
                        <tr>
                            <td>
                                <h4 class="text-primary">'.$row["tname"].'</h4>
                            </td>
                            <td width="5%"></td>
                            <td class="float-right">
                                <form method="POST" action="examreport_action.php">
                                    <input type="hidden" name="examtypeid" value="'.$row["ExamTypeID"].'">
                                    <button type="submit" name="action" value="excel"
                                        class="btn btn-sm btn-primary"><i
                                            class="fas fa-file-excel"></i>&nbsp;'.$lang['staff_excel'].'</button>
                                </form>
                            </td>
                        </tr>
                    </table>                    
                </div>
                <div class="card-body">                    
                    <div class="table-responsive-sm">
            ';

            $sql_v = "select v.*,t.Name as tname  
            from tblexam_voucher v,tblexamtype t  
            where v.ExamTypeID=t.AID  and 
            v.EARYearID={$yearid} and v.GradeID={$gradeid} and 
            v.ExamTypeID='{$row['ExamTypeID']}'";
            $res_v = mysqli_query($con,$sql_v) or die("SQL a Query");
            $no = 0 ;
            if(mysqli_num_rows($res_v) > 0){
                $out.='
                <table class="table table-bordered table-striped responsive nowrap">
                <thead>
                <tr>
                    <th width="7%;">'.$lang['no'].'</th>
                    <th>'.$lang['exam_studentname'].'</th>
                    <th>'.$lang['exam_grade'].'</th>
                    <th>'.$lang['exam_totalpaymark'].'</th>
                    <th>'.$lang['exam_totalgetmark'].'</th>  
                    <th>View</th>        
                </tr>
                </thead>
                <tbody>
                ';
                while($row_v = mysqli_fetch_array($res_v)){
                    $no = $no + 1;
                    $out .= '
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$row_v["EARStudentName"].'</td>
                        <td>'.$row_v["GradeName"].'</td>
                        <td>'.$row_v["TotalPayMark"].'</td>
                        <td>'.$row_v["TotalGetMark"].'</td>
                        <td>
                            <a href="#" id="btnview" 
                                data-vno="'.$row_v["VNO"].'">
                                <i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    ';
                }
                $out.="</tbody>
                </table>";
            }
            $out .= '
            </div>
            </div>
            </div>
            ';
        }
        echo $out;         
    }else{
        echo"<h3>No Record Found</h3>";
    }
}

if($action == "view"){
    $vno = $_POST["vno"];
    $out = "";
    $student = "";
    $grade = "";
    $year = "";  
    $dt = "";  
    $sql_v = "select * from tblexam_voucher where VNO='{$vno}'";
    $res_v = mysqli_query($con,$sql_v);
    if(mysqli_num_rows($res_v) > 0){
        $row_v = mysqli_fetch_array($res_v);
        $student = $row_v["EARStudentName"];
        $grade = $row_v["GradeName"];
        $year = $row_v["EARYearName"];
        $dt = enDate($row_v["Date"]);
    }
    $out .= "
    <div id='printdata'>
        <h5 class='text-center'>
            ".$_SESSION["shopname"]."<br>   
            ".$_SESSION["shopaddress"]."
        </h5>
        <p class='txtl fs'>
            Student : {$student} <br>
            Grade : {$grade} <br>
            Year : {$year} <br>
            Date : {$dt}
        </p>
        <table class='table table\-bordered text\-sm' frame=hsides rules=rows width='100%'>
            <tr>
                <th class='txtl'>Subject</th>
                <th class='text-center txtc'>Pay Mark</th>
                <th class='text-center txtc'>Get Mark</th>
                <th class='text-center txtc'>Result</th>
                <th class='text-center txtc'>D</th>
            </tr>
        ";
        $sql_item = "select e.*,s.Name as sname from tblexam e,tblsubject s 
        where e.SubjectID=s.AID and e.VNO='{$vno}'";
        $res_item = mysqli_query($con,$sql_item);
        $no = 0;
        $totalpay = 0;
        $totalget = 0;
        
        if(mysqli_num_rows($res_item) > 0){
            while($row_item = mysqli_fetch_array($res_item)){
                $no = $no + 1;
                $d = "No";
                $totalpay = $totalpay + $row_item['PayMark'];
                $totalget = $totalget + $row_item['GetMark'];
                if($row_item["D"] == 1){
                    $d = "Yes";
                }
                $out.="                   
                <tr>
                    <td class='txtl'>{$row_item['sname']}</td>
                    <td class='text-center txtc'>{$row_item['PayMark']}</td>
                    <td class='text-center txtc'>{$row_item['GetMark']}</td>
                    <td class='text-center txtc'>{$row_item['Result']}</td>
                    <td class='text-center txtc'>{$d}</td>
                </tr>                    
                ";
            }
        }
        $out.="<tr>
                <td class='txtl'></td>
                <td class='text-center txtc'><b>{$totalpay}</b></td>
                <td class='text-center txtc'><b>{$totalget}</b></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br><br><br>
        <div class='rt txtr text-right'>
            <b>Signature</b>
        </div>
        ";
        $out.="
    </div>
    <br>
    <button class='btn btn-{$color}' id='btnprint' >Print</button>
    ";  
    /////////
    echo $out;
}

if($action == 'excel'){
    $examtypeid = $_POST['examtypeid'];    
    $sql = "select v.*,t.Name as tname  
    from tblexam_voucher v,tblexamtype t  
    where v.ExamTypeID=t.AID and 
    v.EARYearID={$yearid} and v.GradeID={$gradeid} and 
    v.ExamTypeID='{$examtypeid}'";
    $result = mysqli_query($con,$sql);
    $out="";
    $examname = GetString("select Name from tblexamtype where AID={$examtypeid}");
    $fileName = "StudentExamReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>'.$examname.'</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_grade'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_totalpaymark'].'</th> 
                <th style="border: 1px solid ;">'.$lang['exam_totalgetmark'].'</th>      
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["EARStudentName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["GradeName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalPayMark"].'</td>  
                    <td style="border: 1px solid ;">'.$row["TotalGetMark"].'</td>                 
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

if($action == 'show_all'){    
    $search = $_POST["search"];
    $a = "";
    if($search != ""){
        $a = " and v.EARStudentName like '%$search%' ";
    } 
    $st = $_POST["st"];
    $b = "";
    if($st != ""){
        if($st == "hl"){
            $b = " order by sum(v.TotalGetMark) desc ";
        }else if($st == "lh"){
            $b = " order by sum(v.TotalGetMark) ";
        }else{
            $b = "";
        }        
    }    
    $sql = "select v.*,sum(v.TotalGetMark) as totalget,sum(v.TotalPayMark) as totalpay  
    from tblexam_voucher v  
    where  
    v.EARYearID={$yearid} and v.GradeID={$gradeid} ".$a."  
    group by v.EARStudentID ".$b; 
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['exam_studentname'].'</th>
            <th>'.$lang['exam_grade'].'</th>
            <th>'.$lang['exam_totalpaymark'].'</th> 
            <th>'.$lang['exam_totalgetmark'].'</th>                
        </tr>
        </thead>
        <tbody>
        ';
        $no =0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["EARStudentName"]}</td>  
                <td>{$row["GradeName"]}</td>                           
                <td>{$row["totalpay"]}</td> 
                <td>{$row["totalget"]}</td> 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";
        echo $out;         
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang['no'].'</th>
            <th>'.$lang['exam_studentname'].'</th>
            <th>'.$lang['exam_grade'].'</th>
            <th>'.$lang['exam_totalpaymark'].'</th> 
            <th>'.$lang['exam_totalgetmark'].'</th>                   
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

if($action == 'excel_all'){
    $search = $_POST["ser"];
    $a = "";
    if($search != ""){
        $a = " and v.EARStudentName like '%$search%' ";
    } 
    $st = $_POST["hid"];
    $b = "";
    if($st != ""){
        if($st == "hl"){
            $b = " order by sum(v.TotalGetMark) desc ";
        }else if($st == "lh"){
            $b = " order by sum(v.TotalGetMark) ";
        }else{
            $b = "";
        }        
    }    
    $sql = "select v.*,sum(v.TotalGetMark) as totalget,sum(v.TotalPayMark) as totalpay  
    from tblexam_voucher v  
    where  
    v.EARYearID={$yearid} and v.GradeID={$gradeid} ".$a."  
    group by v.EARStudentID ".$b; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "AllStudentExamReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="5" align="center"><h3>Student Exam All Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_grade'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_totalpaymark'].'</th> 
                <th style="border: 1px solid ;">'.$lang['exam_totalgetmark'].'</th>      
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["EARStudentName"].'</td> 
                    <td style="border: 1px solid ;">'.$row["GradeName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["totalpay"].'</td>  
                    <td style="border: 1px solid ;">'.$row["totalget"].'</td>                 
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
                <td colspan="5" align="center"><h3>Student Exam All Report</h3></td>
            </tr>
            <tr><td colspan="5"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">'.$lang['no'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_studentname'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_grade'].'</th>  
                <th style="border: 1px solid ;">'.$lang['exam_totalpaymark'].'</th> 
                <th style="border: 1px solid ;">'.$lang['exam_totalgetmark'].'</th>        
            </tr>
            <tr>
                <td style="border: 1px solid ;" colspan="5" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}


?>