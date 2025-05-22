<?php
include('../config.php');
include(root.'master/header.php');

$userid = $_SESSION['userid'];
$yid = isset($_SESSION['yearid'])?$_SESSION['yearid']:'';
$yname = isset($_SESSION['yearname'])?$_SESSION['yearname']:'';
$gid = isset($_SESSION['gradeid'])?$_SESSION['gradeid']:'';
$gname = isset($_SESSION['gradename'])?$_SESSION['gradename']:'';
$earid = isset($_SESSION['earid'])?$_SESSION['earid']:'';
$sid = isset($_SESSION['sid'])?$_SESSION['sid']:'';
$sname=  isset($_SESSION['sname'])?$_SESSION['sname']:'';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-3">
                <div class="col-sm-6">
                    <h4>Student Report Detail for (<?=$sname?>) </h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?=roothtml.'ear/earstudent.php'?>" type="button" class="btn btn-sm btn-<?=$color?>"><i
                            class="fas fa-arrow-left"></i>&nbsp;
                        Back
                    </a>
                    <a href="<?php echo roothtml.'ear/studentdetail_pdf.php' ?>"
                        class="btn btn-sm btn-<?=$color?>"><i class="fas fa-file-pdf"></i>&nbsp;PDF</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <?php
                    $sql1="select * from tblstudentprofile 
                    where AID={$sid}";                
                    $res1=mysqli_query($con,$sql1);
                    if(mysqli_num_rows($res1) > 0){
                        $row1 = mysqli_fetch_array($res1);
                        $img1 = roothtml.'upload/noimage.png';
                        if($row1["Img"] != "" || $row1["Img"] != NULL){
                            $img1 = roothtml.'upload/student/'.$row1['Img'];
                        }                    
                    ?>
                    <div class="card card-<?=$color?> card-outline p-2">
                        <!-- Card body -->
                        <div class="row">
                            <div class="col-sm-8">
                                <h4>Student Information</h4>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Student ID</td>
                                        <td><?=$row1['StudentID']?></td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td><?=$row1['Name']?></td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td><?=$row1['Age']?></td>
                                    </tr>
                                    <tr>
                                        <td>DOB</td>
                                        <td><?=enDate($row1['DOB'])?></td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td><?=$row1['Gender']?></td>
                                    </tr>
                                    <tr>
                                        <td>Father Name</td>
                                        <td><?=$row1['FatherName']?></td>
                                    </tr>
                                    <tr>
                                        <td>Father Work</td>
                                        <td><?=$row1['FatherWork']?></td>
                                    </tr>
                                    <tr>
                                        <td>Mother Name</td>
                                        <td><?=$row1['MotherName']?></td>
                                    </tr>
                                    <tr>
                                        <td>Mother Work</td>
                                        <td><?=$row1['MotherWork']?></td>
                                    </tr>
                                    <tr>
                                        <td>Religion</td>
                                        <td><?=$row1['Religion']?></td>
                                    </tr>
                                    <tr>
                                        <td>Nationality</td>
                                        <td><?=$row1['Nationality']?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 text-center">
                                <h5>Profile Photo</h5>
                                <img src="<?=$img1?>" class="border border-primary" alt="No Image" width="50%" />

                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <?php } ?>

                    <!-- for exam -->
                    <?php
                    $sql_e = "select * from tblexamtype 
                    where AID is not null";
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

                    ?>
                    <div class="card card-<?=$color?> card-outline p-2">
                        <h4>Exam Mark <span class="text-primary">(<?=$row_e["Name"]?>)</span></h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Exam Type</th>
                                    <th>Subject Name</th>
                                    <th>Pay Mark</th>
                                    <th>Get Mark</th>
                                    <th>Result</th>
                                    <th>D</th>
                                    <th>Date</th>
                                </tr>
                                <?php 
                                    while($row2 = mysqli_fetch_array($res2)){ 
                                    $no2 = $no2 + 1; 
                                    $d = "No";
                                    if($row2["D"] == 1){
                                        $d = "Yes";
                                    } 
                                ?>
                                <tr>
                                    <td class="text-center"><?=$no2?></td>
                                    <td><?=$row_e["Name"]?></td>
                                    <td><?=$row2["sname"]?></td>
                                    <td><?=$row2["PayMark"]?></td>
                                    <td><?=$row2["GetMark"]?></td>
                                    <td><?=$row2["Result"]?></td>
                                    <td><?=$d?></td>
                                    <td><?=enDate($row2["Date"])?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="8"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php }}} ?>

                    <?php
                    $sql3 = "select e.*   
                    from tblstudentactivity e 
                    where e.EARStudentID={$earid}    
                    order by e.AID desc";
                    $res3 = mysqli_query($con,$sql3);
                    $no3 = 0;                                                            

                    ?>
                    <div class="card card-<?=$color?> card-outline p-2">
                        <h4>Student Activity</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                                <?php 
                                if(mysqli_num_rows($res3) > 0){  
                                    while($row3 = mysqli_fetch_array($res3)){ 
                                    $no3 = $no3 + 1; 
                                ?>
                                <tr>
                                    <td class="text-center"><?=$no3?></td>
                                    <td><?=$row3["Description"]?></td>
                                    <td><?=enDate($row3["Date"])?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                                <?php }else{ ?>
                                <tr>
                                    <td colspan="3" class="text-center">No data</td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>

                    <!-- Student Fee -->
                    <?php
                    $sql4 = "select * from tblstudentfee where EARStudentID='{$earid}'";
                    $res4 = mysqli_query($con,$sql4);
                    $no4 = 0;                                                            

                    ?>
                    <div class="card card-<?=$color?> card-outline p-2">
                        <h4>Student Fee</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>VNO</th>
                                    <th>Cash</th>
                                    <th>Mobile</th>
                                    <th>Mobile Rmk</th>
                                    <th>Total Pay</th>
                                    <th>Pay Date</th>
                                </tr>
                                <?php 
                                if(mysqli_num_rows($res4) > 0){  
                                    while($row4 = mysqli_fetch_array($res4)){ 
                                    $no4 = $no4 + 1;
                                    if($row4["MobileRmk"] == ""){
                                        $row4["MobileRmk"] = "---";
                                    } 
                                ?>
                                <tr>
                                    <td class="text-center"><?=$no4?></td>
                                    <td><?=$row4["VNO"]?></td>
                                    <td><?=number_format($row4["Cash"])?></td>
                                    <td><?=number_format($row4["Mobile"])?></td>
                                    <td><?=$row4["MobileRmk"]?></td>
                                    <td><?=number_format($row4["TotalPay"])?></td>
                                    <td><?=enDate($row4["PayDate"])?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                                <?php }else{ ?>
                                <tr>
                                    <td colspan="7" class="text-center">No data</td>
                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>

                    <!-- attendance -->
                    <?php
                    $sql6 = "select *,
                    (select p.Name from tblstudentprofile p,tblearstudent e,tblstudentattendance s 
                    where p.AID=e.StudentID and e.AID=s.EARStudentID and s.AID=a.AID) as sname, 
                    (select count(AID) from tblstudentattendance where EARYearID={$yid} and 
                    GradeID={$gid} and EARStudentID=a.EARStudentID and Status=1) as tin,
                    (select count(AID) from tblstudentattendance where EARYearID={$yid} and 
                    GradeID={$gid} and EARStudentID=a.EARStudentID and Status=0) as tout
                    from tblstudentattendance a where EARYearID={$yid} and 
                    GradeID={$gid} and LoginID={$userid} and EARStudentID={$earid}  group by EARStudentID";
                    $res6 = mysqli_query($con,$sql6);
                    $no6 = 0;                                                            

                    ?>
                    <div class="card card-<?=$color?> card-outline p-2">
                        <h4>Student Attendance</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Student Name</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Absent</th>
                                </tr>
                                <?php 
                                if(mysqli_num_rows($res6) > 0){  
                                    while($row6 = mysqli_fetch_array($res6)){ 
                                    $no6 = $no6 + 1; 
                                ?>
                                <tr>
                                    <td class="text-center"><?=$no6?></td>
                                    <td><?=$row6["sname"]?></td>
                                    <td class="text-center"><?=$row6["tin"]?></td>
                                    <td class="text-center"><?=$row6["tout"]?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <?php }else{ ?>
                                <tr>
                                    <td colspan="4" class="text-center">No data</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->


<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {


});
</script>