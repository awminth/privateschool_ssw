<?php

include('../config.php');
include(root.'master/header.php'); 
?>
<style>
.mg {
    display: flex;
    flex-direction: column-reverse;
    overflow-y: scroll;
    overflow-x: hidden;
    height: 400px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chat Room</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline p-2">
                        <table class="table table-sm" id="show_status">

                        </table>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline p-2">
                        <h5>Send message to :&nbsp;&nbsp; <span class="text-primary">
                                <?php 
                            if(isset($_GET["toUser"])){ 
                                $name = GetString("select UserName from tblparent where AID={$_GET['toUser']}");
                        ?>
                                <?=$name?></span><span class="float-right"><a href='#' id='btnviewchat'
                                    data-toaid="<?=$_GET["toUser"]?>" class='btn btn-sm btn-info text-right'><i
                                        class='fas fa-table'></i> View ALL</a></span></h5>
                        <hr>
                        <input type="hidden" value="<?=$_GET["toUser"]?>" name="toUser" />

                        <?php }else{ ?>
                        <input type="hidden" value="" name="toUser" /></h5>
                        <hr>
                        <?php } ?>
                        <div class="modal-body mg" id="msgBody">

                        </div>
                        <hr>
                        <div class="form-group">
                            <form id="frmsend">
                                <div class="row">
                                    <div class="col-sm-10 pt-1">
                                        <input type="text" class="form-control" name="message" />
                                    </div>
                                    <div class="col-sm-2 pt-1">
                                        <button id="send" type="submit"
                                            class="btn btn-primary form-control">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
    function loadstatus() {
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'chat/chat_action.php' ?>",
            data: {
                action: 'status'
            },
            cache: false,
            global: false,
            success: function(data) {
                $("#show_status").html(data);
            }
        });
    }

    setInterval(function() {
        loadstatus();
        var toUser = $("[name='toUser']").val();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'chat/chat_action.php' ?>",
            data: {
                action: 'two',
                toUser: toUser
            },
            dataType: "text",
            cache: false,
            global: false,
            success: function(data) {
                $("#msgBody").html(data);
            }
        });
    }, 700);

    $(document).on("click", "#btnview", function(e) {
        e.preventDefault();
        var aid = $(this).data("aid");
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'chat/chat_action.php' ?>",
            data: {
                aid: aid,
                action: "view"
            },
            success: function(data) {
                location.href = "<?=roothtml.'chat/chat.php?toUser=' ?>" + data;
            }
        });
    });

    $(document).on("click", "#send", function(e) {
        e.preventDefault();
        var message = $("[name='message'").val();
        var toUser = $("[name='toUser']").val();
        if (message == '' || toUser == "") {
            swal("Error", "Please choose user and type message", "error");
            return false;
        }
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'chat/chat_action.php' ?>",
            data: {
                action: 'one',
                message: message,
                toUser: toUser
            },
            success: function(data) {
                if (data == 1) {
                    $("[name='message'").val("");
                }

            }
        });
    });

    $(document).on("click", "#btnviewchat", function(e) {
        var toaid = $(this).data("toaid");
        location.href = "<?=roothtml.'chat/chat_view.php?toaid='?>" + toaid;
    });



});
</script>