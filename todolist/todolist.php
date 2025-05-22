<?php
    include('../config.php');
    include(root.'master/header.php'); 
?>
<style>
.container {
    margin-top: 10px;
}

.th-height {
    height: 30px;
    width: 70px;
    text-align: center;
}

.td-height {
    height: 100px;
    width: 70px;
}

.today {
    background: #DEB887;
    color: #0000ff;
    font-weight: bold;
}

th:nth-of-type(1),
td:nth-of-type(1) {
    color: #ff0000;
}

th:nth-of-type(7),
td:nth-of-type(7) {
    color: #ff0000;
}

ul {
    list-style-type: none;
}

.month {
    padding: 10px 25px;
    width: 100%;
    background: #1abc9c;
    text-align: center;
}

.month ul {
    margin: 0;
    padding: 0;
}

.month ul li {
    color: white;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 3px;
}

.month .prev a {
    float: left;
    padding-top: 10px;
    color: white;
    font-weight: bold;
}

.month .next a {
    float: right;
    padding-top: 10px;
    color: white;
    font-weight: bold;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?=$lang['home_todolist']?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <input type="hidden" name="hid_dt" value="<?=date('Y-m')?>" />
                        <input type="hidden" name="show_dt" value="<?=date('Y-m-d')?>" />
                        <div class="container mt-3 mb-3 ">
                            <div class="row">
                                <div class="col-7">
                                    <h4>To Do Events</h4>
                                </div>
                                <div class="col-5 text-right">
                                    <a href="#" class="btn btn-sm btn-primary text-right" id="btntoday">Today</a>
                                </div>
                            </div>
                            <hr>
                            <div id="show_list"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="month">
                            <ul>
                                <li class="prev"><a href="#" id="btnleft">&#10094;</a></li>
                                <li class="next"><a href="#" id="btnright">&#10095;</a></li>
                                <li id="showdate1">
                                    <?=date("F")?><br>
                                    <span style="font-size:18px"><?=date("Y")?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="container table-responsive-sm">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <?php for($i=0; $i<count($arr_day); $i++){ ?>
                                        <th class="th-height"><?=$arr_day[$i]?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="show_calendar">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- new Modal -->
<div class="modal fade" id="btnnewmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-<?=$color?>">
                <h4 class="modal-title">Add Event</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="frmsave" method="POST">
                <!-- Modal body -->
                <div class='modal-body' data-spy='scroll' data-offset='50'>
                    <div class="form-group">
                        <label for="usr"> Event Date</label>
                        <input type="date" name="event_dt" class="form-control border-success" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr"> Event Title</label>
                        <textarea name="event_title" class="form-control border-success" rows="4"></textarea>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnsave_event' class='btn btn-<?=$color?>'><i class="fas fa-save"></i>
                        <?=$lang['staff_save']?></button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- edit Modal -->
<div class="modal fade" id="editmodal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Event</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class='modal-body' data-spy='scroll' data-offset='50'>
                <form id="frmedit" method="POST">
                    <input type="hidden" name="action" value="edit" />
                    <input type="hidden" name="eaid" />
                    <div class="form-group">
                        <label for="usr"> Event Date</label>
                        <input type="date" name="eevent_dt" class="form-control border-success" readonly>
                    </div>
                    <div class="form-group">
                        <label for="usr">Event Title:</label>
                        <textarea class="form-control border-primary" name="eevent_title"
                            placeholder="Enter event title" rows="3" required></textarea>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' id='btneditsave' class='btn btn-<?=$color?>'><i class="fas fa-edit"></i>
                            <?=$lang['staff_edit']?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include(root.'master/footer.php'); ?>

<script>
$(document).ready(function() {

    function load_show_event() {
        var dt = $("[name='show_dt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'show',
                dt: dt
            },
            success: function(data) {
                $("#show_list").html(data);
                load_pag_calendar();
            }
        });
    }
    load_show_event();

    function load_pag_calendar() {
        var dt = $("[name='hid_dt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'show_calendar',
                dt: dt
            },
            success: function(data) {
                $("#show_calendar").html(data);
            }
        });
    }

    function dateToYMD(date) {
        var strArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
        ];
        var d = new Date(date);
        var m = strArray[d.getMonth()];
        var res = m + "<br><span style='font-size:18px'>" + d.getFullYear() + "</span>";
        // return m + " - " + d.getFullYear();
        return res;
    }

    $(document).on("click", "#btnleft", function() {
        var dt = $("[name='hid_dt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'left_dt',
                dt: dt
            },
            success: function(data) {
                $("[name='hid_dt']").val(data);
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_show_event();
            }
        });
    });

    $(document).on("click", "#btnright", function() {
        var dt = $("[name='hid_dt']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'right_dt',
                dt: dt
            },
            success: function(data) {
                $("[name='hid_dt']").val(data);
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_show_event();
            }
        });
    });

    $(document).on("click", "#btntoday", function() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'today_dt',
            },
            success: function(data) {
                $("[name='hid_dt']").val(data);
                $("[name='show_dt']").val("<?=date('Y-m-d')?>");
                var a = dateToYMD(data);
                $("#showdate1").html(a);
                load_show_event();
            }
        });
    });

    // for date format
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    $(document).on("click", "#btnevent", function(e) {
        e.preventDefault();
        var dt = $(this).data("dt");
        var res = formatDate(dt);
        $("[name='event_dt']").val(res);
        $("[name='show_dt']").val(res);
        $("#btnnewmodal").modal("show");
        load_show_event();
    });

    $(document).on("click", "#btnsave_event", function(e) {
        e.preventDefault();
        var dt = $("[name='event_dt']").val();
        var title = $("[name='event_title']").val();
        if (title == "") {
            swal("Information", "Please type event title.", "info");
            return false;
        }
        $("#btnnewmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php' ?>",
            data: {
                action: 'save_event',
                dt: dt,
                title: title
            },
            success: function(data) {
                swal("Success", "Save event is successful", "success");
                $("#frmsave")[0].reset();
                load_show_event();
                swal.close();
            }
        });
    });

    $(document).on("click", "#btnedit", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        var dt = $(this).data("dt");
        var title = $(this).data("title");
        $("[name='eaid']").val(aid);
        $("[name='eevent_dt']").val(dt);
        $("[name='eevent_title']").val(title);
        $("#editmodal").modal("show");
    });

    $("#frmedit").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var title = $("[name='eevent_title']").val();
        if (title == "") {
            swal("Information", "Please type event title.", "info");
            return false;
        }
        $("#editmodal").modal("hide");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'todolist/todolist_action.php'; ?>",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1) {
                    swal("Successful",
                        "Delete data success.",
                        "success");
                    load_show_event();
                    swal.close();
                } else {
                    swal("Error",
                        "Delete data failed.",
                        "error");
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
                    url: "<?php echo roothtml.'todolist/todolist_action.php'; ?>",
                    data: {
                        action: 'delete',
                        aid: aid
                    },
                    success: function(data) {
                        if (data == 1) {
                            swal("Successful",
                                "Delete data success.",
                                "success");
                            load_show_event();
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