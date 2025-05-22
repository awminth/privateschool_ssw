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
                <div class="col-sm-12">
                    <h1><?=$lang['earstu_examreport']?></h1>
                    <br>
                    <a href="<?=roothtml.'ear/earstudent.php'?>" type="button" class="btn btn-sm btn-<?=$color?>"><i
                            class="fas fa-arrow-left"></i>&nbsp;
                        <?=$lang['staff_back']?>
                    </a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-<?=$color?> card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="true"><?=$lang['exam_month']?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="false">
                                        <?=$lang['exam_all']?></a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <div id="show_table" class="table-responsive-sm">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <form method="POST" action="examreport_action.php">
                                                                    <input type="hidden" name="hid">
                                                                    <input type="hidden" name="ser">
                                                                    <button type="submit" name="action"
                                                                        value="excel_all"
                                                                        class="btn btn-sm btn-<?=$color?>"><i
                                                                            class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="card-body">
                                                    <table width="100%">
                                                        <tr>
                                                            <td width="30%">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-5 col-form-label"><?=$lang['exam_sort']?></label>
                                                                    <div class="col-sm-7">
                                                                        <select id="entry" class="custom-select btn-sm">
                                                                            <option value="" selected>All</option>
                                                                            <option value="lh">Low-High Mark</option>
                                                                            <option value="hl">High-Low Mark</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td width="60%" class="float-right">
                                                                <div class="form-group row">
                                                                    <label for="inputEmail3"
                                                                        class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="search" class="form-control"
                                                                            id="searching" placeholder="Search...">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div id="show_table_all" class="table-responsive-sm">

                                                    </div>
                                                </div>
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

<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Exam Detail View</h4>
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

    function load_pag() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/examreport_action.php'?>",
            data: {
                action: 'show',
            },
            success: function(data) {
                $("#show_table").html(data);

            }
        });
    }
    load_pag();

    function load_pag_all() {
        var search = $("[name='ser']").val();
        var st = $("[name='hid']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/examreport_action.php'?>",
            data: {
                action: 'show_all',
                search: search,
                st: st
            },
            success: function(data) {
                $("#show_table_all").html(data);

            }
        });
    }
    load_pag_all();

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag_all();
    });

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag_all();
    });

    $(document).on("click", "#btnview", function() {
        var vno = $(this).data("vno");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/examreport_action.php'?>",
            data: {
                action: 'view',
                vno: vno,
            },
            success: function(data) {
                $("#frmpayslip").html(data);
                $("#salarymodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });



});
</script>