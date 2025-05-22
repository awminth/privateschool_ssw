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
                    <h1><?=$lang['home_managetime']?></h1>
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
                                        <form method="POST" action="time_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="time_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
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
                            <table width="100%">
                                <tr>
                                    <td width="20%">
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
                                    <td width="60%" class="float-right">
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
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">New Time</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['time']?> :</label>
                        <input type="text" class="form-control border-success" id="category" name="category">
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
                <h4 class="modal-title">Edit Grade</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->
                <div class='modal-body'>
                    <input type='hidden' id='aid' name='aid' />
                    <input type='hidden' id='action' name='action' value='editsave' />
                    <div class='form-group'>
                        <label for='usr'> <?=$lang['time']?> :</label>
                        <input type='text' class='form-control border-success' id='category1' name='category1'>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-<?=$color?>'><i class='fas fa-edit'></i>
                        <?=$lang['staff_edit']?></button>
                </div>
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
            url: "<?php echo roothtml.'stepup/time_action.php' ?>",
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

    $(document).on("click", "#btnnew", function() {
        $("#category").val('');
        $("#btnnewmodal").modal("show");
    });


    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var category = $("#category").val();
        if (category == "") {
            swal("Information", "Please fill category", "info");
        } else {
            $("#btnnewmodal").modal("hide");
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'stepup/time_action.php' ?>",
                data: {
                    action: 'save',
                    category: category
                },
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
        }
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var name = $(this).data("name");
        $("[name='category1']").val(name);
        $("[name='aid']").val(aid);
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var aid = $("[name='aid']").val();
        var category = $("[name='category1']").val();
        if (category == "") {
            swal("Warning", "Please fill data", "warning");
            return false;
        }
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'stepup/time_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                category: category
            },
            success: function(data) {
                if (data == 1) {
                    swal("Successful", "Edit data success.",
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
                    url: "<?php echo roothtml.'stepup/time_action.php'; ?>",
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