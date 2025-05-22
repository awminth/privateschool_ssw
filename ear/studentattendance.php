<?php
include('../config.php');
include(root.'master/header.php');

$gradeid=$_SESSION['gradeid']; 
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) <?=$lang['earstu_attendance']?></h1>
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
                                    <td><a href="#" type="button" id="btnnew" class="btn btn-sm btn-success"><i
                                                class="fas fa-plus"></i>&nbsp;
                                            <?=$lang['staff_new']?>
                                        </a></td>
                                    <td><a href="#" type="button" id="btnedit" class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-edit"></i>&nbsp;
                                            <?=$lang['staff_edit']?>
                                        </a></td>
                                    <td><a href="#" type="button" id="btndelete" class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash"></i>&nbsp;
                                            <?=$lang['staff_delete']?>
                                        </a></td>
                                    <td><a href="<?=roothtml.'ear/studentatt_view.php'?>" type="button"
                                            class="btn btn-sm btn-info"><i class="fas fa-eye"></i>&nbsp;
                                            <?=$lang['att_alist']?>
                                        </a></td>
                                    <td>
                                        <form method="POST" action="studentattendance_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="dtfrom">
                                            <input type="hidden" name="dtto">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="studentattendance_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <input type="hidden" name="dtfrom">
                                            <input type="hidden" name="dtto">
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
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="card card-primary card-outline p-2">
                                        <div class='form-group'>
                                            <label for='usr'> <?=$lang['nc_date']?> :</label>
                                            <input type='month' class='form-control border-success' name='from'
                                                value="<?=date('Y-m')?>">
                                        </div>
                                        <!-- <div class='form-group'>
                                            <label for='usr'> To :</label>
                                            <input type='date' class='form-control border-success' name='to'
                                                value="<?=date('Y-m-d')?>">
                                        </div> -->
                                        <button id="btnsearch" type="button" class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-search"></i>&nbsp;
                                            <?=$lang['staff_search']?>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <div class="form-group row">
                                                        <div class="col-sm-10">
                                                            <select id="entry" class="custom-select btn-sm">
                                                                <option value="10" selected>10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="80%"></td>
                                            </tr>
                                        </table>
                                        <div id="show_table" class="table-responsive-sm">

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
<div class="modal fade" id="newmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Save Attendance</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['nc_date']?> :</label>
                        <input type="date" class="form-control border-success" name="dtsave" value="<?=date('Y-m-d')?>">
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

<!-- edit Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Attendance</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['nc_date']?> :</label>
                        <input type="date" class="form-control border-success" name="edtsave"
                            value="<?=date('Y-m-d')?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnprepare' class='btn btn-<?=$color?>'><i class="fas fa-edit"></i>
                        <?=$lang['staff_edit']?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete Modal -->
<div class="modal fade" id="deletemodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Delete Attendance</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['nc_date']?> :</label>
                        <input type="date" class="form-control border-success" name="ddtsave"
                            value="<?=date('Y-m-d')?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btndeletesave' class='btn btn-danger'><i class="fas fa-trash"></i>
                        <?=$lang['staff_delete']?></button>
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
        var dtfrom = $("[name='dtfrom']").val();
        var dtto = $("[name='dtto']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                dtfrom: dtfrom,
                dtto: dtto
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
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        $("[name='dtfrom']").val(from);
        $("[name='dtto']").val(to);
        load_pag();
    });

    $(document).on("click", "#btnnew", function(e) {
        e.preventDefault();
        $("#newmodal").modal("show");
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var dtsave = $("[name='dtsave']").val();
        if (dtsave == "") {
            swal("Warning", "Please choose date.", "warning");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'chk_save',
                dtsave: dtsave,
            },
            success: function(data) {
                if (data == 1) {
                    window.location.href = "<?=roothtml.'ear/new_studentattendance.php'?>";
                }
                if (data == 2) {
                    swal("Warning", "Attendance is already have been.", "warning");
                }
                if (data == 0) {
                    swal("Error", "Error", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        $("#editmodal").modal("show");
    });

    $(document).on("click", "#btnprepare", function(e) {
        e.preventDefault();
        var dtsave = $("[name='edtsave']").val();
        if (dtsave == "") {
            swal("Warning", "Please choose date.", "warning");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'chk_prepare',
                dtsave: dtsave,
            },
            success: function(data) {
                if (data == 1) {
                    window.location.href = "<?=roothtml.'ear/new_studentattendance.php'?>";
                }
                if (data == 0) {
                    swal("Error", "Error edit search", "error");
                }
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        $("#deletemodal").modal("show");
    });

    $(document).on("click", "#btndeletesave", function(e) {
        e.preventDefault();
        var dtsave = $("[name='ddtsave']").val();
        if (dtsave == "") {
            swal("Warning", "Please choose date.", "warning");
            return false;
        }
        $("#deletemodal").modal("hide");
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
                        action: 'delete',
                        dtsave: dtsave
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag();
                            swal.close();
                        }
                        if (data == 0) {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                        if (data == 2) {
                            swal("Warning",
                                "Can't delete data and choose another date.",
                                "warning");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btnkhalist", function(e) {
        e.preventDefault();
        var stuname = $(this).data("stuname");
        var earstuid = $(this).data("earstuid");
        var serdt = $(this).data("serdt");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'kha_list',
                stuname: stuname,
                earstuid: earstuid,
                serdt: serdt
            },
            success: function(data) {
                location.href = "<?=roothtml.'ear/studentatt_khalist.php'?>";
            }
        });
    });



});
</script>