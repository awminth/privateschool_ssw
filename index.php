<?php 

include('config.php');

$sql_setting="select * from tblsetting";
$result1 = mysqli_query($con,$sql_setting); 
$row1 = mysqli_fetch_array($result1); 
$title = $row1['SchoolName'];
$icon = roothtml.'upload/noimage.png';
if($row1['SiteIcon'] != "" || $row1['SiteIcon'] != NULL){ 
    $icon = roothtml.'upload/'.$row1['SiteLogo']; 
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/fontawesome-free/css/all.min.css' ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/icheck-bootstrap/icheck-bootstrap.min.css' ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/css/adminlte.min.css' ?>">
    <link rel="shortcut icon" href="<?=$icon?>" />
    <!-- Sweet Alarm -->
    <link href="<?php echo roothtml.'lib/sweet/sweetalert.css' ?>" rel="stylesheet" />
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.min.js' ?>"></script>
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.js' ?>"></script>
    <style>

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    *{
        font-family: 'Poppins', sans-serif;
    }

    .loader {
        position: fixed;
        z-index: 999;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background-color: Black;
        filter: alpha(opacity=60);
        opacity: 0.7;
        -moz-opacity: 0.8;
    }

    .center-load {
        z-index: 1000;
        margin: 300px auto;
        padding: 10px;
        width: 130px;
        background-color: black;
        border-radius: 10px;
        filter: 1;
        -moz-opacity: 1;
    }

    .center-load img {
        height: 128px;
        width: 128px;
    }
    </style>

</head>

<body id="logo" class="hold-transition login-page">
      <div class="container-fluid d-flex align-items-center justify-content-center vh-100">
        <div class="row">
          <div class="col-md-6">
            <img src="lib/images/sunmoonlight.jpg" alt=" " class="img-fluid">
          </div>
          <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="login-logo" style="margin-bottom: 50px;">
                <a href="#"><b class="text-primary" style="font-weight: 700;">LOGIN</a>
              </div>
              <form id="frm" method="post">
                  <div class="input-group mb-3">
                      <input type="text" class="form-control"
                          value="<?php if(isset($_COOKIE['member_login'])){ echo $_COOKIE['member_login'];}?>"
                          name="username" placeholder="UserName">
                      <div class="input-group-append">
                          <div class="input-group-text">
                              <span class="fas fa-user"></span>
                          </div>
                      </div>
                  </div>
                  <div class="input-group mb-3">
                      <input type="password" name="password"
                          value="<?php if(isset($_COOKIE['member_login'])){ echo $_COOKIE['member_password'];}?>"
                          class="form-control" placeholder="Password">
                      <div class="input-group-append">
                          <div class="input-group-text">
                              <span class="fas fa-lock"></span>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-8">
                          <div class="icheck-primary">
                              <input type="checkbox" name="remember" <?php if(isset($_COOKIE['member_password'])){?>
                                  checked <?php } ?> id="remember">
                              <label for="remember">
                                  Remember Me
                              </label>
                          </div>
                      </div>
                      <!-- /.col -->
                      <div class="col-4">
                          <button type="submit" id="btnlogin" class="btn btn-primary btn-block">Sign
                              In</button>
                      </div>
                      <!-- /.col -->
                  </div>
              </form>
          </div>
        </div>
      </div>

    <div class="loader" style="display:none;">
        <div class="center-load">
            <img src="<?php echo roothtml.'lib/images/ajax-loader1.gif'; ?>" />
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo roothtml.'lib/plugins/jquery/jquery.min.js' ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo roothtml.'lib/plugins/bootstrap/js/bootstrap.bundle.min.js' ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo roothtml.'lib/dist/js/adminlte.min.js' ?>"></script>
    <script>
    $(document).ready(function() {

        $(document).on("click", "#btnlogin", function(e) {

            e.preventDefault();

            $.ajax({
                type: "post",
                url: "<?php echo roothtml.'index_action.php' ?>",
                data: $("#frm").serialize() + "&action=login",
                beforeSend: function() {

                    $(".loader").show();

                },
                success: function(data) {

                    $(".loader").hide();

                    if (data == 1) {

                        swal("Successful!",
                            "Login Successful.",
                            "success");
                        location.href =
                            "<?php echo roothtml.'home/home.php' ?>";

                    } else {

                        // swal("Error!",
                        //     "User Name or Password incorrect.",
                        //     "error");
                        alert(data);

                    }

                }
            });



        });

    });
    </script>

</body>

</html>