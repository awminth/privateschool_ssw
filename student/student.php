<?php
    include('../config.php');
    include(root.'master/header.php'); 
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-3">
                <div class="col-sm-8">
                    <h1><?=$lang['home_student']?></h1>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="<?=roothtml.'upload/student/StudentsData.xlsx'?>" download>
                        <i class="fas fa-file-excel"></i>&nbsp;Download Example Excel
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
                        <div class="card-header">
                            <table>
                                <tr>
                                    <td>
                                        <a href="<?=roothtml.'student/new_student.php'?>" type="button"
                                            class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>&nbsp;
                                            <?=$lang['btnnew']?>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="student_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['btnexcel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="student_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-pdf"></i>&nbsp;PDF</button>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button" id="btnimport" class="btn btn-sm btn-primary"><i
                                                class="fas fa-file-excel"></i>&nbsp;Import
                                            Data</button>
                                    </td>
                                </tr>
                            </table><!-- Card body -->
                        </div>
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-6 col-form-label"><?=$lang['show']?></label>
                                            <div class="col-sm-6">
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
                                            <label for="inputEmail3"
                                                class="col-sm-2 col-form-label"><?=$lang['search']?></label>
                                            <div class="col-sm-10">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Searching . . . . . ">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div id="show_table" class="table-responsive-sm">

                            </div>
                            <br>
                            <div id="show_sql"></div>
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

<!-- image modal -->
<div class="modal fade" id="viewmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">View Photo</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class='modal-body'>
                <div class='form-group'>
                    <label for='usr'> Photo:</label><br>
                    <img src='' id="showimg" style='width:100%;height:220px;' />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- import modal -->
<div class="modal fade" id="importmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Import Data</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmimport" method="POST">
                <div class='modal-body'>
                    <input type="hidden" name="action" value="import" />
                    <div class='form-group'>
                        <label for='usr'> Upload Excel File:</label><br>
                        <input type="file" name="file" id="photo" class="form-control border border-primary p-1"
                            accept=".xlsx,.XLSX,.xls,.XLS" required>
                        <span>Upload file must be .xls, .xlsx, .XLS, .XLSX</span>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pag(pageid);
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag();
    });


    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on("click", "#btnedit", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: {
                action: 'prepare',
                aid: aid
            },
            success: function(data) {
                location.href = "<?php echo roothtml.'student/new_student.php' ?>";
            }
        });
    });

    $(document).on("click", "#btnview", function() {
        var img = $(this).data("path");
        var path = "<?php echo roothtml.'upload/noimage.png' ?>";
        if (img != "") {
            path = "<?php echo roothtml.'upload/student/' ?>" + img;
        }
        $('#showimg').attr('src', path);
        $("#viewmodal").modal('show');
    });

    $(document).on("click", "#btnattachment", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: {
                action: 'attachment',
                aid: aid,
            },
            success: function(data) {
                location.href = "<?php echo roothtml.'student/stu_attachment.php' ?>";
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var path = $(this).data("path");
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
                    url: "<?php echo roothtml.'student/student_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        path: path
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

    $(document).on("click", "#btnimport", function(e) {
        e.preventDefault();
        $("#importmodal").modal('show');
    });

    $("#frmimport").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#importmodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                swal("Successful!", "Import Data Successful.",
                    "success");
                load_pag();
                swal.close();
                // $("#show_sql").html(data);
            }
        });
    });

});
</script>