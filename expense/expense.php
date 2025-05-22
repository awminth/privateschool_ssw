<?php
include('../config.php');
include(root.'master/header.php'); 
?>
<title>SHOP | Expense</title>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?=$lang['home_expense']?></h1>
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
                                        <form method="POST" action="expense_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="expense_action.php">
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
<div class="modal animate__animated animate__slideInDown" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">New Expense</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type="hidden" name="action" id="action" value="save">
                    <div class="form-group">
                        <label for="usr"> <?=$lang['expense_category']?> :</label>
                        <select class="form-control border-success" name="expcategory">
                            <option value=''>Select Category</option>
                            <?=load_expensecategory();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['expense_description']?> :</label>
                        <input type="text" class="form-control border-success" name="description"
                            placeholder="description">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['expense_cash']?> :</label>
                                <input type="number" id="cash" class="form-control border-success" name="cash"
                                    placeholder="00.00">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="usr"> <?=$lang['expense_mobile']?> :</label>
                                <input type="number" id="mobile" class="form-control border-success" name="mobile"
                                    placeholder="00.00">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['expense_rmk']?> :</label>
                        <select class="form-control border-success" name="rmk">
                            <option value=''>Select Mobile Rmk</option>
                            <?=load_pay();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['expense_totalamount']?> :</label>
                        <input type="number" class="form-control border-success" name="amt" placeholder="00.00">
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['expense_date']?> :</label>
                        <input type="date" class="form-control border-success" name="date"
                            value="<?php echo date('Y-m-d'); ?>">
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
<div class="modal animate__animated animate__slideInDown" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Expense</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
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
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
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

    function calculate() {
        var cash = $("[name='cash']").val();
        var mobile = $("[name='mobile']").val();
        var total = Number(cash) + Number(mobile);
        $("[name='amt']").val(total);
    }

    $(document).on("keyup", "#cash", function() {
        calculate();
    });
    $(document).on("keyup", "#mobile", function() {
        calculate();
    });


    $("#frm").on("submit", function(e) {
        e.preventDefault();
        var expcategory = $("[name='expcategory']").val();
        var description = $("[name='description']").val();
        var amount = $("[name='amt']").val();
        var mobile = $("[name='mobile']").val();
        var rmk = $("[name='rmk']").val();

        if (mobile > 0 && mobile != '') {
            if (rmk == "") {
                swal("Information", "Please Mobile Rmk", "info");
                return;
            }
        }


        var formData = new FormData(this);
        if (description == "" || amount <= 0 || expcategory == "") {
            swal("Information", "Please fill all data", "info");
        } else {

            $("#btnnewmodal").modal("hide");
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'expense/expense_action.php' ?>",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".loader").show();
                },
                success: function(data) {

                    $(".loader").hide();
                    if (data == "1") {
                        $("#btnnewmodal").modal("hide");
                        swal("Successful!",
                            "Save data Successful.",
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
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
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

    function calculate1() {
        var cash = $("[name='cash1']").val();
        var mobile = $("[name='mobile1']").val();
        var total = Number(cash) + Number(mobile);
        $("[name='amt1']").val(total);
    }

    $(document).on("keyup", "#cash1", function() {
        calculate1();
    });
    $(document).on("keyup", "#mobile1", function() {
        calculate1();
    });


    $("#frm1").on("submit", function(e) {
        e.preventDefault();

        var expcategory = $("[name='expcategory1']").val();
        var description = $("[name='description1']").val();
        var amt = $("[name='amt1']").val();
        var mobile = $("[name='mobile1']").val();
        var rmk = $("[name='rmk1']").val();



        if (mobile > 0 && mobile != '') {
            if (rmk == "") {
                swal("Information", "Please Mobile Rmk", "info");
                return;
            }
        }


        var formData = new FormData(this);
        if (description == "" || amt <= 0 || expcategory == "") {
            swal("Information", "Please fill all data", "info");
        } else {

            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'expense/expense_action.php' ?>",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".loader").show();
                },
                success: function(data) {

                    $(".loader").hide();
                    if (data == 1) {
                        $("#editmodal").modal('hide');
                        swal("Successful", "Edit data success.",
                            "success");
                        load_pag();
                    } else {
                        swal("Error", "Edit data failed.", "error");
                    }
                }
            });
        }
    });


    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var vno = $(this).data("vno");
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
                    url: "<?php echo roothtml.'expense/expense_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        vno: vno
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


    $(document).on("click", "#btneditfile", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php'; ?>",
            data: {
                action: 'editfile',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frmfile").html(data);
            }
        });
    });

    $("#frmfile").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#filemodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'expense/expense_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                if (data == "success") {
                    swal("Successful",
                        "Change file success.",
                        "success");
                    location.href =
                        "<?php echo roothtml.'expense/expense.php' ?>";
                }
                if (data == "fail") {
                    swal("Error",
                        "Change file failed.",
                        "error");
                }
                if (data == "nofile") {
                    swal("Warning",
                        "Please choose file.",
                        "warning");
                }
                if (data == "wrongtype") {
                    swal("Information",
                        "Your file must be .pdf, .xls, .xlsx, .docx !",
                        "info");
                }
            }
        });
    });

});
</script>