<?php
include('../config.php');
include(root.'master/header.php');
$gradeid = $_SESSION['gradeid'];
$vno = $_SESSION['go_pay_vno'];
$ferryamt = 0;
$foodamt = 0;
$otheramt = 0;
$feeamt = 0;
$amt = 0;
$disc = 0;
$tax = 0;
$total = 0;
$totalpay = 0;
$remain = 0;
$chk = "";
$paytypeid = "";
$paytypename = "";
$stuid = $_SESSION['go_pay_stuid'];
$earstuid = $_SESSION['go_pay_earstuid'];
if($vno != ""){
    $sql = "select * from tblfee where VNO='{$vno}'";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $ferryamt = $row["FerryAmt"];
        $foodamt = $row["FoodAmt"];
        $otheramt = $row["OtherAmt"];
        $nightstudyamt = $row["NightStudyAmt"];
        $feeamt = $row["FeeAmt"];
        $amt = $row["Amt"];
        $total = $row["Total"];
        $totalpay = $row["TotalPay"];
        $remain = $row["Remain"];
        $chk = "readonly";
        // show paytype
        $sql_paytype = "select p.AID,c.Name from tblfee f,tblpaytypecategory c,tblpaytype p    
        where f.PayTypeID=p.AID and p.PayTypeID=c.AID and f.PayTypeID='{$row['PayTypeID']}' 
        and f.EARStudentID='{$row['EARStudentID']}'";
        $res_paytype = mysqli_query($con,$sql_paytype);
        if(mysqli_num_rows($res_paytype) > 0){
            $row_paytype = mysqli_fetch_array($res_paytype);
            $paytypeid = $row_paytype["AID"];
            $paytypename = $row_paytype["Name"];
        }
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-2">
                <div class="col-sm-12">
                    <h1>Student Fee For (<?=$_SESSION['go_pay_stuname']?>)</h1>
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
                                    <td><a href="<?=roothtml.'ear/studentfee.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a></td>
                                    <td>
                                        <input type="hidden" name="hid1">
                                        <input type="hidden" name="ser1">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control border-success" name="vno1"
                                            value="<?=$vno?>">
                                        <input type="hidden" class="form-control border-success" name="totalpay1"
                                            value="<?=$totalpay?>">
                                        <input type="hidden" class="form-control border-success" name="stuid"
                                            value="<?=$_SESSION['go_pay_stuid']?>">
                                        <input type="hidden" class="form-control border-success" name="earstuid"
                                            value="<?=$_SESSION['go_pay_earstuid']?>">
                                        <label for="usr"> <?=$lang['feepay_stuname']?></label>
                                        <input type="text" class="form-control border-success" id="stuname"
                                            name="stuname" value="<?=$_SESSION['go_pay_stuname']?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_paytype']?> </label>
                                        <select class="form-control border-success" id="paytype" name="paytype">
                                            <?php if($vno != ""){ ?>
                                            <option value="<?=$paytypeid?>"><?=$paytypename?></option>
                                            <?php }else{ ?>
                                            <option value="">Select Pay Type</option>
                                            <?=load_paytypestudent($gradeid);?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_fee']?> </label>
                                        <input type="number" class="form-control border-success" id="feeamt"
                                            name="feeamt" value="<?=$feeamt?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_ferry']?> </label>
                                        <input type="number" class="form-control border-success" id="ferryamt"
                                            name="ferryamt" value="<?=$ferryamt?>" <?=$chk?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_food']?> </label>
                                        <input type="number" class="form-control border-success" id="foodamt"
                                            name="foodamt" value="<?=$foodamt?>" <?=$chk?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_other']?> </label>
                                        <input type="number" class="form-control border-success" id="otheramt"
                                            name="otheramt" value="<?=$otheramt?>" <?=$chk?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> Night Study Amount </label>
                                        <input type="number" class="form-control border-success" id="nightstudyamt"
                                            name="nightstudyamt" value="<?=$nightstudyamt?>" <?=$chk?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usr"> <?=$lang['feepay_total']?> </label>
                                <input type="number" class="form-control border-success" readonly name="amt"
                                    value="<?=$amt?>" <?=$chk?>>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_disc']?> </label>
                                        <input type="number" class="form-control border-success" id="disc" name="disc"
                                            <?=$chk?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_tax']?> </label>
                                        <input type="number" class="form-control border-success" id="tax" name="tax"
                                            <?=$chk?>>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_grand']?> </label>
                                        <input type="number" class="form-control border-success" readonly name="total"
                                            value="<?=$total?>" <?=$chk?>>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_cash']?> </label>
                                        <input type="number" class="form-control border-success" id="cash" name="cash">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_mobile']?> </label>
                                        <input type="number" class="form-control border-success" id="mobile"
                                            name="mobile">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_mobilermk']?> </label>
                                        <select class="form-control border-success" name="rmk">
                                            <option value="">Select Rmk</option>
                                            <?=load_pay();?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_totalpay']?> </label>
                                        <input type="number" class="form-control border-success" readonly
                                            name="totalpay">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_remain']?> </label>
                                        <input type="number" class="form-control border-success" readonly name="remain"
                                            value="<?=$remain?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_paydt']?> </label>
                                        <input type="date" class="form-control border-success"
                                            value="<?=date('Y-m-d')?>" name="dt">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_payname']?></label>
                                        <input type="text" class="form-control border-success" name="payname">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr"> <?=$lang['feepay_receivename']?></label>
                                        <input type="text" class="form-control border-success" name="receivename">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="usr">For Month(Payment)</label>
                                        <input type="text" class="form-control border-success" name="paymonth">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-right">
                                <button id="btnsave" style="width:30%" class="btn btn-<?=$color?>"><i
                                        class="fas fa-save"></i>&nbsp;<?=$lang['btnsave']?></button>
                            </div>
                            <div id="error">

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

<!-- The Modal -->
<div class="modal fade" id="salarymodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Student Fee Pay Slip</h4>
                <button type="button" class="close text-white" id="btnpayclose">&times;</button>
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

    function calculatefun() {
        var feeamt = $("[name='feeamt'").val();
        var ferryamt = $("[name='ferryamt'").val();
        var foodamt = $("[name='foodamt'").val();
        var otheramt = $("[name='otheramt'").val();
        var nightstudyamt = $("[name='nightstudyamt'").val();
        var total = Number(feeamt) + Number(ferryamt) + Number(foodamt) + Number(otheramt) + Number(nightstudyamt);
        $("[name='amt'").val(total);

        var amt = $("[name='amt'").val();
        var disc = $("[name='disc'").val();
        var tax = $("[name='tax'").val();
        var total = $("[name='total'").val();
        var cash = $("[name='cash'").val();
        var mobile = $("[name='mobile'").val();
        var totalpay = $("[name='totalpay'").val();
        var totalpay1 = $("[name='totalpay1'").val();
        var remain = $("[name='remain'").val();

        var a1 = ((Number(amt) + Number(tax)) - Number(disc)) - Number(totalpay1);
        // var a1 = (Number(amt) + Number(disc)) - Number(tax);
        var a2 = Number(cash) + Number(mobile);
        var a3 = a1 - a2;
        $("[name='total'").val(a1);
        $("[name='totalpay'").val(a2);
        $("[name='remain'").val(a3);
    }

    $(document).on("keyup", "#ferryamt", function() {
        calculatefun();
    });

    $(document).on("keyup", "#foodamt", function() {
        calculatefun();
    });

    $(document).on("keyup", "#otheramt", function() {
        calculatefun();
    });

    $(document).on("keyup", "#nightstudyamt", function() {
        calculatefun();
    });

    $(document).on("keyup", "#disc", function() {
        calculatefun();
    });

    $(document).on("keyup", "#tax", function() {
        calculatefun();
    });

    $(document).on("keyup", "#cash", function() {
        calculatefun();
    });

    $(document).on("keyup", "#mobile", function() {
        calculatefun();
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var studentid = $("[name='stuid']").val();
        var earstuid = $("[name='earstuid']").val();
        var earstuname = $("[name='stuname']").val();
        var paytype = $("[name='paytype']").val();
        var feeamt = $("[name='feeamt'").val();
        var ferryamt = $("[name='ferryamt'").val();
        var foodamt = $("[name='foodamt'").val();
        var otheramt = $("[name='otheramt'").val();
        var nightstudyamt = $("[name='nightstudyamt'").val();
        var amt = $("[name='amt']").val();
        var disc = $("[name='disc']").val();
        var tax = $("[name='tax']").val();
        var total = $("[name='total']").val();
        var cash = $("[name='cash']").val();
        var mobile = $("[name='mobile']").val();
        var rmk = $("[name='rmk']").val();
        var totalpay = $("[name='totalpay']").val();
        var totalpay1 = $("[name='totalpay1']").val();
        var remain = $("[name='remain']").val();
        var dt = $("[name='dt']").val();
        var feevno = $("[name='vno1']").val();
        var payname = $("[name='payname']").val();
        var receivename = $("[name='receivename']").val();
        var paymonth = $("[name='paymonth']").val();
        if (mobile >= 0 && mobile != "") {
            if (rmk == "") {
                swal('info', 'Please fill mobile rmk', 'info');
                return;
            }
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
            data: {
                action: 'savefee',
                studentid: studentid,
                earstuid: earstuid,
                earstuname: earstuname,
                paytype: paytype,
                feeamt: feeamt,
                ferryamt: ferryamt,
                foodamt: foodamt,
                otheramt: otheramt,
                nightstudyamt: nightstudyamt,
                amt: amt,
                disc: disc,
                tax: tax,
                total: total,
                cash: cash,
                mobile: mobile,
                rmk: rmk,
                totalpay: totalpay,
                totalpay1: totalpay1,
                remain: remain,
                dt: dt,
                feevno: feevno,
                payname: payname,
                receivename: receivename,
                paymonth: paymonth
            },
            success: function(data) {
                // $("#error").html(data);
                if (data != 0) {
                    // window.location.reload();
                    $("#frmpayslip").html(data);
                    $("#salarymodal").modal("show");
                } else {
                    swal("error", "error", "error");
                }
            }
        });
    });

    $(document).on("click", "#btnpayclose", function(e) {
        e.preventDefault();
        $("#salarymodal").modal("hide");
        location.href = "<?=roothtml.'ear/studentfee.php'?>";
    });

    $(document).on("change", "#paytype", function(e) {
        e.preventDefault();
        var aid = $("[name='paytype']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentfee_action.php' ?>",
            data: {
                action: 'paytypeamt',
                aid: aid
            },
            success: function(data) {
                $("[name='feeamt']").val(data);
                $("[name='amt']").val(data);
                $("[name='total']").val(data);
                $("[name='remain']").val(data);
            }
        });
    });

    $(document).on("click", "#btnprint", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });



});
</script>