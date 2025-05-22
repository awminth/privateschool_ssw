<?php
    include('../config.php');
    include(root.'master/header.php'); 
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Teacher Information</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <?php
        $aid = (isset($_SESSION["last_teacher_id"])?$_SESSION["last_teacher_id"]:0);
        $action = "save";
        $img = roothtml.'upload/noimage.png';
        $path = "";
        $staffid = "S-".date("YmdHis");
        $name = "";
        $dob = date("Y-m-d");
        $gender = "Female";
        $phno = "";
        $address = "";
        $email = "";
        $education = "";
        $edulevel="";
        $salary = 0;
        $namemm="";
        $startdt = date("Y-m-d");
        $sql = "select * from tblstaff where AID={$aid}";
        $res = mysqli_query($con,$sql);
        if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_array($res);
            $staffid = $row["StaffID"];
            $name = $row["Name"];
            $namemm = $row["NameMM"];
            $dob = $row["DOB"];
            $gender = $row["Gender"];
            $phno = $row["PhoneNo"];
            $address = $row["Address"];
            $email = $row["Email"];
            $education = $row["Education"];
            $salary = $row["Salary"];
            $startdt = $row["StartDate"];
            $edulevel = $row["EducationLevel"];
            if($row["Img"] != "" || $row["Img"] != NULL){
                $img = roothtml.'upload/staff/'.$row["Img"];
                $path = $row["Img"];
            }
            $action = "edit";            
        }
    ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <br>
                        <form id="frmsave" method="POST">
                            <input type="hidden" name="action" value="<?=$action?>" />
                            <input type="hidden" name="aid" value="<?=$aid?>" />
                            <input type="hidden" name="path" value="<?=$path?>" />
                            <div class="card-body row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_teacherid']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="staffid"
                                            value="<?=$staffid?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_name']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="name"
                                            placeholder="Name" value="<?=$name?>" required>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_namemm']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="namemm"
                                            placeholder="Name" value="<?=$namemm?>" required>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_dob']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <input type="date" class="col-sm-8 form-control border-primary" name="dob"
                                            value="<?=$dob?>" placeholder="DOB" required>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_gender']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <select class="col-sm-8 form-control border-primary" name="gender">
                                            <?php 
                                                for($i=0;$i<count($arr_gender);$i++){ 
                                                    $txt_gender = "";
                                                    if($arr_gender[$i] == $gender){
                                                        $txt_gender = "selected";
                                                    }
                                            ?>
                                            <option value="<?=$arr_gender[$i]?>" <?=$txt_gender?>><?=$arr_gender[$i]?>
                                                <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_phoneno']?> <span
                                                class="text-danger" style="font-size:20px;">*</span>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="phno"
                                            placeholder="Phone No" value="<?=$phno?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_address']?>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="address"
                                            placeholder="Address" value="<?=$address?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_email']?>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="email"
                                            placeholder="Email" value="<?=$email?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_education']?>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="education"
                                            placeholder="Education" value="<?=$education?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_salary']?>:</label>
                                        <input type="number" class="col-sm-8 form-control border-primary" name="salary"
                                            placeholder="Salary" value="<?=$salary?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_startdate']?>:</label>
                                        <input type="date" class="col-sm-8 form-control border-primary" name="startdt"
                                            placeholder="Start Date" value="<?=$startdt?>">
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4" for="usr"><?=$lang['tea_edulevel']?>:</label>
                                        <input type="text" class="col-sm-8 form-control border-primary" name="edulevel"
                                            placeholder="Education Level" value="<?=$edulevel?>">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2" for="usr">Upload Photo:</label>
                                        <input type="file" name="file" id="photo"
                                            class="col-sm-10 border border-primary p-1"
                                            accept=".jpg,.png,.jpeg,.JPG,.PNG,.JPEG">

                                    </div>
                                    <div class="form-group text-center">
                                        <img src="<?=$img?>" id="img" class="border border-primary" width="160"
                                            height="160" />
                                    </div>
                                </div>
                                <div class="col-sm-12 text-center">
                                    <a href="<?=roothtml.'teacher/teacher.php'?>" type="button"
                                        class="btn btn-<?=$color?> next-step next-button"><i
                                            class="fas fa-arrow-left"></i>&nbsp;<?=$lang['tea_back']?>
                                    </a>
                                    <button type="submit" class="btn btn-<?=$color?> next-step next-button"><i
                                            class="fas fa-save"></i>&nbsp;<?=$lang['tea_save']?>
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

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teacher_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    window.location.reload();
                    location.href= "<?=roothtml.'teacher/teacher.php'?>";
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




});
</script>