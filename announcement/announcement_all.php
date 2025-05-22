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
                    <h1><?=$lang['home_allannouncement']?></h1>
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
                                        <form method="POST" action="announcement_all_action.php">
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
                                        <form method="POST" action="announcement_all_action.php">
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
                                                <div class="col-sm-9">
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
                    <div class="form-group">
                        <label for="usr"><?=$lang['ann_description']?>:</label>
                        <textarea class="form-control border-primary" name="description" placeholder="Enter description"
                            rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['staff_date']?> :</label>
                        <input type="date" class="form-control border-primary" name="dt" placeholder="Enter date"
                            required value="<?=date('Y-m-d')?>">
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
                <h4 class="modal-title">Edit Announcements</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmedit" method="POST">
                    <input type="hidden" name="action" value="edit" />
                    <input type="hidden" name="eaid" />
                    <div class="form-group">
                        <label for="usr"><?=$lang['ann_description']?>:</label>
                        <textarea class="form-control border-primary" name="edescription"
                            placeholder="Enter description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="usr"><?=$lang['staff_date']?> :</label>
                        <input type="date" class="form-control border-primary" name="edt" placeholder="Enter date"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btneditsave" class="btn btn-<?=$color?>"><i class="fas fa-edit"></i>
                            <?=$lang['staff_edit']?></button>
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
            url: "<?php echo roothtml.'announcement/announcement_all_action.php' ?>",
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

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_all_action.php' ?>",
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
        var dt = $(this).data("dt");
        $("[name='eaid']").val(aid);
        $("[name='edescription']").val(description);
        $("[name='edt']").val(dt);
        $("#editmodal").modal("show");
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'announcement/announcement_all_action.php' ?>",
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
                    url: "<?php echo roothtml.'announcement/announcement_all_action.php'; ?>",
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