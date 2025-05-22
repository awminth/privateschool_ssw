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
                    <h1><?=$lang['home_parentannouncement']?></h1>
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
                                        <button id="btnnew" type="button" class="btn btn-sm btn-primary"><i
                                                class="fas fa-plus"></i>&nbsp; <?=$lang['staff_new']?>
                                        </button>
                                    </td>
                                    <td>
                                        <form method="POST" action="announcement_parent_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="dtfrom">
                                            <input type="hidden" name="dtto">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="announcement_parent_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="dtfrom">
                                            <input type="hidden" name="dtto">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                        </form>
                                    </td>
                                </tr>
                            </table><!-- Card body -->
                        </div>
                        <div class="card-body row">
                            <div class="col-sm-3 card card-primary card-outline">
                                <div class="form-group">
                                    <label for="usr"><?=$lang['ann_from']?> :</label>
                                    <input type="date" class=" form-control border-primary" name="from"
                                        value="<?=date('Y-m-d')?>">
                                </div>
                                <div class="form-group">
                                    <label for="usr"><?=$lang['ann_to']?> :</label>
                                    <input type="date" class=" form-control border-primary" name="to"
                                        value="<?=date('Y-m-d')?>">
                                </div>
                                <div class="form-group">
                                    <button type='button' id='btnsearch' class='btn btn-<?=$color?> form-control'><i
                                            class="fas fa-search"></i>
                                        <?=$lang['staff_search']?></button>
                                </div>
                            </div>
                            <div class="col-sm-9 ">
                                <table width="100%">
                                    <tr>
                                        <td width="20%">
                                            <div class="form-group row">
                                                <label for="inputEmail3"
                                                    class="col-sm-6 col-form-label"><?=$lang['staff_show']?></label>
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
                                        <td width="65%" class="float-right">
                                            <div class="form-group row">
                                                <label for="inputEmail3"
                                                    class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
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
                <h4 class="modal-title">Add Announcement</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmsave" method="POST">
                    <input type="hidden" name="action" value="save" />
                    <div class="form-group ">
                        <label for="usr"><?=$lang['ann_studentname']?>:</label>
                        <select class="form-control border-primary select2" name="student" id="student">
                            <option value="">Choose Student</option>
                            <?php echo load_student_app(); ?>
                        </select>
                    </div>
                    <div id="show_parent">
                        <label for="usr"><?=$lang['ann_parentname']?>:</label>
                        <input type="text" class="form-control border-primary" name="parent" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['ann_description']?>:</label>
                        <textarea class="form-control border-primary" name="description" placeholder="Enter description"
                            rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['ann_date']?> :</label>
                        <input type="date" class="form-control border-primary" name="dt" placeholder="Enter date"
                            required value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="form-group">
                        <label for="usr">Upload File :</label>
                        <input type="file" name="file" class="form-control  border-primary p-1"
                            accept=".xls,.pdf,.zip,.rar">
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                            <?=$lang['staff_save']?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit App User</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmedit" method="POST">


                </form>
            </div>
        </div>
    </div>
</div>

<!-- new Modal -->
<div class="modal fade" id="btnfilemodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Announcement File</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmfile" method="POST">
                    <input type="hidden" name="action" value="change_file" />
                    <input type="hidden" name="faid" />
                    <div class="form-group">
                        <label for="usr">Old File :</label>
                        <input type="text" class="form-control border-primary" name="path" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr">New Upload File :</label>
                        <input type="file" name="cfile" class="form-control  border-primary p-1"
                            accept=".xls,.pdf,.zip,.rar" required>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' id='btnfilesave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                            Save</button>
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
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var dtfrom = $("[name='dtfrom']").val();
        var dtto = $("[name='dtto']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                dtto: dtto,
                dtfrom: dtfrom
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

    $(document).on("click", "#btnsearch", function() {
        var dtfrom = $("[name='from']").val();
        var dtto = $("[name='to']").val();
        $("[name='dtfrom']").val(dtfrom);
        $("[name='dtto']").val(dtto);
        load_pag();
    });

    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show");
    });

    $(document).on("change", "#student", function(e) {
        e.preventDefault();
        var data = $(this).val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: {
                action: 'parent',
                data: data
            },
            success: function(data) {
                $("#show_parent").html(data);
            }
        });
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error!", "Error Save.", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var description = $(this).data("description");
        var sid = $(this).data("sid");
        var sname = $(this).data("sname");
        var pid = $(this).data("pid");
        var pname = $(this).data("pname");
        var path = $(this).data("path");
        var dt = $(this).data("dt");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: {
                action: 'prepare',
                aid: aid,
                description: description,
                sid: sid,
                sname: sname,
                pid: pid,
                pname: pname,
                path: path,
                dt: dt
            },
            success: function(data) {
                $("#frmedit").html(data);
                $("#editmodal").modal("show");
            }
        });
        $("#editmodal").modal("show");
    });

    $(document).on("change", "#e_student", function(e) {
        e.preventDefault();
        var data = $(this).val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: {
                action: 'e_parent',
                data: data
            },
            success: function(data) {
                $("#e_show_parent").html(data);
            }
        });
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error!", "Error Save.", "error");
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
                    url: "<?php echo roothtml.'announcement/announcement_parent_action.php'; ?>",
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

    $(document).on("click", "#btnfile", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var path = $(this).data("path");
        $("[name='faid']").val(aid);
        $("[name='path']").val(path);
        $("#btnfilemodal").modal("show");
    });

    $("#frmfile").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnfilemodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_parent_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("Error!", "Error Save.", "error");
                }
            }
        });
    });

});
</script>