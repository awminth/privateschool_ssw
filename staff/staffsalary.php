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
                    <h1><?=$lang['home_managestaffsalary'] ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="true"><?=$lang['staff_salarytab1']?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="false">
                                        <?=$lang['staff_salarytab2']?></a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">

                                    <table>
                                        <tr>
                                            <td>
                                                <button id="btnnew" type="button" class="btn btn-sm btn-<?=$color?>"><i
                                                        class="fas fa-plus"></i>&nbsp; <?=$lang['staff_bonuscut']?>
                                                </button>
                                            </td>
                                            <td>
                                                <form method="POST" action="staffsalary_action.php">
                                                    <input type="hidden" name="hid">
                                                    <input type="hidden" name="ser">
                                                    <input type="hidden" name="hid_dt" value="<?=date('Y-m')?>">
                                                    <input type="hidden" name="hid_teacher">
                                                    <button type="submit" name="action" value="excel"
                                                        class="btn btn-sm btn-<?=$color?>"><i
                                                            class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST" action="staffsalary_action.php">
                                                    <input type="hidden" name="hid">
                                                    <input type="hidden" name="ser">
                                                    <input type="hidden" name="hid_dt" value="<?=date('Y-m')?>">
                                                    <input type="hidden" name="hid_teacher">
                                                    <button type="submit" name="action" value="pdf"
                                                        class="btn btn-sm btn-<?=$color?>"><i
                                                            class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"> <?=$lang['staff_month']?> :</label>
                                                    <input type="month" class="form-control border-success"
                                                        value="<?=date('Y-m')?>" name="findmonth" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> <?=$lang['staff_staffname']?> :</label>
                                                    <select class="form-control border-success" name="findteacher">
                                                        <option value=''>Select Staff</option>
                                                        <?=load_staff();?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-<?=$color?>"
                                                    id="btnsearch"><?=$lang['staff_search']?></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="card card-primary card-outline p-2">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="22%">
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
                                                        <td width="70%" class="float-right">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3"
                                                                    class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
                                                                <div class="col-sm-9">
                                                                    <input type="search" class="form-control"
                                                                        id="searching" placeholder="Search....">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <div id="show_table" class="table-responsive-sm">

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <table>
                                        <tr>
                                            <td>
                                                <a href="<?=roothtml.'staff/salarypaystaff.php'?>" type="button"
                                                    class="btn btn-sm btn-<?=$color?>"><i class="fas fa-plus"></i>&nbsp;
                                                    <?=$lang['staff_paysalary']?>
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST" action="staffsalary_action.php">
                                                    <input type="hidden" name="hid1">
                                                    <input type="hidden" name="ser1">
                                                    <input type="hidden" name="hid_dt1" value="<?=date('Y-m')?>">
                                                    <input type="hidden" name="hid_teacher1">
                                                    <button type="submit" name="action" value="excel1"
                                                        class="btn btn-sm btn-<?=$color?>"><i
                                                            class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST" action="staffsalary_action.php">
                                                    <input type="hidden" name="hid1">
                                                    <input type="hidden" name="ser1">
                                                    <input type="hidden" name="hid_dt1" value="<?=date('Y-m')?>">
                                                    <input type="hidden" name="hid_teacher1">
                                                    <button type="submit" name="action" value="pdf1"
                                                        class="btn btn-sm btn-<?=$color?>"><i
                                                            class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"> <?=$lang['staff_month']?> :</label>
                                                    <input type="month" class="form-control border-success"
                                                        value="<?=date('Y-m')?>" name="findmonth1" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"> <?=$lang['staff_staffname']?> :</label>
                                                    <select class="form-control border-success" name="findteacher1">
                                                        <option value=''>Select Staff</option>
                                                        <?=load_staff();?>
                                                    </select>
                                                </div>
                                                <button class="form-control btn btn-<?=$color?>"
                                                    id="btnsearch1"><?=$lang['staff_search']?></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="card card-primary card-outline p-2">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="22%">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3"
                                                                    class="col-sm-5 col-form-label"><?=$lang['staff_show']?></label>
                                                                <div class="col-sm-7">
                                                                    <select id="entry1" class="custom-select btn-sm">
                                                                        <option value="10" selected>10</option>
                                                                        <option value="25">25</option>
                                                                        <option value="50">50</option>
                                                                        <option value="100">100</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td width="70%" class="float-right">
                                                            <div class="form-group row">
                                                                <label for="inputEmail3"
                                                                    class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
                                                                <div class="col-sm-9">
                                                                    <input type="search" class="form-control"
                                                                        id="searching1" placeholder="Search.....">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <div id="show_table1" class="table-responsive-sm">

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- /.card -->
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
                <h4 class="modal-title">New Salary Bonus / Cut</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST" enctype="multipart/form-data">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type="hidden" name="action" id="action" value="save">
                    <div class="form-group">
                        <label for="usr"> <?=$lang['staff_staffname']?> :</label>
                        <select class="form-control border-success" name="teacher">
                            <option value=''>Select Staff</option>
                            <?=load_staff();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['staff_status']?> :</label>
                        <select class="form-control border-success" id="status" name="status">
                            <option value="">Seleted Status</option>
                            <?=load_status();?>
                        </select>
                    </div>
                    <div class="form-group" id="bonus" style="display:none">
                        <label for="usr"> Bonus :</label>
                        <select class="form-control border-success" name="bonus">
                            <option value="">Seleted Bonus</option>
                            <?=load_bonus();?>
                        </select>
                    </div>
                    <div class="form-group" id="cut" style="display:none">
                        <label for="usr"> Cut :</label>
                        <select class="form-control border-success" name="cut">
                            <option value="">Seleted Cut</option>
                            <?=load_cut();?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['staff_amount']?> :</label>
                        <input type="number" class="form-control border-success" name="amt" placeholder="00.00">
                    </div>
                    <div class="form-group">
                        <label for="usr"> <?=$lang['staff_date']?> :</label>
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
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Salary Bonus/Cut</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Salary Pay Slip</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm2" method="POST" class="p-3">
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
        var dtmonth = $("[name='findmonth']").val();
        var teacherid = $("[name='findteacher']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                dtmonth: dtmonth,
                teacherid: teacherid
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on('click', '.pagin1', function() {
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

    $(document).on("click", "#btnsearch", function(e) {
        e.preventDefault();
        var dtmonth = $("[name='findmonth']").val();
        var teacherid = $("[name='findteacher']").val();
        $("[name='hid_dt']").val(dtmonth);
        $("[name='hid_teacher']").val(teacherid);
        load_pag();
    });

    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show");
    });

    $(document).on("click", "#status", function() {
        var status = $("[name='status']").val();
        if (status == 'Bonus') {
            $("#bonus").css('display', '');
        } else {
            $("#bonus").css('display', 'none');
        }
        if (status == 'Cut') {
            $("#cut").css('display', '');
        } else {
            $("#cut").css('display', 'none');
        }
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var teacher = $("[name='teacher']").val();
        var status = $("[name='status']").val();
        var amt = $("[name='amt']").val();
        var date = $("[name='date']").val();
        var cut = $("[name='cut']").val();
        var bonus = $("[name='bonus']").val();
        if (teacher == "" || status == "" || amt == "" || date == "") {
            swal("Information", "Please fill data", "info");
            return false;
        }
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'save',
                teacher: teacher,
                status: status,
                amt: amt,
                date: date,
                cut: cut,
                bonus: bonus
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
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var status = $(this).data("status");

        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid,
                status: status
            },
            success: function(data) {
                $("#frm1").html(data);
                if (status == 'get') {
                    $("#bonus1").css('display', '');
                } else {
                    $("#bonus1").css('display', 'none');
                }
                if (status == 'cut') {
                    $("#cut1").css('display', '');
                } else {
                    $("#cut1").css('display', 'none');
                }
            }
        });
    });

    $(document).on("click", "#status1", function() {
        var status = $("[name='status1']").val();
        if (status == 'Bonus') {
            $("#bonus1").css('display', '');
        } else {
            $("#bonus1").css('display', 'none');
        }
        if (status == 'Cut') {
            $("#cut1").css('display', '');
        } else {
            $("#cut1").css('display', 'none');
        }
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var teacher = $("[name='teacher1']").val();
        var status = $("[name='status1']").val();
        var amt = $("[name='amt1']").val();
        var date = $("[name='date1']").val();
        var cut = $("[name='cut1']").val();
        var bonus = $("[name='bonus1']").val();
        var aid = $("[name='aid']").val();

        if (teacher == "" || status == "" || amt == "" || date == "") {
            swal("Information", "Please fill data", "info");
            return false;
        }
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                teacher: teacher,
                status: status,
                amt: amt,
                date: date,
                cut: cut,
                bonus: bonus
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
                    url: "<?php echo roothtml.'staff/staffsalary_action.php'; ?>",
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


    function load_pag1(page) {
        var entryvalue = $("[name='hid1']").val();
        var search = $("[name='ser1']").val();
        var dtmonth = $("[name='findmonth1']").val();
        var teacherid = $("[name='findteacher1']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'show1',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                dtmonth: dtmonth,
                teacherid: teacherid
            },
            success: function(data) {
                $("#show_table1").html(data);
            }
        });
    }
    load_pag1();

    $(document).on('click', '.pagin2', function() {
        var pageid = $(this).data('page_number');
        load_pag1(pageid);
    });

    $(document).on('click', '#btnsearch1', function() {
        var dtmonth = $("[name='findmonth1']").val();
        var teacherid = $("[name='findteacher1']").val();
        $("[name='hid_dt1']").val(dtmonth);
        $("[name='hid_teacher1']").val(teacherid);
        load_pag1();
    });

    $(document).on("change", "#entry1", function() {
        var entryvalue = $(this).val();
        $("[name='hid1']").val(entryvalue);
        load_pag1();
    });


    $(document).on("keyup", "#searching1", function() {
        var serdata = $(this).val();
        $("[name='ser1'").val(serdata);
        load_pag1();
    });

    $(document).on("click", "#btndelete1", function(e) {
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
                    url: "<?php echo roothtml.'staff/staffsalary_action.php'; ?>",
                    data: {
                        action: 'delete1',
                        aid: aid,
                        vno: vno
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag1();
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

    $(document).on("click", "#btneditsalary", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var date = $(this).data("date");
        var staffid = $(this).data("staffid");
        var name = $(this).data("name");
        var salary = $(this).data("salary");
        var rmk = $(this).data("rmk");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/staffsalary_action.php' ?>",
            data: {
                action: 'slipsalary',
                aid: aid,
                date: date,
                staffid: staffid,
                name: name,
                salary: salary,
                rmk: rmk
            },
            success: function(data) {
                $("#frm2").html(data);

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
                '.txt,h5,h3 {text-align: center;font-size: 10px;font-weight: bold;}' +
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