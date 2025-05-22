<?php
include('../config.php');
include(root.'master/header.php');
$gradeid=$_SESSION['gradeid'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) <?=$lang['fee_title']?></h1>
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
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3"
                                                            class="col-sm-4 col-form-label"><?=$lang['show']?></label>
                                                        <div class="col-sm-8">
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
                                                            class="col-sm-3 col-form-label"><?=$lang['search']?></label>
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
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
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

    $(document).on("click", "#btnview", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        var vno = $(this).data('vno');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
            data: {
                action: 'gofeeview',
                aid: aid,
                name: name,
                vno: vno
            },
            success: function(data) {
                location.href = "<?=roothtml.'ear/studentfeeview.php'?>";

            }
        });
    });

    $(document).on("click", "#btnpay", function(e) {
        e.preventDefault();
        var vno = $(this).data('vno');
        var aid = $(this).data('aid');
        var earstuid = $(this).data('earstuid');
        var stuname = $(this).data('stuname');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
            data: {
                action: 'go_pay',
                vno: vno,
                aid: aid,
                earstuid: earstuid,
                stuname: stuname
            },
            success: function(data) {
                location.href = "<?=roothtml.'ear/studentfeepay.php'?>";
            }
        });
    });

    $(document).on("click", "#btnaddstudent", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var earstuid = $(this).data('earstuid');
        var name = $(this).data('name');
        var amt = $(this).data('amt');
        var totalpay = $(this).data('totalpay');
        var remain = $(this).data('remain');
        var vno = $(this).data('vno');
        var paytypeid = $(this).data('paytypeid');
        $("[name='stuid']").val(aid);
        $("[name='earstuid']").val(earstuid);
        $("[name='stuname']").val(name);
        $("[name='vno1']").val(vno);
        $("[name='totalpay1']").val(totalpay);
        $("[name='amt']").val(amt);
        $("[name='remain']").val(remain);
        if (vno != "") {
            $("[name='paytype']").attr("disabled", true);
            $("[name='disc']").attr("readonly", true);
            $("[name='tax']").attr("readonly", true);
        } else {
            $("[name='paytype']").attr("disabled", false);
            $("[name='disc']").attr("readonly", false);
            $("[name='tax']").attr("readonly", false);
        }
        // reload paytype name
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
            data: {
                action: 'show_paytype',
                paytypeid: paytypeid,
                aid: aid
            },
            success: function(data) {
                $("#paytype").html(data);
            }
        });
    });

    



});
</script>