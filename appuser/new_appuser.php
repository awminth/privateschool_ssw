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
                    <h1>New App User</h1>
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
                        <div class="card-header">
                            <table>
                                <tr>
                                    <td>
                                        <a href="<?=roothtml.'appuser/appuser.php'?>" class="btn btn-sm btn-primary"><i
                                                class="fas fa-arrow-left"></i>&nbsp; <?=$lang['staff_back']?>
                                        </a>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table><!-- Card body -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="card p-3">
                                        <div class="form-group">
                                            <input type="hidden" name="ser" />
                                            <input type="text" class="form-control border-success"  
                                                name="serstudent" id="serstudent" placeholder="Searching . . . ">
                                        </div>
                                        <div id="show">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="card p-3">
                                        <form method="POST" id="frmsave">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="hidden" name="action" value="savestudent" />
                                                        <input type="hidden" name="stuid" />
                                                        <label for="usr"> <?=$lang['app_studentname']?> :</label>
                                                        <input type="text" class="form-control border-success"
                                                            id="stuname" required name="stuname">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="form-group">
                                                        <label for="usr"> <?=$lang['app_parent']?> :</label>
                                                        <select class="form-control border-success select2"
                                                            name="parent" required>
                                                            <option value="">Select Parent</option>
                                                            <?=load_parent();?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label><br></label>
                                                        <button type="button" id="btnaddparent"
                                                            class="form-control btn btn-sm btn-success">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <button class="form-control btn btn-success"
                                                        id="btnsave"><?=$lang['staff_save']?></button>
                                                </div>

                                            </div>
                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- new Modal -->
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">New Parent</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmsaveparent" method="POST">
                    <input type="hidden" name="action" value="saveparent" />
                    <div class="form-group">
                        <label for="usr"><?=$lang['app_parent']?>:</label>
                        <input type="text" required class="form-control border-primary" name="name"
                            placeholder="Enter parent name" required>
                    </div>
                    <div class="form-group">
                        <label for="usr">User Name:</label>
                        <input type="text" required class="form-control border-primary" name="username"
                            placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="usr">Password :</label>
                        <input type="password" required class="form-control border-primary" name="password"
                            placeholder="Enter password" required>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' id="btnsaveparent" class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                            <?=$lang['staff_save']?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var ser = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'appuser/appuser_action.php' ?>",
            data: {
                action: 'showstudent',
                ser: ser
            },
            success: function(data) {
                $("#show").html(data);
            }
        });
    }
    load_pag();

    $(document).on("keyup", "#serstudent", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on("click", "#btnaddparent", function(e) {
        $("#btnnewmodal").modal("show");

    });

    $(document).on("click", "#btnclickstudent", function(e) {
        var aid = $(this).data("aid");
        var name = $(this).data("name");
        $("[name='stuid']").val(aid);
        $("[name='stuname']").val(name);

    });


    $(document).on("click", "#btnsaveparent", function(e) {
        e.preventDefault();
        var name = $("[name='name']").val();
        var username = $("[name='username']").val();
        var password = $("[name='password']").val();
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'appuser/appuser_action.php' ?>",
            data: {
                action: 'saveparent',
                name: name,
                username: username,
                password: password

            },
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    location.reload();
                } else if (data == 2) {
                    swal("Warning", "change username and name.", "warning");
                } else {
                    swal('error', data, 'error');
                }
            }
        });
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'appuser/appuser_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    location.reload();
                    swal.close();
                } else if (data == 2) {
                    swal("Warning", "Already have been user.", "warning");
                } else {
                    swal("Error!", "Error Save.", "error");
                }
            }
        });
    });


});
</script>