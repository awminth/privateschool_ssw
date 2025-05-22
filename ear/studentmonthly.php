<?php
include('../config.php');
include(root.'master/header.php') ;

$gradeid=$_SESSION['gradeid'];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) <?=$_SESSION['sname']?> ၏ Monthly
                        Record</h1>
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
                                    <td>
                                        <a href="<?=roothtml.'ear/earstudent.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            Back
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?=roothtml.'ear/new_studentmonthly.php'?>" type="button"
                                            class="btn btn-sm btn-info"><i class="fas fa-plus"></i>&nbsp; New
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="studentmonthly_action.php">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-success"><i
                                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="studentmonthly_action.php">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-success"><i
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
            url: "<?php echo roothtml.'ear/studentmonthly_action.php' ?>",
            data: {
                action: 'show',
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }
    load_pag();

    $(document).on("click", "#btnedit", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentmonthly_action.php' ?>",
            data: {
                action: 'prepare',
                aid: aid
            },
            success: function(data) {
                location.href = "<?php echo roothtml.'ear/new_studentmonthly.php' ?>";
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
                    url: "<?php echo roothtml.'ear/studentmonthly_action.php'; ?>",
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





});
</script>