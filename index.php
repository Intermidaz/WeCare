<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeCare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }
    </style>
<body>
    <div class="container my-5" >
        <h2>List of Patients</h2>
        <a href="/wecare/create.php" class="btn btn-primary mb-2" role="button">New Patient</a>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Religion</th>
                        <th>Phone</th>
                        <th>Adress</th>
                        <th>NIK</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "wecare";

                    //database connection
                    $connection = new mysqli($servername, $username, $password, $database);

                    //check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    // read all row
                    $sql = "SELECT * FROM pasien";
                    $result = $connection->query($sql);

                    if(!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    //read data in row
                    while($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[name]</td>
                            <td>$row[sex]</td>
                            <td>$row[religion]</td>
                            <td>$row[phone]</td>
                            <td>$row[address]</td>
                            <td>$row[nik]</td>
                            <td>$row[created_at]</td>
                            <td>
                                <a href='/wecare/edit.php?id=$row[id]' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='/wecare/delete.php?id=$row[id]' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>