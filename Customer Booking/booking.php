<?php
session_start();

require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the already booked slots from the database
$bookedSlots = array();
$stmt = $conn->prepare("SELECT ParkingNumber FROM vehicle_info");
if ($stmt->execute()) {
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($parkingNumber);
        while ($stmt->fetch()) {
            $bookedSlots[] = $parkingNumber;
        }
    }
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = [
        "parking_number",
        "vehicle_category",
        "vehicle_company_name",
        "registration_number",
        "owner_name",
        "owner_contact_number",
        "in_time",
        "out_time",
        "remark"
    ];

    $sanitizedData = [];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $sanitizedData[$field] = $_POST[$field];
        }
    }

    // Calculate parking charges
    $hours = (strtotime($sanitizedData["out_time"]) - strtotime($sanitizedData["in_time"])) / 3600;
    $baseRate = 4.00; // Base rate per hour
    $discountedRate = $baseRate * 0.8; // Discounted rate per hour (20% off)
    $maxRegularHours = 5; // Maximum hours for regular rate

    $rate = $baseRate;
    if (in_array($sanitizedData["vehicle_category"], ["A", "VIP"])) {
        $rate = ($hours <= $maxRegularHours) ? $baseRate * $hours : ($baseRate * $maxRegularHours) + ($discountedRate * ($hours - $maxRegularHours));
    } elseif (in_array($sanitizedData["vehicle_category"], ["C", "OKU"])) {
        $rate = ($hours <= $maxRegularHours) ? $baseRate * $hours * 0.8 : ($baseRate * $maxRegularHours * 0.8) + ($discountedRate * ($hours - $maxRegularHours) * 0.8);
    }

    $stmt = $conn->prepare("INSERT INTO vehicle_info (ParkingNumber, VehicleCategory, VehicleCompanyname, RegistrationNumber, OwnerName, OwnerContactNumber, InTime, OutTime, Remark, ParkingCharge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error in preparing the statement: " . $conn->error);
    }

    // Bind the parameters dynamically
    $bindParams = array_merge(["sssssssssd"], array_values($sanitizedData), [$rate]);
    $stmt->bind_param(...$bindParams);

    // Execute the query
    if ($stmt->execute()) {
        header("Location: success_page.php"); // Redirect to payment.html
        exit(); // Terminate script execution after redirect
    } else {
        $error = "Error occurred while booking. Please try again. Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
    $conn->close(); // Close the connection
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Parking Booking</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url("parking 1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }

        .booking-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: transparent;
        }

        .booking-card {
            width: 800px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        .booking-card-header {
            background-color: #0d6efd;
            border-radius: 10px 10px 0px 0px;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .booking-card-header h4 {
            margin: 0;
        }

        .booking-card-body {
            padding: 20px;
        }

        .booking-card-body label {
            font-weight: bold;
        }

        .booking-card-body input[type="text"],
        .booking-card-body select,
        .booking-card-body textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .booking-card-body input[type="text"]:focus,
        .booking-card-body select:focus,
        .booking-card-body textarea:focus {
            outline: none;
        }

        .booking-card-footer {
            background-color: #333;
            border-radius: 0px 0px 10px 10px;
            padding: 20px;
            text-align: center;
        }

        .booking-card-footer p {
            margin: 0;
            color: #fff;
        }

        .btn-booking {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .btn-booking:hover {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.7);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
        }

        .image-container img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
            opacity: 0.8;
        }

        .close-button:hover {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="container-fluid booking-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card booking-card">
                    <div class="card-header booking-card-header">
                        <h4>Booking</h4>
                    </div>
                    <div class="card-body booking-card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                                <!-- First Container - Left Side -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="owner_name">Owner Name</label>
                                        <input type="text" name="owner_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="owner_contact_number">Owner Contact Number(without '-')</label>
                                        <input type="text" name="owner_contact_number" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_company_name">Vehicle Company Name</label>
                                        <input type="text" name="vehicle_company_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="registration_number">Registration Number (ABC-1234)</label>
                                        <input type="text" name="registration_number" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Second Container - Right Side -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="parking_number">Parking Number</label>
                                        <select name="parking_number" class="form-control" required>
                                            <option value="">Select Parking Number</option>
                                            <optgroup label="A - VIP">
                                                <?php for ($i = 1; $i <= 16; $i++) : ?>
                                                    <?php $slot = "A$i"; ?>
                                                    <option value="<?php echo $slot; ?>" <?php echo in_array($slot, $bookedSlots) ? 'disabled' : ''; ?>>
                                                        <?php echo $slot; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </optgroup>
                                            <optgroup label="B - REGULAR">
                                                <?php for ($i = 1; $i <= 84; $i++) : ?>
                                                    <?php $slot = "B$i"; ?>
                                                    <option value="<?php echo $slot; ?>" <?php echo in_array($slot, $bookedSlots) ? 'disabled' : ''; ?>>
                                                        <?php echo $slot; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </optgroup>
                                            <optgroup label="C - OKU">
                                                <?php for ($i = 1; $i <= 8; $i++) : ?>
                                                    <?php $slot = "C$i"; ?>
                                                    <option value="<?php echo $slot; ?>" <?php echo in_array($slot, $bookedSlots) ? 'disabled' : ''; ?>>
                                                        <?php echo $slot; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_category">Vehicle Category</label>
                                        <select name="vehicle_category" class="form-control" required>
                                            <option value="">Select Vehicle Category</option>
                                            <option value="A">A - VIP</option>
                                            <option value="B">B - REGULAR</option>
                                            <option value="C">C - OKU</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="in_time">In Time</label>
                                        <input type="datetime-local" name="in_time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="out_time">Out Time</label>
                                        <input type="datetime-local" name="out_time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <script>
                                    function openModel() {
                                        window.open('model.html', '_blank');
                                    }
                                </script>
                                <div class="col-md-12">
                                    <div class="form-group text-center">
										<button type="button" class="btn btn-secondary" onclick="redirect()">Check Out</button>
                                        <button type="button" class="btn btn-secondary" onclick="openModel()">3D Model</button>
                                        <button type="button" class="btn btn-primary" onclick="showImage()">Parking Layout</button>
                                        <button type="submit" class="btn btn-booking" name="submit">Book Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if (isset($success)) : ?>
                        <div class="card-footer booking-card-footer">
                            <p><?php echo $success; ?></p>
                        </div>
                    <?php elseif (isset($error)) : ?>
                        <div class="card-footer booking-card-footer">
                            <p><?php echo $error; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="image-container" id="imageContainer" style="display: none;">
        <button class="close-button" onclick="hideImage()">&times;</button>
        <img src="Asset 2.png" alt="Parking Layout">
    </div>

    <script>
        function showImage() {
            document.getElementById('imageContainer').style.display = 'flex';
        }

        function hideImage() {
            document.getElementById('imageContainer').style.display = 'none';
        }
		function redirect() {
			window.location.href = "update_slot.php";
    }
    </script>
</body>

</html>