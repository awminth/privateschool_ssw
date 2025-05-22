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
                    <h1>Grade (<?=$_SESSION['yearname']?> ပညာသင်နှစ်)</h1>
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
                                    <td><a href="<?=roothtml.'ear/eargrade.php'?>" type="button"
                                            class="btn btn-sm btn-<?=$color?>"><i class="fas fa-arrow-left"></i>&nbsp;
                                            Back
                                        </a></td>
                                    <td>
                                        <form method="POST" action="studentreport_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="student_pdf"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-pdf"></i>&nbsp;Student PDF</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="studentreport_action.php">
                                            <input type="hidden" name="hid">
                                            <input type="hidden" name="ser">
                                            <button type="submit" name="action" value="teacher_pdf"
                                                class="btn btn-sm btn-<?=$color?>"><i
                                                    class="fas fa-file-pdf"></i>&nbsp;Teacher PDF</button>
                                        </form>
                                    </td>
                                </tr>

                            </table>

                        </div>

                        <!-- Card body -->
                        <div class="card-body">


                            <table width="100%">

                                <tr>
                                    <td width="15%">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Show</label>
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
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Search</label>
                                            <div class="col-sm-10">
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

<?php include(root.'master/footer.php') ?>

<script>
$(document).ready(function() {




});
</script>