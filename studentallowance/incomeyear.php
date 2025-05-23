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
                    <h1>Choose Year for Allowance Income</h1>
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
                                    <td><button id="btnnew" type="button" class="btn btn-sm btn-<?= $color ?>"><i
                                                class="fas fa-plus"></i>&nbsp; <?= $lang['staff_new'] ?>
                                        </button></td>
                                    <td>
                                        <form method="POST" action="allowanceyear_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
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
<div class="modal fade" id="newmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Year Name</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Year Name :</label>
                        <input type="text" class="form-control border-success" id="name" name="name">
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

<!-- Edit Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?= $color ?>">
                <h4 class="modal-title">Edit Year Name</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm1" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type='hidden' id='eaid' name='eaid'>
                    <div class="form-group">
                        <label for="usr"> Year Name :</label>
                        <input type="text" class="form-control border-success" id="ename" name="ename">
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
            url: "<?php echo roothtml . 'studentallowance/allowanceyear_action.php' ?>",
            data: {
                action: 'showIncome',
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
        $("#newmodal").modal("show");
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var yearname = $("#name").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'studentallowance/allowanceyear_action.php' ?>",
            data: {
                action: 'saveincomeyear',
                yearname: yearname
            },
            success: function(data) {
                if (data == 1) {
                    $("#newmodal").modal("hide");
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

    $(document).on('click', '#btngo', function() {
        var aid = $(this).data('aid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'studentallowance/allowanceyear_action.php'; ?>",
            data: {
                action: 'goincome',
                aid: aid
            },
            success: function(data) {
                location.href = "<?=roothtml.'studentallowance/allowanceincome.php'?>";
            }
        });
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var yearname = $(this).data("yearname");
        $('[name="eaid"]').val(aid);
        $('[name="ename"]').val(yearname);
        $("#editmodal").modal("show");
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var aid = $("[name='eaid']").val();
        var yearname = $("[name='ename']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml . 'studentallowance/allowanceyear_action.php' ?>",
            data: {
                action: 'updateincomeyear',
                aid: aid,
                yearname: yearname,
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

});
</script>