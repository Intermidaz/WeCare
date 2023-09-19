<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wecare";

$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$sex = "";
$religion = "";
$phone = "";
$address = "";
$nik = "";

$errorMessage = "";
$successMessage = "";

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET["id"])) {
        header("location: /wecare/index.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM pasien WHERE id = $id";
    $result = $connection->query($sql);

    if (!$result) {
        die("Invalid query: " . $connection->error);
    }

    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /wecare/index.php");
        exit;
    }

    $name = $row["name"];
    $sex = $row["sex"];
    $religion = $row["religion"];
    $phone = $row["phone"];
    $address = $row["address"];
    $nik = $row["nik"];

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $sex = $_POST["sex"];
    $religion = $_POST["religion"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $nik = $_POST["nik"];

    do {
        if(empty($name) || empty($sex) || empty($religion) || empty($phone) || empty($address) || empty($nik)) {
            $response["status"] = array(
                "code" => 400,
                "response" => "error",
                "message" => "All fields are required!"
            );
            $errorMessage = $response["status"]["message"];
            echo json_encode($response);
            break;
        }

        $checkSql = "SELECT id FROM pasien WHERE nik = '$nik' AND id != $id";
        $checkResult = $connection->query($checkSql);

        if ($checkResult && $checkResult->num_rows > 0) {
            $response["status"] = array(
                "code" => 400,
                "response" => "error",
                "message" => "NIK is already in use."
            );
            echo json_encode($response);
            $errorMessage = $response["status"]["message"];
            break;
        }

        $updateSql = "UPDATE pasien " .
            "SET name = '$name', sex = '$sex', religion = '$religion', phone = '$phone', address = '$address', nik = '$nik' " .
            "WHERE id = $id";
        $updateResult = $connection->query($updateSql);

        if (!$updateResult) {
            $response["status"] = array(
                "code" => 500,
                "response" => "error",
                "message" => "Invalid query: " . $connection->error
            );
            echo json_encode($response);
            $errorMessage = $response["status"]["message"];

            break;
        }

        $successMessage = "Patient updated";
        $response["status"] = array(
            "code" => 200,
            "response" => "success",
            "message" => $successMessage
        );
        echo json_encode($response);
        header("location: /wecare/index.php");
        exit;

    } while(false);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src=https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js></script>
</head>
<body>
    <div class="container my-5">
        <h2>Edit Client</h2>

        <?php
        if(!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
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

            <?php
            if(!empty($successMessage)){
                echo"
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>$successMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
            }
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a href="/wecare/index.php" class="btn btn-outline-primary" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>