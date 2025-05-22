<?php
include('../config.php');
include(root.'master/header.php');

$aid=$_SESSION['userid'];
$sql="select * from tbllogin where AID={$aid}";
$result=mysqli_query($con,$sql) or die("SQL a Query");
$row = mysqli_fetch_array($result);
$A1=$row["A1"];
$A2=$row["A2"];
$A3=$row["A3"];
$A4=$row["A4"];
$A5=$row["A5"];
$A6=$row["A6"];
$A7=$row["A7"];
$A8=$row["A8"];
$A9=$row["A9"];
$A10=$row["A10"];
$A11=$row["A11"];
$A12=$row["A12"];
$A13=$row["A13"];
$A14=$row["A14"];
$A15=$row["A15"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?=$lang['home_dashboard']?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php 
         
?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid table-responsive-sm">
            <div class="row">
                <?php if($A2==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'ear/ear.php'?>">
                            <p class="text-sm"><?=$lang['home_ear']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A3==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'student/student.php'?>">
                            <p class="text-sm"><?=$lang['home_student']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A4==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'teacher/teacher.php'?>">
                            <p class="text-sm"><?=$lang['home_teacher']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A5==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'staff/staff.php'?>">
                            <p class="text-sm"><?=$lang['home_staff']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A7==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'expense/expense.php'?>">
                            <p class="text-sm"><?=$lang['home_expense']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A8==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'announcement/announcement_all.php'?>">
                            <p class="text-sm"><?=$lang['home_announcement']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A9==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'cms/cms.php'?>">
                            <p class="text-sm"><?=$lang['home_cms']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A10==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'user/user.php'?>">
                            <p class="text-sm"><?=$lang['home_useraccount']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A11==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'appuser/appuser.php'?>">
                            <p class="text-sm"><?=$lang['home_appuseraccount']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A12==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'finance/finance.php'?>">
                            <p class="text-sm"><?=$lang['home_finance']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A13==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'todolist/todolist.php'?>">
                            <p class="text-sm"><?=$lang['home_todolist'] ?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A14==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'chat/chat.php'?>">
                            <p class="text-sm"><?=$lang['home_chatroom']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php if($A15==1){ ?>
                <div class="col-4">
                    <div class="card card-primary card-outline text-center p-2">
                        <a href="<?=roothtml.'log/log.php'?>">
                            <p class="text-sm"><?=$lang['home_loghistory']?></p>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline p-2">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"><?=$lang['h_studentlist']?></h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="show_table">

                                </div>
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

        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/ear_action.php' ?>",
            data: {
                action: 'showall',

            },
            success: function(data) {
                $("#show_table").html(data);

            }
        });
    }

    load_pag();









});
</script>