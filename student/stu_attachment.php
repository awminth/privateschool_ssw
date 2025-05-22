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
                <div class="col-sm-6">
                    <h1><?=$lang['stu_attheader']?></h1>
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
                                        <a href="<?=roothtml.'student/student.php'?>" type="button"
                                            class="btn btn-sm btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a>
                                    </td>
                                    <td>
                                        <input type="hidden" name="hid">
                                        <input type="hidden" name="ser">
                                        <a href="#" id="btnnew" type="button" class="btn btn-sm btn-success"><i
                                                class="fas fa-plus"></i>&nbsp; <?=$lang['btnnew']?>
                                        </a>
                                    </td>
                                </tr>
                            </table><!-- Card body -->
                        </div>
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-6 col-form-label"><?=$lang['show']?></label>
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
                                            <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$lang['search']?></label>
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

<!-- import modal -->
<div class="modal fade" id="importmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><?=$lang['stu_attnew']?></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmimport" method="POST">
                <div class='modal-body'>
                    <input type="hidden" name="action" value="save_attachment" />
                    <div class='form-group'>
                        <label for='usr'> <?=$lang['stu_atttitle']?>:</label><br>
                        <input type="text" name="title" class="form-control border border-primary p-1"
                            placeholder="Enter Title" required>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> <?=$lang['stu_attupload']?>:</label><br>
                        <input type="file" name="file" class="form-control border border-primary p-1" required>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                    <?=$lang['btnsave']?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><?=$lang['stu_attedit']?></h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmedit" method="POST">
                <div class='modal-body'>
                    <input type="hidden" name="action" value="edit_attachment" />
                    <input type="hidden" name="eaid" />
                    <div class='form-group'>
                        <label for='usr'> <?=$lang['stu_atttitle']?>:</label><br>
                        <input type="text" name="etitle" class="form-control border border-primary p-1"
                            placeholder="Enter Title" required>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> <?=$lang['stu_attold']?>:</label><br>
                        <input type="text" name="epath" class="form-control border border-primary p-1"
                            placeholder="Enter Title" readonly>
                    </div>
                    <div class='form-group'>
                        <label for='usr'><?=$lang['stu_attupload']?>:</label><br>
                        <input type="file" name="efile" class="form-control border border-primary p-1">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btneditsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                    <?=$lang['btnedit']?></button>
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
                action: 'show_attachment',
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

    $(document).on("click", "#btnnew", function() {
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
                if (data == 1) {
                    swal("Successful!", "Save Data Successful.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", "Save data failed.", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnedit", function() {
        var aid = $(this).data("aid");
        var title = $(this).data("title");
        var path = $(this).data("path");
        $("[name='eaid']").val(aid);
        $("[name='etitle']").val(title);
        $("[name='epath']").val(path);
        $("#editmodal").modal('show');
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editmodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'student/student_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Edit Data Successful.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error", "Edit data failed.", "error");
                }
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
                        action: 'delete_attachment',
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



});
</script>