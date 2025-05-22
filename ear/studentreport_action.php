<?php
include('../config.php');
include(root.'lib/vendor/autoload.php');

$action = $_POST["action"];
$userid = $_SESSION['userid'];
$gradeid = $_SESSION['gradeid'];
$gradename = $_SESSION['gradename'];
$yearid = $_SESSION['yearid'];
$yearname = $_SESSION['yearname'];

if($action == 'student_pdf'){
    $out = '
    <html>
        <head>
            <meta http-equiv=Content-Type content="text/html; charset=utf-8">                
        </head>
        <body>
            <h2 style="text-align:center">'.$yearname.' ပညာသင်နှစ် ကျောင်းသားများစာရင်း</h2>            
    '; 
    $out.='
    <div style="overflow-x:auto;">
        <table>
            <tr>
                <th class="bd" rowspan="2" align="center">စဉ်</th>
                <th class="bd" rowspan="2" align="center">အတန်း</th>
                <th class="bd" colspan="2" align="center">ပေါင်း</th>  
                <th class="bd" colspan="2" align="center">2</th>
                <th class="bd" colspan="2" align="center">3</th>   
                <th class="bd" colspan="2" align="center">4</th>   
                <th class="bd" colspan="2" align="center">5</th>     
            </tr>';
        $out.='<tr>
            <th class="bd" align="center">ပ</th>  
            <th class="bd" align="center">မ</th>
            <th class="bd" align="center">ပ</th>  
            <th class="bd" align="center">မ</th>
            <th class="bd" align="center">ပ</th>  
            <th class="bd" align="center">မ</th>
            <th class="bd" align="center">ပ</th>  
            <th class="bd" align="center">မ</th>
            <th class="bd" align="center">ပ</th>  
            <th class="bd" align="center">မ</th>
        </tr>';
    $sql_g = "select * from tblgrade  
    where AID is not null order by AID desc";
    $res_g = mysqli_query($con,$sql_g) or die("SQL a Query");
    $no = 0;
    $total = 0;
    $mtota = 0;
    if(mysqli_num_rows($res_g) > 0){ 
        while($row_g = mysqli_fetch_array($res_g)){
            $no = $no + 1;            
            $out.='
            <tr>
                <td class="bd" align="center">'.$no.'</td>
                <td class="bd" align="center">'.$row_g["Name"].'</td>
            ';
            $sql_s = "select count(e.AID) as total,
            (select count(AID) from tblstudentprofile p 
            where p.Gender='Female' and p.AID=e.StudentID) as mtotal 
            from tblearstudent e,tblstudentprofile p 
            where e.StudentID=p.AID and e.EARYearID={$yearid} 
            and  e.GradeID='{$row_g['AID']}'";
            $res_s = mysqli_query($con,$sql_s);
            if(mysqli_num_rows($res_s) > 0){
                $row_s = mysqli_fetch_array($res_s);
                $total = $total + $row_s["total"];
                $mtotal = $mtotal + $row_s["mtotal"];
                $out.='
                    <td class="bd" align="center">'.$row_s["total"].'</td>
                    <td class="bd" align="center">'.$row_s["mtotal"].'</td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                    <td class="bd" align="center"></td>
                ';
            }
            $out.='</tr>';
        }
        $out.='<tr>
            <td class="bd" align="center"></td>
            <td class="bd" align="center">စုစုပေါင်း</td>
            <td class="bd" align="center">'.$total.'</td>
            <td class="bd" align="center">'.$mtotal.'</td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
            <td class="bd" align="center"></td>
        </tr>';
    }
        
    $out.='
        </table>
    </div>';

    $out.='<br><br><br>
    <div class="pl">
        <p style="float:right;">ကျောင်းအုပ်ကြီး<br>
            ကိုယ်ပိုင်အထက်တန်းကျောင်း<br>
            လိပ်စာ
    </div>';

    $out.='</body>
    </html>';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = 'StudentReport_'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
}

if($action == 'teacher_pdf'){
    $out = '
    <html>
        <head>
            <meta http-equiv=Content-Type content="text/html; charset=utf-8">                
        </head>
        <body>
            <h2 style="text-align:center">'.$yearname.' ပညာသင်နှစ် ဆရာ/ဆရာမများစာရင်း</h2>            
    '; 
    $out.='
    <div style="overflow-x:auto;">
        <table>
            <tr>
                <th class="bd" rowspan="2" align="center">စဉ်</th>
                <th class="bd" rowspan="2" align="center">ကျောင်းအမည်</th>
                <th class="bd" align="center">ပေါင်း</th>                  
                <th class="bd" rowspan="2" align="center">မူဆင့်သင်</th>  
                <th class="bd" rowspan="2" align="center">လယ်ဆင့်သင်</th> 
                <th class="bd" rowspan="2" align="center">ထက်ဆင့်သင်</th> 
                <th class="bd" rowspan="2" align="center">Blue Card</th> 
                <th class="bd" rowspan="2" align="center">စုစုပေါင်း</th> 
                <th class="bd" rowspan="2" align="center">မှတ်ချက်</th>   
            </tr>
            <tr>
                <th class="bd" align="center">မ</th>
            </tr>';
        $sql = "select count(AID) as total,
        (select count(AID) from tblstaff  
        where Gender='Female' and AID=s.AID and Status=0) as mtotal 
        from tblstaff s 
        where Status=0";
        $res = mysqli_query($con,$sql);
        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
			$total = $total + $row["total"];
            $mtotal = $mtotal + $row["mtotal"];
            $out.='
            <tr>
                <td class="bd" rowspan="2" align="center">၁။</td>
                <td class="bd" rowspan="2" align="center">ပန်းတိုင်သစ်</td>
                <td class="bd" align="center">ပေါင်း</td>
                <td class="bd" align="center">'.$row["total"].'</td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center">'.$total.'</td>
                <td class="bd" align="center"></td>
            </tr>
            <tr>
                <td class="bd" align="center">မ</td>
                <td class="bd" align="center">'.$row["mtotal"].'</td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center"></td>
                <td class="bd" align="center">'.$mtotal.'</td>
                <td class="bd" align="center"></td>
            </tr>';
        }
        
    $out.='
        </table>
    </div>';

    $out.='<br><br><br>
    <div class="pl">
        <p style="float:right;">ကျောင်းအုပ်ကြီး<br>
            ကိုယ်ပိုင်အထက်တန်းကျောင်း<br>
            လိပ်စာ
    </div>';

    $out.='</body>
    </html>';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = 'TeacherReport_'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
}




?>