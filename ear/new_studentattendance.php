<?php

include('../config.php');
include(root.'master/header.php'); 

$chkdt = (isset($_SESSION["chk_dt"])?$_SESSION["chk_dt"]:date('Y-m-d'));
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) Student Attendance For
                        <b><?=enDate($chkdt)?></b>
                    </h1>
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
                                    <td><a href="<?=roothtml.'ear/studentattendance.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            Back
                                        </a></td>
                                    <td>
                                        <form method="POST" action="studentattendance_action.php">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="absent_excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <!-- Card body -->
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Show</label>
                                            <div class="col-sm-7">
                                                <select id="entry" class="custom-select btn-sm">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="60%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Search</label>
                                            <div class="col-sm-10">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Search...">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div id="show_table" class="table-responsive-sm">

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
<div class="modal fade" id="reasonmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Add Absent Reason</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmreason" method="POST">
                <input type="hidden" name="aid" />
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Description :</label>
                        <input type="text" class="form-control border-success" name="rmk">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag() {
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'show_student',
                search: search
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    function delete_absent(aid) {
        var aid = aid;
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
                    url: "<?php echo roothtml.'ear/studentattendance_action.php'; ?>",
                    data: {
                        action: 'delete_absent',
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
    }

    $(document).on("change", ".checkItem", function() {
        var aid = $(this).val();
        var chk = '#checkItem' + aid;
        if ($(chk).is(':checked')) {
            $("[name='aid']").val(aid);
            $("#reasonmodal").modal("show");
        } else {
            delete_absent(aid);
        }
    });

    $(document).on("click", "#btnsave", function() {
        var aid = $("[name='aid']").val();
        var rmk = $("[name='rmk']").val();
        if (rmk == "") {
            swal("Information", "Please enter absent reason", "info");
            return false;
        }
        $("#reasonmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'save_absent',
                aid: aid,
                rmk: rmk
            },
            success: function(data) {
                if (data == 1) {
                    swal("Success", "Save absent reason is successfully", "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", "Error save absent", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnfinal", function() {
        var dt = $(this).data("dt");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'final_save',
                dt: dt,
            },
            success: function(data) {
                if (data == 1) {
                    swal("Success", "Save successfully", "success");
                    window.location.href = "<?=roothtml.'ear/studentattendance.php'?>";
                } else {
                    swal("Error", "Error save absent", "error");
                }
            }
        });
    });

});
</script>