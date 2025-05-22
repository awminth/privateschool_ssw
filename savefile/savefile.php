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
                    <h1>Save File</h1>
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
                                    <td><button id="btnnew" type="button" class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-plus"></i>&nbsp; <?=$lang['staff_new']?>
                                        </button></td>
                                    <td>
                                        <form method="POST" action="savefile_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action_excel" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Card body -->
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="15%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-5 col-form-label"><?=$lang['staff_show']?></label>
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
                                    <td width="50%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
                                            <div class="col-sm-9">
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
<div class="modal fade animate__animated animate__bounce" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">New File Save</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST">
            <input type="hidden" name="action" value="save"/>
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Description :</label>
                        <input type="text" class="form-control border-success" id="description" name="description">
                    </div>
                    <div class="form-group">
                        <label for="usr">Upload File:</label>
                        <input type="file" name="file" id="qfile"
                            class="form-control border-success"
                            accept=".pdf,.PDF">
                    </div>
                    <div class="form-group">
                        <label for="usr"> Date :</label>
                        <input type="date" class="form-control border-success" name="dt"
                            placeholder="Date" value="<?= date("Y-m-d")?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        <?=$lang['staff_save']?></button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade animate__animated flipInY" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit File Save</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<div class="modal fade animate__animated flipInY" id="editfilemodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Question Type</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmfile" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
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

    $('.select2').select2({
        theme: 'bootstrap4'
    });

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
        $("#category").val('');
        $("#btnnewmodal").modal("show");
    });


    $("#frm").on("submit", function() {
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    window.location.reload();
                }
                else{
                    swal("Error!", "Error Save.", "error");
                }
            }
        });
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frm1").html(data);
            }
        });
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var aid = $("#aid").val();
        var gname = $("[name='gname1']").val();
        var description = $("[name='description1']").val();
        var dt = $("[name='dt1']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                gname: gname,
                description: description,
                dt: dt
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                if (data == 1) {
                    $("#editmodal").modal("hide");
                    swal("Successful", "Edit data success.",
                        "success");
                    load_pag();
                } else {
                    swal("Error", "Edit data failed.", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnupdatefile", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
            data: {
                action: 'editpreparefile',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frmfile").html(data);
                $("#editfilemodal").modal("show");
            }
        });
    });

    $("#frmfile").on("submit", function() {
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'savefile/savefile_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    window.location.reload();
                }
                else{
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
                    url: "<?php echo roothtml.'savefile/savefile_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        path: path
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    success: function(data) {
                        $(".loader").hide();
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            window.location.reload();
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