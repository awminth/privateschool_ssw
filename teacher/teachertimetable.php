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
                    <h1>Teacher Timetable</h1>
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
                        <input type="hidden" name="hid">
                        <input type="hidden" name="ser">
                        <div class="card-body">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <div class="form-group row">
                                            <label for="inputEmail3"
                                                class="col-sm-6 col-form-label"><?=$lang['tea_show']?></label>
                                            <div class="col-sm-6">
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
                                                class="col-sm-3 col-form-label"><?=$lang['tea_search']?></label>
                                            <div class="col-sm-9">
                                                <input type="search" class="form-control" id="searching"
                                                    placeholder="Searching . . . . . ">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <div id="show_table" class="table-responsive-sm">

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

<div class="modal fade" id="viewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">View Teacher Timetable</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm" method="POST">
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <input type="hidden" name="taid" />
                    <div class="form-group">
                        <label for="usr"> Teacher Name :</label>
                        <input type="text" class="form-control border-success" name="tname" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr"> Choose Year</label>
                        <select class="form-control border-success" name="earyear">
                            <?=load_earyear();?>
                        </select>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnshow' class='btn btn-<?=$color?>'><i class="fas fa-eye"></i>
                        View</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teachertimetable_action.php' ?>",
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

    $(document).on("click", "#btnview", function() {
        var aid = $(this).data("aid");
        var name = $(this).data("name");
        $("[name='taid']").val(aid);
        $("[name='tname']").val(name);
        $("#viewmodal").modal("show");
    });

    $(document).on("click", "#btnshow", function() {
        var taid = $("[name='taid']").val();
        var tname = $("[name='tname']").val();
        var earyear = $("[name='earyear']").val();
        $("#viewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teachertimetable_action.php' ?>",
            data: {
                action: 'go_timetable',
                taid: taid,
                tname: tname,
                earyear: earyear
            },
            success: function(data) {
                if (data == 1) {
                    location.href = "<?=roothtml.'teacher/showteachertimetable.php'?>";
                }
            }
        });
    });

});
</script>