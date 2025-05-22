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
                    <h1><?=$lang['home_manageteacher']?></h1>
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
                                        <a href="<?=roothtml.'teacher/new_teacher.php'?>" type="button"
                                            class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>&nbsp;
                                            <?=$lang['tea_new']?>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="teacher_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['tea_excel']?></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="teacher_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="pdf"
                                                class="btn btn-sm btn-primary"><i
                                                    class="fas fa-file-excel"></i>&nbsp;PDF</button>
                                        </form>
                                    </td>
                                    <!-- <td>
                                        <button type="button" id="btnimport" class="btn btn-sm btn-primary"><i
                                                class="fas fa-file-excel"></i>&nbsp;
                                            <?=$lang['tea_importdata']?></button>
                                    </td> -->
                                </tr>
                            </table><!-- Card body -->
                        </div>
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

<!-- image modal -->
<div class="modal fade" id="viewmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">View Photo</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class='modal-body'>
                <div class='form-group'>
                    <label for='usr'> Photo:</label><br>
                    <img src='' id="showimg" style='width:100%;height:220px;' />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- import modal -->
<div class="modal fade" id="importmodal">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Import Data</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmimport" method="POST">
                <div class='modal-body'>
                    <input type="hidden" name="action" value="import" />
                    <div class='form-group'>
                        <label for='usr'> Upload Excel File:</label><br>
                        <input type="file" name="file" id="photo" class="form-control border border-primary p-1"
                            accept=".xlsx,.XLSX,.xls,.XLS" required>
                        <span>Upload file must be .xls, .xlsx, .XLS, .XLSX</span>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Teacher Card Modal -->
<div class="modal fade animate__animated flipInY" id="studentcardmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Teacher Card</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmcard" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

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

    function load_pag(page) {
        var entryvalue = $("[name='hid']").val();
        var search = $("[name='ser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teacher_action.php' ?>",
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

    $(document).on("click", "#btnedit", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teacher_action.php' ?>",
            data: {
                action: 'prepare',
                aid: aid
            },
            success: function(data) {
                location.href = "<?php echo roothtml.'teacher/new_teacher.php' ?>";
            }
        });
    });

    $(document).on("click", "#btnview", function() {
        var img = $(this).data("path");
        var path = "<?php echo roothtml.'upload/noimage.png' ?>";
        if (img != "") {
            path = "<?php echo roothtml.'upload/staff/' ?>" + img;
        }
        $('#showimg').attr('src', path);
        $("#viewmodal").modal('show');
    });

    $(document).on("click", "#btnprintcard", function(e) {
        e.preventDefault();
        print_fun("printdata");
    });

    $(document).on("click", "#btncard", function() {
        var aid = $(this).data("aid");
        var path = $(this).data("path");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teacher_action.php' ?>",
            data: {
                action: 'preparecard',
                aid: aid,
                path: path
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frmcard").html(data);
                $("#studentcardmodal").modal('show');
            }
        });
    });

    $(document).on("click", "#btndelete", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var path = $(this).data("path");
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
                    url: "<?php echo roothtml.'teacher/teacher_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid,
                        path: path
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

    $(document).on("click", "#btnimport", function(e) {
        e.preventDefault();
        $("#importmodal").modal('show');
    });

    $("#frmimport").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#importmodal").modal('hide');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'teacher/teacher_action.php' ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                // swal("",data,"");
                swal("Successful!", "Import Data Successful.",
                    "success");
                load_pag();
            }
        });
    });

});
</script>