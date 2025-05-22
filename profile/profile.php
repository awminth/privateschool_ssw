<?php

include('../config.php');

include(root.'master/header.php') 
?>

<style>
.pass_show {
    position: relative
}

.pass_show .ptxt {
    position: absolute;
    right: 15px;
    z-index: 1;
    color: #f36c01;
    margin-top: -30px;
    cursor: pointer;
    transition: .3s ease all;
}

.pass_show .ptxt:hover {
    color: green;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-info card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="true">Change Password</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Old
                                                Password</label>
                                            <div class="col-sm-10 pass_show">
                                                <input type="password" class="form-control" id="oldpassword"
                                                    value="<?php echo $_SESSION['userpassword'] ?>" name="oldpassword"
                                                    placeholder="Old password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">New
                                                Password</label>
                                            <div class="col-sm-10 pass_show">
                                                <input type="password" class="form-control" id="newpassword"
                                                    name="newpassword" placeholder="New password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Confirm New
                                                Password</label>
                                            <div class="col-sm-10 pass_show">
                                                <input type="password" class="form-control" id="connewpassword"
                                                    name="connewpassword" placeholder="Confirm new password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <button type='submit' id='updatepassword' class='btn btn-primary'>
                                                    <i class='fas fa-edit text-white'></i>&nbsp;Change
                                                    Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {



    $(document).on("click", "#updatepassword", function(e) {
        e.preventDefault();
        var newpassword = $("[name='newpassword']").val();
        var conpassword = $("[name='connewpassword']").val();
        if (newpassword == conpassword) {

            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'profile/profile_action.php' ?>",
                data: {
                    action: 'updatepassword',
                    newpassword: newpassword,
                    conpassword: conpassword
                },
                success: function(data) {
                    if (data == 1) {
                        swal("Successful", "Update success", "success");
                        location.href =
                            "<?php echo roothtml.'profile/profile.php' ?>";
                    } else {
                        swal("Error", "Update fail!", "error");
                    }
                }
            });



        } else {
            swal("Error !", "New Password and Confirm Password not match", "error");
            return false;
        }



    });

    //show and hide password
    $('.pass_show').append('<span class="ptxt">Show</span>');

    $(document).on('click', '.pass_show .ptxt', function() {
        $(this).text($(this).text() == "Show" ? "Hide" : "Show");
        $(this).prev().attr('type', function(index, attr) {
            return attr == 'password' ? 'text' : 'password';
        });

    });




});
</script>