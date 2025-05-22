<?php 
include('../config.php');
include(root.'master/header.php'); 

$toUser = $_GET["toaid"];

$cusname = GetString("select UserName from tblparent where AID={$toUser}");
?>
<style>
.mg {
    display: flex;
    flex-direction: column-reverse;
    overflow-y: scroll;
    overflow-x: hidden;
    height: 450px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View All Messages</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <input type="hidden" name="dtfrom">
            <input type="hidden" name="dtto">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>
                        <a href="<?=roothtml.'chat/chat.php'?>" class="btn btn-success btn-sm"><i
                                class="fas fa-arrow-left"></i></a>
                        View Chat With :&nbsp;&nbsp; <b><?=$cusname?></b>
                    </h4>
                    <input type="hidden" name="toaid" value="<?=$toUser?>" />
                </div>
                <div class="col-md-6 col-sm-12" style="display:none;">
                    <div class="form-inline justify-content-end">
                        <label for="email" class="mr-sm-2">From : </label>
                        <input type="date" value="<?=date('Y-m-d') ?>" name="from" class="form-control mb-2 mr-sm-2">
                        <label for="email" class="mr-sm-2">To : </label>
                        <input type="date" value="<?=date('Y-m-d') ?>" name="to" class="form-control mb-2 mr-sm-2">
                        <button type="submit" id="btnsearch" class="btn btn-primary btn-sm mb-2">Search</button>
                    </div>
                </div>
            </div>
            <div class="modal-body mg" id="show_table">

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
        var toaid = $("[name='toaid']").val();
        var dtto = $("[name='dtto']").val();
        var dtfrom = $("[name='dtfrom']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'chat/chat_action.php' ?>",
            data: {
                action: 'show_all',
                toaid: toaid,
                dtto: dtto,
                dtfrom: dtfrom
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on("click", '#btnsearch', function() {
        var dtfrom = $("[name='from']").val();
        var dtto = $("[name='to']").val();
        $("[name='dtto']").val(dtto);
        $("[name='dtfrom']").val(dtfrom);
        load_pag();
    });




});
</script>