<?php

include('../config.php');

include(root.'master/header.php') ;

$gradeid=$_SESSION['gradeid']; 
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>(<?=$_SESSION['yearname'].' - '. $_SESSION['gradename'] ?>) <?=$lang['earstu_activity']?></h1>
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
                                    <td><a href="<?=roothtml.'ear/earstudent.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['staff_back']?>
                                        </a></td>
                                    <td>
                                        <input type="hidden" name="hid">
                                        <input type="hidden" name="ser">
                                        <input type="hidden" name="hid1">
                                        <input type="hidden" name="ser1">

                                    </td>
                                </tr>

                            </table>

                        </div>

                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="card card-primary card-outline p-2">
                                        <table width="100%">

                                            <tr>
                                                <td width="20%">
                                                    <div class="form-group row">
                                                        <div class="col-sm-10">
                                                            <select id="entry" class="custom-select btn-sm">
                                                                <option value="10" selected>10</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="80%" class="float-right">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <input type="search" class="form-control" id="searching"
                                                                placeholder="Search...">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div id="show_table" class="table-responsive-sm">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="card card-primary card-outline p-2">
                                        <form method="POST" id="frmsave">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control border-success"
                                                            id="stuid" name="stuid">
                                                        <label for="usr"> <?=$lang['activity_studentname']?> :</label>
                                                        <input type="text" class="form-control border-success"
                                                            id="stuname" name="stuname">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="usr"> <?=$lang['activity_description']?> :</label>
                                                <textarea rows="3" class="form-control border-success"
                                                    name="desc"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="usr"> <?=$lang['nc_date']?> :</label>
                                                <input type="text" class="form-control border-success" name="dt"
                                                    value="<?=date('Y-m-d')?>">
                                            </div>

                                            <hr>
                                            <div class="text-right">
                                                <button type="submit" id="btnsave" style="width:30%"
                                                    class="btn btn-<?=$color?>"><?=$lang['staff_save']?></button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

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

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivity_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search
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

    $(document).on("change", "#entry", function() {
        var entryvalue = $(this).val();
        $("[name='hid']").val(entryvalue);
        load_pag();
    });


    $(document).on("keyup", "#searching", function() {
        var serdata = $(this).val();
        $("[name='ser']").val(serdata);
        load_pag();
    });

    $(document).on("click", "#btnaddstudent", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        $("[name='stuid']").val(aid);
        $("[name='stuname']").val(name);
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var stuid = $("[name='stuid']").val();
        var desc = $("[name='desc']").val();
        var dt = $("[name='dt']").val();
        if (stuid == "") {
            swal("Warning", "Please choose student.", "warning");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivity_action.php' ?>",
            data: {
                action: 'save',
                stuid: stuid,
                desc: desc,
                dt: dt
            },
            success: function(data) {
                if (data == 1) {
                    swal("Success", "Save successful", "success");

                } else {
                    swal("error", "error", "error");
                }
            }
        });
    });

    $(document).on("change", "#paytype", function(e) {
        e.preventDefault();
        var aid = $("[name='paytype']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivity_action.php' ?>",
            data: {
                action: 'paytypeamt',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("[name='amt']").val(data);
                $("[name='total']").val(data);


            }
        });
    });

    $(document).on("click", "#btnview", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        location.href = "<?=roothtml.'ear/studentactivityview.php?aid='?>" + aid;

    });



});
</script>