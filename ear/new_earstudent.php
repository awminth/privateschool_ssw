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
                    <h1><?=$lang['newear_title']?></h1>
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
                                            <?=$lang['btnback']?>
                                        </a></td>
                                    <td>
                                        <input type="hidden" name="hid">
                                        <input type="hidden" name="ser">
                                        <input type="hidden" name="hid1">
                                        <input type="hidden" name="ser1">

                                    </td>
                                </tr>

                            </table>

                        </div>

                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">

                                            <tr>
                                                <td width="30%">
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
                                                <td width="70%" class="float-right">
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
                                <div class="col-sm-7">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">

                                            <tr>
                                                <td width="20%">
                                                    <div class="form-group row">
                                                        <div class="col-sm-10">
                                                            <select id="entry1" class="custom-select btn-sm">
                                                                <option value="10" selected>10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="80%" class="float-right">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <input type="search" class="form-control" id="searching1"
                                                                placeholder="Search...">
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
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/new_earstudent_action.php' ?>",
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




    $(document).on("click", "#btnaddstudent", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/new_earstudent_action.php' ?>",
            data: {
                action: 'addstudent',
                aid: aid,
                name: name
            },
            success: function(data) {
                if (data == "exit") {
                    swal("Information", "Already exists", "info");
                } else if (data == 1) {
                    load_pag1();
                    load_pag();
                } else {
                    swal("error", "error", "error");
                }

            }
        });
    });

    function load_pag1(page) {
        var entryvalue = $("[name='hid1'").val();
        var search = $("[name='ser1'").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/new_earstudent_action.php' ?>",
            data: {
                action: 'show1',
                page_no: page,
                entryvalue: entryvalue,
                search: search
            },
            success: function(data) {
                $("#show_table1").html(data);

            }
        });
    }
    load_pag1();

    $(document).on('click', '.page-link', function() {
        var pageid = $(this).data('page_number');
        load_pag1(pageid);
    });

    $(document).on("change", "#entry1", function() {
        var entryvalue = $(this).val();
        $("[name='hid1']").val(entryvalue);
        load_pag();
    });


    $(document).on("keyup", "#searching1", function() {
        var serdata = $(this).val();
        $("[name='ser1']").val(serdata);
        load_pag1();
    });

    $(document).on("click", "#btndeleteear", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/new_earstudent_action.php' ?>",
            data: {
                action: 'deleteear',
                aid: aid
            },
            success: function(data) {
                if (data == 1) {
                    load_pag1();
                    load_pag();
                } else {
                    swal("error", "error", "error");
                }

            }
        });
    });




});
</script>