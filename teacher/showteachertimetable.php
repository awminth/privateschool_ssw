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
                    <h1>Timetable For <b class="text-primary"><?=$_SESSION['teacher_table_name']?></b></h1>
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
                                    <td><a href="<?=roothtml.'teacher/teachertimetable.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['staff_back']?>
                                        </a></td>
                                    <td>
                                        <form method="POST" action="teachertimetable_action.php">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="teachertimetable_action.php">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
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

    function load_pag() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teachertimetable_action.php' ?>",
            data: {
                action: 'show_timetable',
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();



});
</script>