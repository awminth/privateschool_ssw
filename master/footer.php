<!-- <footer class="main-footer">
    
</footer> -->



<!-- ./wrapper -->

<!-- Summernote -->
<script src="<?php echo roothtml.'lib/plugins/summernote/summernote-bs4.min.js' ?>"></script>

<!-- jQuery -->
<script src="<?php echo roothtml.'lib/plugins/jquery/jquery.min.js' ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo roothtml.'lib/plugins/bootstrap/js/bootstrap.bundle.min.js' ?>"></script>
<!-- Select2 -->
<script src="<?php echo roothtml.'lib/plugins/select2/js/select2.full.min.js' ?>"></script>
<!-- BS-Stepper -->
<script src="<?php echo roothtml.'lib/plugins/bs-stepper/js/bs-stepper.min.js' ?>"></script>
<!-- DataTables -->
<script src="<?php echo roothtml.'lib/plugins/datatables/jquery.dataTables.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-responsive/js/dataTables.responsive.min.js' ?>"></script>
<script src="<?php echo roothtml.'lib/plugins/datatables-responsive/js/responsive.bootstrap4.min.js' ?>"></script>
<!-- bs-custom-file-input -->
<script src="<?php echo roothtml.'lib/plugins/bs-custom-file-input/bs-custom-file-input.min.js' ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo roothtml.'lib/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js' ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo roothtml.'lib/dist/js/adminlte.min.js' ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo roothtml.'lib/dist/js/demo.js' ?>"></script>
<!-- ChartJS -->
<script src="<?php echo roothtml.'lib/plugins/chart.js/Chart.min.js' ?>"></script>

<!-- Print -->
<script src="<?php echo roothtml.'lib/printThis.js' ?>"></script>
<!-- Calendar Events -->
<script src="<?php echo roothtml.'lib/calender/moment.min.js'?>"></script>
<script src="<?php echo roothtml.'lib/calender/fullcalendar.min.js'?>"></script>
<script src="<?php echo roothtml.'lib/print.min.js'?>"></script>

<script>
$(document).ready(function() {
    $(document).ajaxStart(function() {
        $(".loader").show();
    });

    $(document).ajaxComplete(function() {
        $(".loader").hide();
    });

    $('[data-toggle="tooltip"]').tooltip();

    //for input type file  
    bsCustomFileInput.init();

    $(document).on("click", "#btnlogout", function(e) {
        e.preventDefault();
        swal({
                title: "Answer ?",
                text: "Are you sure Exit!",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes,Sure!",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    type: "post",
                    url: "<?php echo roothtml.'index_action.php' ?>",
                    data: {
                        action: 'logout'
                    },
                    success: function(data) {

                        if (data == 1) {

                            location.href =
                                "<?php echo roothtml.'index.php' ?>";


                        }



                    }
                });
            });
    });


    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    $(document).on("click", "#btnmy", function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'index_action.php' ?>",
            data: {
                action: 'language',
                data: 'my'
            },
            success: function(data) {
                if (data == 1) {
                    window.location.reload();
                }
            }
        });
    });

    $(document).on("click", "#btnen", function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'index_action.php' ?>",
            data: {
                action: 'language',
                data: 'en'
            },
            success: function(data) {
                if (data == 1) {
                    window.location.reload();
                }
            }
        });
    });

    $(document).on("click", "#btnchina", function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'index_action.php' ?>",
            data: {
                action: 'language',
                data: 'china'
            },
            success: function(data) {
                if (data == 1) {
                    window.location.reload();
                }
            }
        });
    });


    




});
</script>
</div>

</body>

</html>