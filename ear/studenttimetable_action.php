<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];
$dt = date('Y-m-d');
 
if($action == 'show'){          
    $sql="select t.* 
    from tbltime t 
    where t.AID is not null"; 
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out = "";
    $arr_time = [];
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
        <thead>
            <tr>
                <th>Day/Time</th>';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th class="text-center">'.$row["Name"].'</th>';
            }        
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr style="height:100px;">
                    <th class="text-center pb-5">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname    
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m    
                        where t.TeacherID=s.AID and t.SubjectID=b.AID  
                        and t.EARYearID={$yearid} and t.GradeID={$gradeid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID"; 
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td class="text-center" style="cursor:pointer;" 
                                    id="btnedit" 
                                    data-aid="'.$row_t["AID"].'" >
                                <span style="font-size:16px;"><b>'.$row_t["sname"].'</b></span><br>
                                <span class="text-primary" style="font-size:14px;">'.$row_t["bname"].'</span>
                            </td>';
                        }else{
                            $out.='<td style="cursor:pointer;" 
                                id="btnsave" 
                                data-timeid="'.$time.'" 
                                data-dayname="'.$day.'" >
                            </td>';
                        }
                    }
                    
                $out.='</tr>';
            }

        $out.='</thead>
        <tbody>
        ';
        $out.="</tbody>";
        $out.="</table>"; 
        echo $out;
    }else{
        echo"<h3>No Record Found</h3>";
    }

}

if($action == "save_prepare"){
    $timename = GetString("select Name from tbltime where AID='{$_POST["timeid"]}'");
    $timeid = $_POST["timeid"];
    $dayname = $_POST["dayname"];
    $out = "";
    $out.='
    <input type="hidden" name="action" value="save" />
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_academicyear'].' :</label>
                    <input type="text" class="form-control border-success" name="estuname"
                        value="'.$_SESSION['yearname'].'" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_grade'].' :</label>
                    <input type="text" class="form-control border-success" name="estuname"
                        value="'.$_SESSION['gradename'].'" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="usr">Room :</label>
                    <select class="form-control border-success" name="roomid">
                        <option value="">Choose Room</option>
                        '.load_room($gradeid).'
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_teachername'].' :</label>
                    <select class="form-control border-success" name="teacherid" required>
                        <option value="">Choose Teacher</option>
                        '.load_teacher().'
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_subjectname'].' :</label>
                    <select class="form-control border-success" name="subjectid" required>
                        <option value="">Choose Subject</option>
                        '.load_subject_grade($gradeid).'
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_time'].' :</label>
                    <input type="hidden" name="timeid" value="'.$timeid.'" />
                    <input type="text" class="form-control border-success" name="timename"
                        value="'.$timename.'" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="usr"> '.$lang['timetable_dayname'].' :</label>
                    <input type="text" class="form-control border-success" name="dname"
                        value="'.$dayname.'" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="btninsertsave" class="btn btn-'.$color.'"><i class="fas fa-save"></i>
        '.$lang['staff_save'].'</button>
    </div>
    ';
    echo $out;
}

if($action == "save"){
    $teacherid = $_POST["teacherid"];
    $subjectid = $_POST["subjectid"];
    $timeid = $_POST["timeid"]; 
    $roomid = $_POST["roomid"]; 
    $dname = $_POST["dname"];
    $dt = date('Y-m-d');

    $chk = "select AID from tblstudenttimetable where EARYearID='{$yearid}' and 
    TimeID='{$timeid}' and TeacherID='{$teacherid}' and DName='{$dname}'";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        echo 2;
    }else{
        $sql = "insert into tblstudenttimetable (LoginID,EARYearID,GradeID,TimeID,TeacherID,SubjectID,
        DName,Date,RoomID) values ('{$userid}','{$yearid}','{$gradeid}','{$timeid}','{$teacherid}',
        '{$subjectid}','{$dname}','{$dt}','{$roomid}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် student timetable အား save သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == "edit_prepare"){
    $aid = $_POST["aid"];
    $sql = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname,ro.Name as room    
    from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m,tblroom ro     
    where t.TeacherID=s.AID and t.SubjectID=b.AID and t.TimeID=m.AID and 
    t.RoomID=ro.AID and t.AID={$aid}";

    $res = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $out.='
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="eaid" value="'.$row["AID"].'" />
        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_academicyear'].' :</label>
                        <input type="text" class="form-control border-success" name="estuname"
                            value="'.$_SESSION['yearname'].'" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_grade'].' :</label>
                        <input type="text" class="form-control border-success" name="estuname"
                            value="'.$_SESSION['gradename'].'" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="usr">Room :</label>
                        <select class="form-control border-success" name="roomidone">
                            <option value="'.$row["RoomID"].'">'.$row["room"].'</option>
                            '.load_room($gradeid).'
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_teachername'].' :</label>
                        <select class="form-control border-success" name="eteacherid">
                            <option value="'.$row["TeacherID"].'">'.$row["sname"].'</option>
                            '.load_teacher().'
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_subjectname'].' :</label>
                        <select class="form-control border-success" name="esubjectid">
                            <option value="'.$row["SubjectID"].'">'.$row["bname"].'</option>
                            '.load_subject_grade($gradeid).'
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_time'].' :</label>
                        <input type="hidden" name="etimeid" value="'.$row["TimeID"].'" />
                        <input type="text" class="form-control border-success" name="etimename"
                            value="'.$row["mname"].'" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="usr"> '.$lang['timetable_dayname'].' :</label>
                        <input type="text" class="form-control border-success" name="edname"
                            value="'.$row["DName"].'" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btnupdatesave" class="btn btn-'.$color.' btn-sm"><i class="fas fa-edit"></i>
            '.$lang['staff_edit'].'</button>
            <button type="submit" id="btndelete" data-aid="'.$aid.'" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
            '.$lang['staff_delete'].'</button>
        </div>
        ';
    }
    echo $out;
}

if($action == "update"){
    $aid = $_POST["eaid"];
    $teacherid = $_POST["eteacherid"];
    $subjectid = $_POST["esubjectid"];
    $roomid = $_POST["roomidone"];
    $timeid = $_POST["etimeid"];
    $dname = $_POST["edname"];
    $dt = date('Y-m-d');

    $chk = "select AID from tblstudenttimetable where EARYearID='{$yearid}' and 
    TimeID='{$timeid}' and TeacherID='{$teacherid}' and DName='{$dname}' and AID!='{$aid}'";
    $res = mysqli_query($con,$chk);
    if(mysqli_num_rows($res) > 0){
        echo 2;
    }else{
        $sql = "update tblstudenttimetable set TimeID='{$timeid}',TeacherID='{$teacherid}',
        SubjectID='{$subjectid}',DName='{$dname}',Date='{$dt}',RoomID='{$roomid}' where AID={$aid}";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် student timetable အား update သွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }
}

if($action == "delete"){
    $aid = $_POST["aid"];
    $sql = "delete from tblstudenttimetable where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် student timetable အားဖျက်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'excel'){      
    $sql = "select t.* from tbltime t where t.AID is not null"; 
    $result = mysqli_query($con,$sql);
    $out = "";
    $arr_time = [];
    $fileName = "StudentTimetableReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="8" align="center">
                    <h3>('.$_SESSION['yearname'].' - '.$_SESSION['gradename'].') '.$lang['earstu_timetable'].'</h3>
                </td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;height:50px;">Day/Time</th>  
            ';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th style="border: 1px solid ;height:50px;" align="center">'.$row["Name"].'</th>';
            } 
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr>
                    <th style="border: 1px solid ;height:50px;" align="center">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname    
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m    
                        where t.TeacherID=s.AID and t.SubjectID=b.AID  
                        and t.EARYearID={$yearid} and t.GradeID={$gradeid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID"; 
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td style="border: 1px solid ;height:50px;" align="center">
                                '.$row_t["sname"].' <br>
                                <span style="color:blue;">'.$row_t["bname"].'<span>
                            </td>';
                        }else{
                            $out.='<td style="border: 1px solid ;height:50px;" align="center"></td>';
                        }
                    }

                $out.='</tr>';
            }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        echo "No Record Found.";
    }  
}

if($action == 'pdf'){      
    $sql = "select t.* from tbltime t where t.AID is not null"; 
    $result = mysqli_query($con,$sql);
    $out = "";
    $arr_time = [];
    $fileName = "StudentTimetableReports-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '
        <head><meta charset="UTF-8"></head>
        <table >  
            <tr>
                <td colspan="15" align="center">
                    <h3>('.$_SESSION['yearname'].' - '.$_SESSION['gradename'].') '.$lang['earstu_timetable'].'</h3>
                </td>
            </tr>
            <tr><td colspan="8"><td></tr>
            <tr>  
                <th style="border: 1px solid ;height:50px;">Day/Time</th>  
            ';
            while($row = mysqli_fetch_array($result)){
                $arr_time[] = $row['AID'];
                $out.='<th style="border: 1px solid ;height:50px;" align="center">'.$row["Name"].'</th>';
            } 
            $out.='</tr>';

            foreach($arr_day as $day){
                $out.='<tr>
                    <th style="border: 1px solid ;height:50px;" align="center">'.$day.'</th>';

                    foreach($arr_time as $time){
                        $sql_t = "select t.*,s.Name as sname,b.Name as bname,m.Name as mname    
                        from tblstudenttimetable t,tblstaff s,tblsubject b,tbltime m    
                        where t.TeacherID=s.AID and t.SubjectID=b.AID  
                        and t.EARYearID={$yearid} and t.GradeID={$gradeid} and 
                        t.TimeID={$time} and DName='{$day}' and t.TimeID=m.AID"; 
                        $res_t = mysqli_query($con,$sql_t);
                        if(mysqli_num_rows($res_t) > 0){
                            $row_t = mysqli_fetch_array($res_t);
                            $out.='<td style="border: 1px solid ;height:50px;" align="center">
                                '.$row_t["sname"].' <br>
                                <span style="color:blue;">'.$row_t["bname"].'<span>
                            </td>';
                        }else{
                            $out.='<td style="border: 1px solid ;height:50px;" align="center"></td>';
                        }
                    }

                $out.='</tr>';
            }
        $out .= '</table>';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;  
        $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
        $mpdf->WriteHTML($stylesheet,1);  
        $mpdf->WriteHTML($out,2);
        $file = 'StudentTimetablePDF'.date("d_m_Y").'.pdf';
        $mpdf->output($file,'D');
    }else{
        echo "No Record Found.";
    }  
}


?>