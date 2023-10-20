<?php
session_start();
  if(isset($_REQUEST['logout'])){
    session_unset();
    session_destroy();
  }
  if(!isset($_SESSION['user_id'])){
    header("location:login.php");
  }
?>

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
    <div class="row">
        <div class="col-11"></div>
        <div class="col-1">
            <form method="get" action=''>
                <button type="submit" name="logout" class="btn btn-danger px-2 py-1">
                    Logout
                </button>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="text-center m-2 mb-4">
            <h1>Available Blood Samples</h1>
        </div>

        <?php 
  $compatibility = [
    "A+" => ["A+","O+","A-","O-"],
    "O+" => ["O+","O-"],
    "B+" => ["B+","O+","B-","O-"],
    "AB+" => ["A+","B+","AB+","O+","A-","B-","AB-","O-"],
    "A-" => ["A-","O-"],
    "O-" => ["O-"],
    "B-" => ["B-","O-"],
    "AB-" => ["A-","B-","AB-","O-"],
  ];
  require("config.php");
  $user_id = $_SESSION["user_id"];
  $sql = "SELECT receiver_id,blood_group FROM receiver WHERE user_id= $user_id;";
  $data = $conn->query($sql)->fetch_assoc();
  $receiver_id = $data['receiver_id'];
  $receiver_blood_group = $data['blood_group'];

  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_REQUEST['request'])){
      $blood_info_id = $_REQUEST['bloodId'];
      $request_date_time = date("Y/m/d H:i:s");
      $sql = "INSERT INTO request(receiver_id, blood_info_id, request_date_time) VALUES ($receiver_id, $blood_info_id, '$request_date_time');";
      if($conn->query($sql)){
          echo"Request Sent Sucessfully";
      }
      else{
          echo"Request Failed";
      }
    }
  }
?>
        <div class="container">
            <?php
$sql = "SELECT * FROM bloodinfo ORDER BY blood_info_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-hover text-center'>";
    echo "<thead><tr><th>S.No</th><th>Hospital</th><th>Blood Type</th><th>Expiration Date</th><th>Request Blood Sample</th></tr></thead>";
    $i=1;
    while($row = $result->fetch_assoc()) {
        $hospital_query = "SELECT * FROM hospital WHERE hospital_id = {$row['hospital_id']};";
        $hospital_details = $conn->query($hospital_query)->fetch_assoc();
        echo "<tr>";
        echo "<td>" .$i. "</td>";
        echo <<<details
        <td>{$hospital_details['hospital_name']}<br>{$hospital_details['address']} {$hospital_details['city']} {$hospital_details['state']} {$hospital_details['zip_code']}<br>Contact: {$hospital_details['phone_number']}</td>
details;
echo "<td>" . $row["blood_type"] . "</td>";
        echo "<td>" . $row["expiration_date"] . "</td>";
        if( in_array($row["blood_type"],$compatibility[$receiver_blood_group])){
            $request_query = "SELECT receiver_id,blood_info_id FROM request WHERE receiver_id = $receiver_id;";
            $request_query_result = $conn->query($request_query);
            if($request_query_result->num_rows > 0){
                $flag = 0;
                while($request_details = $request_query_result->fetch_assoc()){
                    if($row['blood_info_id'] == $request_details['blood_info_id']){
                        $flag =1;
                    }
                }
                if($flag == 0){
                    echo "<td><form action='' method='POST'><button class='btn btn-primary' type='submit' name='request'>Request</button><input type='hidden' value={$row['blood_info_id']} name='bloodId' ></form></td>";
                }
                else{
                    echo "<td><button class='btn btn-warning' disabled>Requested</button></td>";
                }
            }
            else{
                echo "<td><form action='' method='POST'><button class='btn btn-primary' type='submit' name='request'>Request</button><input type='hidden' value={$row['blood_info_id']} name='bloodId' ></form></td>";
            }
        }
        else{
            echo "<td><button class='btn btn-danger' disabled>Request</button><br><span style='color: red; font-size: small;'>Can't request for $receiver_blood_group</span></td>";
        }
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