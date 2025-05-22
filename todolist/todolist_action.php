<?php
include('../config.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == "show"){
    $dt = $_POST["dt"];
    $sql = "select *,Date(StartEvent) as dt from tbltodolist 
    where Date(StartEvent)='{$dt}'";
    $res = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($res) > 0){
        $out .= '<h5 class="text-center text-primary">'.enDate1($dt).'</h5>
        <table class="table">';
        while($row = mysqli_fetch_array($res)){
            $out .= '
            <tr>
                <td>'.$row["Title"].'</td>
                <td width="35%" class="text-right">
                    <a href="#" id="btnedit" 
                        data-aid="'.$row['AID'].'" 
                        data-dt="'.$row['dt'].'" 
                        data-title="'.$row['Title'].'" 
                        class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    <a href="#" id="btndelete" 
                        data-aid="'.$row['AID'].'" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            ';
        }
        $out .= '</table>';
        echo $out;
    }else{
        $out .= '
            <h5 class="text-center text-primary">'.enDate1($dt).'</h5>
            <p class="text-center">No event found.</p>
        ';
        echo $out;
    }
}

if($action == "save_event"){
    $dt = $_POST["dt"];
    $title = $_POST["title"];
    $userid = $_SESSION['userid'];
    $query = 'insert into tbltodolist (Title,StartEvent,EndEvent,LoginID,UserID) 
    values ("'.$title.'","'.$dt.'","'.$dt.'","'.$userid.'","'.$userid.'")';
    if(mysqli_query($con,$query)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "edit"){
    $title = $_POST['eevent_title'];
    $aid = $_POST['eaid'];
    $query = 'update tbltodolist set Title="'.$title.'" where AID="'.$aid.'"';
    if(mysqli_query($con,$query)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "delete"){
    $aid = $_POST['aid'];
    $query = "delete from tbltodolist where AID='{$aid}'";
    if(mysqli_query($con,$query)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "show_calendar"){
    $dt = $_POST["dt"];
    // load show function for calendar
    custom_calendar($dt);
}

if($action == 'today_dt'){
    $data = date("Y-m");
    echo $data;
}

if($action == 'left_dt'){
    $dt = $_POST["dt"];
    $data = date('Y-m', strtotime($dt. ' - 1 months')); 
    echo $data;
}

if($action == 'right_dt'){
    $dt = $_POST["dt"];
    $data = date('Y-m', strtotime($dt. ' + 1 months')); 
    echo $data;
}


?>