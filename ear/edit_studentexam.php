<?php
include('../config.php');
include(root.'master/header.php');

$examtypeid = $_SESSION["exam_earstudent_examtypeid"];
$examtypename = $_SESSION["exam_earstudent_examtypename"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>)
                        <?=$lang['exam_edit']?></h1>
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
                                        <a href="<?=roothtml.'ear/studentexam.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card card-primary card-outline p-2">
                                        <div id="show_subject" class="table-responsive-sm">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="card card-primary card-outline p-3">
                                        <div class="form-group row">
                                            <label for="usr" class="col-sm-4"> <?=$lang['exam_stuname']?></label>
                                            <input type="text" class="col-sm-8 form-control border-success"
                                                value="<?=$_SESSION["exam_earstudent_name"]?>" name="stuname" readonly>
                                        </div>
                                        <div class="form-group row">
                                            <label for="usr" class="col-sm-4"><?=$lang['exam_examtype']?></label>
                                            <input type="text" class="col-sm-8 form-control border-success"
                                                value="<?=$examtypename?>" readonly>
                                        </div>
                                        <div id="show_data" class="table-responsive-sm">

                                        </div>
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
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title"><?=$lang['exam_new']?></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmsave" method="POST">
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="subjectid" />
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['exam_subname']?></label>
                        <input type="text" class="form-control border-success" name="subjectname" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['exam_pay']?></label>
                        <input type="number" class="form-control border-success" value="100" required name="paymark">
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['exam_get']?></label>
                        <input type="number" class="form-control border-success" required name="getmark">
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['exam_result']?></label>
                        <select class="form-control border-success" name="result">
                            <?=load_exam_status();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['exam_d']?></label>
                        <select class="form-control border-success" name="d">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                    <?=$lang['exam_save']?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title"><?=$lang['exam_edit']?></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmedit" method="POST">

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    function load_show_subject() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php' ?>",
            data: {
                action: 'show_subject',
            },
            success: function(data) {
                $("#show_subject").html(data);
            }
        });
    }
    load_show_subject();

    function load_show_data() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php' ?>",
            data: {
                action: 'show_data_edit',
            },
            success: function(data) {
                $("#show_data").html(data);
            }
        });
    }
    load_show_data();

    $(document).on("click", "#btnaddrecord", function(e) {
        e.preventDefault();
        var said = $(this).data("said");
        var sname = $(this).data("sname");
        $("[name='subjectid']").val(said);
        $("[name='subjectname']").val(sname);
        $("#btnnewmodal").modal("show");
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    load_show_data();
                    swal.close();
                }
                if (data == 0) {
                    swal("Error!", "Error Save.", "error");
                }
                if (data == 2) {
                    swal("Information!",
                        "Data is already save.",
                        "info");
                }
            }
        });
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            success: function(data) {
                $("#frmedit").html(data);
                $("#editmodal").modal("show");
            }
        });
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Edit data Successful.",
                        "success");
                    load_show_data();
                    swal.close();
                } else {
                    swal("Error!", "Error edit.", "error");
                }
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        swal({
                title: "Delete?",
                text: "<?=$lang['alert_deletetitle'];?>!",
                type: "error",
                showCancelButton: true,
                cancelButtonText: "<?=$lang['alert_btncancel'];?>",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "<?=$lang['alert_confirm'];?>!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'ear/edit_studentexam_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_show_data();
                            swal.close();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btnsaveall", function(e) {
        e.preventDefault();
        var examtype = $("[name='examtype']").val();
        var totalpay = $(this).data("totalpay");
        var totalget = $(this).data("totalget");
        if (examtype == "") {
            swal("Information", "Select exam type.", "info");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/edit_studentexam_action.php'; ?>",
            data: {
                action: 'save_all',
                examtype: examtype,
                totalpay: totalpay,
                totalget: totalget
            },
            success: function(data) {
                if (data == 1) {
                    swal("Successful",
                        "Delete data success.",
                        "success");
                    location.href = "<?=roothtml.'ear/studentexam.php'?>";
                }else {
                    swal("Error",
                        "Delete data failed.",
                        "error");
                }
            }
        });
    });



});
</script>