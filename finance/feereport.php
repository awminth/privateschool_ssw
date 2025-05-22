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
                    <h1>Student Fee Report</h1>
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
                                        aria-selected="true">Student Fee</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="false">
                                        Student Fee Detail</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <form method="POST" action="feereport_action.php">
                                        <input type="hidden" name="hid">
                                        <input type="hidden" name="ser">
                                        <input type="hidden" name="dtfrom">
                                        <input type="hidden" name="dtto">
                                        <button type="submit" name="action" value="excel"
                                            class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        <button type="submit" name="action" value="pdf"
                                            class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                    </form>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"><?=$lang['cms_from']?> :</label>
                                                    <input type="date" class=" form-control border-primary" name="from"
                                                        value="<?=date('Y-m-d')?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"><?=$lang['cms_to']?> :</label>
                                                    <input type="date" class=" form-control border-primary" name="to"
                                                        value="<?=date('Y-m-d')?>">
                                                </div>
                                                <div class="form-group">
                                                    <button type='button' id='btnsearch'
                                                        class='btn btn-<?=$color?> form-control'><i
                                                            class="fas fa-search"></i>
                                                        <?=$lang['staff_search']?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <table width="100%">

                                                <tr>
                                                    <td width="20%">
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
                                                                class="col-sm-2 col-form-label"><?=$lang['staff_search']?></label>
                                                            <div class="col-sm-10">
                                                                <input type="search" class="form-control" id="searching"
                                                                    placeholder="Search..">
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
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <form method="POST" action="feereport_action.php">
                                        <input type="hidden" name="hid1">
                                        <input type="hidden" name="ser1">
                                        <input type="hidden" name="dtfrom1">
                                        <input type="hidden" name="dtto1">
                                        <button type="submit" name="action" value="excel1"
                                            class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        <button type="submit" name="action" value="pdf1"
                                            class="btn btn-sm btn-<?=$color?>"><i
                                                class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                    </form>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="card card-primary card-outline p-2">
                                                <div class="form-group">
                                                    <label for="usr"><?=$lang['cms_from']?> :</label>
                                                    <input type="date" class=" form-control border-primary" name="from1"
                                                        value="<?=date('Y-m-d')?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="usr"><?=$lang['cms_to']?> :</label>
                                                    <input type="date" class=" form-control border-primary" name="to1"
                                                        value="<?=date('Y-m-d')?>">
                                                </div>
                                                <div class="form-group">
                                                    <button type='button' id='btnsearch1'
                                                        class='btn btn-<?=$color?> form-control'><i
                                                            class="fas fa-search"></i>
                                                        <?=$lang['staff_search']?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <table width="100%">

                                                <tr>
                                                    <td width="20%">
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
                                                    <td width="60%" class="float-right">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3"
                                                                class="col-sm-2 col-form-label"><?=$lang['staff_search']?></label>
                                                            <div class="col-sm-10">
                                                                <input type="search" class="form-control"
                                                                    id="searching1" placeholder="Search..">
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
                            <!-- /.card -->
                        </div>
                    </div>

                </div>
            </div>
    </section>

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        var pay = $("[name='cms']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'finance/feereport_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                pay: pay
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

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        var pay = $("[name='cms']").val();
        $("[name='dtfrom'").val(from);
        $("[name='dtto'").val(to);
        $("[name='dtpay'").val(pay);
        load_pag();
    });



    function load_pag1(page) {
        var entryvalue = $("[name='hid1']").val();
        var search = $("[name='ser1']").val();
        var from = $("[name='from1']").val();
        var to = $("[name='to1']").val();
        var pay = $("[name='cms1']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'finance/feereport_action.php' ?>",
            data: {
                action: 'show1',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to,
                pay: pay
            },
            success: function(data) {
                $("#show_table1").html(data);

            }
        });
    }
    load_pag1();

    $(document).on("click", "#btnsearch1", function() {
        var from = $("[name='from1']").val();
        var to = $("[name='to1']").val();
        var pay = $("[name='cms1']").val();
        $("[name='dtfrom1'").val(from);
        $("[name='dtto1'").val(to);
        $("[name='dtpay1'").val(pay);
        load_pag1();
    });

    $(document).on('click', '.pagin2', function() {
        var pageid = $(this).data('page_number');
        load_pag1(pageid);
    });

    $(document).on("change", "#entry1", function() {
        var entryvalue = $(this).val();
        $("[name='hid1'").val(entryvalue);
        load_pag1();
    });


    $(document).on("keyup", "#searching1", function() {
        var serdata = $(this).val();
        $("[name='ser1'").val(serdata);
        load_pag1();
    });



});
</script>