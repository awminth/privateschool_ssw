<?php
include('../config.php');
include(root.'master/header.php');
?>
<style>
.container {
    margin-top: 10px;
}

.th-height {
    height: 30px;
    width: 70px;
    text-align: center;
}

.td-height {
    height: 100px;
    width: 70px;
}

.today {
    background: #DEB887;
    color: #0000ff;
    font-weight: bold;
}

th:nth-of-type(1),
td:nth-of-type(1) {
    color: #ff0000;
}

th:nth-of-type(7),
td:nth-of-type(7) {
    color: #ff0000;
}

ul {
    list-style-type: none;
}

.month {
    padding: 10px 25px;
    width: 100%;
    background: #1abc9c;
    text-align: center;
}

.month ul {
    margin: 0;
    padding: 0;
}

.month ul li {
    color: white;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 3px;
}

.month .prev a {
    float: left;
    padding-top: 10px;
    color: white;
    font-weight: bold;
}

.month .next a {
    float: right;
    padding-top: 10px;
    color: white;
    font-weight: bold;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) Attendance <?=$lang['att_blist']?></h1>
            <br>
            <table>
                <tr>
                    <td>
                        <a href="<?=roothtml.'ear/studentattendance.php'?>" type="button"
                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                            <?=$lang['staff_back']?>
                        </a>
                    </td>
                    <td>
                        <input type="hidden" name="serdt" value="<?=$_SESSION['kha_studentserdt']?>">
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
                                        <?php $txt = enDate3($_SESSION['kha_studentserdt']); ?>
                                        <h5 class="text-primary" id="showdate1"><?=$txt?></h5>
                                    </td>
                                    <td width="40%" class="text-center">
                                        <h4 class="text-primary"><?=$_SESSION["kha_studentname"]?><br>
                                            <span id="show_count" style="font-size:15px;"></span>
                                        </h4>
                                    </td>
                                    <td width="30%" class="text-right">
                                        <button id="btnleft" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-left"></i></button>
                                        <button id="btntoday" type="button"
                                            class="btn btn-primary btn-sm">Today</button>
                                        <button id="btnright" type="button" class="btn btn-primary btn-sm"><i
                                                class="fas fa-arrow-right"></i></button>
                                    </td>
                                </tr>
                            </table>
                            <div class="container table-responsive-sm pt-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <?php for($i=0; $i<count($arr_day); $i++){ ?>
                                            <th class="th-height"><?=$arr_day[$i]?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody id="show_calendar">

                                    </tbody>
                                </table>
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
        var serdt = $("[name='serdt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'show_calendar',
                serdt: serdt
            },
            success: function(data) {
                $("#show_calendar").html(data);
                show_att_count(serdt);
            }
        });
    }
    load_pag();

    function show_att_count(dt) {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentattendance_action.php' ?>",
            data: {
                action: 'cal_count',
                dt: dt
            },
            success: function(data) {
                $("#show_count").html(data);
            }
        });
    }

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
                action: 'kha_todaydt',
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
                action: 'kha_rightdt',
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
                action: 'kha_leftdt',
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