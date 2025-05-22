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
                    <h1><?=$lang['home_finance']?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline p-2">
                <div class="row p-2">
                    <div class="col-sm-4 p-2">
                        <div class="form-group row">
                            <label class="col-sm-4" for="usr"><?=$lang['finance_from']?> :</label>
                            <input type="date" class="col-sm-8 form-control border-primary" name="from"
                                value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4" for="usr"><?=$lang['finance_to']?> :</label>
                            <input type="date" class="col-sm-8 form-control border-primary" name="to"
                                value="<?=date('Y-m-d')?>">
                        </div>
                        <button id="btnsearch" class="form-control btn btn-<?=$color?>">
                            <?=$lang['finance_search']?></button>

                    </div>
                    <div class="col-sm-6 p-2" id="show">
                        <div class="card card-primary card-outline p-3">
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_income']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="income" value=""
                                    readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr">Uniform Fee :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="uniform"
                                    value="" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_totalincome']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="income" value=""
                                    readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_salary']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="expense"
                                    value="" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_otherexpense']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="expense"
                                    value="" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_totalexpense']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="expense"
                                    value="" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4" for="usr"><?=$lang['finance_profit']?> :</label>
                                <input type="number" class="col-sm-8 form-control border-primary" name="profit" value=""
                                    readonly>
                            </div>
                        </div>
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

    function load_pag() {
        var from = $("[name='from']").val();
        var to = $("[name='to']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'finance/finance_action.php' ?>",
            data: {
                action: 'show',
                from: from,
                to: to

            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#show").html(data);

            }
        });
    }


    $(document).on("click", "#btnsearch", function() {
        load_pag();

    });



});
</script>