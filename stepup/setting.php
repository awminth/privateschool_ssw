<?php
include('../config.php');
include(root.'master/header.php');

$sitetitle = $_SESSION["shopname"];
$sitephno = $_SESSION["shopphno"];
$siteemail = $_SESSION["shopemail"];
$siteaddress = $_SESSION["shopaddress"];
$sitelogo = $_SESSION["shoplogo"];
$siteicon = $_SESSION["shopicon"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Setting</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-<?=$color?> card-outline p-1">
                        <p class="pl-3">Change General Setting</p>
                        <div class="row m-2">
                            <div class="col-sm-4">
                                <div class="card card-<?=$color?> card-outline p-3">
                                    <div class="form-group">
                                        <label>Site Title</label>
                                        <input type="text" value="<?=$sitetitle?>" name="sitetitle"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>Phone No</label>
                                        <input type="text" value="<?=$sitephno?>" name="sitephno"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" value="<?=$siteemail?>" name="siteemail"
                                            class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" rows="3"
                                            name="siteaddress"><?=$siteaddress?></textarea>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='submit' id='btnsitetitle' class='btn  btn-sm btn-<?=$color?>'><i
                                                class="fas fa-save"></i>
                                            Save</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <form id="frmlogo">
                                    <div class="card card-<?=$color?> card-outline p-3">
                                        <div class="form-group">
                                            <input type="hidden" name="action" value="sitelogo" />
                                            <label>Site Logo</label>
                                            <div class="custom-file">
                                                <input type="file" name="logofile" class="custom-file-input"
                                                    id="customFile" accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG">
                                                <label class="custom-file-label" for="customFile">Upload Your
                                                    Photo</label>
                                            </div>
                                            <span class="text-sm">Upload photo must be .PNG, .JPEG, .JPG, .jpg,
                                                .jpeg,
                                                .png</span>

                                        </div>
                                        <div class='form-group'>
                                            <img id="blah" src="<?=$sitelogo?>" width="230px" height="200px"
                                                alt="your upload photo" class="border border-<?=$color?>" />
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='submit' id='btnsite_logo'
                                                class='btn  btn-sm btn-<?=$color?>'><i class="fas fa-save"></i>
                                                Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-4">
                                <form id="frmicon">
                                    <div class="card card-<?=$color?> card-outline p-3">
                                        <div class="form-group">
                                            <input type="hidden" name="action" value="siteicon" />
                                            <label>Site Icon</label>
                                            <div class="custom-file">
                                                <input type="file" name="iconfile"
                                                    class="custom-file-input custom-file-input-sm" id="customFile"
                                                    accept=".jpg,.jpeg,.png,.JPG,.JPEG,.PNG">
                                                <label class="custom-file-label" for="customFile">Upload Your
                                                    Photo</label>
                                            </div>
                                            <span class="text-sm">Upload photo must be .PNG, .JPEG, .JPG, .jpg,
                                                .jpeg,
                                                .png</span>

                                        </div>
                                        <div class='form-group'>
                                            <img id="blah1" src="<?=$siteicon?>" width="230px" height="200px"
                                                alt="your upload photo" class="border border-<?=$color?>" />
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='submit' id='btncolor' class='btn  btn-sm btn-<?=$color?>'><i
                                                    class="fas fa-save"></i>
                                                Save</button>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {
    $(document).on("click", "#btnsitetitle", function(e) {
        var sitetitle = $("[name='sitetitle']").val();
        var sitephno = $("[name='sitephno']").val();
        var siteemail = $("[name='siteemail']").val();
        var siteaddress = $("[name='siteaddress']").val();
        if (sitetitle == '') {
            swal("Warning", "Please fill title.", "warning");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'stepup/setting_action.php' ?>",
            data: {
                action: 'sitetitle',
                sitetitle: sitetitle,
                sitephno: sitephno,
                siteemail: siteemail,
                siteaddress: siteaddress
            },
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save data Successful.",
                        "success");
                    window.location.reload();
                } else {
                    swal("Error", "Edit data failed.", "error");
                }
            }
        });
    });

    $("#frmlogo").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'stepup/setting_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save data Successful.",
                        "success");
                    window.location.reload();
                } else if (data == 2) {
                    swal("Error!", "Upload photo must be .jpg, .png, .jpeg!",
                        "error");
                } else if (data == 3) {
                    swal("Warning", "Pleae upload photo.",
                        "warning");
                }else {
                    swal("Error!", "Error save photo.", "error");
                }
            }
        });
    });

    $("#frmicon").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'stepup/setting_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save data Successful.",
                        "success");
                    window.location.reload();
                } else if (data == 2) {
                    swal("Error!", "Upload photo must be .jpg, .png, .jpeg!",
                        "error");
                } else if (data == 3) {
                    swal("Warning", "Pleae upload photo.",
                        "warning");
                }else {
                    swal("Error!", "Error save photo.", "error");
                }
            }
        });
    });



});
</script>