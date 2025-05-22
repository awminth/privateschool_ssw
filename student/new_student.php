<?php
    include('../config.php');
    include(root.'master/header.php'); 
?>
<?php
    $aid = (isset($_SESSION["last_student_id"])?$_SESSION["last_student_id"]:0);
    $header = $lang['stu_add'];
    $action = "save";
    $img = roothtml.'upload/noimage.png';
    $path = "";
    $studentid = (isset($_SESSION["last_student_id"])?'':'s_'.date('YmdHis'));;        
    $name = "";
    $dob = date("Y-m-d");
    $age = "";
    $nationalid = 0;
    $nationalname = "";
    $religionid = 0;
    $religionname = "";
    $fname = "";
    $fwork = "";
    $mname = "";
    $mwork = "";
    $address = "";
    $gender = "Male";
    $schooldt = date("Y-m-d");
    $lastschoolname = "";
    $allowgrade = "";
    $topgrade = "";
    $KG = "";
    $G1 = "";
    $G2 = "";
    $G3 = "";
    $G4 = "";
    $G5 = "";
    $G6 = "";
    $G7 = "";
    $G8 = "";
    $G9 = "";
    $G10 = "";
    $G11 = "";
    $G12 = "";
    $outreason = "";
    $rmk = "";
    $place = "";
    $phno = "";
    $email = "";
    $emergence = "";
    $attachdoc="";
    $realgrade="";
    $cellphone="";
    $namemm="";
    $sql = "select p.*,n.Name as nname,r.Name as rname  
    from tblstudentprofile p  
    left join tblnational n on n.AID=p.NationalID  
    left join tblreligion r on r.AID=p.ReligionID   
    where p.AID={$aid}";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $header = $lang['stu_edit'];
        $studentid = $row["StudentID"];
        $name = $row["Name"];
        $dob = $row["DOB"];
        $age = $row["Age"];
        $nationalid = $row["NationalID"];
        $nationalname = $row["nname"];
        $religionid = $row["ReligionID"];
        $religionname = $row["rname"];
        $fname = $row["FatherName"];
        $fwork = $row["FatherWork"];
        $mname = $row["MotherName"];
        $mwork = $row["MotherWork"];
        $gender = $row["Gender"];
        if($row["Img"] != "" || $row["Img"] != NULL){
            $img = roothtml.'upload/student/'.$row["Img"];            
            $path = $row["Img"];
        }
        $action = "edit";
        if($row["SchoolDate"] != ""){
            $schooldt = $row["SchoolDate"];
        }
        $lastschoolname = $row["LastSchoolName"];
        $allowgrade = $row["AllowGrade"];
        $topgrade = $row["TopGrade"];
        $KG = $row["KG"];
        $G1 = $row["G1"];
        $G2 = $row["G2"];
        $G3 = $row["G3"];
        $G4 = $row["G4"];
        $G5 = $row["G5"];
        $G6 = $row["G6"];
        $G7 = $row["G7"];
        $G8 = $row["G8"];
        $G9 = $row["G9"];
        $G10 = $row["G10"];
        $G11 = $row["G11"];
        $G12 = $row["G12"];
        $outreason = $row["OutReason"];
        $rmk = $row["Rmk"];
        $place = $row["BirthPlace"];
        $phno = $row["PhoneNo"];
        $email = $row["Email"];
        $emergence = $row["Emergence"];
        $attachdoc=$row["AttachDoc"];
        $cellphone=$row["CellPhone"];
        $realgrade=$row["RealGrade"];
        $namemm=$row["NameMM"];
        $address=$row["ParentAddress"];
    }
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-3">
                <div class="col-sm-12">
                    <h1><?=$header?></h1>
                    <br>
                    <a href="<?=roothtml.'student/student.php'?>" type="button" class="btn btn-sm btn-<?=$color?>"><i
                            class="fas fa-arrow-left"></i>&nbsp;<?=$lang['btnback']?>
                    </a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <form id="frmsave" method="POST">
                            <input type="hidden" name="action" value="<?=$action?>" />
                            <input type="hidden" name="aid" value="<?=$aid?>" />
                            <input type="hidden" name="path" value="<?=$path?>" />
                            <div class="card-body row">
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_id']?> <span class="text-danger"
                                            style="font-size:16px;">*</span></label>
                                    <input type="text" class="form-control border-primary" name="studentid"
                                        value="<?=$studentid?>" placeholder="Student ID" id="studentid" required>
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_acceptdt']?> <span class="text-danger"
                                            style="font-size:16px;">*</span></label>
                                    <input type="date" class="form-control border-primary" name="schooldt"
                                        value="<?=$schooldt?>" placeholder="School Date" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_name']?> <span class="text-danger"
                                            style="font-size:16px;">*</span></label>
                                    <input type="text" class="form-control border-primary" name="name"
                                        placeholder="Name" value="<?=$name?>" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr">(Myanmar) <span class="text-danger"
                                            style="font-size:16px;"></span></label>
                                    <input type="text" class="form-control border-primary" name="namemm"
                                        placeholder="Name" value="<?=$namemm?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_dob']?> <span class="text-danger"
                                            style="font-size:16px;">*</span></label>
                                    <input type="date" class="form-control border-primary" name="dob" id="dob"
                                        value="<?=$dob?>" placeholder="DOB" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_age']?> <span class="text-danger"
                                            style="font-size:16px;">*</span></label>
                                    <input type="number" class="form-control border-primary" name="age"
                                        placeholder="Age" value="<?=$age?>" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_place']?></label>
                                    <input type="text" class="form-control border-primary" name="place"
                                        placeholder="Birth Place" value="<?=$place?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_religion']?></label>
                                    <select class="form-control border-primary" name="religion">
                                        <?php if($religionid != 0){ ?>
                                        <option value="<?=$religionid?>"><?=$religionname?></option>
                                        <?php } ?>
                                        <?=load_religion()?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_attachment_doc']?></label>
                                    <select class="form-control border-primary" name="attachdoc">
                                        <?php if($attachdoc!=""){ ?>
                                        <option value="<?=$attachdoc?>"><?=$attachdoc?></option>
                                        <?php } ?>
                                        <?=load_attach()?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_nationality']?></label>
                                    <select class="form-control border-primary" name="nationality">
                                        <?php if($nationalid != 0){ ?>
                                        <option value="<?=$nationalid?>"><?=$nationalname?></option>
                                        <?php } ?>
                                        <?=load_national()?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_attend_grade']?></label>
                                    <input type="text" class="form-control border-primary" name="realgrade"
                                        placeholder="" value="<?=$realgrade?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_gender']?> <span class="text-danger"
                                            style="font-size:16px;"></span></label>
                                    <select class="form-control border-primary" name="gender">
                                        <?php 
                                            for($i=0;$i<count($arr_gender);$i++){ 
                                                $txt_gender = "";
                                                if($arr_gender[$i] == $gender){
                                                    $txt_gender = "selected";
                                                }
                                        ?>
                                        <option value="<?=$arr_gender[$i]?>" <?=$txt_gender?>><?=$arr_gender[$i]?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_fname']?></label>
                                    <input type="text" class="form-control border-primary" name="fname"
                                        placeholder="Father Name" value="<?=$fname?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_fwork']?></label>
                                    <input type="text" class="form-control border-primary" name="fwork"
                                        placeholder="Father Work" value="<?=$fwork?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_mname']?></label>
                                    <input type="text" class=" form-control border-primary" name="mname"
                                        placeholder="Mother Name" value="<?=$mname?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_mwork']?></label>
                                    <input type="text" class="form-control border-primary" name="mwork"
                                        placeholder="Occupation" value="<?=$mwork?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_phno']?></label>
                                    <input type="text" class="form-control border-primary" name="phno"
                                        placeholder="Phone No" value="<?=$phno?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_cell_phone']?></label>
                                    <input type="text" class="form-control border-primary" name="cellphone"
                                        placeholder="Cell Phone No" value="<?=$cellphone?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_email']?></label>
                                    <input type="text" class="form-control border-primary" name="email"
                                        placeholder="Email" value="<?=$email?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_emergence']?></label>
                                    <input type="text" class="form-control border-primary" name="emergence"
                                        placeholder="Emergency Contact" value="<?=$emergence?>">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="usr"><?=$lang['stu_photo']?></label>
                                    <input type="file" class="form-control border-primary p-1" name="file" id="photo"
                                        accept=".jpg,.png,.jpeg,.JPG,.PNG,.JPEG">
                                </div>
                                <div class="form-group col-sm-12 ">
                                    <label for="usr"><?=$lang['stu_address']?></label>
                                    <textarea class="form-control border-primary" name="address"
                                        placeholder="Address"><?=$address?></textarea>
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_lastsname']?></label>
                                    <input type="text" class="form-control border-primary" name="lastschoolname"
                                        placeholder="Last School Name" value="<?=$lastschoolname?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_allowgrade']?></label>
                                    <input type="text" class="form-control border-primary" name="allowgrade"
                                        placeholder="Allow Grade" value="<?=$allowgrade?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_topgrade']?></label>
                                    <input type="text" class="form-control border-primary" name="topgrade"
                                        placeholder="Top Grade" value="<?=$topgrade?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_kg']?></label>
                                    <input type="text" class="form-control border-primary" name="KG" placeholder="KG"
                                        value="<?=$KG?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g1']?></label>
                                    <input type="text" class="form-control border-primary" name="G1"
                                        placeholder="Grade 1" value="<?=$G1?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g2']?></label>
                                    <input type="text" class="form-control border-primary" name="G2"
                                        placeholder="Grade 2" value="<?=$G2?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g3']?></label>
                                    <input type="text" class="form-control border-primary" name="G3"
                                        placeholder="Grade 3" value="<?=$G3?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g4']?></label>
                                    <input type="text" class="form-control border-primary" name="G4"
                                        placeholder="Grade 4" value="<?=$G4?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g5']?></label>
                                    <input type="text" class="form-control border-primary" name="G5"
                                        placeholder="Grade 5" value="<?=$G5?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g6']?></label>
                                    <input type="text" class="form-control border-primary" name="G6"
                                        placeholder="Grade 6" value="<?=$G6?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g7']?></label>
                                    <input type="text" class="form-control border-primary" name="G7"
                                        placeholder="Grade 7" value="<?=$G7?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g8']?></label>
                                    <input type="text" class="form-control border-primary" name="G8"
                                        placeholder="Grade 8" value="<?=$G8?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g9']?></label>
                                    <input type="text" class="form-control border-primary" name="G9"
                                        placeholder="Grade 9" value="<?=$G9?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g10']?></label>
                                    <input type="text" class="form-control border-primary" name="G10"
                                        placeholder="Grade 10" value="<?=$G10?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g11']?></label>
                                    <input type="text" class="form-control border-primary" name="G11"
                                        placeholder="Grade 11" value="<?=$G11?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_g12']?></label>
                                    <input type="text" class="form-control border-primary" name="G12"
                                        placeholder="Grade 12" value="<?=$G12?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_leave']?></label>
                                    <input type="text" class="form-control border-primary" name="outreason"
                                        value="<?=$outreason?>">
                                </div>
                                <div class="form-group col-sm-4" style="display:none">
                                    <label for="usr"><?=$lang['stu_rmk']?></label>
                                    <input type="text" class="form-control border-primary" name="rmk" value="<?=$rmk?>">
                                </div>
                                <div class="form-group col-sm-12 text-center">
                                    <img src="<?=$img?>" id="img" class="border border-primary" width="160"
                                        height="160" />
                                </div>
                                <div class="from-group col-sm-12 text-center">
                                    <hr>
                                </div>
                                <div class="from-group col-sm-12 text-center">
                                    <a href="<?=roothtml.'student/student.php'?>" type="button"
                                        class="btn btn-<?=$color?> next-step next-button"><i
                                            class="fas fa-arrow-left"></i>&nbsp;<?=$lang['btnback']?>
                                    </a>
                                    <button type="submit" class="btn btn-<?=$color?> next-step next-button"><i
                                            class="fas fa-save"></i>&nbsp;<?=$lang['btnsave']?>
                                    </button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </form>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#photo").change(function() {
        readURL(this);
    });

    $(document).on("change", "#dob", function() {
        var dob = new Date($('#dob').val());
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        $("[name='age']").val(age);
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                // swal('', data, '');
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    location.href = "<?=roothtml.'student/student.php'?>";
                }
                if (data == 0) {
                    swal("Error!", "Error Save.", "error");
                }
                if (data == 2) {
                    swal("Information!",
                        "Your upload file is wrong type.",
                        "info");
                }
            }
        });
    });

    $(document).on("change", "#studentid", function(e) {
        e.preventDefault();
        var stuid = $(this).val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: {
                action: "check_stuid",
                stuid: stuid
            },
            success: function(data) {
                if (data == 1) {
                    swal("Information", "Student ID is already have been.", "info");
                    $("[name='studentid']").val("");
                }
            }
        });
    });




});
</script>