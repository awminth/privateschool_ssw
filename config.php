<?php 

session_start();

date_default_timezone_set("Asia/Rangoon");

define('server_name',$_SERVER['HTTP_HOST']);

if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
    $chk_link = "https";
}else{
    $chk_link = "http";
}

define('root',__DIR__.'/');

//Local
define('roothtml',$chk_link."://".server_name."/privateschoolsunmoonlight/");
$con=new mysqli("localhost","root","root","privateschoolssw");

define('curlink',basename($_SERVER['SCRIPT_NAME']));

//Online
//define('roothtml',$chk_link."://".server_name."/");
//$con=new mysqli("108.178.44.242","kfskyur_admin","kyoungunity*007*","kfskyur_khingfamily");

mysqli_set_charset($con,"utf8");

$color="secondary";
$pay=array('K Pay','Wave Pay','KBZ','AYA','CB');
$statussalary=array('Bonus','Cut');

$arr_gender = array('Male','Female');
$arr_national = array('ဗမာ','ကရင်','ချင်း','မွန်','ရခိုင်','ရှမ်း');
$arr_religion = array('ဗုဒ္ဓဘာသာ','ခရစ်ယာန်','ဟိနျူ','အခြား');
$exam_status = array('အောင်','ကျ');
$arr_day = array('SUN','MON','TUE','WED','THU','FRI','SAT');
$arr_day1 = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
$arr_month = array('ဇန်နဝါရီလ','ဖေဖော်ဝါရီလ','မတ်လ','ဧပြီလ','မေလ','ဇွန်လ','ဇူလိုင်လ','ဩဂုတ်လ','စက်တင်ဘာလ','အောက်တိုဘာလ','နိုဝင်ဘာလ','ဒီဇင်ဘာလ');
$arr_mark = array('A','B','C','D');
$arr_montheng = array('January','February','March','April','May','June','July','August','September','October','November','December');
$arr_attachment = array('Birth Certificate',"Parents NRC");

if(isset($_SESSION['la'])){
    switch($_SESSION['la']){
          case "en":
                include(root.'lang/en.php');		
          break;
          case "my":
                include(root.'lang/my.php');				
          break;	
          case "china":
                include(root.'lang/china.php');				
          break;
          default: 
                include(root.'lang/en.php');			
          }      
         
}else{
    include(root.'lang/en.php');  
}

function load_earyear(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblearyear order by AID desc ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_uniformcategory(){
    global $con;
    $sql="select * from tblcategoryuniform";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_subjectgrade($gradeid){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblsubject where GradeID={$gradeid}";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_subject(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblsubject ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}


function load_parent(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblparent order by AID desc";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_subject_grade($gradeid){
    global $con;
    $gid = $gradeid;
    $sql="select * from tblsubject where GradeID={$gid}";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_time(){
    global $con;
    $sql="select * from tbltime";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function enDate1($date){
    if($date!=NULL && $date!=''){
        $date = date_create($date);
        $date = date_format($date,"j F Y");
        return $date;
    }else{
        return "";
    }   
}

function enDate2($date){
    if($date!=NULL && $date!=''){
        $date = date_create($date);
        $date = date_format($date,"F Y");
        return $date;
    }else{
        return "";
    }   
}

function enDate3($date){
    if($date!=NULL && $date!=''){
        $date = date_create($date);
        $date = date_format($date,"M - Y");
        return $date;
    }else{
        return "";
    }   
}

function load_pay()
{
    global $pay;
    $out="";
    foreach($pay as $row){
        $out.="<option value='{$row}'>{$row}</option>";
    }
    return $out;
}

function load_month()
{
    global $arr_month;
    $out="";
    foreach($arr_month as $row){
        $out.="<option value='{$row}'>{$row}</option>";
    }
    return $out;
}

function load_attach()
{
    global $arr_attachment;
    $out="";
    foreach($arr_attachment as $row){
        $out.="<option value='{$row}'>{$row}</option>";
    }
    return $out;
}

function load_exam_status()
{
    global $exam_status;
    $out="";
    foreach($exam_status as $row){
        $out.="<option value='{$row}'>{$row}</option>";
    }
    return $out;
}

function load_status()
{
    global $statussalary;
    $out="";
    foreach($statussalary as $row){
        $out.="<option value='{$row}'>{$row}</option>";
    }
    return $out;
}


function savecms($vno,$cash,$mobile,$paydes,$paystatus,$status){
    global $con;
    $userid=$_SESSION['userid'];
    $date=date('Y-m-d');

    $sql1="select VNO from tblcms where VNO='{$vno}'";
    $result=mysqli_query($con,$sql1) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $sqldel="delete from tblcms where VNO='{$vno}'";
        mysqli_query($con,$sqldel);
    }

    if($cash>0){
        $sql="insert into tblcms (VNO,LoginID,Amt,PayDescription,PayStatus,Date,Status) values 
        ('{$vno}',{$userid},{$cash},'cash','{$paystatus}','{$date}',{$status})";
        mysqli_query($con,$sql);  
    }

    if($mobile>0){
        $sql="insert into tblcms (VNO,LoginID,Amt,PayDescription,PayStatus,Date,Status) values 
        ('{$vno}',{$userid},{$mobile},'{$paydes}','{$paystatus}','{$date}',{$status})";
        mysqli_query($con,$sql);  
    }

     

}

function deletecms($vno){
    global $con;    
    $sqldel="delete from tblcms where VNO='{$vno}'";
    mysqli_query($con,$sqldel);

}

function GetString($sql){
    global $con;
    $str="";   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
       $str= $row[0];
    }

    return $str;

}

function GetInt($sql){
    global $con;
    $str=0;   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_array($result);
       $str= $row[0];
    }

    return $str;

}

function GetBool($sql){
    global $con;
    $str = false;   
    $result=mysqli_query($con,$sql) or die("Query Fail");
    if(mysqli_num_rows($result)>0){
        $str = true;
    }
    return $str;
}

function enDate($date){
    if($date!=NULL && $date!=''){
        $date = date_create($date);
        $date = date_format($date,"d-m-Y");
        return $date;
    }else{
        return "";
    }
   
}

function load_grade(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblgrade ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_room($gradeid){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblroom where GradeID={$gradeid}";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_religion(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblreligion ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_national(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblnational ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_paytype(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblpaytypecategory ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_paytypestudent($gradeid){
    global $con;
    $sql="select p.AID,c.Name from tblpaytype p,tblpaytypecategory c where 
    p.PayTypeID=c.AID and p.GradeID={$gradeid}";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_expensecategory(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblexpensecategory ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_examtype(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblexamtype ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_student(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblstudentprofile";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}


function load_student_app(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select s.* from tblstudentprofile s,tblparent_student p where p.StudentID=s.AID 
     ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_teacher(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblstaff where Status=0 ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_staff(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblstaff where Status=1 ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_bonus(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblbonus ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_cut(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tblcut ";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Name"]}</option>";
    }
    return $out;
}

function load_learningtitle(){
    global $con;
    $loginid= $_SESSION["userid"];
    $sql="select * from tbllearningtitle";
    $result=mysqli_query($con,$sql) or die("Query fail.");
    $out="";
    while($row = mysqli_fetch_array($result)){
        $out.="<option value='{$row["AID"]}'>{$row["Description"]}</option>";
    }
    return $out;
}

function save_log($des){
    global $con;
    $dt=date("Y-m-d H:i:s");
    $userid=$_SESSION['userid'];
    $userid=$_SESSION['userid'];
    $sql="insert into tbllog (Description,UserAID,Date,LoginID) values ('{$des}'
    ,$userid,'{$dt}',{$userid})";
    mysqli_query($con,$sql);   
}

function custom_calendar($dt){
    $ym = date('Y-m');
    if($dt != ""){
        $ym = $dt;
    }

    // Check format
    $timestamp = strtotime($ym . '-01');
    if ($timestamp === false) {
        $ym = date('Y-m');
        $timestamp = strtotime($ym . '-01');
    }

    // Today
    $today = date('Y-m-j', time());  

    // Number of days in the month
    $day_count = date('t', $timestamp);
 
    // 0:Sun 1:Mon 2:Tue ...
    $str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
    //$str = date('w', $timestamp);

    // Create Calendar!!
    $weeks = array();
    $week = '';

    // create Add empty cell
    $week .= str_repeat('<td class="td-height"></td>', $str);
    // userid
    $userid = $_SESSION['userid'];

    for ( $day = 1; $day <= $day_count; $day++, $str++) {     
        $date = $ym . '-' . $day;
        // search event count from tbltodolist 
        $txt = '';
        $sql = "select count(AID) as cnt from tbltodolist 
        where Date(StartEvent)='{$date}'";
        $res = GetInt($sql);
        if($res > 0){
            $txt = '<br><br><span class="badge badge-primary text-center">'.$res.'&nbsp;Events</span>';
        }
     
        if ($today == $date) {
            $week .= '<td class="td-height today" id="btnevent" data-dt="'.$date.'">'.$day.$txt.'</td>';
        } else {
            $week .= '<td class="td-height" id="btnevent" data-dt="'.$date.'">'.$day.$txt.'</td>';
        }
     
        // End of the week OR End of the month
        if ($str % 7 == 6 || $day == $day_count) {

            if ($day == $day_count) {
                // Add empty cell
                $week .= str_repeat('<td class="td-height"></td>', 6 - ($str % 7));
            }

            $weeks[] = '<tr>'.$week.'</tr>';

            // Prepare for new week
            $week = '';
        }
    }

    // show data
    foreach ($weeks as $week) {
        echo $week;
    }
}

function NumtoText($number){
    $array = [
        '1' => 'First',
        '2' => 'Second',
        '3' => 'Third',
        '4' => 'Four',
        '5' => 'Five',
        '6' => 'Six',
        '7' => 'Seven',
        '8' => 'Eight',
        '9' => 'Nine',
        '10' => 'Ten',
    ];
    return strtr($number, $array);
}


function toMyanmar($number){
    $array = [
        '0' => '၀',
        '1' => '၁',
        '2' => '၂',
        '3' => '၃',
        '4' => '၄',
        '5' => '၅',
        '6' => '၆',
        '7' => '၇',
        '8' => '၈',
        '9' => '၉',
    ];
    return strtr($number, $array);
}


function toEnglish($number){
    $array = [
        '၀' => '0',
        '၁' => '1',
        '၂' => '2',
        '၃' => '3',
        '၄' => '4',
        '၅' => '5',
        '၆' => '6',
        '၇' => '7',
        '၈' => '8',
        '၉' => '9',
    ];
    return strtr($number, $array);
}



?>