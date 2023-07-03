<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM vehicle_info");

$vehicleDetails = [];
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $vehicleDetails[] = $row;
    }
} else {
    die("Error occurred while fetching data. Error: " . $stmt->error);
}

$stmt->close();

$searchResults = [];
if (isset($_POST['search'])) {
    $searchRegNumber = $_POST['searchRegNumber'];

    $stmt = $conn->prepare("SELECT * FROM vehicle_info WHERE RegistrationNumber = ?");
    $stmt->bind_param("s", $searchRegNumber);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    } else {
        die("Error occurred while searching. Error: " . $stmt->error);
    }

    $stmt->close();
}

if (isset($_POST['checkout'])) {
    $checkoutRegNumber = $_POST['checkoutRegNumber'];
    $parkingCharge = $_POST['parkingCharge'];

    $stmt = $conn->prepare("UPDATE vehicle_info SET Status = 'Out' WHERE RegistrationNumber = ?");
    $stmt->bind_param("s", $checkoutRegNumber);

    if ($stmt->execute()) {
        $checkoutSuccess = true;
		header("Location: ..\..\stripe\checkout.php?parkingCharge=" . urlencode($parkingCharge));

        exit();
    } else {
        die("Error occurred while checking out. Error: " . $stmt->error);
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Parking Booking</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles */
        body {
            background-image: url("parking 1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            margin-bottom: 20px;
        }

        .booking-card-header {
            background-color: #0d6efd;
            border-radius: 10px 10px 0px 0px;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header booking-card-header">
                <h4>Vehicle Check-out</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="searchRegNumber">Search by Registration Number:</label>
                        <input type="text" class="form-control" id="searchRegNumber" name="searchRegNumber"
                            placeholder="Enter registration number" required>
                    </div>
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Parking Number</th>
                            <th>Vehicle Category</th>
                            <th>Vehicle Company Name</th>
                            <th>Registration Number</th>
                            <th>Owner Name</th>
                            <th>Owner Contact Number</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Remark</th>
                            <th>Parking Charge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $results = isset($searchResults) && count($searchResults) > 0 ? $searchResults : $vehicleDetails;
                        foreach ($results as $vehicle) {
                            if ($vehicle['Status'] !== 'Out') {
                        ?>
                        <tr>
                            <td><?php echo $vehicle['ParkingNumber']; ?></td>
                            <td><?php echo $vehicle['VehicleCategory']; ?></td>
                            <td><?php echo $vehicle['VehicleCompanyname']; ?></td>
                            <td><?php echo $vehicle['RegistrationNumber']; ?></td>
                            <td><?php echo $vehicle['OwnerName']; ?></td>
                            <td><?php echo '0' . $vehicle['OwnerContactNumber']; ?></td>
                            <td><?php echo $vehicle['InTime']; ?></td>
                            <td><?php echo $vehicle['OutTime']; ?></td>
                            <td><?php echo $vehicle['Remark']; ?></td>
                            <td><?php echo $vehicle['ParkingCharge']; ?></td>
                            <td>
                                <?php if ($vehicle['Status'] !== 'Out') { ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="checkoutRegNumber"
                                        value="<?php echo $vehicle['RegistrationNumber']; ?>">
                                    <input type="hidden" name="parkingCharge"
                                        value="<?php echo $vehicle['ParkingCharge']; ?>">
                                    <button type="submit" name="checkout" class="btn btn-success">Check Out</button>
                                </form>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	
	<script>
    var price = <?php echo isset($_POST['parkingCharge']) ? json_encode($_POST['parkingCharge']) : 0; ?>;
</script>
</body>

</html>
