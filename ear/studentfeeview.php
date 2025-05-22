<?php
include('../config.php');
include(root.'master/header.php') ;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid mt-2">
            <h1><?=$lang['feeview_title']?> (<?=$_SESSION['stuname']?>)</h1>
            <br>
            <table>
                <tr>
                    <td><a href="<?=roothtml.'ear/studentfee.php'?>" type="button" class="btn btn-sm btn-<?=$color?>"><i
                                class="fas fa-arrow-left"></i>&nbsp;
                            <?=$lang['btnback']?>
                        </a></td>
                    <td>
                        <form method="POST" action="studentfeeview_action.php">
                            <input type="hidden" name="hid">
                            <input type="hidden" name="ser">
                            <button type="submit" name="action" value="excel" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['btnexcel']?></button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="studentfeeview_action.php">
                            <input type="hidden" name="hid">
                            <input type="hidden" name="ser">
                            <button type="submit" name="action" value="pdf" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                        </form>
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
                        <!-- Card body -->
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
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
                                    <td width="60%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-2 col-form-label"><?=$lang['search']?></label>
                                            <div class="col-sm-10">
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

<!-- The Modal -->
<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Student Fee Pay Slip</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmpayslip" method="POST" class="p-3">
                <!-- Modal body -->

            </form>
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
            url: "<?php echo roothtml.'ear/studentfeeview_action.php' ?>",
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



    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var category = $("#category").val();
        if (category == "") {
            swal("Information", "Please fill category", "info");
        } else {
            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'ear/studentfeeview_action.php' ?>",
                data: {
                    action: 'save',
                    category: category
                },
                success: function(data) {
                    if (data == 1) {
                        $("#btnnewmodal").modal("hide");
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
        var vno = $(this).data("vno");
        var feevno = $(this).data("feevno");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfeeview_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid,
                vno: vno,
                feevno: feevno
            },
            success: function(data) {
                if (data == 1) {
                    load_pag();
                    swal.close();
                } else {
                    swal('error', 'error', 'error');
                }
            }
        });
    });



    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var vno = $(this).data("vno");
        var feevno = $(this).data("feevno");
        var totalpay = $(this).data("totalpay");
        swal({
                title: "Delete?",
                text: "<?=$lang['alert_deletetitle'];?>!",
                type: "error",
                showCancelButton: true,
                cancelButtonText: "<?=$lang['alert_btncancel'];?>",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "<?=$lang['alert_confirm'];?>!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'ear/studentfeeview_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        vno: vno,
                        feevno: feevno,
                        totalpay: totalpay
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

    $(document).on("click", "#btnview", function(e) {
        e.preventDefault();
        var feevno = $(this).data('feevno');
        var totalpay = $(this).data('totalpay');
        var paydt = $(this).data('paydt');
        var payname = $(this).data('payname');
        var receivename = $(this).data('receivename');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfeeview_action.php' ?>",
            data: {
                action: 'view',
                feevno: feevno,
                totalpay: totalpay,
                payname: payname,
                receivename: receivename,
                paydt: paydt
            },
            success: function(data) {
                $("#frmpayslip").html(data);
                $("#salarymodal").modal("show");
            }
        });
    });

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

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });



});
</script>