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
                    <table>
                        <tr>
                            <td><a href="<?=roothtml.'staff/staffsalary.php'?>" class="btn btn-sm btn-<?=$color?>">
                                    <i class="fas fa-arrow-left text-white"></i><?=$lang['staff_back']?></a>
                            </td>
                            <td>
                                <h1>Staff Salary Pay</h1>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline p-3">
                <table>
                    <tr>
                        <td><label for="inputEmail3" class="col-form-label"><?=$lang['staff_date']?></label></td>
                        <td><input type="month" class="form-control" name="dtmonth" value="<?=date('Y-m')?>"></td>
                        <td><button class="btn btn-<?=$color?>" id="btnfind"><?=$lang['staff_search']?></button></td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <br>
                        <div class="row p-3">
                            <div class="col-sm-5">
                                <div id="show_table" class="table-responsive-sm">

                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div id="show_table1" class="table-responsive-sm">

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

<!-- The Modal -->
<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Salary Pay Slip</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmpayslip" method="POST" class="p-3">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    $(document).on("click", "#btnfind", function(e) {
        var dtmonth = $("[name='dtmonth']").val();
        load_pag(dtmonth);

    });


    function load_pag(dtmonth) {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/salarypaystaff_action.php' ?>",
            data: {
                action: 'showteacher',
                dtmonth: dtmonth
            },
            success: function(data) {
                $("#show_table").html(data);
            }
        });
    }


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var staffid = $(this).data("staffid");
        var name = $(this).data("name");
        var salary = $(this).data("salary");
        var dtmonth = $("[name='dtmonth']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/salarypaystaff_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid,
                staffid: staffid,
                name: name,
                salary: salary,
                dtmonth: dtmonth
            },
            success: function(data) {
                $("#show_table1").html(data);
            }
        });
    });

    $(document).on("click", "#btnpay", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var staffid = $(this).data("staffid");
        var staffname = $(this).data("staffname");
        var remain = $(this).data("remain");
        var salary = $(this).data("salary");
        var dtmonth = $("[name='dtmonth']").val();
        var rmk = $("[name='rmk']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'staff/salarypaystaff_action.php' ?>",
            data: {
                action: 'pay',
                aid: aid,
                staffid: staffid,
                staffname: staffname,
                remain: remain,
                salary: salary,
                dtmonth: dtmonth,
                rmk: rmk
            },
            success: function(data) {
                var dtmonth = $("[name='dtmonth']").val();
                load_pag(dtmonth);
                $("#show_table1").html('');
                $("#frmpayslip").html(data);
                $("#salarymodal").modal("show");
            }
        });
    });

    //print js fun
    function print_fun(place) {
        printJS({
            printable: place,
            type: 'html',
            style: 'table, tr,td {font-weight: bold; font-size: 10px;border-bottom: 1px solid LightGray;border-collapse: collapse;}' +
                '.txtc{text-align: center;font-weight: bold;}' +
                '.txtr{text-align: right;font-weight: bold;}' +
                '.txtl{text-align: left;font-weight: bold;}' +
                ' h5{ text-align: center;font-weight: bold;}' +
                '.fs{font-size: 10px;font-weight: bold;}' +
                '.txt,h5,h3,h6 {text-align: center;font-size: 10px;font-weight: bold;}' +
                '.lt{width:50%;float:left;font-weight: bold;}' +
                '.rt{width:50%;float:right;font-weight: bold;}' +
                '.wno{width:5%;font-weight: bold;}',
        });
    }

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });

});
</script>