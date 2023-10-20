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

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid"><img src="img.jpg" alt="" style="width:20px">
            <a class="navbar-brand" href="#">
                Blood Bank </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active " href="https://www.linkedin.com/in/tanya-mishra-b74410239/">Contact
                            Developer</a>
                    </li>
                    <li class="nav-item">
                        <a href="#addblood" class="nav-link active ">Add blood sample +</a>
                    </li>
                    
                </ul>
                <form method="get" action=''>
                        <button type="submit" name="logout" class="btn btn-danger px-2 py-1">
                            Logout
                        </button>
                    </form>
            </div>
        </div>
    </nav>
    </nav>


  <?php 
  require("config.php");
  $user_id = $_SESSION["user_id"];
  $sql = "SELECT hospital_id FROM hospital WHERE user_id= $user_id;";
  $result = $conn->query($sql);
  $data = $result->fetch_assoc();
  $hospital_id = $data['hospital_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $blood_type = $_POST['blood_type'];
  $expiration_date = $_POST['expiration_date'];
      $stmt = $conn->prepare("INSERT INTO bloodinfo (hospital_id, blood_type, expiration_date) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $hospital_id, $blood_type, $expiration_date);
      if ($stmt->execute()) {
          echo "Record inserted successfully";
      }
      $stmt->close();
  
  }

?>

<div class="row">
    <div class="col-7">

    <div class="container ">
        <div class="text-center m-2 mb-4">
            <h1>View Requests</h1>
        </div>
       
    </div>
    <div class="container mt-4">
<?php
$sql =  "SELECT 
r.full_name,r.phone_number,b.blood_type,b.expiration_date,rq.request_date_time
FROM request rq
JOIN receiver r ON rq.receiver_id = r.receiver_id
JOIN bloodinfo b ON rq.blood_info_id = b.blood_info_id
JOIN hospital h ON b.hospital_id = h.hospital_id
WHERE h.hospital_id = $hospital_id ORDER BY request_id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-hover text-center'>";
    echo "<thead><tr><th>S.No</th>
    <th>Receiver</th>
    <th>Blood Type</th>
    <th>Expiration Date</th>
    <th>Contact</th>
    <th>Request Date Time</th></tr></thead>";
    $i=1;
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" .$i. "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row["blood_type"] . "</td>";
        echo "<td>" . $row["expiration_date"] . "</td>";
        echo "<td>" . $row["phone_number"] . "</td>";
        echo "<td>" . $row["request_date_time"] . "</td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";
} else {
    echo "No blood request available.";
}
 $conn->close();
?>

    </div></div>
    <div class="col-5">

    <div class="conatiner mt-3" id="addblood">
    <h1 class="text-center mt-3">Add Blood Info</h1>

        <div class="row  ">

            <div class="col-12 mx-auto mt-2 card shadow-lg">

                <form method="POST" action="">
                    <div class="form-group my-2">
                        <label for="blood_type">Blood Type:</label>
                        <select class="form-control" id="blood_type" name="blood_type" required>
                            <option value="" disabled selected>Select Blood Type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="form-group my-2">
                        <label for="expiration_date">Expiration date:</label>
                        <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mb-4 ">Add Blood </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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