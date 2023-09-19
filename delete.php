<?php
if(isset($_GET["id"]) ){
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "wecare";

    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM pasien WHERE id=$id";
    
    if ($connection->query($sql)) {
        $response["status"] = array(
            "code" => 200,
            "response" => "success",
            "message" => "Patient deleted"
        );
        echo json_encode($response);

    } else {
        $response["status"] = array(
            "code" => 500,
            "response" => "error",
            "message" => "Failed to delete patient: " . $connection->error
        );
        echo json_encode($response);

    }
} else {
    $response["status"] = array(
        "code" => 400,
        "response" => "error",
        "message" => "Invalid request: Missing 'id' parameter"
    );
    echo json_encode($response);

}

header("location: /wecare/index.php");
exit;
?>