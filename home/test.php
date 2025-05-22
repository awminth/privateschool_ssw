<?php
include('../config.php');
include(root.'master/header.php');

$aid=$_SESSION['userid'];
$sql="select * from tbllogin where AID={$aid}";
$result=mysqli_query($con,$sql) or die("SQL a Query");
$row = mysqli_fetch_array($result);
$A1=$row["A1"];
$A2=$row["A2"];
$A3=$row["A3"];
$A4=$row["A4"];
$A5=$row["A5"];
$A6=$row["A6"];
$A7=$row["A7"];
$A8=$row["A8"];
$A9=$row["A9"];
$A10=$row["A10"];
$A11=$row["A11"];
$A12=$row["A12"];
$A13=$row["A13"];
$A14=$row["A14"];
$A15=$row["A15"];
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?=$lang['home_dashboard']?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <?php 
         
?>

    <!-- Main content -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline p-2" style="background-color:#e4e5e9;">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">School Fee</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline p-2" style="background-color:#e4e5e9;">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Total Expense</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChartone" style="width:100%;max-width:600px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline p-2" style="background-color:#e4e5e9;">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Net Profit</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChartthree" style="width:100%;max-width:600px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline p-2" style="background-color:#e4e5e9;">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><?=$lang['h_studentlist']?></h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="show_table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->




<?php include(root.'master/footer.php'); ?>

<script>
const xValues = ["January", "February", "March", "April", "May"];
const yValues = [55, 49, 44, 24, 15];
const barColors = ["red", "green","blue","orange","brown"];

new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Student Income Fee"
    }
  }
});

const aValues = ["June", "July", "August", "September", "Otober"];
const bValues = [55, 49, 44, 24, 15];
const carColors = [
  "#b91d47",
  "#00aba9",
  "#2b5797",
  "#e8c3b9",
  "#1e7145"
];

new Chart("myChartone", {
  type: "doughnut",
  data: {
    labels: aValues,
    datasets: [{
      backgroundColor: barColors,
      data: bValues
    }]
  },
  options: {
    title: {
      display: true,
      text: "Total Expense"
    }
  }
});

const dValues = [100,200,300,400,500,600,700,800,900,1000];
new Chart("myChartthree", {
  type: "line",
  data: {
    labels: dValues,
    datasets: [{ 
      data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      borderColor: "red",
      fill: false
    }, { 
      data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      borderColor: "green",
      fill: false
    }, { 
      data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      borderColor: "blue",
      fill: false
    }]
  },
  options: {
    legend: {display: false}
  }
});

$(document).ready(function() {

    function load_pag() {

        $.ajax({
            type: "post",
            url: "<?php echo roothtml.'ear/ear_action.php' ?>",
            data: {
                action: 'showall',

            },
            success: function(data) {
                $("#show_table").html(data);

            }
        });
    }

    load_pag();









});
</script>