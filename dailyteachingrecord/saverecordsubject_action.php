<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeidsession = $_SESSION['gradeid'];

if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $out = "";
    $sql = "select r.*,t.Name as time,s.Name as sname from 
    tblteachingrecord r,tbltime t,tblstaff s where 
    r.TimeID=t.AID and r.TeacherID=s.AID and s.Status=0 and r.TeachingCategoryID='{$aid}'";
    $result = mysqli_query($con,$sql);
    if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_array($result);
        $out.="<div class='modal-body'>
            <input type='hidden' name='aid' value='{$aid}'/>
            <div class='form-group'>
                <label for='usr'> Teacher Name :</label>
                <select class='form-control border-success select2' name='teacherid'>
                    <option value='".$row["TeacherID"]."'>{$row["sname"]}</option>
                    ".load_teacher()."
                </select>
            </div>
            <div class='form-group'>
                <label for='usr'> Choose Date :</label>
                <input type='date' class='form-control border-success' name='dt' value = '".$row["Date"]."'
            </div>
            <div class='form-group'>
                <label for='usr'> Choose Time :</label>
                <select class='form-control border-success select2' name='time'>
                    <option value='".$row["TimeID"]."'>{$row["time"]}</option>
                    ".load_time()."
                </select>
            </div>
            <div class='form-group'>
                <label for='usr'> Remark :</label>
                <input type='text' class='form-control border-success' name='rmk' value='{$row["Remark"]}'>
            </div>                               
        </div>
        <div class='modal-footer'>
            <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>Edit Record</button>
        </div>";
    }
    
    
        echo $out;
}

if($action == 'update'){
    $aid = $_POST["aid"];
    $teacherid = $_POST["teacherid"];
    $dt = $_POST["dt"];
    $timeid = $_POST["timeid"];
    $rmk = $_POST["rmk"];
    $sql = "update tblteachingrecord set TimeID='{$timeid}',
    TeacherID='{$teacherid}',Date='{$dt}',Remark='{$rmk}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]."သည် Teaching Record အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}

if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblteachingrecord where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် Save Record အားဖျက်သွားသည်။");
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



?>