<?php 
if(isset($_SESSION['userid'])){
    if(isset($_SESSION['la'])){
        switch($_SESSION['la']){
            case "en":
                include(root.'lang/en.php');		
            break;
            case "my":
                include(root.'lang/my.php');				
            break;
            case "china":
                include(root.'lang/china.php');			
            break;
            default: 
                include(root.'lang/en.php');			
        } 
    }else{
            include(root.'lang/en.php');  
    }

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
    $A16=$row["A16"];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/fontawesome-free/css/all.min.css' ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css' ?>">
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/datatables-responsive/css/responsive.bootstrap4.min.css' ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/overlayScrollbars/css/OverlayScrollbars.min.css' ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?php echo roothtml.'lib/dist/font-awesome.min.css' ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/select2/css/select2.min.css' ?>">
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css' ?>">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet"
        href="<?php echo roothtml.'lib/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css' ?>">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/bs-stepper/css/bs-stepper.min.css' ?>">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/dropzone/min/dropzone.min.css' ?>">

    <link rel="stylesheet" href="<?php echo roothtml.'lib/animate.min.css' ?>">

    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/summernote/summernote-bs4.min.css' ?>">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/plugins/jquery-ui/jquery-ui.css'?>" />
    <script src="<?php echo roothtml.'lib/plugins/jquery-ui/jquery-ui.min.js'?>"></script>

    <!-- Sweet Alarm -->
    <link href="<?php echo roothtml.'lib/sweet/sweetalert.css' ?>" rel="stylesheet" />
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.min.js' ?>"></script>
    <script src="<?php echo roothtml.'lib/sweet/sweetalert.js' ?>"></script>
    <script src="<?php echo roothtml.'lib/plugins/Chart.js' ?>"></script>
    <title><?=$_SESSION["shopname"]?></title>
    <!-- Calendar Events -->
    <link rel="stylesheet" href="<?php echo roothtml.'lib/calender/fullcalendar.css'?>" />

    <link rel="shortcut icon" href="<?=$_SESSION["shopicon"]?>" />
    <link href="<?php echo roothtml.'lib/print.min.css'?>" rel="stylesheet" />
    <style>
    #logo {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo roothtml.'images/logoback.png' ?>');
        /* Used if the image is unavailable */
        height: 550px;
        /* You must set a specified height */
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Do not repeat the image */
        background-size: cover;
        /* Resize the background image to cover the entire container */

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

    .bgactive {
        background-color: RGB(73, 78, 83);
    }
    </style>

</head>

<body class="hold-transition sidebar-mini <?php echo (curlink == 'pos.php')?'sidebar-collapse' : 'layout-fixed' ?> ">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand fixed-top navbar-<?=$color?> navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars text-white"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="Home"
                        href="<?php echo roothtml.'home/home.php' ?>" class="nav-link text-white">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-none d-sm-inline-block" id="btnmy" href="">
                        <img class="img-circle img-sm" src="<?php echo roothtml.'lib/images/my.jpg' ?>"
                            alt="<?=$lang['lang-my'];?>" title="<?=$lang['lang-my'];?>">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-none d-sm-inline-block" id="btnen" href="">
                        <img class="img-circle img-sm" src="<?php echo roothtml.'lib/images/eng.png' ?>"
                            alt="<?=$lang['lang-en'];?>" title="<?=$lang['lang-en'];?>">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-none d-sm-inline-block" id="btnchina" href="">
                        <img class="img-circle img-sm" src="<?php echo roothtml.'lib/images/china.png' ?>"
                            alt="<?=$lang['lang-china'];?>" title="<?=$lang['lang-china'];?>">
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" href="#"
                        class="nav-link text-white"><?=$lang['language']?></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->

                <!-- <li class="nav-item d-none d-sm-inline-block">
                    <a data-toggle="tooltip" data-placement="bottom" title="လက်ကျန် ၃ ခုနှင့်အောက်စာရင်း"
                        href="<?php echo roothtml.'item/notiremain.php' ?>" class="nav-link text-red"><i
                            class="fas fa-bullhorn text-white"></i>
                        <span class="badge badge-warning navbar-badge"></span>
                    </a>
                </li> -->

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img class="img-circle img-sm" src="<?php echo roothtml.'lib/images/img.jpg' ?>"
                            alt="Profile">&nbsp;&nbsp;

                        <span class="text-white"><?php echo isset($_SESSION['username'])?$_SESSION['username'] : ''; ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="bg-<?=$color?> text-center p-1 m-1">
                            <img class="img-circle" src="<?php echo roothtml.'lib/images/img.jpg' ?>" alt="Profile">
                            <p class="text-white">
                                <?php echo isset($_SESSION['username'])?$_SESSION['username'] : ''; ?>
                            </p>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="float-left m-1">
                            <a href="<?php echo roothtml.'profile/profile.php' ?>" class="btn btn-<?=$color?> ">
                                <i class="fas fa-user text-white"></i>&nbsp;Change Password</a>
                        </div>
                        <div class="float-right m-1">
                            <a href="#" id="btnlogout" class="btn btn-<?=$color?> ">
                                <i class="fas fa-sign-out-alt text-white"></i>&nbsp;Logout</a>
                        </div>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-success elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo roothtml.'home/home.php' ?>" class="brand-link bg-<?=$color?>">
                <img src="<?=$_SESSION["shoplogo"]?>" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light" style="font-size:17px"><?=$_SESSION["shopname"]?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item" <?=($A1==1)?'' : 'style="display:none"' ?>>
                            <a href="<?php echo roothtml.'home/test.php' ?>"
                                class="nav-link  <?=(curlink == 'test.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p><?=$lang['home_dashboard']?></p>
                            </a>
                        </li>
                        <li class="nav-item" <?=($A2==1)?'' : 'style="display:none"' ?>>
                            <a href="<?php echo roothtml.'ear/ear.php' ?>"
                                class="nav-link <?php echo (curlink == 'ear.php' || curlink == 'eargrade.php' 
                                || curlink == 'earstudent.php' || curlink == 'new_earstudent.php' || 
                                curlink == 'studentexam.php' || curlink == 'studentactivity.php' || 
                                curlink == 'studenttimetable.php' || curlink == 'studentattendance.php' || 
                                curlink == 'new_studentattendance.php' || curlink == 'studentdetail.php' || 
                                curlink == 'studentmonthly.php' || curlink == 'new_studentmonthly.php' || 
                                curlink == 'show_studentexam.php' || curlink == 'add_studentexam.php' || 
                                curlink == 'edit_studentexam.php' || curlink == 'examreport.php' || 
                                curlink == 'studentfee.php' || curlink == 'studentfeepay.php' || curlink == 'studentfeeview.php' || 
                                curlink == 'studentatt_view.php' || curlink == 'studentatt_khalist.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-table"></i>
                                <p><?=$lang['home_ear']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A2==1)?'' : 'style="display:none"' ?> class="nav-item <?php echo (curlink == 'uniformcategory.php' || curlink == 'uniformitem.php' || 
                            curlink == 'uniformsell.php' || curlink == 'uniformvoucher.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    <?=$lang['sale']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'uniform/uniformsell.php' ?>"
                                        class="nav-link <?php echo (curlink == 'uniformsell.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['sale uniform']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'uniform/uniformvoucher.php' ?>"
                                        class="nav-link <?php echo (curlink == 'uniformvoucher.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['voucher uniform']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'uniform/uniformitem.php' ?>"
                                        class="nav-link <?php echo (curlink == 'uniformitem.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['manage item']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'uniform/uniformcategory.php' ?>"
                                        class="nav-link <?php echo (curlink == 'uniformcategory.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['manage category']?></p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item" <?=($A3==1)?'' : 'style="display:none"' ?>>
                            <a href="<?php echo roothtml.'student/student.php' ?>" class="nav-link <?php echo (curlink == 'student.php' || curlink == 'new_student.php' || 
                                curlink == 'stu_attachment.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-user"></i>
                                <p><?=$lang['home_student']?></p>
                            </a>
                        </li>
                        <li <?=($A5==1)?'' : 'style="display:none"' ?> class="nav-item <?php echo (curlink == 'allowanceincome.php' 
                        || curlink=='allowanceexpense.php' || curlink=='allowancereport.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-comments-dollar"></i>
                                <p>
                                    Student Allowance
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'studentallowance/allowanceincome.php' ?>"
                                        class="nav-link <?php echo (curlink == 'allowanceincome.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Allowance Income</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'studentallowance/allowanceexpense.php' ?>"
                                        class="nav-link <?php echo (curlink == 'allowanceexpense.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Allowance Expense</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'studentallowance/allowancereport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'allowancereport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Allowance Report</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item" <?=($A3==1)?'' : 'style="display:none"' ?>>
                            <a href="<?php echo roothtml.'savefile/savefile.php' ?>" class="nav-link <?php echo (curlink == 'savefile.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-file-export"></i>
                                <p>Save File</p>
                            </a>
                        </li>
                        <li class="nav-item" <?=($A3==1)?'' : 'style="display:none"' ?>>
                            <a href="<?php echo roothtml.'questiontype/questiontype.php' ?>" class="nav-link <?php echo (curlink == 'questiontype.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>Question Type</p>
                            </a>
                        </li>
                        <li <?=($A5==1)?'' : 'style="display:none"' ?> class="nav-item <?php echo (curlink == 'saverecordgrade.php' 
                        || curlink=='savetitle.php' || curlink=='setupgrade.php' || curlink=='saverecordsubject.php' || 
                        curlink=='setupsubject.php' || curlink=='saverecordroom.php' || curlink=='setuproom.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-record-vinyl"></i>
                                <p>
                                    Teaching Record
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'dailyteachingrecord/setupgrade.php' ?>"
                                        class="nav-link <?php echo (curlink == 'setupgrade.php' || curlink == 'setupsubject.php' || curlink=='setuproom.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Setup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'dailyteachingrecord/saverecordgrade.php' ?>"
                                        class="nav-link <?php echo (curlink == 'saverecordgrade.php' || curlink == 'saverecordsubject.php' || curlink=='saverecordroom.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Save Title</p>
                                    </a>
                                </li>
                                <li class="nav-item" style="display:none";>
                                    <a href="<?php echo roothtml.'dailyteachingrecord/savetitle.php' ?>"
                                        class="nav-link <?php echo (curlink == 'savetitle.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p>Report Title</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?=($A4==1)?'' : 'style="display:none"' ?>
                            class="nav-item <?php echo (curlink == 'teacher.php' || 
                            curlink=='teachertimetable.php' || curlink=='showteachertimetable.php' || 
                            curlink=='new_teacher.php' || curlink=='teachersalary.php' || curlink=='salarypay.php' || curlink=='notiremain.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    <?=$lang['home_teacher']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'teacher/teacher.php' ?>"
                                        class="nav-link <?php echo (curlink == 'teacher.php' || curlink == 'new_teacher.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_manageteacher']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'teacher/teachertimetable.php' ?>"
                                        class="nav-link <?php echo (curlink == 'teachertimetable.php' || curlink=='showteachertimetable.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['teacher timetable']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'teacher/teachersalary.php' ?>"
                                        class="nav-link <?php echo (curlink == 'teachersalary.php' || curlink=='salarypay.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managesalary']?></p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li <?=($A5==1)?'' : 'style="display:none"' ?> class="nav-item <?php echo (curlink == 'staff.php' || curlink=='new_staff.php' 
                            || curlink=='staffsalary.php' || curlink=='salarypaystaff.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    <?=$lang['home_staff']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'staff/staff.php' ?>"
                                        class="nav-link <?php echo (curlink == 'staff.php' || curlink == 'new_staff.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managestaff']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'staff/staffsalary.php' ?>"
                                        class="nav-link <?php echo (curlink == 'staffsalary.php' || curlink == 'salarypaystaff.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managestaffsalary']?></p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li <?=($A6==1)?'' : 'style="display:none"' ?>
                            class="nav-item <?php echo ( curlink == 'hostel.php' || 
                            curlink == 'religion.php' || curlink == 'national.php' || 
                             curlink == 'paycategory.php' || curlink=='examtype.php' || 
                             curlink=='subject.php' || curlink == 'grade.php' || 
                             curlink=='bonus.php' || curlink == 'cut.php' || 
                             curlink=='paytype.php' || curlink=='room.php' || curlink=='time.php' || curlink=='setting.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    <?=$lang['home_setup']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/religion.php' ?>"
                                        class="nav-link <?php echo (curlink == 'religion.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managereligion']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/national.php' ?>"
                                        class="nav-link <?php echo (curlink == 'national.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managenational']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/grade.php' ?>"
                                        class="nav-link <?php echo (curlink == 'grade.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managegrade']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/room.php' ?>"
                                        class="nav-link <?php echo (curlink == 'room.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_manageroom']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/hostel.php' ?>"
                                        class="nav-link <?php echo (curlink == 'hostel.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managehostel']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/subject.php' ?>"
                                        class="nav-link <?php echo (curlink == 'subject.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managesubject']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/paycategory.php' ?>"
                                        class="nav-link <?php echo (curlink == 'paycategory.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managepaycategory']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/paytype.php' ?>"
                                        class="nav-link <?php echo (curlink == 'paytype.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managepaytype']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/examtype.php' ?>"
                                        class="nav-link <?php echo (curlink == 'examtype.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_manageexamtype']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/bonus.php' ?>"
                                        class="nav-link <?php echo (curlink == 'bonus.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managesalarybonus']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/cut.php' ?>"
                                        class="nav-link <?php echo (curlink == 'cut.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managesalarycut']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/time.php' ?>"
                                        class="nav-link <?php echo (curlink == 'time.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_managetime']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'stepup/setting.php' ?>"
                                        class="nav-link <?php echo (curlink == 'setting.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['manage setting']?></p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?=($A7==1)?'' : 'style="display:none"' ?>
                            class="nav-item <?php echo (curlink=='expense.php' || curlink=='expensecategory.php' || curlink=='voucherreport.php' ||curlink == 'todayreport.php' || curlink=='monthreport.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-dollar"></i>
                                <p>
                                    <?=$lang['home_expense']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'expense/expense.php' ?>"
                                        class="nav-link <?php echo (curlink == 'expense.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_manageexpense']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'expense/expensecategory.php' ?>"
                                        class="nav-link <?php echo (curlink == 'expensecategory.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_manageexpensecat']?></p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?=($A8==1)?'' : 'style="display:none"' ?>
                            class="nav-item <?php echo (curlink == 'announcement_all.php' || curlink=='announcement_parent.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    <?=$lang['home_announcement']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'announcement/announcement_all.php' ?>"
                                        class="nav-link <?php echo (curlink == 'announcement_all.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_allannouncement']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'announcement/announcement_parent.php' ?>"
                                        class="nav-link <?php echo (curlink == 'announcement_parent.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['home_parentannouncement']?></p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?=($A9==1)?'' : 'style="display:none"' ?> class="nav-item">
                            <a href="<?php echo roothtml.'cms/cms.php' ?>"
                                class="nav-link <?php echo (curlink == 'cms.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-dollar"></i>
                                <p>
                                    <?=$lang['home_cms']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A10==1)?'' : 'style="display:none"' ?> class="nav-item">
                            <a href="<?php echo roothtml.'user/user.php' ?>"
                                class="nav-link <?php echo (curlink == 'user.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    <?=$lang['home_useraccount']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A11==1)?'' : 'style="display:none"' ?> class="nav-item" style="display: none;">
                            <a href="<?php echo roothtml.'appuser/appuser.php' ?>"
                                class="nav-link <?php echo (curlink == 'appuser.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-phone"></i>
                                <p>
                                    <?=$lang['home_appuseraccount']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A12==1)?'' : 'style="display:none"' ?>
                            class="nav-item <?php echo (curlink == 'finance.php' || curlink=='feereport.php' || curlink=='teachersalary.php')?'menu-open' : '' ?>">

                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bar-chart-o"></i>
                                <p>
                                    <?=$lang['home_finance']?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'finance/finance.php' ?>"
                                        class="nav-link <?php echo (curlink == 'finance.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['financial']?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo roothtml.'finance/feereport.php' ?>"
                                        class="nav-link <?php echo (curlink == 'feereport.php')?'bgactive' : '' ?>">
                                        <i class="far fa-circle nav-icon" style="font-size:10px;"></i>
                                        <p><?=$lang['student fee report']?></p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li <?=($A13==1)?'' : 'style="display:none"' ?> class="nav-item">
                            <a href="<?php echo roothtml.'todolist/todolist.php' ?>"
                                class="nav-link <?php echo (curlink == 'todolist.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-notes-medical"></i>
                                <p>
                                    <?=$lang['home_todolist']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A14==1)?'' : 'style="display:none"' ?> class="nav-item" style="display: none;">
                            <a href="<?php echo roothtml.'chat/chat.php' ?>"
                                class="nav-link <?php echo (curlink == 'chat.php' || 
                                curlink == 'chat_view.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    <?=$lang['home_chatroom']?>
                                </p>
                            </a>
                        </li>
                        <li <?=($A15==1)?'' : 'style="display:none"' ?> class="nav-item">
                            <a href="<?php echo roothtml.'log/log.php' ?>"
                                class="nav-link <?php echo (curlink == 'log.php')?'bgactive' : '' ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    <?=$lang['home_loghistory']?>
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <br><br>

        <div class="loader" style="display:none;">
            <div class="center-load">
                <img src="<?php echo roothtml.'lib/images/ajax-loader1.gif'; ?>" />
            </div>
        </div>


        <div class="modal fade" id="modaltoday">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-teal">
                        <h4 class="modal-title">ယနေ့အရောင်းကိုကြည့်ရန်</h4>
                        <div class="float-right m-2">
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            <a id="btnprintvoucher" class="text-white" style="float:right;"><i class="fas fa-print"
                                    style="font-size:20px;"></i></a>

                        </div>
                    </div>

                    <div id="frmtoday" class="container  modal-body">

                    </div>
                </div>
            </div>
        </div>


        <?php }else{  

header("location:". roothtml."errorpage.php");   
      
}
?>