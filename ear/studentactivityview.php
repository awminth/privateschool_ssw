<?php

include('../config.php');
include(root.'master/header.php');
$aid = (isset($_GET["aid"]))?$_GET["aid"]:0;
$stuname = GetString("select p.Name from tblearstudent e,tblstudentprofile p 
where e.AID={$aid} and e.StudentID=p.AID");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Student Activity Transcation - <?=$stuname?></h1>
            <br>
            <table>
                <tr>
                    <td><a href="<?=roothtml.'ear/studentactivity.php'?>" type="button"
                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                            <?=$lang['staff_back']?>
                        </a></td>
                    <td>
                        <form method="POST" action="studentactivityview_action.php">
                            <input type="hidden" name="hid">
                            <input type="hidden" name="ser">
                            <input type="hidden" name="aid" value="<?=$aid?>">
                            <button type="submit" name="action" value="excel" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['staff_excel']?></button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="studentactivityview_action.php">
                            <input type="hidden" name="hid">
                            <input type="hidden" name="ser">
                            <input type="hidden" name="aid" value="<?=$aid?>">
                            <button type="submit" name="action" value="pdf" class="btn btn-sm btn-<?=$color?>"><i
                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                        </form>
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
                                    <td width="15%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-5 col-form-label"><?=$lang['staff_show']?></label>
                                            <div class="col-sm-7">
                                                <select id="entry" class="custom-select btn-sm">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="50%" class="float-right">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-3 col-form-label"><?=$lang['staff_search']?></label>
                                            <div class="col-sm-9">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Search...">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>


                            <div id="show_table">

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
<div class="modal fade animate__animated flipInY" id="editmodal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Student Activity</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmedit" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        var aid = $("[name='aid']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivityview_action.php' ?>",
            data: {
                action: 'show',
                page_no: page,
                entryvalue: entryvalue,
                search: search,
                aid: aid
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

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivityview_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            success: function(data) {
                $("#frmedit").html(data);
                $("#editmodal").modal("show");
            }
        });
    });


    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var aid = $("[name='eaid']").val();
        var desc = $("[name='edesc']").val();
        var dt = $("[name='edt']").val();
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/studentactivityview_action.php' ?>",
            data: {
                action: 'edit',
                aid: aid,
                desc: desc,
                dt: dt
            },
            success: function(data) {
                if (data == 1) {
                    swal("Success", "Edit successful", "success");
                    load_pag();
                    swal.close();
                } else {
                    swal("error", "error", "error");
                }

            }
        });
    });


    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        swal({
                title: "Delete?",
                text: "Are you sure delete!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'ear/studentactivityview_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_pag();
                            swal.close();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });

});
</script>