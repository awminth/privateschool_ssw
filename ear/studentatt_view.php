<?php
include('../config.php');
include(root.'master/header.php');
?>
<style>
.t1 {
    border: 1px solid;
    padding: 2px 2px 2px 2px;
    text-align: center;
}

.t2 {
    width: 100%;
    border-collapse: collapse;
    padding: 2px 2px 2px 2px;
    text-align: center;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) Attendance က စာရင်း</h1>
            <br>
            <table>
                <tr>
                    <td>
                        <a href="<?=roothtml.'ear/studentattendance.php'?>" type="button"
                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                            Back
                        </a>
                    </td>
                    <td>
                        <form method="POST" action="studentattendance_action.php">
                            <input type="date" name="serdt" value="<?=date('Y-m-d')?>" style="display:none;">
                            <input type="hidden" name="ser">
                            <button type="submit" name="action" value="att_excel" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;Excel</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="studentattendance_action.php">
                            <input type="date" name="serdt" value="<?=date('Y-m-d')?>" style="display:none;">
                            <input type="hidden" name="ser">
                            <button type="submit" name="action" value="att_pdf" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                        </form>
                    </td>
                </tr>
            </table>
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
                            <table width="100%">
                                <tr>
                                    <td width="30%">
                                        <h5 class="text-primary" id="showdate1"><?=date("M - Y")?></h5>
                                    </td>
                                    <td width="40%" class="text-center">
                                        <button id="btnleft" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-left"></i></button>
                                        <button id="btntoday" type="button"
                                            class="btn btn-primary btn-sm">Today</button>
                                        <button id="btnright" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-right"></i></button>
                                    </td>
                                    <td width="30%">
                                        <input type="search" class="form-control" id="searching"
                                            placeholder="Search ....">
                                    </td>
                                </tr>
                            </table>

                            <div id="show_table" class="table-responsive-sm pt-5">

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
        var search = $("[name='ser']").val();
        var serdt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'ka_list',
                page_no: page,
                search: search,
                serdt: serdt
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

    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    function dateToYMD(date) {
        var strArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var d = new Date(date);
        var m = strArray[d.getMonth()];
        return m + " - " + d.getFullYear();
    }

    $(document).on("click", "#btntoday", function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'todaydt',
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_pag();
            }
        });
    });

    $(document).on("click", "#btnright", function(e) {
        e.preventDefault();
        var dt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'rightdt',
                dt: dt
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_pag();
            }
        });
    });

    $(document).on("click", "#btnleft", function(e) {
        e.preventDefault();
        var dt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'leftdt',
                dt: dt
            },
            success: function(data) {
                $("[name='serdt']").val(data);
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_pag();
            }
        });
    });

});
</script>