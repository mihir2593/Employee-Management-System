<?php
session_start();
include ("./config.php");

class Demo {
    public $DBconn;
    public $sql;

    function __construct($conn) {
        $this->DBconn = $conn;
    }

    function updateData($request, $files) {

        $id = $request['update'];
        $emid = $request['emid'];
        $empid = $request['empid'];
        $depidArray = $request['depid'];
        // $depid = implode(',', $depidArray);
        $name = $request['name'];
        $salary = $request['salary'];
        $designation = $request['designation'];

    

        $existSql = "SELECT * FROM `emp` WHERE empid = '$empid' AND empid != '$emid'";
        $result = mysqli_query($this->DBconn, $existSql);
        $numExistRows = mysqli_num_rows($result);
        

        if ($numExistRows > 0) {
            $_SESSION['status'] =  "This Employee ID Already Exists. Please select a different Employee ID.";
            header("Location: edit_data.php?edit=$id"); 
            return;
        }

        $imgquery = "SELECT * FROM `emp` WHERE `id` = '$id'";
        $imgdata = mysqli_query($this->DBconn, $imgquery);
        $existingimg = mysqli_fetch_assoc($imgdata)['image'];
        

        if (isset($files['image']) && $files['image']['error'] == 0) {

            if ($existingimg) {
                unlink($existingimg); 
            }

            $img_loc = $files['image']['tmp_name'];     
            $img_name = basename($files['image']['name']);  
            $file_extension = pathinfo($img_name, PATHINFO_EXTENSION);
            $allowed_extention = ['jpg','png','jpeg','gif'];

            if(!in_array(strtolower($file_extension),$allowed_extention)){
                $_SESSION['status'] = "Invlid File extention ,file extention must be only jpg,png,jpeg and gif";
                header("Location:edit_data.php?edit=$id");
                return;
            }
            $unique_name = uniqid('img_') . '.' . $file_extension;

            $img_dest = "uploads/" . $unique_name;   
            
       
            if (move_uploaded_file($img_loc, $img_dest)) {
                echo "Image uploaded successfully.<br>";

                $updateimage = "UPDATE `emp` SET  `image` = '$img_dest', 
                                `original_image` = '$img_name'
                                WHERE `id` = '$id'";
                           
                $updateimagedata = mysqli_query($this->DBconn, $updateimage); 
                           
            } else {
                $_SESSION['status'] = "Failed to upload the image.<br>";
                header("Location: edit_data.php?edit=$id");     
                return;
            }
        }
        
            $updateemp = "UPDATE `details`.`emp`
                            SET `empid` = '$empid',
                            `name` = '$name',
                            `designation` = '$designation',
                            `salary` = '$salary'
                            WHERE `id` = '$id'";

            $result = mysqli_query($this->DBconn, $updateemp);
            

            if ($result) {
                    $deleteEmpDepQuery = "DELETE FROM `emp_dep` WHERE `emp_id` = '$id'";
                    mysqli_query($this->DBconn, $deleteEmpDepQuery);
        
                  
                    foreach ($depidArray as $dep_id) {
                        $empdepquery = "INSERT INTO `emp_dep`(`emp_id`, `dep_id`) 
                                        VALUES ('$id', '$dep_id')";
                        $empdepdata = mysqli_query($this->DBconn, $empdepquery);
                        
                        if (!$empdepdata) {
                            echo "Error inserting relationship data " . mysqli_error($this->DBconn);
                            header("Location: delete_data.php");
                            return;
                        }
                    }

                    $_SESSION['status'] = "Employee data updated successfully!";
                    header("Location: delete_data.php"); 
            } else {
                if ($img_dest != "") {
                    unlink($img_dest); 
                }
                $_SESSION['status'] = "Error updating data: " . mysqli_error($this->DBconn);
                header("Location: form.php");
                }
    }
}

$demo = new Demo($conn);
if (isset($_POST['update'])) {
    $demo->updateData($_POST, $_FILES);
}
?>
