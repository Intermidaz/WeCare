<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wecare";

$connection = new mysqli($servername, $username, $password, $database);

$name = "";
$sex = "";
$religion = "";
$phone = "";
$address = "";
$nik = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $sex = $_POST["sex"];
    $religion = $_POST["religion"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $nik = $_POST["nik"];

        // Check for empty fields
        if (empty($name) || empty($sex) || empty($religion) || empty($phone) || empty($address) || empty($nik)) {
            $response["status"] = array(
                "code" => 400,
                "response" => "error",
                "message" => "All fields are required!"
            );
            $errorMessage = $response["status"]["message"];
            echo json_encode($response);
        } else {
            $checkDuplicateSQL = "SELECT COUNT(*) FROM pasien WHERE nik = '$nik'";
            $result = $connection->query($checkDuplicateSQL);

            if($result) {
                $row = $result->fetch_assoc();
                if($row["COUNT(*)"] > 0) {
                    $response["status"] = array(
                        "code" => 400,
                        "response" => "error",
                        "message" => "NIK is already in use."
                    );
                    $errorMessage = $response["status"]["message"];
                    echo json_encode($response);
                } else {
                    $insertSQL = "INSERT INTO pasien (name, sex, religion, phone, address, nik) VALUES ('$name', '$sex', '$religion', '$phone', '$address', '$nik')";
                    $insertResult = $connection->query($insertSQL);

                if ($insertResult) {
                    $response["status"] = array(
                        "code" => 200,
                        "response" => "success",
                        "message" => "Patient added"
                    );
                    $errorMessage = $response["status"]["message"];
                    echo json_encode($response);
                    $name = "";
                    $sex = "";
                    $religion = "";
                    $phone = "";
                    $address = "";
                    $nik = "";

                    header("location: /wecare/index.php");
                    exit;
                } else {
                    $response["status"] = array(
                        "code" => 500,
                        "response" => "error",
                        "message" => "Error adding patient: " . $connection->error
                    );
                    $errorMessage = $response["status"]["message"];
                    echo json_encode($response);
                }
            }
        } else {
            $response["status"] = array(
                "code" => 500,
                "response" => "error",
                "message" => "Error checking for duplicate NIK: " . $connection->error
            );
            $errorMessage = $response["status"]["message"];
            echo json_encode($response);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>New Client</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissable fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        } elseif (!empty($successMessage)) {
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class=col-sm-6>
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Sex</label>
                <div class="col-sm-6">
                    <select class="form-select" name="sex">
                        <option value="Male" <?php if($sex == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if($sex == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if($sex == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Religion</label>
                <div class="col-sm-6">
                    <select class="form-select" name="religion">
                        <option value="Christian" <?php if($religion == 'Christian') echo 'selected'; ?>>Christian</option>
                        <option value="Muslim" <?php if($religion == 'Muslim') echo 'selected'; ?>>Muslim</option>
                        <option value="Hindu" <?php if($religion == 'Hindu') echo 'selected'; ?>>Hindu</option>
                        <option value="Buddhist" <?php if($religion == 'Buddhist') echo 'selected'; ?>>Buddhist</option>
                        <option value="Catholic" <?php if($religion == 'Catholic') echo 'selected'; ?>>Catholic</option>
                        <option value="Other" <?php if($religion == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                 </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class=col-sm-6>
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class=col-sm-6>
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">NIK</label>
                <div class=col-sm-6>
                    <input type="text" class="form-control" name="nik" value="<?php echo $nik; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/wecare/index.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>