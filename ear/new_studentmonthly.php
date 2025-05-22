<?php
    include('../config.php');
    include(root.'master/header.php'); 

    $aid = (isset($_SESSION["stu_monthly_aid"])?$_SESSION["stu_monthly_aid"]:0);
    $action = "save";
    $monthname = "";
    $r1 = "";
    $r2 = "";
    $r3 = "";
    $r4 = "";
    $r5 = "";
    $r6 = "";
    $r7 = "";
    $r8 = "";
    $r9 = "";
    $r10 = "";
    $r11 = "";
    $sql = "select * from tblstudentmonthly where AID={$aid}";
    $res = mysqli_query($con,$sql);
    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_array($res);
        $action = "edit";
        $monthname = $row["MonthName"];
        $r1 = $row["R1"];
        $r2 = $row["R2"];
        $r3 = $row["R3"];
        $r4 = $row["R4"];
        $r5 = $row["R5"];
        $r6 = $row["R6"];
        $r7 = $row["R7"];
        $r8 = $row["R8"];
        $r9 = $row["R9"];
        $r10 = $row["R10"];
        $r11 = $row["R11"];
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <table>
                                <tr>
                                    <td>
                                        <a href="<?=roothtml.'ear/studentmonthly.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            Back
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            <form id="frmsave" method="POST">
                                <input type="hidden" name="action" value="<?=$action?>" />
                                <input type="hidden" name="aid" value="<?=$aid?>" />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="usr">လအမည် <span class="text-danger"
                                                    style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="monthname">
                                                <?php 
                                                for($i=0;$i<count($arr_month);$i++){ 
                                                    $txt_month = "";
                                                    if($arr_month[$i] == $monthname){
                                                        $txt_month = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_month[$i]?>" <?=$txt_month?>>
                                                    <?=$arr_month[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">ကျောင်းခေါ်ကြိမ်၅၇%ပြည့်/မပြည့် <span class="text-danger"
                                                    style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r1">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r1){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">လူမှုရေးပြစ်ချက်ကင်း၍ကျောင်းစည်းကမ်းလိုက်နာခြင်း <span
                                                    class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r2">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "selected";
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r2){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">ကျောင်း၏ဆရာ/ဆရာမဝေယျာဝစ္စ <span class="text-danger"
                                                    style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r3">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r3){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">သစ်ပင်ပန်းပင်မြက်ခင်းတို့ကိုစိုက်ပျိုးပြုစုခြင်း <span
                                                    class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r4">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "selected";
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r4){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="usr">ဒေသ၊နိုင်ငံတော်ဖွံ့ဖြိုးရေးလုပ်ငန်းများလေ့လာမှုနှင့်ပါဝင်မှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r5">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r5){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="usr">အများဆိုင်ရာလုပ်ငန်းများတွင်လုပ်အားပါဝင်မှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r6">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r6){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">မိဘ၏လုပ်ငန်းတွင်ပါဝင်ကူညီမှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r7">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r7){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">အားကစားနှင့်ကိုယ်ကာယလှုပ်ရှားမှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r8">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r8){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="usr">အမျိုးသားရေးစိတ်ဓာတ်နှင့်အမျိုးသားရေးခံယူချက်မြင့်မားရေးလုပ်ဆောင်မှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r9">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r9){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="usr">စာပေ၊အနုပညာ၊ပန်းချီ၊ဂီတကဏ္ဏများတွင်ပါဝင်မှု
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r10">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r10){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="usr">ကျောင်းမှဖွဲ့စည်းသောအသင်းများ၊လူမှုရေးအသင်းများတွင်ပါဝင်လှုပ်ရှားခြင်း
                                                <span class="text-danger" style="font-size:20px;">*</span></label>
                                            <select class="form-control border-primary" name="r11">
                                                <?php 
                                                for($i=0;$i<count($arr_mark);$i++){ 
                                                    $txt_mark = "";
                                                    if($arr_mark[$i] == $r11){
                                                        $txt_mark = "selected";
                                                    }
                                            ?>
                                                <option value="<?=$arr_mark[$i]?>" <?=$txt_mark?>>
                                                    <?=$arr_mark[$i]?>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <a href="<?=roothtml.'ear/studentmonthly.php'?>" type="button"
                                            class="btn btn-<?=$color?> next-step next-button"><i
                                                class="fas fa-arrow-left"></i>&nbsp;Back
                                        </a>
                                        <button type="submit" class="btn btn-<?=$color?> next-step next-button"><i
                                                class="fas fa-save"></i>&nbsp;Save
                                        </button>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
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
            url: "<?php echo roothtml.'ear/studentmonthly_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful!", "Save Successful.",
                        "success");
                    location.href = "<?=roothtml.'ear/studentmonthly.php'?>";
                } else if (data == 2) {
                    swal("Warning", "This month is already inserted.", "warning");
                } else {
                    swal("Error!", "Error Save.", "error");
                }
            }
        });
    });




});
</script>