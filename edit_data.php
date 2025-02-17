<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Employee Management System</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="">EMS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="form.php">Add Emp </a>
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
    <h1 class="mt-3" style="text-align:center; color:green;">Employee Detail Form</h1>


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

    include("./config.php");

    class Demo
    {
        public $DBconn;
        public $sql;
        function __construct($conn)
        {
            $this->DBconn = $conn;
        }

        function selectData($request)
        {
            $id = $request['edit'];
            $selectemp = $this->DBconn->sql = "SELECT * FROM `details`.`emp`
                                            WHERE `id` = $id ";
            $result = mysqli_query($this->DBconn, $selectemp);

            foreach ($result as $empdata) {
                $empid = $empdata['empid'];
                $name = $empdata['name'];
                $designation = $empdata['designation'];
                $salary = $empdata['salary'];
                $image = $empdata['image'];
                $original_existing_image = $empdata['original_image'];
            }
        ?>
            <div class="d-flex justify-content-center align-items-center">
                <div class="card mt-2" style="width: 30%;">

                    <form action="./update_data.php" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                        <input type="text" value="<?php echo $empid ?>" name="emid" hidden>

                        <div class="col-10  mb-3 ">
                            Enter Employee ID : <input class="form-control" id="empid" type="text" value="<?php echo $empid ?>" name="empid">
                            <span id="empidmessage" style="color: red;"></span>

                        </div>

                        <div class="col-10 mb-3">
                            <label for="depid">Select Your Department :</label>
                            <select id="depid" class="form-control" size="3" name="depid[]" multiple>
                                <?php
                                include("./config.php");
                                $selectdepquery = "SELECT `dep_id` FROM `emp_dep` WHERE `emp_id` = $id";
                                $selectdepdata = $conn->query($selectdepquery);
                                $selected_deps = [];
                                if ($selectdepdata->num_rows > 0) {
                                    while ($row = $selectdepdata->fetch_assoc()) {
                                        $selected_deps[] = $row['dep_id'];
                                    }
                                }
                                $sql = "SELECT * FROM department";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = in_array($row['id'], $selected_deps) ? 'selected' : '';
                                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['department_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No Departments Available</option>';
                                }
                                ?>
                            </select>
                            <span id="depnamemessage" style="color: red;"></span>
                        </div>

                        <div class="col-10  mb-3 ">
                            Enter your Name : <input class="form-control" id="name" type="text" value="<?php echo $name ?>" name="name">
                            <span id="namemessage" style="color: red;"></span>
                        </div>

                        <div class="col-10  mb-3 ">
                            Enter your Designation : <input class="form-control" id="designation" type="text" value="<?php echo $designation ?>" name="designation">
                            <span id="designationmessage" style="color: red;"></span>
                        </div>

                        <div class="col-10  mb-3 ">
                            Enter your Salary : <input class="form-control" id="salary" type="number" value="<?php echo $salary ?>" name="salary">
                            <span id="salarymessage" style="color: red;"></span>
                        </div>

                        <div class="col-10  mb-3 ">
                            Upload Your Image :
                            <div class="custom-file">
                                <input class="custom-file-input" id="image" type="file" value="<?php echo $image ?>" name="image">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <span id="imagemessage" style="color: red;"></span>
                        </div>

                        <div class="col-10  mb-3 ">
                            <button class="btn btn-primary" type="submit" value="<?php echo $id ?>" name="update">Update data </button>
                        </div>
                    </form>
                </div>
            </div>

    <?php
        }
    }

    $demo = new Demo($conn);
    if (isset($_GET['edit'])) {
        $demo->selectData($_GET);
    }
    ?>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#depid').select2({
                placeholder: "Select Department",
                allowClear: true
            });
        });


        function validateForm() {
            var isValid = true;
            var empid = document.getElementById("empid").value;
            var depid = document.getElementById("depid").value;
            var name = document.getElementById("name").value;
            var designation = document.getElementById("designation").value;
            var salary = document.getElementById("salary").value;

            document.getElementById("empidmessage").innerHTML = '';
            document.getElementById("depnamemessage").innerHTML = '';
            document.getElementById("namemessage").innerHTML = '';
            document.getElementById("designationmessage").innerHTML = '';
            document.getElementById("salarymessage").innerHTML = '';

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