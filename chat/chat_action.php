<?php
include('../config.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'status'){  
    $out = "";
    $sql = "select * from tblparent 
    where AID is not null";
    $res = mysqli_query($con,$sql);
    $out .= '
    <tr>
        <th colspan="2" class="text-center text-success">Active User</th>
    </tr>';
    if(mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_array($res)){
            $url = roothtml.'chat/chat.php?toUser='.$row["AID"];
            $chk = "text-success";
            if($row["Status"] == 0){
                $chk = "text-danger";
            }
            // $cnt = GetString("select count(AID) from tblchat where FromUser={$row['AID']} 
            // and FromUser!={$userid} and View='No Seen'");
            $cnt = GetString("select count(AID) from tblchat where ToUser={$row['AID']} 
            and MessageType='sender' and View='No Seen'");
            $out.='
            <tr id="btnview" data-aid="'.$row['AID'].'" style="cursor:pointer">
                <td><a href="#">
                    <span><i class="fas fa-circle '.$chk.'"></i>&nbsp;'.$row["UserName"].'</span>
                    </a>
                </td>
                <td class="text-right">';
                if($cnt != 0){
                    $out.='<span class="badge badge-primary badge-pill">'.$cnt.'</span>';
                }
                $out.='</td>                
            </tr>
            ';
        }
    }
    echo $out;
}

if($action == 'one'){
    $fromUser = $userid; //sender
    $message = $_POST["message"];
    $toUser = $_POST["toUser"]; //receiver
    $dt = date("Y-m-d H:i:s");
    $output = "";      
    // $sql = "insert into tblchat (FromUser,ToUser,Message,Date,LoginID) values 
    // ({$fromUser},{$toUser},'{$message}','{$dt}',{$userid})";
    $sql = 'insert into tblchat (FromUser,ToUser,Message,Date,LoginID) values 
    ("'.$toUser.'","'.$fromUser.'","'.$message.'","'.$dt.'","'.$userid.'")';
    if(mysqli_query($con,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'two'){
    $fromUser = $userid;
    $toUser = $_POST["toUser"]; 
    $output = "";
    // $sql = "select * from (select * from tblchat 
    // where AID is not null and (FromUser='{$fromUser}' and ToUser='{$toUser}') or 
    // (FromUser='{$toUser}' and ToUser='{$fromUser}') order by AID Desc limit 50) var1 
    // order by AID";
    $sql = "select * from (select * from tblchat 
    where AID is not null and (ToUser='{$toUser}' and MessageType='sender') or 
    (FromUser='{$toUser}' and MessageType='receiver') order by AID Desc limit 50) var1 
    order by AID desc";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        while( $chat = mysqli_fetch_array($res)){
            $url = roothtml.'upload/noimage.png';
            if($chat["MessageType"] == "receiver"){
                $nn = "Admin";
                $output.= "<div style='text-align:right;'>
                                <div style='padding:5px 10px;border-radius:5px;margin-bottom:8px;background-color:gainsboro;'>
                                    <p style='color:blue;margin:0;' >".$chat["Message"]." </p>
                                    <img class='img-circle' src='{$url}' width='25px;'>
                                    <span style='font-size:12px;color:black;'><em> {$nn} </em></span><br>
                                    <span style='font-size:10px;'>".$chat["Date"]."</span>                            
                                </div>
                            </div> ";
            }else{
                $cusname = GetString("select UserName from tblparent where AID={$toUser}");
                $output.= "<div style='text-align:left'>
                                <div style='padding:5px 10px;border-radius:5px;margin-bottom:8px;background-color:gainsboro;'>
                                    <p style='color:royalblue;margin:0;' >".$chat["Message"]."  </p>
                                    <img class='img-circle' src='{$url}' width='25px;'>
                                    <span style='font-size:12px;color:black;'><em>".$cusname."</em></span><br>
                                    <span style='font-size:10px;'>".$chat["Date"]."</span>                                
                                </div>
                            </div>" ;
            }
        }
    }                               
    echo $output;
}

if($action == 'view'){
    $fromuser = $_POST['aid'];
    // $toUser = $userid;
    // $sql = "update tblchat set View='Seen' 
    // where FromUser={$fromuser} and ToUser={$toUser}";
    $sql = "update tblchat set View='Seen' 
    where ToUser={$fromuser}";
    if(mysqli_query($con,$sql)){
       echo $fromuser;
    }
}

if($action == 'show_all'){  
    $toUser = $_POST["toaid"];
    $dtto = $_POST['dtto'];
    $dtfrom = $_POST['dtfrom'];
    $output = "";
    $a = "";
    if($dtfrom!='' || $dtto!=''){
        $a = " and Date(Date)>='{$dtfrom}' and Date(Date)<='{$dtto}' ";
    }
    $sql = "select * from (select * from tblchat 
    where AID is not null and (ToUser='{$toUser}' and MessageType='sender') or 
    (FromUser='{$toUser}' and MessageType='receiver') order by AID Desc limit 50) var1 
    order by AID desc";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        while( $chat = mysqli_fetch_array($res)){
            $url = roothtml.'upload/noimage.png';
            if($chat["MessageType"] == "receiver"){
                $nn = "Admin";
                $output.= "<div style='text-align:right;'>
                                <div style='padding:5px 10px;border-radius:5px;margin-bottom:8px;background-color:gainsboro;'>
                                    <p style='color:blue;margin:0;' >".$chat["Message"]." </p>
                                    <img class='img-circle' src='{$url}' width='25px;'>
                                    <span style='font-size:12px;color:black;'><em> {$nn} </em></span><br>
                                    <span style='font-size:10px;'>".$chat["Date"]."</span>                            
                                </div>
                            </div> ";
            }else{
                $cusname = GetString("select UserName from tblparent where AID={$toUser}");
                $output.= "<div style='text-align:left'>
                                <div style='padding:5px 10px;border-radius:5px;margin-bottom:8px;background-color:gainsboro;'>
                                    <p style='color:royalblue;margin:0;' >".$chat["Message"]."  </p>
                                    <img class='img-circle' src='{$url}' width='25px;'>
                                    <span style='font-size:12px;color:black;'><em>".$cusname."</em></span><br>
                                    <span style='font-size:10px;'>".$chat["Date"]."</span>                                
                                </div>
                            </div>" ;
            }
        }
        echo $output;
    }else{
        $output = "<h3 class='text-center text-danger'>No Record Found</h3>";
        echo $output;
    }

}


?>