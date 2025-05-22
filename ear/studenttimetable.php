<?php

include('../config.php');

include(root.'master/header.php') ;

$gradeid=$_SESSION['gradeid'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) <?=$lang['earstu_timetable']?></h1>
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
                                    <td><a href="<?=roothtml.'ear/earstudent.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['staff_back']?>
                                        </a></td>
                                    <td>
                                        <form method="POST" action="studenttimetable_action.php">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="studenttimetable_action.php">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="card card-primary card-outline p-2">
                                <div id="show_table" class="table-responsive-sm">
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

<!-- The Modal -->
<div class="modal fade animate__animated flipInY" id="savemodal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Save Student Timetable</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmsave" method="POST">
                <input type="hidden" name="action" value="save" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_academicyear']?> :</label>
                                <input type="text" class="form-control border-success" name="estuname"
                                    value="<?=$_SESSION['yearname']?>" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_grade']?> :</label>
                                <input type="text" class="form-control border-success" name="estuname"
                                    value="<?=$_SESSION['gradename']?>" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr">Room :</label>
                                <select class="form-control border-success" name="roomid">
                                    <option value="">Choose Room</option>
                                    <?=load_room($gradeid)?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_teachername']?> :</label>
                                <select class="form-control border-success" name="teacherid">
                                    <option value="">Choose Teacher</option>
                                    <?=load_teacher()?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_subjectname']?> :</label>
                                <select class="form-control border-success" name="subjectid">
                                    <option value="">Choose Subject</option>
                                    <?=load_subject_grade($gradeid)?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_time']?> :</label>
                                <select class="form-control border-success" name="timeid">
                                    <option value="">Choose Time</option>
                                    <?=load_time()?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['timetable_dayname']?> :</label>
                                <select class="form-control border-success" name="edname">
                                    <?php for($i=0; $i<count($arr_day); $i++){ ?>
                                    <option value="<?=$arr_day[$i]?>"><?=$arr_day[$i]?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnsave11" class="btn btn-'.$color.'"><i class="fas fa-save"></i>
                        <?=$lang['staff_save']?><button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade animate__animated flipInY" id="editmodal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Student Timetable</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmedit" method="POST">

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studenttimetable_action.php' ?>",
            data: {
                action: 'show',
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on('click', '#btnsave', function(e) {
        e.preventDefault();
        var timeid = $(this).data('timeid');
        var dayname = $(this).data('dayname');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studenttimetable_action.php' ?>",
            data: {
                action: 'save_prepare',
                timeid: timeid,
                dayname: dayname
            },
            success: function(data) {
                $("#frmsave").html(data);
                $("#savemodal").modal("show");
            }
        });
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#savemodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studenttimetable_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    load_pag();
                    swal.close();
                }
                if (data == 0) {
                    swal("Error!", "Error save.", "error");
                }
                if (data == 2) {
                    swal("Warning",
                        "Can't add because it's already in another room's class.",
                        "warning");
                }
            }
        });
    });

    $(document).on('click', '#btnedit', function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studenttimetable_action.php' ?>",
            data: {
                action: 'edit_prepare',
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
            url: "<?php echo roothtml.'ear/studenttimetable_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Edit Successful.",
                        "success");
                    load_pag();
                    swal.close();
                }
                if (data == 0) {
                    swal("Error!", "Error edit.", "error");
                }
                if (data == 2) {
                    swal("Warning",
                        "Can't update because it's already in another room's class.",
                        "warning");
                }
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $("#editmodal").modal("hide");
        swal({
                title: "Delete?",
                text: "Are you sure delete!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'ear/studenttimetable_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag();
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



});
</script>