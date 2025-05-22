<?php
include('../config.php');
$action = $_POST["action"];
$userid = $_SESSION['userid'];

if($action == 'sitetitle'){
    $sitetitle = $_POST["sitetitle"];
    $sitephno = $_POST["sitephno"];
    $siteemail = $_POST["siteemail"];
    $siteaddress = $_POST["siteaddress"];
    $_SESSION['sitetitle'] = $sitetitle;
    $sql = 'update tblsetting set SchoolName="'.$sitetitle.'",
    SchoolAddress="'.$siteaddress.'",PhoneNo="'.$sitephno.'",Email="'.$siteemail.'" ';
    if(mysqli_query($con,$sql)){
        $_SESSION["shopname"] = $sitetitle;
        $_SESSION["shopaddress"] = $siteaddress;                  
        $_SESSION["shopphno"] = $sitephno;
        $_SESSION["shopemail"] = $siteemail;

        save_log($_SESSION["username"]." သည် general အား edit သွားသည်။");
        echo 1;    
    }else{
        echo 0;
    } 
}

if($action == 'sitelogo'){
    if($_FILES['logofile']['name'] != ''){
        $filename = $_FILES['logofile']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['logofile']['tmp_name'];
        $valid_extension = array("png","jpeg","jpg","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "update tblsetting set SiteLogo='{$new_filename}'";
                if(mysqli_query($con,$sql)){                       
                    $_SESSION["shoplogo"] = roothtml.'upload/'.$new_filename; 
                    save_log($_SESSION["username"]." သည် logo အား edit သွားသည်။");                                 
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }else{
            echo 2;
        }
    }else{
        echo 3;
    }
    
}

if($action == 'siteicon'){
    if($_FILES['iconfile']['name'] != ''){
        $filename = $_FILES['iconfile']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['iconfile']['tmp_name'];
        $valid_extension = array("png","jpeg","jpg","JPG","PNG","JPEG");
        if(in_array($extension,$valid_extension)){
            $new_filename = date("YmdHis").".". $extension;
            $new_path = root."upload/". $new_filename;

            if(move_uploaded_file($file,$new_path)){
                $sql = "update tblsetting set SiteIcon='{$new_filename}'";
                if(mysqli_query($con,$sql)){                       
                    $_SESSION["shopicon"] = roothtml.'upload/'.$new_filename; 
                    save_log($_SESSION["username"]." သည် icon အား edit သွားသည်။");                                 
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }else{
            echo 2;
        }
    }else{
        echo 3;
    }    
}


?>