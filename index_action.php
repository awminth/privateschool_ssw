<?php 

include("config.php");

$action=$_POST["action"];

if($action=="login"){

      $username=$_POST["username"];
      $password=$_POST["password"];     

      $sql="select * from tbllogin where UserName='{$username}' and Password='{$password}'";
      $result = mysqli_query($con,$sql);     
    

      if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_array($result);
            $_SESSION["userid"] = $row['AID'];
            $_SESSION["username"] = $row['UserName'];                  
            $_SESSION["usertype"] = $row['UserType'];
            $_SESSION["userpassword"] = $row['Password'];  
            
            $sql_setting="select * from tblsetting";
            $result1 = mysqli_query($con,$sql_setting); 
            $row1 = mysqli_fetch_array($result1); 

            $_SESSION["shopname"] = $row1['SchoolName'];
            $_SESSION["shopaddress"] = $row1['SchoolAddress'];                  
            $_SESSION["shopphno"] = $row1['PhoneNo'];
            $_SESSION["shopemail"] = $row1['Email'];
            $_SESSION["shoplogo"] = roothtml.'upload/noimage.png';
            $_SESSION["shopicon"] = roothtml.'upload/noimage.png';
            $_SESSION["examlogo"] = roothtml.'lib/images/zzzshwesarwine.png';
            if($row1['SiteLogo'] != "" || $row1['SiteLogo'] != NULL){ 
                  $_SESSION["shoplogo"] = roothtml.'upload/'.$row1['SiteLogo']; 
            }
            if($row1['SiteIcon'] != "" || $row1['SiteIcon'] != NULL){ 
                  $_SESSION["shopicon"] = roothtml.'upload/'.$row1['SiteLogo']; 
            }
            
            save_log($row['UserName']." Login ဝင်သွားသည်");

                   //remember username and password
            if(!empty($_POST['remember'])){
                  setcookie("member_login",$row['UserName'],time()+(10*365*24*60*60));
                  setcookie("member_password",$row['Password'],time()+(10*365*24*60*60));
            }
            else{
                  if(isset($_COOKIE['member_login'])){
                        setcookie("member_login",'');
                  }
                  if(isset($_COOKIE['member_password'])){
                        setcookie("member_password",'');
                  }
            }

                  echo 1;

      }else{

            session_unset();
            echo $sql;

      }


}

if($action=="logout"){   
 

    save_log($_SESSION['username']."Logout လုပ်သွားသည်");
      session_unset();
      echo 1;

}

if($action == 'language'){
      $la = $_POST["data"];
      if($la == 'my'){
            $_SESSION['la'] = 'my';
      }else{
            $_SESSION['la'] = 'en';
      }
      echo 1;     
}





?>