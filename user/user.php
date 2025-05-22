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
                    <h1><?=$lang['home_useraccount']?></h1>
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
                                        <form method="POST" action="user_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="user_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                        </form>
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
                                    <td width="50%" class="float-right">
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
                <h4 class="modal-title">New User</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmsave" method="POST">
                    <input type="hidden" name="action" value="save" />
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
                <h4 class="modal-title">Edit User</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frm1" method="POST">

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
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'user/user_action.php' ?>",
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
        $("#btnnewmodal").modal("show");
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var username = $("[name='username']").val();
        var password = $("[name='password']").val();
        if (username == "" || password == "") {
            swal("Information", "Please fill data", "info");
        } else {
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'user/user_action.php' ?>",
                data: {
                    action: 'save',
                    username: username,
                    password: password
                },
                beforeSend: function() {
                    $(".loader").show();
                },
                success: function(data) {
                    $(".loader").hide();
                    if (data == 1) {
                        $("#btnnewmodal").modal("hide");
                        swal("Successful!", "Save Successful.",
                            "success");

                        load_pag();
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
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'user/user_action.php' ?>",
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
        var username = $("[name='username1']").val();
        var password = $("[name='password1']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'user/user_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                username: username,
                password: password
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
                    url: "<?php echo roothtml.'user/user_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
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
                            load_pag();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btnpermission", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        location.href = "<?=roothtml.'user/permission.php?aid='?>" + aid;



    });

});
</script>