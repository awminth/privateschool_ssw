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
                    <h1><?=$lang['ear_title']?></h1>
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

                        <!-- Card body -->
                        <div class="card-body">
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
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/studentear_action.php' ?>",
            data: {
                action: 'show',
                page_no: page
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

    $(document).on('click', '#btngo', function() {
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'uniform/studentear_action.php'; ?>",
            data: {
                action: 'goear',
                aid: aid,
                name: name
            },
            success: function(data) {
                location.href = "<?=roothtml.'uniform/studentgrade.php'?>";
            }
        });
    });


});
</script>