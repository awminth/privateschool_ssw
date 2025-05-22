<?php
include('../config.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];
$stuid = $_SESSION["exam_earstudent_aid"];
$stuname = $_SESSION["exam_earstudent_name"];
$dt = date('Y-m-d');

// show subject
if($action == "show_subject"){
    $sql = "select * from tblsubject 
    where GradeID={$gradeid} ";    
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-sm table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["exam_subname"].'</th>
            <th width="10%;" class="text-center">Action</th>           
        </tr>
        </thead>
        <tbody>
        ';
        $no = 0;
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
                <td class='text-center'>  
                    <a href='#' id='btnaddrecord' class='dropdown-item'
                        data-said='{$row['AID']}' 
                        data-sname='{$row['Name']}' ><i class='fas fa-edit text-primary'
                        style='font-size:13px;'></i>
                        ".$lang["exam_put"]."</a>                           
                </td> 
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";
        echo $out;         
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["exam_subname"].'</th>
            <th width="10%;" class="text-center">Action</th>           
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

// show for new data 
if($action == "show_data"){
    $sql = "select e.*,s.Name as sname  
    from tblexam e,tblsubject s 
    where e.SubjectID=s.AID 
    and e.EARStudentID={$stuid} and e.Chk=0";    
    $result = mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["exam_subname"].'</th>
            <th>'.$lang["exam_pay"].'</th>
            <th>'.$lang["exam_get"].'</th>
            <th>'.$lang["exam_result"].'</th>
            <th>'.$lang["exam_d"].'</th>
            <th width="10%;">Action</th>            
        </tr>
        </thead>
        <tbody>
        ';
        $no = 0;
        $totalpay = 0;
        $totalget = 0;
        while($row = mysqli_fetch_array($result)){
            $no=$no+1;
            $d = "No";
            if($row["D"] == 1){
                $d = "Yes";
            }
            $totalpay = $totalpay + $row["PayMark"];
            $totalget = $totalget + $row["GetMark"];
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
                <td>{$row["sname"]}</td> 
                <td>{$row["PayMark"]}</td> 
                <td>{$row["GetMark"]}</td> 
                <td>{$row["Result"]}</td>
                <td>{$d}</td> 
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>                       
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
                                class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                                ".$lang["btnedit"]."</a>  
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                                ".$lang["btndelete"]."</a>                  
                        </div>
                    </div>
                </td>      
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table><br>";
        $out.='
        <div class="text-right">
            <button id="btnsaveall" 
                data-totalpay="'.$totalpay.'" 
                data-totalget="'.$totalget.'" type="button" class="btn btn-primary">
                <i class="fas fa-save"></i>&nbsp;'.$lang["btnsave"].'
            </button>
        </div>
        ';
        echo $out;         
    }else{
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">'.$lang["no"].'</th>
            <th>'.$lang["exam_subname"].'</th>
            <th>'.$lang["exam_pay"].'</th>
            <th>'.$lang["exam_get"].'</th>
            <th>'.$lang["exam_result"].'</th>
            <th>'.$lang["exam_d"].'</th>
            <th width="10%;">Action</th>          
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        ';
        echo $out;
    }
}

if($action=='save'){
    $subjectid = $_POST['subjectid'];
    $paymark = $_POST['paymark'];
    $getmark = $_POST['getmark'];
    $result = $_POST['result'];
    $d = $_POST['d'];
    $dt = date("Y-m-d");

    $sqlchk="select AID from tblexam where 
     EARStudentID={$stuid} and SubjectID={$subjectid} and Chk=0";
    $res=mysqli_query($con,$sqlchk);    
    if(mysqli_num_rows($res) > 0){
        echo 2;
    }else{
        $sql="insert into tblexam (LoginID,EARStudentID,SubjectID,
        PayMark,GetMark,Result,D,Date) values ({$userid},'{$stuid}','{$subjectid}',
        '{$paymark}','{$getmark}','{$result}','{$d}','{$dt}')";   
       
        if(mysqli_query($con,$sql)){
            echo 1;
        }else{
            echo 0;
        }
    }    
}

if($action == "editprepare"){
    $aid = $_POST["aid"];
    $sql = "select e.*,s.Name as sname    
    from tblexam e,tblsubject s  
    where e.SubjectID=s.AID and e.AID={$aid}";
    
    $res = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $out .= '
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="eaid" value="'.$row["AID"].'"/>
        <div class="modal-body" data-spy="scroll" data-offset="50">
            <div class="form-group">
                <label for="usr"> '.$lang["exam_subname"].'</label>
                <input type="text" class="form-control border-success" name="esubjectname" 
                    value="'.$row["sname"].'" readonly>
            </div>
            <div class="form-group">
                <label for="usr"> '.$lang["exam_pay"].'</label>
                <input type="number" class="form-control border-success" value="100" 
                    value="'.$row["PayMark"].'" required name="epaymark">
            </div>
            <div class="form-group">
                <label for="usr"> '.$lang["exam_get"].'</label>
                <input type="number" class="form-control border-success" 
                    value="'.$row["GetMark"].'" required name="egetmark">
            </div>
            <div class="form-group">
                <label for="usr">'.$lang["exam_result"].'</label>
                <select class="form-control border-success" name="eresult">
                    <option value="'.$row["Result"].'">'.$row["Result"].'</option>
                    '.load_exam_status().'
                </select>
            </div>
            <div class="form-group">
                <label for="usr">'.$lang["exam_d"].'</label>
                <select class="form-control border-success" name="ed">';
                if($row["D"] == 0){
                    $out.='<option value="0" selected>No</option>
                    <option value="1">Yes</option>';
                }else{
                    $out.='<option value="0" >No</option>
                    <option value="1" selected>Yes</option>';
                }
                $out.='
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i>
                '.$lang["btnedit"].'</button>
        </div>
        ';
        echo $out;
    }
}

if($action == 'update'){
    $aid = $_POST['eaid'];
    $paymark = $_POST['epaymark'];
    $getmark = $_POST['egetmark'];
    $result = $_POST['eresult'];
    $d = $_POST['ed'];
    $dt = date("Y-m-d");
    $sql="update tblexam set PayMark='{$paymark}',GetMark='{$getmark}',
    Result='{$result}',D='{$d}',Date='{$dt}' where AID={$aid}";      
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }   
}

if($action == 'delete'){
    $aid = $_POST['aid'];
    $sql = "delete from tblexam where AID={$aid}";
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

// final save
if($action == "save_all"){
    $examtypeid = $_POST["examtype"];
    $totalpay = $_POST["totalpay"];
    $totalget = $_POST["totalget"];
    $vno = date("Ymd-His");
    $chk = "select AID from tblexam_voucher where  
    EARStudentID={$stuid} and GradeID={$gradeid} and ExamTypeID={$examtypeid}";
    if(GetBool($chk)){
        echo 2;
    }else{
        $sql_exchk = "select Name from tblexamtype where AID={$examtypeid}";
        $res_exchk = mysqli_query($con,$sql_exchk);
        if(mysqli_num_rows($res_exchk)>0){
            $row_exchk = mysqli_fetch_array($res_exchk);
            $sql_v = "insert into tblexam_voucher (VNO,LoginID,EARStudentID,EARStudentName,
            EARYearID,EARYearName,GradeID,GradeName,ExamTypeID,ExamTypeName,TotalPayMark,TotalGetMark,Date) 
            values ('{$vno}','{$userid}','{$stuid}','{$stuname}','{$yearid}','{$yearname}',
            '{$gradeid}','{$gradename}','{$examtypeid}','{$row_exchk["Name"]}','{$totalpay}','{$totalget}','{$dt}')";        
            if(mysqli_query($con,$sql_v)){
                $sql_s = "update tblexam set VNO='{$vno}',Chk=1,ExamTypeID='{$examtypeid}', 
                ExamTypeName='{$row_exchk["Name"]}' where LoginID={$userid} 
                and EARStudentID={$stuid} and Chk=0";
                if(mysqli_query($con,$sql_s)){
                    save_log($_SESSION["username"]." သည် EAR Student exam အား အသစ်သွင်းသွားသည်။");
                    echo 1;
                }else{
                    echo 3;
                }
            }else{
                echo 0;
            }
        }
        
    }
}


?>