<?php
    include('../config.php');
    include(root.'master/header.php');
    $gradeidsession = $_SESSION["gradeid"];
    $roomidsession = $_SESSION["roomid"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 mt-2">
                <div class="col-sm-12">
                    <h1><?= $_SESSION["gradename"]?>(<?= $_SESSION["roomname"]?>)</h1>
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
                                        <a href="<?=roothtml.'dailyteachingrecord/setuproom.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="setupsubject_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="excel"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-excel"></i>&nbsp;<?=$lang['btnexcel']?></button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">  
                            <div id="accordion">
                                <?php 
                                    $sql = "select * from tblsubject where GradeID='{$gradeidsession}'";
                                    $result = mysqli_query($con,$sql);
                                    $no = 0;
                                    if(mysqli_num_rows($result)>0){
                                        while($row=mysqli_fetch_array($result)){
                                        $no += 1;    ?>
                                        <div class="card">
                                            <div class="card-header" style="background-color:#b7bbbc">
                                                <button type="button" class="btn btn-light collapsed card-link" 
                                                 href="#collapse<?= $no?>" data-toggle="collapse" id="sub<?= $no?>"><?= $row["Name"]?></button>
                                            </div>
                                            <div id="collapse<?= $no?>" class="collapse" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="container">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Action</th>
                                                                    <th>NO</th>
                                                                    <th>Description</th>
                                                                    <th>Type</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                        <?php 
                                                        $sql2 = "select * from tblteachingcategory where GradeID='{$gradeidsession}' 
                                                                and SubjectID='{$row["AID"]}' and RoomID='{$roomidsession}'";
                                                        $result2 = mysqli_query($con,$sql2);
                                                        if(mysqli_num_rows($result2)>0){?>
                                                            <?php 
                                                                $no_two = 0;
                                                                while($row2=mysqli_fetch_array($result2)){
                                                                $no_two += 1;?>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class='dropdown'>
                                                                            <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                                <i class='fas fa-plus-square text-primary' style='font-size:22px;cursor:pointer;'></i>
                                                                            </a>
                                                                            <div class='dropdown-menu'>
                                                                                    <a href='#' id='btnnew' class='dropdown-item'
                                                                                    data-aid=<?= $row["AID"]?>><i
                                                                                    class='fas fa-plus text-success'
                                                                                    style='font-size:13px;'></i>
                                                                                    New Contents</a>
                                                                                <div class='dropdown-divider'></div>
                                                                                    <a href='#' id='btnedit' class='dropdown-item'
                                                                                    data-aid=<?= $row2["AID"]?>>
                                                                                    <i class='fas fa-edit text-primary'
                                                                                    style='font-size:13px;'></i>
                                                                                    Edit</a>
                                                                                <div class='dropdown-divider'></div>
                                                                                    <a href='#' id='btndelete' class='dropdown-item'
                                                                                    data-aid=<?= $row2["AID"]?>><i
                                                                                    class='fas fa-trash text-danger'
                                                                                    style='font-size:13px;'></i>
                                                                                    Delete</a>                  
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= $no_two?></td>
                                                                        <td><?= $row2["Description"]?></td>
                                                                        <td><?= $row2["Type"]?></td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-info" id="btnsaverecord" data-aid="<?= $row2["AID"]?>">Add Record</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </div>
                                                            <?php }
                                                        }
                                                        else{?>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <div class='dropdown'>
                                                                        <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                                            <i class='fas fa-plus-square text-primary' style='font-size:22px;cursor:pointer;'></i>
                                                                        </a>
                                                                        <div class='dropdown-menu'>
                                                                                <a href='#' id='btnnewnodata' class='dropdown-item'
                                                                                data-aid=<?= $row["AID"]?>><i
                                                                                class='fas fa-plus text-success'
                                                                                style='font-size:13px;'></i>
                                                                                New Contents</a>                  
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td colspan="3" class="text-center">No Data</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    <?php }
                                                    ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    }?>
                                 
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
<!-- new Modal -->

<div class="modal fade animate__animated animate__bounce" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">New Setup</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="frm" method="POST">
            <input type="hidden" name="action" value="save"/>
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Description :</label>
                        <input type="text" class="form-control border-success" id="description" name="description">
                    </div>
                    <div class="form-group">
                        <label for="usr"> Type :</label>
                        <input type="text" class="form-control border-success" id="type" name="type">
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        <?=$lang['staff_save']?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade animate__animated flipInY" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Edit Setup</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="frm1" method="POST">
                <!-- Modal body -->

            </form>
        </div>
    </div>
</div>

<div class="modal fade animate__animated animate__bounce" id="newrecordmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Add Record</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmrecord" method="POST">
                <input type="hidden" name="action" value="saverecord"/>

            </form>

        </div>
    </div>
</div>


<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    $(document).on("click", "#btnnewnodata", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'savepreparenodata',
                aid: aid
            },
            success: function(data) {
                $("#btnnewmodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btnnew", function() {
        $("#btnnewmodal").modal("show");
    });

    $(document).on("click", "#btnsave", function(e) {
        e.preventDefault();
        var type = $("[name='type']").val();
        var description = $("[name='description']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'save',
                type: type,
                description: description
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                if (data == 1) {
                    $("#editmodal").modal("hide");
                    swal("Successful", "Save data success.",
                        "success");
                    window.location.reload();
                } else {
                    swal("Error", "Save data failed.", "error");
                }
            }
        });
    });


    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'editprepare',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frm1").html(data);
                $("#editmodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btnupdate", function(e) {
        e.preventDefault();
        var aid = $("#aid").val();
        var type = $("[name='typeone']").val();
        var description = $("[name='descriptionone']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                type: type,
                description: description
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                if (data == 1) {
                    $("#editmodal").modal("hide");
                    swal("Successful", "Edit data success.",
                        "success");
                    window.location.reload();
                } else {
                    swal("Error", "Edit data failed.", "error");
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
                    url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    success: function(data) {
                        $(".loader").hide();
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            window.location.reload();
                        } else {
                            swal("Error",
                                "Delete data failed.",
                                "error");
                        }
                    }
                });
            });
    });

    $(document).on("click", "#btnsaverecord", function() {
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'saverecordprepare',
                aid: aid
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                $("#frmrecord").html(data);
                $("#newrecordmodal").modal("show");
            }
        });
    });

    $(document).on("click", "#btnsaverecordmodal", function(e) {
        e.preventDefault();
        var aid = $("[name='aid']").val();
        var teacherid = $("[name='teacherid']").val();
        var dt = $("[name='dt']").val();
        var time = $("[name='time']").val();
        var rmk = $("[name='rmk']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupsubject_action.php' ?>",
            data: {
                action: 'saverecord',
                aid: aid,
                teacherid: teacherid,
                dt: dt,
                time: time,
                rmk: rmk
            },
            beforeSend: function() {
                $(".loader").show();
            },
            success: function(data) {
                $(".loader").hide();
                if (data == 1) {
                    $("#newrecordmodal").modal("hide");
                    swal("Successful", "Save data success.",
                        "success");
                    window.location.reload();
                } else {
                    swal("Error", "Save data failed.", "error");
                }
            }
        });
    });


});
</script>