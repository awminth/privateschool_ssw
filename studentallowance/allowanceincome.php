<?php
include('../config.php');
include(root . 'master/header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Student Allowance Income</h1>
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
                                    <td><a href="<?=roothtml.'studentallowance/incomeyear.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a>
                                    </td>
                                    <td><button id="btnnew" type="button" class="btn btn-sm btn-<?= $color ?>"><i
                                                class="fas fa-plus"></i>&nbsp; <?= $lang['staff_new'] ?>
                                        </button></td>
                                    <td>
                                        <form method="POST" action="allowanceincome_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?= $color ?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?= $lang['staff_excel'] ?></button>
                                        </form>
                                    </td>
                                    <td style="display:none;">
                                        <form method="POST" action="allowanceincome_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-<?= $color ?>"><i
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
                                    <td width="15%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-5 col-form-label"><?= $lang['staff_show'] ?></label>
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
                                                class="col-sm-3 col-form-label"><?= $lang['staff_search'] ?></label>
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
            <div class="modal-header bg-<?= $color ?>">
                <h4 class="modal-title">New Allowance Income</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Student Name :</label>
                        <select class="form-control boder-success select2" name="stuname">
                            <option value="">Selected Student</option>
                            <?= load_student(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> Amount :</label>
                        <input type="number" class="form-control border-success" id="amount" name="amount">
                    </div>
                    <div class="form-group">
                        <label for="usr"> Date :</label>
                        <input type="date" class="form-control border-success" id="dt" name="dt" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?= $color ?>'><i class="fas fa-save"></i>
                        <?= $lang['staff_save'] ?></button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade animate__animated flipInY" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?= $color ?>">
                <h4 class="modal-title">Edit Allowance Income</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type='hidden' id='eaid' name='eaid'>
                    <div class="form-group">
                        <label for="usr"> Student Name :</label>
                        <select class="form-control boder-success select2" name="estuid">
                            <option value="estuname" id="estunameid">Select Student</option>
                            <?= load_student(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> Amount :</label>
                        <input type="number" class="form-control border-success" id="eamount" name="eamount">
                    </div>
                    <div class="form-group">
                        <label for="usr"> Date :</label>
                        <input type="date" class="form-control border-success" id="dt" name="dt" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-<?= $color ?>'><i class="fas fa-edit"></i>
                        <?= $lang['staff_edit'] ?></button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include(root . 'master/footer.php') ?>

<script>
    $(document).ready(function() {

        function load_pag(page) {
            var entryvalue = $("[name='hid']").val();
            var search = $("[name='ser']").val();
            $.ajax({
                type: "post",
                url: "<?php echo roothtml . 'studentallowance/allowanceincome_action.php' ?>",
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


        $(document).on("click", "#btnsave", function(e) {
            e.preventDefault();
            var stuid = $("[name='stuname']").val();
            var amount = $("[name='amount']").val();
            var dt = $("[name='dt']").val();
            if (stuid == "" || amount == "") {
                swal("Information", "Please fill data", "info");
            } else {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml . 'studentallowance/allowanceincome_action.php' ?>",
                    data: {
                        action: 'save',
                        stuid: stuid,
                        amount: amount,
                        dt: dt
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
                            swal.close();
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
            var stuid = $(this).data("stuid");
            var sname = $(this).data("sname");
            var amount = $(this).data("amount");
            $('[name="eaid"]').val(aid);
            $('[name="estuid"]').val(stuid);
            $('[name="estuname"]').val(sname);
            $('[name="eamount"]').val(amount);
            $('#estunameid').val(stuid).trigger('change');
            $("#editmodal").modal("show");
        });


        $(document).on("click", "#btnupdate", function(e) {
            e.preventDefault();
            var aid = $("[name='eaid']").val();
            var stuid = $("[name='estuid']").val();
            var amount = $("[name='eamount']").val();
            $.ajax({
                type: "post",
                url: "<?php echo roothtml . 'studentallowance/allowanceincome_action.php' ?>",
                data: {
                    action: 'update',
                    aid: aid,
                    stuid: stuid,
                    amount: amount
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
                        swal.close();
                        load_pag();
                    } else {
                        swal("Error", data, "error");
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
                        url: "<?php echo roothtml . 'studentallowance/allowanceincome_action.php'; ?>",
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
                                swal.close();
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

    });
</script>