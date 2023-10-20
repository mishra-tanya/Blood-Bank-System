<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank System</title>
    <link rel="shortcut icon" href="img.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <?php
        require('nav.php');
    ?>
    <div class="container">
        <div class="text-center m-2 mb-4">
            <h1>Available Blood Samples</h1>
        </div>
        <?php  
        include_once "config.php";
        $sql = "SELECT * FROM bloodinfo ORDER BY blood_info_id DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table class='table  table-bordered table-hover text-center'>";
            echo "<thead><tr><th>S.No</th>
            <th>Hospital</th>
            <th>Blood Type</th>
            <th>Expiration Date</th>
            <th>Request Blood Sample</th>
            </tr></thead>";


            $i = 1;
            while ($row = $result->fetch_assoc()) {
                $hospital_query = "SELECT * FROM hospital WHERE hospital_id = {$row['hospital_id']};";
                $hospital_details = $conn->query($hospital_query)->fetch_assoc();
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo <<<details
                <td>{$hospital_details['hospital_name']} , {$hospital_details['address']} , {$hospital_details['city']} , {$hospital_details['state']} , {$hospital_details['zip_code']}<br>Contact: {$hospital_details['phone_number']}</td>
details;
                echo "<td>" . $row["blood_type"] . "</td>";
                echo "<td>" . $row["expiration_date"] . "</td>";
                echo "<td><button class='btn btn-primary' disabled>Request</button><br><span style='color: red; font-size:small;'>Login to Request</span></td>";
                echo "</tr>";
                $i++;
            }
            echo "</table>";
        } else {
            echo "No blood samples available.";
        }
        $conn->close();
        ?>
    </div>
    </div>

    <footer class="bg-dark text-white py-2">
        <div class="container ">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; 2023 Blood Bank System</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>