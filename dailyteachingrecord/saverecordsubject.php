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
                    <h1><?= $_SESSION["gradename"] ?>(<?= $_SESSION["roomname"]?>)</h1>
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
                                        <a href="<?=roothtml.'dailyteachingrecord/saverecordroom.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            <?=$lang['btnback']?>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="saverecordsubject_action.php">
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
                                                                    <th>Teacher Name</th>
                                                                    <th>Date</th>
                                                                    <th>Time</th>
                                                                    <th>Remark</th>
                                                                </tr>
                                                            </thead>
                                                        <?php 
                                                        $sql2 = "select r.*,t.Name as name,g.AID as gaid,g.Description as description,
                                                                ti.Name as tiname from tblteachingcategory g,
                                                                tblteachingrecord r,tbltime ti,tblstaff t where g.AID=r.TeachingCategoryID and 
                                                                g.GradeID='{$gradeidsession}' and g.SubjectID='{$row["AID"]}' and g.RoomID='{$roomidsession}' and 
                                                                r.TeacherID=t.AID and t.Status=0 and r.TimeID=ti.AID order by g.Description";
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
                                                                                    <a href='#' id='btnedit' class='dropdown-item'
                                                                                    data-aid=<?= $row2["AID"]?>><i
                                                                                    class='fas fa-edit text-success'
                                                                                    style='font-size:13px;'></i>
                                                                                    Edit Record</a>
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
                                                                        <td><?= $row2["description"]?></td>
                                                                        <td><?= $row2["name"]?></td>
                                                                        <td><?= $row2["Date"]?></td>
                                                                        <td><?= $row2["tiname"]?></td>
                                                                        <td><?= $row2["Remark"]?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </div>
                                                            <?php }
                                                        }
                                                        else{?>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" class="text-center">No data</td>
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


<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    $('.select2').select2({
        theme: 'bootstrap4'
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/saverecordsubject_action.php' ?>",
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
        var aid = $("[name='aid']").val();
        var teacherid = $("[name='teacherid']").val();
        var dt = $("[name='dt']").val();
        var timeid = $("[name='time']").val();
        var rmk = $("[name='rmk']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/saverecordsubject_action.php' ?>",
            data: {
                action: 'update',
                aid: aid,
                teacherid: teacherid,
                dt: dt,
                timeid: timeid,
                rmk: rmk
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
                    url: "<?php echo roothtml.'dailyteachingrecord/saverecordsubject_action.php'; ?>",
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

    $(document).on("click", "#btngograde", function(e) {
        e.preventDefault();
        var aid = $(this).data('aid');
        var name = $(this).data('name');
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'dailyteachingrecord/setupgrade_action.php' ?>",
            data: {
                action: 'gograde',
                aid: aid,
                name: name
            },
            success: function(data) {
                location.href = "<?=roothtml.'dailyteachingrecord/setupsubject.php'?>";
            }
        });
    });


});
</script>