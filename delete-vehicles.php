<?php
include('includes/dbconn.php');

session_start();

error_reporting(0);

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

// Check if the form is submitted
if (isset($_POST['delete'])) {
    $registration_number = $_POST['registration_number'];

    // Delete the user data from the database based on the registration number
    $delete_query = "DELETE FROM vehicle_info WHERE RegistrationNumber = '$registration_number'";
    $result = mysqli_query($con, $delete_query);

    if ($result) {
        // Data deleted successfully
        echo "User data with registration number $registration_number has been deleted.";
    } else {
        // Error occurred while deleting data
        echo "Error deleting user data. Please try again.";
    }
}

// Fetch the data from the table
$data_query = "SELECT * FROM vehicle_info WHERE Status='Out'";
$result = mysqli_query($con, $data_query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VPS</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/datatable.css" rel="stylesheet">
    <link href="css/datepicker3.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

    <!--Custom Font-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navigation.php' ?>

    <?php
    $page = "delete-vehicles";
    include 'includes/sidebar.php'
    ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><em class="fa fa-home"></em></a></li>
                <li class="active">Delete Vehicles</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Delete Vehicles Data</div>
                    <div class="panel-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="registration_number">Registration Number:</label>
                                <input type="text" class="form-control" name="registration_number" id="registration_number" required>
                            </div>
                            <button type="submit" name="delete" class="btn btn-danger">Delete Vehicle Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Vehicle Data</div>
                    <div class="panel-body">
                        <table id="example" class="table table-striped table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle No.</th>
                                    <th>Company</th>
                                    <th>Category</th>
                                    <th>Parking Number</th>
                                    <th>Charge</th>
                                    <th>Vehicle's Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;

                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['RegistrationNumber']; ?></td>
                                        <td><?php echo $row['VehicleCompanyname']; ?></td>
                                        <td><?php echo $row['VehicleCategory']; ?></td>
                                        <td><?php echo 'CA-' . $row['ParkingNumber']; ?></td>
                                        <td><?php echo 'RM' . $row['ParkingCharge']; ?></td>
                                        <td><?php echo $row['OwnerName']; ?></td>
                                    </tr>
                                <?php
                                    $cnt = $cnt + 1;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!--/.row-->
        <?php include 'includes/footer.php' ?>
    </div> <!--/.main-->

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/chart.min.js"></script>
    <script src="js/chart-data.js"></script>
    <script src="js/easypiechart.js"></script>
    <script src="js/easypiechart-data.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/custom.js"></script>
    <script>
        window.onload = function () {
            var chart1 = document.getElementById("line-chart").getContext("2d");
            window.myLine = new Chart(chart1).Line(lineChartData, {
                responsive: true,
                scaleLineColor: "rgba(0,0,0,.2)",
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleFontColor: "#c5c7cc"
            });
        };
    </script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
