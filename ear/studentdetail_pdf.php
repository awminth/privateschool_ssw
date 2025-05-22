<?php
    include('../config.php');
    include(root.'lib/vendor/autoload.php');

    $userid = $_SESSION['userid'];
    $yid = isset($_SESSION['yearid'])?$_SESSION['yearid']:'';
    $yname = isset($_SESSION['yearname'])?$_SESSION['yearname']:'';
    $gid = isset($_SESSION['gradeid'])?$_SESSION['gradeid']:'';
    $gname = isset($_SESSION['gradename'])?$_SESSION['gradename']:'';
    $earid = isset($_SESSION['earid'])?$_SESSION['earid']:'';
    $sid = isset($_SESSION['sid'])?$_SESSION['sid']:'';
    $sname=  isset($_SESSION['sname'])?$_SESSION['sname']:'';
    
    $out='<html>
            <head>
            <meta http-equiv=Content-Type content="text/html; charset=utf-8">                
            </head>
            <body>
            <h2 style="text-align:center">Student Report Detail for ( '.$sname.' )</h2>
            ';  
            $sql1="select * from tblstudentprofile 
            where AID={$sid}";                
            $res1=mysqli_query($con,$sql1);
            if(mysqli_num_rows($res1) > 0){
                $row1 = mysqli_fetch_array($res1); 
                $img1 = roothtml.'upload/noimage.png';
                if($row1["Img"] != "" || $row1["Img"] != NULL){
                    $img1 = roothtml.'upload/student/'.$row1['Img'];
                } 
                $out.='<h2>Student Information</h2>
                <div>
                <div class="div1">
                    <table>
                        <tr>
                            <td class="td1">Name</td>
                            <td class="td2">'.$row1['te_name'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Student ID</td>
                            <td class="td2">'.$row1['StudentID'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Name</td>
                            <td class="td2">'.$row1['Name'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Age</td>
                            <td class="td2">'.$row1['Age'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">DOB</td>
                            <td class="td2">'.enDate($row1['DOB']).'</td>
                        </tr>
                        <tr>
                            <td class="td1">Gender</td>
                            <td class="td2">'.$row1['Gender'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Father Name</td>
                            <td class="td2">'.$row1['FatherName'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Father Work</td>
                            <td class="td2">'.$row1['FatherWork'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Mother Name</td>
                            <td class="td2">'.$row1['MotherName'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Mother Work</td>
                            <td class="td2">'.$row1['MotherWork'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Religion</td>
                            <td class="td2">'.$row1['Religion'].'</td>
                        </tr>
                        <tr>
                            <td class="td1">Nationality</td>
                            <td class="td2">'.$row1['Nationality'].'</td>
                        </tr>
                    </table>
                </div>
                <div class="div2">
                    <h5>Profile Photo</h5>
                    <img src="'.$img1.'"
                        class="image" />

                </div>
                </div>
                <br>
                '; 
            }  

            $sql_e = "select * from tblexamtype 
            where LoginID={$userid}";
            $res_e = mysqli_query($con,$sql_e);
            if(mysqli_num_rows($res_e) > 0){  
                while($row_e = mysqli_fetch_array($res_e)){
                    $sql2 = "select e.*,s.Name as sname  
                    from tblexam e,tblsubject s 
                    where e.SubjectID=s.AID and e.EARStudentID={$earid}    
                    and e.ExamTypeID={$row_e['AID']} order by e.AID desc";
                    $res2 = mysqli_query($con,$sql2);
                    $no2 = 0;
                    if(mysqli_num_rows($res2) > 0){
                        $out.='<p class="line"></p>
                        <h2>Exam Mark <span class="text-primary">('.$row_e["Name"].')</span></h2>
                        <div style="overflow-x:auto;">
                            <table>
                                <tr>
                                    <th>No</th>
                                    <th>Exam Type</th>
                                    <th>Subject Name</th>
                                    <th>Pay Mark</th>
                                    <th>Get Mark</th>
                                    <th>Result</th>
                                    <th>D</th>
                                    <th>Date</th>
                                </tr>';
                        while($row2 = mysqli_fetch_array($res2)){ 
                            $no = $no + 1;
                            $d = "No";
                            if($row2["D"] == 1){
                                $d = "Yes";
                            }
                            $out.='
                            <tr>
                                <td>'.$no.'</td>
                                <td>'.$row2["tname"].'</td>
                                <td>'.$row2["sname"].'</td>
                                <td>'.$row2["PayMark"].'</td>
                                <td>'.$row2["GetMark"].'</td>
                                <td>'.$row2["Result"].'</td>
                                <td>'.$d.'</td>
                                <td>'.enDate($row2["Date"]).'</td>
                            </tr>';
                        }
                        $out.='
                            </table>
                        </div>
                        <br>';
                    }
                }
            }
            

            $sql3="select e.*   
            from tblstudentactivity e 
            where e.EARStudentID={$earid}    
            order by e.AID desc";
            $res3=mysqli_query($con,$sql3);
            $n3 = 0;
            $out.='<p class="line"></p>
            <h2>Student Activity</h2>
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>';
                if(mysqli_num_rows($res3) > 0){
                    while($row3 = mysqli_fetch_array($res3)){ 
                    $n3 = $n3 + 1; 
                    $out.=' 
                        <tr>
                            <td>'.$n3.'</td>
                            <td>'.$row3["Description"].'</td>
                            <td>'.enDate($row3["Date"]).'</td>
                        </tr>';
                    } 
                }else{
                    $out.=' 
                        <tr>
                            <td align="center" colspan="3">No data</td>
                        </tr>';
                }
            $out.='
                </table>
            </div>
            <br>';

            $sql4="select e.*,s.Name,
            (select Amt from tblfee where LoginID={$userid} and EARStudentID=e.AID) as samt,
            (select TotalPay from tblfee where LoginID={$userid} and EARStudentID=e.AID) as stotalpay,
            (select Remain from tblfee where LoginID={$userid} and EARStudentID=e.AID) as sremain,
            (select VNO from tblfee where LoginID={$userid} and EARStudentID=e.AID) as svno 
            from tblearstudent e,tblstudentprofile s where 
            s.AID=e.StudentID and e.LoginID={$userid} and e.EARYearID={$yid} and 
            e.GradeID={$gid} and e.AID={$earid} 
            order by e.AID desc";
            $res4=mysqli_query($con,$sql4);
            $n4 = 0;
            $out.='<p class="line"></p>
            <h2>Student Fee</h2>
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>No</th>
                        <th>VNO</th>
                        <th align="right">Cash</th>
                        <th align="right">Mobile</th>
                        <th>Mobile Rmk</th>
                        <th align="right">Total Pay</th>
                        <th class="text-center">Pay Date</th>
                    </tr>';
                if(mysqli_num_rows($res4) > 0){
                    $row4 = mysqli_fetch_array($res4); 
                    $sql5 = "select d.*  
                    from tblfeedetail d,tblfee f 
                    where d.FeeVNO=f.VNO and d.LoginID={$userid} and 
                    f.EARStudentID='{$row4['AID']}' order by d.AID desc";
                    $res5 = mysqli_query($con,$sql5);
                    if(mysqli_num_rows($res5) > 0){  
                        while($row5 = mysqli_fetch_array($res5)){ 
                            $n4 = $n4 + 1; 
                            $out.='
                            <tr>
                                <td>'.$n4.'</td>
                                <td>'.$row5["VNO"].'</td>
                                <td align="right">'.number_format($row5["Cash"]).'</td>
                                <td align="right">'.number_format($row5["Mobile"]).'</td>
                                <td>'.$row5["MobileRmk"].'</td>
                                <td align="right">'.number_format($row5["TotalAmt"]).'</td>
                                <td class="text-center">'.enDate($row5["PayDate"]).'</td>
                            </tr>';
                        }
                    }
                    $out.='
                    <tr>
                        <td colspan="5" align="right"><b>Fee Amount</b></td>
                        <td colspan="1" align="right"><b>'.number_format($row4["samt"]).'</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right"><b>Total Pay</b></td>
                        <td colspan="1" align="right"><b>'.number_format($row4["stotalpay"]).'</b>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5" align="right"><b>Remain</b></td>
                        <td colspan="1" align="right"><b>'.number_format($row4["sremain"]).'</b></td>
                        <td></td>
                    </tr>';  
                        
                }else{
                    $out.='
                    <tr>
                        <td colspan="7" align="center">No data</td>
                    </tr>';
                }
            $out.='
                </table>
            </div>
            <br>';

            $sql6="select *,
            (select p.Name from tblstudentprofile p,tblearstudent e,tblstudentattendance s 
            where p.AID=e.StudentID and e.AID=s.EARStudentID and s.AID=a.AID) as sname, 
            (select count(AID) from tblstudentattendance where EARYearID={$yid} and 
            GradeID={$gid} and EARStudentID=a.EARStudentID and Status=1) as tin,
            (select count(AID) from tblstudentattendance where EARYearID={$yid} and 
            GradeID={$gid} and EARStudentID=a.EARStudentID and Status=0) as tout
            from tblstudentattendance a where EARYearID={$yid} and 
            GradeID={$gid} and LoginID={$userid} and EARStudentID={$earid}  group by EARStudentID";
            $res6=mysqli_query($con,$sql6);
            $n3 = 0;
            $out.='<p class="line"></p>
            <h2>Student Attendance</h2>
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>No</th>
                        <th>Student Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>';
                if(mysqli_num_rows($res6) > 0){
                    while($row6 = mysqli_fetch_array($res6)){ 
                    $n3 = $n3 + 1; 
                    $out.=' 
                        <tr>
                            <td>'.$n3.'</td>
                            <td>'.$row6["sname"].'</td>
                            <td>'.$row6["tin"].'</td>
                            <td>'.$row6["tout"].'</td>
                        </tr>';
                    } 
                }else{
                    $out.=' 
                        <tr>
                            <td align="center" colspan="4">No data</td>
                        </tr>';
                }
            $out.='
                </table>
            </div>
            <br>';

        $out.='</body>
        </html>';
    
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;  
    $stylesheet = file_get_contents(roothtml.'lib/mypdfcss.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);  
    $mpdf->WriteHTML($out,2);
    $file = $sname.'_'.date("d_m_Y").'.pdf';
    $mpdf->output($file,'D');
?>