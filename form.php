<?php
session_start();
include("./config.php");
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title>Employee Management System</title>
  <script src="script.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="">EMS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="">Add Emp</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="delete_data.php">Employees</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">Contact</a>
        </li>
      </ul>
    </div>
  </nav>

  <h1 class="mt-2" style="text-align:center; color:green;">Employee Detail Form</h1>

  <?php
  if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
  ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong></strong><?php echo  $_SESSION['status']; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php
    unset($_SESSION['status']);
  }
  ?>

  <div class="d-flex justify-content-center align-items-center">
    <div class="card mt-2" style="width: 30%; ">
      <form action="./insert_data.php" name="myform" id="empForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
        <div class="col-12  mb-3">
          Enter Employee ID : <input type="text" class="form-control" name="empid" id="empid" value="<?php echo isset($_SESSION['form']['empid']) ? $_SESSION['form']['empid'] : ''; ?>">
          <span id="empidmessage" style="color: red;"></span>
        </div>

        <div class="col-12  mb-3">
          <label for="depid" class="form-label">Select Your Department : </label>
          <select id="depid" class="form-control" name="depid[]" multiple="multiple">
            <?php
            $sql = "SELECT * FROM department";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
              $selected = isset($_SESSION['form']['depid']) && $_SESSION['form']['depid'] == $row['depid'] ? 'selected' : '';
              echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['department_name'] . '</option>';
            }
            ?>
          </select>
          <span id="depnamemessage" style="color: red;"></span>
        </div>

        <div class="col-12 mb-3 ">
          Enter Your Name : <input class="form-control " type="text" name="name" id="name" value="<?php echo isset($_SESSION['form']['name']) ? $_SESSION['form']['name'] : ''; ?>">
          <span id="namemessage" style="color: red;"></span>
        </div>

        <div class="col-12  mb-3">
          Enter Your Designation : <input type="text" class="form-control" name="designation" id="designation" value="<?php echo isset($_SESSION['form']['designation']) ? $_SESSION['form']['designation'] : ''; ?>">
          <span id="designationmessage" style="color: red;"></span>
        </div>

        <div class="col-12  mb-3">
          Enter Your Salary : <input type="number" name="salary" class="form-control" id="salary" value="<?php echo isset($_SESSION['form']['salary']) ? $_SESSION['form']['salary'] : ''; ?>">
          <span id="salarymessage" style="color: red;"></span>
        </div>

        <div class="col-12 mb-3">
          <label for="image" class="form-label">Upload Your Image :</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="image" name="image">
            <label class="custom-file-label" for="image">Choose file</label>
          </div>
          <span id="imagemessage" style="color: red;"></span>
        </div>

        <div class="col-12  mb-3">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>

      <div class="col-3  mb-3">
        <button class="btn btn-danger" id="myButton">Cancel</button>
      </div>
    </div>
  </div>
  <br>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#depid').select2({
        placeholder: "Select Department",
        allowClear: true
      });
    });

    document.getElementById("myButton").onclick = function() {
      <?php unset($_SESSION['form']); ?>
      window.location.href = "delete_data.php";
    };

    function validateForm() {
      var isValid = true;
      var empid = document.getElementById("empid").value;
      var depid = document.getElementById("depid").value;
      var name = document.getElementById("name").value;
      var designation = document.getElementById("designation").value;
      var salary = document.getElementById("salary").value;
      var image = document.getElementById("image").value;

      document.getElementById("empidmessage").innerHTML = '';
      document.getElementById("depnamemessage").innerHTML = '';
      document.getElementById("namemessage").innerHTML = '';
      document.getElementById("designationmessage").innerHTML = '';
      document.getElementById("salarymessage").innerHTML = '';
      document.getElementById("imagemessage").innerHTML = '';

      if (empid == "") {
        document.getElementById("empidmessage").innerHTML = "Employee ID is required.";
        isValid = false;
      }

      if (depid == "") {
        document.getElementById("depnamemessage").innerHTML = "Department is required.";
        isValid = false;
      }

      if (name == "") {
        document.getElementById("namemessage").innerHTML = "Name is required.";
        isValid = false;
      }

      if (designation == "") {
        document.getElementById("designationmessage").innerHTML = "Designation is required.";
        isValid = false;
      }

      if (salary == "") {
        document.getElementById("salarymessage").innerHTML = "Salary is required.";
        isValid = false;
      }

      if (image == "") {
        document.getElementById("imagemessage").innerHTML = "Image is required.";
        isValid = false;
      }

      if (isValid) {
        return true;
      } else {
        return false;
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>