<?php
include('../config.php');
include(root.'master/header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-2">
                <div class="col-sm-12">
                    <h1>Sale Uniform</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <input type="hidden" name="hid">
                <input type="hidden" name="ser">
                <input type="hidden" name="hid1">
                <input type="hidden" name="ser1">
                <div class="col-sm-6">
                    <div class="card card-primary card-outline p-2">
                        <table width="100%">
                            <tr>
                                <td width="20%">
                                </td>
                                <td width="80%" class="float-right">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
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
                </div>
                <div class="col-sm-6">
                    <div class="card card-primary card-outline p-2">
                        <div class="pr-2">
                            <div class="form-group row">
                                <label for="usr" class="col-sm-5 text-right">Student Name</label>
                                <select class="form-control select2 col-sm-7" name="student">
                                    <option value="">Selected Student</option>
                                    <?=load_student();?>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="usr" class="col-sm-5 text-right">Student Grade</label>
                                <select class="form-control select2 col-sm-7" name="grade">
                                    <option value="">Selected Grade</option>
                                    <?=load_grade();?>
                                </select>
                            </div>
                        </div>
                        <div id="show_table1" class="table-responsive-sm">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Sale Uniform</h4>
                <button type="button" class="close text-white" id="btnpayclose">&times;</button>
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
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/uniformsell_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
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

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on('click', '#btnchooseitem', function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var itemname = $(this).data('itemname');
        var size = $(this).data('size');
        var price = $(this).data('price');
        var categoryid = $(this).data('categoryid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/uniformsell_action.php' ?>",
            data: {
                action: 'add_item',
                aid: aid,
                itemname: itemname,
                size: size,
                price: price,
                categoryid: categoryid
            },
            success: function(data) {
                load_chooseitem();
            }
        });
    });

    $(document).on('click', '#btndelete', function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/uniformsell_action.php' ?>",
            data: {
                action: 'delete_item',
                aid: aid,
            },
            success: function(data) {
                load_chooseitem();
            }
        });
    });

    function load_chooseitem() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/uniformsell_action.php' ?>",
            data: {
                action: 'choose_item',
            },
            success: function(data) {
                $("#show_table1").html(data);
            }
        });
    }
    load_chooseitem();

    function calc_amt() {
        var disc = $("[name='disc']").val();
        var tax = $("[name='tax']").val();
        var total = $("[name='total']").val();
        if (disc == '') {
            disc = 0;
        }
        if (tax == '') {
            tax = 0;
        }
        var pdis = total * (disc / 100);
        var ptax = total * (tax / 100);

        var sub = Number(total) + Number(ptax);
        var total = sub - pdis;
        $("[name='grandtotal']").val(total);
    }

    $(document).on("keyup", "#disc", function() {
        calc_amt();
    });

    $(document).on("keyup", "#tax", function() {
        calc_amt();
    });

    $(document).on("click", "#btnsave", function(e) {
        var student = $("[name='student']").val();
        var grade = $("[name='grade']").val();
        var disc = $("[name='disc']").val();
        var tax = $("[name='tax']").val();
        var total = $("[name='total']").val();
        var grandtotal = $("[name='grandtotal']").val();
        if (student == "" || grade == "") {
            swal("Information", "Please choose student and grade.", "info");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/uniformsell_action.php' ?>",
            data: {
                action: 'save',
                disc: disc,
                tax: tax,
                total: total,
                grandtotal: grandtotal,
                student: student,
                grade: grade
            },
            success: function(data) {
                if (data != 0) {
                    // swal("Success","Save data successful.","success");
                    // load_chooseitem();
                    // swal.close();
                    $("#frmpayslip").html(data);
                    $("#salarymodal").modal("show");
                } else {
                    swal("Fail", "Save data fail.", "error");
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




});
</script>