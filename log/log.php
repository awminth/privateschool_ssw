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
                    <h1><?=$lang['home_loghistory'] ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline p-3">
                        <div class="form-group row">
                            <label class="col-sm-4" for="usr"><?=$lang['log_from'] ?> :</label>
                            <input type="date" class="col-sm-8 form-control border-primary" name="from"
                                value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4" for="usr"><?=$lang['log_to'] ?> :</label>
                            <input type="date" class="col-sm-8 form-control border-primary" name="to"
                                value="<?=date('Y-m-d')?>">
                        </div>
                        <button id="btnsearch" class="form-control btn btn-<?=$color?>">
                            <?=$lang['log_search'] ?></button>

                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">

                            <form method="POST" action="log_action.php">
                                <input type="hidden" name="hid">
                                <input type="hidden" name="ser">
                                <input type="hidden" name="dtfrom">
                                <input type="hidden" name="dtto">
                                <button type="submit" name="action" value="excel" class="btn btn-sm btn-<?=$color?>"><i
                                        class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel'] ?></button>
                                <button type="submit" name="action" value="pdf" class="btn btn-sm btn-<?=$color?>"><i
                                        class="fas fa-file-excel"></i>&nbsp;PDF</button>
                            </form>

                        </div>
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-6 col-form-label"><?=$lang['staff_show'] ?></label>
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
                                    <td width="65%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-3 col-form-label"><?=$lang['log_search'] ?></label>
                                            <div class="col-sm-10">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Search by description or username">
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

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'log/log_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                from: from,
                to: to
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

    $(document).on("click", "#btnsearch", function() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        $("[name='dtfrom']").val(from);
        $("[name='dtto']").val(to);
        load_pag();

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



});
</script>