
    <?php   
    session_start();
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Employee Management System</title>

    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="">EMS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"> </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="form.php">Add Emp</a>
            </li>
            <li class="nav-item active">
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
        
        <?php  
        include ("./config.php");
        if(isset($_SESSION['status']) && $_SESSION != '')
        {
        ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong></strong><?php  echo  $_SESSION['status']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        unset($_SESSION['status']);
    }

        class Demo{
            public $DBconn;
            public $sql;
            function __construct($conn){
                $this->DBconn = $conn;
            }

            function getData(){
                $getEmp = "
                SELECT e.id, e.empid, e.name, e.designation, e.salary, e.image, GROUP_CONCAT(d.department_name) AS department_names
                FROM `emp` e
                LEFT JOIN `emp_dep` ed ON e.id = ed.emp_id
                LEFT JOIN `department` d ON ed.dep_id = d.id
                GROUP BY e.id
            ";
                $result = mysqli_query($this->DBconn, $getEmp);
                ?>
                
                <br>
                <form action='./form.php'>
                    <button class="btn btn-primary">Add New Data</button>
                </form>
                <br>
               <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered ">
                    <thead>
                        <tr>
                            <th> Id</th>
                            <th> Emp_ID</th>
                            <th> Department Name</th>
                            <th> Name</th>
                            <th> Designation</th>
                            <th> Salary</th>
                            <th> Image</th>
                            <th> Delete</th>
                            <th> Edit</th>
                        </tr>
                    </thead>               
                    
                <?php   
                foreach($result as $row){
                ?>    
                    <tr>
                    <td> <?php echo  $row['id'] ?></td>
                    <td> <?php echo  $row['empid'] ?></td>
                    <td> <?php echo  $row['department_names'] ?></td>
                    <td> <?php echo  $row['name'] ?></td>
                    <td> <?php echo  $row['designation'] ?></td>
                    <td> <?php echo  $row['salary'] ?></td>
                    <td> <img src = '<?php echo $row['image'] ?>' width='100' height='100'></td>

                    <td><form method='get' onsubmit='return confirmDelete()'>
                    <input type='hidden' name='del_image' value = <?php echo $row['image'] ?>>
                    <button class="btn btn-danger" name='delete' value= <?php echo $row['id'] ?>>Delete</button>
                    </form></td>
                    
                    <td><form action='./edit_data.php' method='get'>
                    <button class="btn btn-primary" name='edit' value= <?php echo $row['id'] ?>>Edit</button></form></td>
                    </tr>


            <?php
                }
            ?>
                
                </table>
                </div>
                <br><br><br>
                <?php
           
            }


            function deleteData($request){
                $id = $request['delete'];
                $del_image = $request['del_image'];

                $delempdep = "DELETE FROM `emp_dep` WHERE `emp_id` = '$id'";
                $delempdepdata = mysqli_query($this->DBconn, $delempdep);

                if(!$delempdepdata){
                    $_SESSION['status'] = "Data not deleted in relationshp table :". mysqli_error($this->DBconn);
                    header("Location:delete_data.php");
                    return;
                }

                $deleteEmp = $this->DBconn->sql = "DELETE FROM `details`.`emp` WHERE `id` = $id ";
                                                
                $result = mysqli_query($this->DBconn, $deleteEmp);

                if($result){
                    header("Location: " . $_SERVER['PHP_SELF']);
                    unlink($del_image);
                    $_SESSION['status'] = "Record deleted successfully"; 
                }else{
                    $_SESSION['status'] = "Record not Deleted <br> Error: ".mysqli_error($this->DBconn);
                }
            }

        }

        $demo = new Demo($conn);

        if(isset($_GET['delete'])) {
            $deleteSuccess = $demo->deleteData($_GET);
        
            if($deleteSuccess) {
                header("Location: " . $_SERVER['PHP_SELF']);
            } 
        }
        
        $demo->getData();
        ?>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this item?");
        }
    </script>
    <script>
        
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
    </html>