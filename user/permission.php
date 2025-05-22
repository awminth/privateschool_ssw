<?php
    include('../config.php');
    include(root.'master/header.php'); 
    $aid=$_GET['aid'];
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
                    <h1>Change Permission</h1>
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
                                        <a href="<?=roothtml.'user/user.php'?>" class="btn btn-sm btn-primary">Go Back
                                        </a>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table><!-- Card body -->
                        </div>
                        <div class="card-body">
                            <form id="frmsave" method="POST">
                                <input type="hidden" name="action" value="changepermission" />
                                <input type="hidden" name="aid" value="<?=$aid?>" />
                                <table class="table">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A1==1)?'checked':'' ?>
                                                        class="form-check-input" name="A1"><?=$lang['home_dashboard']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A2==1)?'checked':'' ?>
                                                        class="form-check-input" name="A2"><?=$lang['home_ear']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A3==1)?'checked':'' ?>
                                                        class="form-check-input" name="A3"><?=$lang['home_student']?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A4==1)?'checked':'' ?>
                                                        class="form-check-input" name="A4"><?=$lang['home_teacher']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A5==1)?'checked':'' ?>
                                                        class="form-check-input" name="A5"><?=$lang['home_staff']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A6==1)?'checked':'' ?>
                                                        class="form-check-input" name="A6"><?=$lang['home_setup']?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A7==1)?'checked':'' ?>
                                                        class="form-check-input" name="A7"><?=$lang['home_expense']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A8==1)?'checked':'' ?>
                                                        class="form-check-input" name="A8"><?=$lang['home_announcement']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A9==1)?'checked':'' ?>
                                                        class="form-check-input" name="A9"><?=$lang['home_cms']?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A10==1)?'checked':'' ?>
                                                        class="form-check-input" name="A10"><?=$lang['home_useraccount']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A11==1)?'checked':'' ?>
                                                        class="form-check-input" name="A11"><?=$lang['home_appuseraccount']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A12==1)?'checked':'' ?>
                                                        class="form-check-input" name="A12"><?=$lang['home_finance']?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A13==1)?'checked':'' ?>
                                                        class="form-check-input" name="A13"><?=$lang['home_todolist']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A14==1)?'checked':'' ?>
                                                        class="form-check-input" name="A14"><?=$lang['home_chatroom']?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" <?=($A15==1)?'checked':'' ?>
                                                        class="form-check-input" name="A15"><?=$lang['home_loghistory']?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>

                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-primary"> Change Permission
                                            </button>
                                        </td>
                                    </tr>

                                </table>
                            </form>

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

    $("#frmsave").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'user/permission_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                location.href = "<?=roothtml.'user/user.php'?>";


            }
        });
    });


});
</script>