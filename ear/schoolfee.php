<?php
include('../config.php');
include(root.'master/header.php');
?>
<style>
.t1 {
    border: 1px solid;
    padding: 2px 2px 2px 2px;
    text-align: center;
}

.t2 {
    width: 100%;
    border-collapse: collapse;
    padding: 2px 2px 2px 2px;
    text-align: center;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Student Fee For (<?=$_SESSION["yearname"]?>)</h1>
            <br>
            <table width="100%">
                <tr>
                    <td width="7%">
                        <a href="<?=roothtml.'ear/earstudent.php'?>" type="button"
                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                            Back
                        </a>
                    </td>
                    <td>
                        <form method="POST" action="schoolfee_action.php">
                            <input type="hidden" name="serdt" value="<?=date('Y')?>" style="">
                            <input type="hidden" name="ser">
                            <input type="hidden" name="h_gid">
                            <input type="hidden" name="hid">
                            <button type="submit" name="action" value="excel" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                        </form>
                    </td>
                    <td width="20%" class="float-right">
                        <div class="form-group row">
                            <label for="inputEmail3"
                                class="col-sm-5 col-form-label"><?=$lang['show']?></label>
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
                    <td width="20%" class="float-right mr-5">
                        <select class="form-control select2 border-<?=$color?>" id="grade" name="grade">
                            <option value="">Select Grade</option>
                            <?=load_grade();?>
                        </select>
                    </td>
                </tr>
            </table>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        
                        <input type="hidden" name="ser">
                        <!-- Card body -->
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <h5 class="text-primary">YEAR -
                                            <span id="show_currentyear"><?=date("Y")?></span>
                                        </h5>
                                    </td>
                                    <td width="50%" class="text-center">
                                        <button id="btnleft" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-left"></i></button>
                                        <button id="btntoday" type="button" class="btn btn-primary btn-sm">Current
                                            Year</button>
                                        <button id="btnright" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-right"></i></button>
                                    </td>
                                    <td width="30%">
                                        <input type="search" class="form-control" id="searching"
                                            placeholder="Search ....">
                                    </td>
                                </tr>
                            </table>

                            <div id="show_table" class="table-responsive-sm pt-5">

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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Add Student Fee</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmsave" method="POST">

            </form>
        </div>
    </div>
</div>

<!-- edit Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Student Fee</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmedit" method="POST">

            </form>
        </div>
    </div>
</div>

<!-- Pay slip Modal -->
<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Student Fee Pay Slip</h4>
                <button type="button" class="close text-white" id="btnpayclose">&times;</button>
            </div>
            <form id="frmpayslip" method="POST" class="p-3">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<!-- Pay slip Modal -->
<div class="modal fade" id="salarymodal1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Student Fee Pay Slip</h4>
                <button type="button" class="close text-white" id="btnpayclose1">&times;</button>
            </div>
            <form id="frmpayslip1" method="POST" class="p-3">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<!-- Pay slip Modal -->
<div class="modal fade" id="printmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Student Fee Pay Slip</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmvoucher" method="POST" class="p-3">

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {
    //print js fun
    function print_fun(place) {
        printJS({
            printable: place,
            type: 'html',
            style: 'table, tr,td {font-weight: bold; font-size: 10px;border-bottom: 1px solid LightGray;border-collapse: collapse;}' +
                '.txtc{text-align: center;font-weight: bold;}' +
                '.txtr{text-align: right;font-weight: bold;}' +
                '.txtl{text-align: left;font-weight: bold;}' +
                ' h5{ text-align: center;font-weight: bold;}' +
                '.fs{font-size: 10px;font-weight: bold;}' +
                '.txt,h5,h3,h6 {text-align: center;font-size: 10px;font-weight: bold;}' +
                '.lt{width:50%;float:left;font-weight: bold;}' +
                '.rt{width:50%;float:right;font-weight: bold;}' +
                '.wno{width:5%;font-weight: bold;}',
        });
    }

    function load_pag(page) {
        var search = $("[name='ser']").val();
        var serdt = $("[name='serdt']").val();
        var gradeid = $("[name='h_gid']").val();
        var entryvalue = $("[name='hid']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'show_view',
                search: search,
                serdt: serdt,
                gradeid: gradeid,
                entryvalue: entryvalue,
                page_no: page
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on("keyup", "#searching", function(e) {
        e.preventDefault();
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on("change", "#grade", function(e) {
        e.preventDefault();
        var data = $(this).val();
        $("[name='h_gid']").val(data);
        load_pag();
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag();
    });

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pag(pageid);
    });

    $(document).on("click", "#btntoday", function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'todaydt',
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                $("#show_currentyear").html(data);
                load_pag();
            }
        });
    });

    $(document).on("click", "#btnright", function(e) {
        e.preventDefault();
        var dt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'rightdt',
                dt: dt
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                $("#show_currentyear").html(data);
                load_pag();
            }
        });
    });

    $(document).on("click", "#btnleft", function(e) {
        e.preventDefault();
        var dt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'leftdt',
                dt: dt
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                $("#show_currentyear").html(data);
                load_pag();
            }
        });
    });

    $(document).on("click", "#btnfeepay", function(e) {
        e.preventDefault();
        var yid = $(this).data('yid');
        var yname = $(this).data('yname');
        var gid = $(this).data('gid');
        var gname = $(this).data('gname');
        var earid = $(this).data('earid');
        var stuname = $(this).data('stuname');
        var monthname = $(this).data('monthname');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'prepare_save',
                yid: yid,
                yname: yname,
                gid: gid,
                gname: gname,
                earid: earid,
                stuname: stuname,
                monthname: monthname,
            },
            success: function(data) {
                $("#frmsave").html(data);
                $("#newmodal").modal("show");
            }
        });
    });

    function calculatefun() {
        var feeamt = $("[name='feeamt'").val();
        var ferryamt = $("[name='ferryamt'").val();
        var foodamt = $("[name='foodamt'").val();
        var otheramt = $("[name='otheramt'").val();
        var registeramt = $("[name='registeramt'").val();
        var uniformamt = $("[name='uniformamt'").val();
        var materialamt = $("[name='materialamt'").val();
        var sample_disc = $("[name='disc'").val();
        var disc = (Number(sample_disc)*Number(feeamt))/100;
        var sample_total = Number(feeamt) + Number(ferryamt) + Number(foodamt) + Number(otheramt)
                        + Number(registeramt) + Number(uniformamt) + Number(materialamt);
                        
        //var disc = (Number(sample_disc)*Number(sample_total))/100;
        var total=Number(sample_total)-Number(disc);

        var cash = $("[name='cash'").val();
        var mobile = $("[name='mobile'").val();
        var totalpay = $("[name='totalpay'").val();
        var remain = $("[name='remain'").val();
        var a2 = Number(cash) + Number(mobile);
        var a3 = Number(total) - Number(a2);
        $("[name='totalamt'").val(total);
        $("[name='totalpay'").val(a2);
        $("[name='remain'").val(a3);
    }

    $(document).on("keyup", "#foodamt", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#ferryamt", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#otheramt", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#registeramt", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#uniformamt", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#materialamt", function(e) {
        e.preventDefault();
        calculatefun();
    });
    
    $(document).on("keyup", "#disc", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#cash", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $(document).on("keyup", "#mobile", function(e) {
        e.preventDefault();
        calculatefun();
    });

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var totalpay = $("[name='totalpay'").val();
        var mobile = $("[name='mobile'").val();
        var mobilermk = $("[name='mobilermk']").val();
        var disc = $("[name='disc'").val();
        if (totalpay <= 0) {
            swal("Info", "Please fill pay amount.", "info");
            return false;
        }
        if (mobile > 0 && mobile != "") {
            if (mobilermk == "") {
                swal('info', 'Please fill mobile rmk', 'info');
                return;
            }
        }
        $("#newmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    $("#frmpayslip").html(data);
                    $("#newmodal").on("hidden.bs.modal", function() {
                        $("#salarymodal").modal("show");
                    });
                } else {
                    swal("error", "error", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnpayclose", function(e) {
        e.preventDefault();
        window.location.reload();
    });

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });

    $(document).on("click", "#btnfeeedit", function(e) {
        e.preventDefault();
        var yid = $(this).data('yid');
        var yname = $(this).data('yname');
        var gid = $(this).data('gid');
        var gname = $(this).data('gname');
        var earid = $(this).data('earid');
        var stuname = $(this).data('stuname');
        var monthname = $(this).data('monthname');
        var vno = $(this).data('vno');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: 'prepare_edit',
                yid: yid,
                yname: yname,
                gid: gid,
                gname: gname,
                earid: earid,
                stuname: stuname,
                monthname: monthname,
                vno: vno
            },
            success: function(data) {
                $("#frmedit").html(data);
                $("#editmodal").modal("show");
            }
        });
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var totalpay = $("[name='totalpay'").val();
        var mobile = $("[name='mobile'").val();
        var mobilermk = $("[name='mobilermk']").val();
        if (totalpay <= 0) {
            swal("Info", "Please fill pay amount.", "info");
            return false;
        }
        if (mobile > 0 && mobile != "") {
            if (mobilermk == "") {
                swal('info', 'Please fill mobile rmk', 'info');
                return;
            }
        }
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                // swal("",data,"");
                if (data != 0) {
                    $("#frmpayslip1").html(data);
                    $("#editmodal").on("hidden.bs.modal", function() {
                        $("#salarymodal1").modal("show");
                    });
                } else {
                    swal("error", "error", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnpayclose1", function(e) {
        e.preventDefault();
        window.location.reload();
    });

    $(document).on("click", "#btndeletefeepay", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: "delete_feepay",
                vno: vno
            },
            success: function(data) {
                // swal("",data,"");
                if (data == 1) {
                    swal("Success", "Delete data is successful.", "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("error", "error", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnvoucherfeepay", function(e) {
        e.preventDefault();
        var vno = $(this).data("vno");
        var yname = $(this).data('yname');
        var gname = $(this).data('gname');
        var stuname = $(this).data('stuname');
        var monthname = $(this).data('monthname');
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/schoolfee_action.php' ?>",
            data: {
                action: "voucher_feepay",
                vno: vno,
                yname: yname,
                gname: gname,
                stuname: stuname,
                monthname: monthname,
            },
            success: function(data) {
                $("#frmvoucher").html(data);
                $("#editmodal").on("hidden.bs.modal", function() {
                    $("#printmodal").modal("show");
                });
            }
        });
    });



});
</script>