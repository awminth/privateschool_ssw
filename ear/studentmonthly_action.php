<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$yid = isset($_SESSION['yearid'])?$_SESSION['yearid']:'';
$yname = isset($_SESSION['yearname'])?$_SESSION['yearname']:'';
$gid = isset($_SESSION['gradeid'])?$_SESSION['gradeid']:'';
$gname = isset($_SESSION['gradename'])?$_SESSION['gradename']:'';
$earid = isset($_SESSION['earid'])?$_SESSION['earid']:'';
$sid = isset($_SESSION['sid'])?$_SESSION['sid']:'';
$sname=  isset($_SESSION['sname'])?$_SESSION['sname']:'';

if($action == 'show'){  
    unset($_SESSION["stu_monthly_aid"]);  
    $out = "";
    $out .= '
    <table class="table table-bordered table-striped table-responsive nowarp">
        <thead>
            <tr>
                <th>လအမည်</th>
                <th>ကျောင်းခေါ်ကြိမ် ၅၇%ပြည့်/မပြည့်</th>
                <th>လူမှုရေးပြစ်ချက်ကင်း၍ကျောင်းစည်းကမ်းလိုက်နာခြင်း</th>
                <th>ကျောင်း၏ဆရာ/ဆရာမဝေယျာဝစ္စ</th>
                <th>သစ်ပင်ပန်းပင်မြက်ခင်းတို့ကိုစိုက်ပျိုးပြုစုခြင်း</th>
                <th>ဒေသ၊နိုင်ငံတော်ဖွံ့ဖြိုးရေးလုပ်ငန်းများလေ့လာမှုနှင့်ပါဝင်မှု</th>
                <th>အများဆိုင်ရာလုပ်ငန်းများတွင်လုပ်အားပါဝင်မှု</th>
                <th>မိဘ၏လုပ်ငန်းတွင်ပါဝင်ကူညီမှု</th>
                <th>အားကစားနှင့်ကိုယ်ကာယလှုပ်ရှားမှု</th>
                <th>အမျိုးသားရေးစိတ်ဓာတ်နှင့်အမျိုးသားရေးခံယူချက်မြင့်မားရေးလုပ်ဆောင်မှု</th>
                <th>စာပေ၊အနုပညာ၊ပန်းချီ၊ဂီတကဏ္ဏများတွင်ပါဝင်မှု</th>
                <th>ကျောင်းမှဖွဲ့စည်းသောအသင်းများ၊လူမှုရေးအသင်းများတွင်ပါဝင်လှုပ်ရှားခြင်း</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    ';   
        $status = "no_data";
        for($i=0; $i<count($arr_month); $i++){           

            $sql = "select * from tblstudentmonthly where 
            EARStudentID='{$sid}' and EARYearID='{$yid}' and GradeID='{$gid}' and 
            MonthName='{$arr_month[$i]}'";        
            $res = mysqli_query($con,$sql) or die("SQL a Query");
            if(mysqli_num_rows($res) > 0){
                $status = "has_data";
                $row = mysqli_fetch_array($res);
                $out .= '
                <tr>
                    <td>'.$arr_month[$i].'</td>
                    <td>'.$row["R1"].'</td>
                    <td>'.$row["R2"].'</td>
                    <td>'.$row["R3"].'</td>
                    <td>'.$row["R4"].'</td>
                    <td>'.$row["R5"].'</td>
                    <td>'.$row["R6"].'</td>
                    <td>'.$row["R7"].'</td>
                    <td>'.$row["R8"].'</td>
                    <td>'.$row["R9"].'</td>
                    <td>'.$row["R10"].'</td>
                    <td>'.$row["R11"].'</td>
                    <td class="text-center">
                        <div class="dropdown dropleft">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h text-primary" style="font-size:22px;cursor:pointer;"></i>
                        </a>
                            <div class="dropdown-menu">
                                <a href="#" id="btnedit" class="dropdown-item"
                                    data-aid="'.$row["AID"].'">
                                    <i class="fas fa-edit text-primary" style="font-size:13px;"></i>
                                    Edit</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" id="btndelete" class="dropdown-item"
                                    data-aid="'.$row["AID"].'" ><i
                                    class="fas fa-trash text-danger"
                                    style="font-size:13px;"></i>
                                    Delete</a>                           
                            </div>
                        </div>
                    </td> 
                </tr>
                ';                               
            }
        } 
        if($status == "no_data"){
            $out .= '
            <tr>
                <td colspan="13" class="text-center">No record.</td>
            </tr>
            ';
        }   
        $out .= '
        </tbody>
    </table>';
    echo $out;
}

if($action == "save"){
    $monthname = $_POST["monthname"];
    $r1 = $_POST["r1"];
    $r2 = $_POST["r2"];
    $r3 = $_POST["r3"];
    $r4 = $_POST["r4"];
    $r5 = $_POST["r5"];
    $r6 = $_POST["r6"];
    $r7 = $_POST["r7"];
    $r8 = $_POST["r8"];
    $r9 = $_POST["r9"];
    $r10 = $_POST["r10"];
    $r11 = $_POST["r11"];
    $dt = date("Y-m-d");
    $chk = "select AID from tblstudentmonthly where 
    EARStudentID='{$sid}' and EARYearID='{$yid}' and GradeID='{$gid}' and 
    MonthName='{$monthname}'";
    if(GetBool($chk)){
        echo 2;
    }else{
        $sql = "insert into tblstudentmonthly (LoginID,EARStudentID,EARYearID,GradeID,
        MonthName,R1,R2,R3,R4,R5,R6,R7,R8,R9,R10,R11,Date) values ('{$userid}','{$sid}',
        '{$yid}','{$gid}','{$monthname}','{$r1}','{$r2}','{$r3}','{$r4}','{$r5}',
        '{$r6}','{$r7}','{$r8}','{$r9}','{$r10}','{$r11}','{$dt}')";
        if(mysqli_query($con,$sql)){
            save_log($_SESSION["username"]." သည် student monthly အားအသစ်သွင်းသွားသည်။");
            echo 1;
        }else{
            echo 0;
        }
    }    
}

if($action == "prepare"){
    $aid = $_POST["aid"];
    $_SESSION["stu_monthly_aid"] = $aid;
    echo 1;
}

if($action == "edit"){
    $aid = $_POST["aid"];
    $monthname = $_POST["monthname"];
    $r1 = $_POST["r1"];
    $r2 = $_POST["r2"];
    $r3 = $_POST["r3"];
    $r4 = $_POST["r4"];
    $r5 = $_POST["r5"];
    $r6 = $_POST["r6"];
    $r7 = $_POST["r7"];
    $r8 = $_POST["r8"];
    $r9 = $_POST["r9"];
    $r10 = $_POST["r10"];
    $r11 = $_POST["r11"];
    $dt = date("Y-m-d");
    $sql = "update tblstudentmonthly set MonthName='{$monthname}',R1='{$r1}',R2='{$r2}',
    R3='{$r3}',R4='{$r4}',R5='{$r5}',R6='{$r6}',R7='{$r7}',R8='{$r8}',R9='{$r9}',
    R10='{$r10}',R11='{$r11}',Date='{$dt}' where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် student monthly အား edit သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "delete"){
    $aid = $_POST["aid"];
    $sql = "delete from tblstudentmonthly where AID={$aid}";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["username"]." သည် student monthly အား delete သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == "excel"){
    $out = "";
    $fileName = "StudentMonthlyReport_".date('d_m_Y').".xls";
    $out .= '<head><meta charset="utf-8"></head>
    <table >  
        <tr>
            <td colspan="12" align="center"><h3>လအလိုက် ကျောင်းလုပ်ငန်းစဉ်များတွင် ပါဝင်ဆောင်ရွက်မှုအကဲဖြတ်မှတ်တမ်း</h3></td>
        </tr>
        <tr><td colspan="12"><td></tr>
        <tr>  
            <th style="border: 1px solid ;">လအမည်</th>
            <th style="border: 1px solid ;">ကျောင်းခေါ်ကြိမ် ၅၇%ပြည့်/မပြည့်</th>
            <th style="border: 1px solid ;">လူမှုရေးပြစ်ချက်ကင်း၍ကျောင်းစည်းကမ်းလိုက်နာခြင်း</th>
            <th style="border: 1px solid ;">ကျောင်း၏ဆရာ/ဆရာမဝေယျာဝစ္စ</th>
            <th style="border: 1px solid ;">သစ်ပင်ပန်းပင်မြက်ခင်းတို့ကိုစိုက်ပျိုးပြုစုခြင်း</th>
            <th style="border: 1px solid ;">ဒေသ၊နိုင်ငံတော်ဖွံ့ဖြိုးရေးလုပ်ငန်းများလေ့လာမှုနှင့်ပါဝင်မှု</th>
            <th style="border: 1px solid ;">အများဆိုင်ရာလုပ်ငန်းများတွင်လုပ်အားပါဝင်မှု</th>
            <th style="border: 1px solid ;">မိဘ၏လုပ်ငန်းတွင်ပါဝင်ကူညီမှု</th>
            <th style="border: 1px solid ;">အားကစားနှင့်ကိုယ်ကာယလှုပ်ရှားမှု</th>
            <th style="border: 1px solid ;">အမျိုးသားရေးစိတ်ဓာတ်နှင့်အမျိုးသားရေးခံယူချက်မြင့်မားရေးလုပ်ဆောင်မှု</th>
            <th style="border: 1px solid ;">စာပေ၊အနုပညာ၊ပန်းချီ၊ဂီတကဏ္ဏများတွင်ပါဝင်မှု</th>
            <th style="border: 1px solid ;">ကျောင်းမှဖွဲ့စည်းသောအသင်းများ၊လူမှုရေးအသင်းများတွင်ပါဝင်လှုပ်ရှားခြင်း</th> 
            <th style="border: 1px solid ;">အတန်းပိုင်လက်မှတ်နှင့်ရက်စွဲ</th>
            <th style="border: 1px solid ;">မိဘအုပ်ထိန်းသူလက်မှတ်နှင့်ရက်စွဲ</th>
            <th style="border: 1px solid ;">ကျောင်းအုပ်ကြီးလက်မှတ်နှင့်ရက်စွဲ</th>    
        </tr>';
    $status = "no_data";
    for($i=0; $i<count($arr_month); $i++){           

        $sql = "select * from tblstudentmonthly where 
        EARStudentID='{$sid}' and EARYearID='{$yid}' and GradeID='{$gid}' and 
        MonthName='{$arr_month[$i]}'";        
        $res = mysqli_query($con,$sql) or die("SQL a Query");
        if(mysqli_num_rows($res) > 0){
            $status = "has_data";
            $row = mysqli_fetch_array($res);
            $out .= '
            <tr>
                <td style="border: 1px solid ;" align="center">'.$arr_month[$i].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R1"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R2"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R3"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R4"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R5"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R6"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R7"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R8"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R9"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R10"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R11"].'</td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
            </tr>            
            ';                               
        }
    } 
    if($status == "no_data"){
        $out .= '
        <tr>
            <td colspan="12"  style="border: 1px solid ;" align="center">No record.</td>
        </tr>
        ';
    }else{
        $out .= '
        <tr>
            <td style="border: 1px solid ;" align="center">ကျောင်းခေါ်ကြိမ်ပေါင်း/အဆင့်</td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid ;" align="center">စုစုပေါင်းရမှတ်/ပျမ်းမျှအဆင့်</td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
        </tr>
        ';
    }
    $out .= '</table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
}

if($action == "pdf"){
    $out = "";
    $fileName = "StudentMonthlyReport_".date('d_m_Y').".xls";
    $out .= '<head><meta charset="utf-8"></head>
    <table >  
        <tr>
            <td colspan="12" align="center"><h3>လအလိုက် ကျောင်းလုပ်ငန်းစဉ်များတွင် ပါဝင်ဆောင်ရွက်မှုအကဲဖြတ်မှတ်တမ်း</h3></td>
        </tr>
        <tr><td colspan="12"><td></tr>
        <tr>  
            <th style="border: 1px solid ;">လအမည်</th>
            <th style="border: 1px solid ;">ကျောင်းခေါ်ကြိမ် ၅၇%ပြည့်/မပြည့်</th>
            <th style="border: 1px solid ;">လူမှုရေးပြစ်ချက်ကင်း၍ကျောင်းစည်းကမ်းလိုက်နာခြင်း</th>
            <th style="border: 1px solid ;">ကျောင်း၏ဆရာ/ဆရာမဝေယျာဝစ္စ</th>
            <th style="border: 1px solid ;">သစ်ပင်ပန်းပင်မြက်ခင်းတို့ကိုစိုက်ပျိုးပြုစုခြင်း</th>
            <th style="border: 1px solid ;">ဒေသ၊နိုင်ငံတော်ဖွံ့ဖြိုးရေးလုပ်ငန်းများလေ့လာမှုနှင့်ပါဝင်မှု</th>
            <th style="border: 1px solid ;">အများဆိုင်ရာလုပ်ငန်းများတွင်လုပ်အားပါဝင်မှု</th>
            <th style="border: 1px solid ;">မိဘ၏လုပ်ငန်းတွင်ပါဝင်ကူညီမှု</th>
            <th style="border: 1px solid ;">အားကစားနှင့်ကိုယ်ကာယလှုပ်ရှားမှု</th>
            <th style="border: 1px solid ;">အမျိုးသားရေးစိတ်ဓာတ်နှင့်အမျိုးသားရေးခံယူချက်မြင့်မားရေးလုပ်ဆောင်မှု</th>
            <th style="border: 1px solid ;">စာပေ၊အနုပညာ၊ပန်းချီ၊ဂီတကဏ္ဏများတွင်ပါဝင်မှု</th>
            <th style="border: 1px solid ;">ကျောင်းမှဖွဲ့စည်းသောအသင်းများ၊လူမှုရေးအသင်းများတွင်ပါဝင်လှုပ်ရှားခြင်း</th> 
            <th style="border: 1px solid ;">အတန်းပိုင်လက်မှတ်နှင့်ရက်စွဲ</th>
            <th style="border: 1px solid ;">မိဘအုပ်ထိန်းသူလက်မှတ်နှင့်ရက်စွဲ</th>
            <th style="border: 1px solid ;">ကျောင်းအုပ်ကြီးလက်မှတ်နှင့်ရက်စွဲ</th>    
        </tr>';
    $status = "no_data";
    for($i=0; $i<count($arr_month); $i++){           

        $sql = "select * from tblstudentmonthly where 
        EARStudentID='{$sid}' and EARYearID='{$yid}' and GradeID='{$gid}' and 
        MonthName='{$arr_month[$i]}'";        
        $res = mysqli_query($con,$sql) or die("SQL a Query");
        if(mysqli_num_rows($res) > 0){
            $status = "has_data";
            $row = mysqli_fetch_array($res);
            $out .= '
            <tr>
                <td style="border: 1px solid ;" align="center">'.$arr_month[$i].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R1"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R2"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R3"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R4"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R5"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R6"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R7"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R8"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R9"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R10"].'</td>
                <td style="border: 1px solid ;" align="center">'.$row["R11"].'</td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
                <td style="border: 1px solid ;"></td>
            </tr>            
            ';                               
        }
    } 
    if($status == "no_data"){
        $out .= '
        <tr>
            <td colspan="12"  style="border: 1px solid ;" align="center">No record.</td>
        </tr>
        ';
    }else{
        $out .= '
        <tr>
            <td style="border: 1px solid ;" align="center">ကျောင်းခေါ်ကြိမ်ပေါင်း/အဆင့်</td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid ;" align="center">စုစုပေါင်းရမှတ်/ပျမ်းမျှအဆင့်</td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
            <td style="border: 1px solid ;"></td>
        </tr>
        ';
    }
    $out .= '</table>';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = 'StudentMonthlyPDF'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
}




?>