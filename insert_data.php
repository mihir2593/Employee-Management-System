    <?php
    session_start();
        include("./config.php");

    class Demo {
        public $DBconn;
        function __construct($conn){
            $this->DBconn = $conn;
        }

        function insertdata($request, $files) {  
            $empid = $request['empid'];
            $depidArray = $request['depid'];
            // $depid = implode(',', $depidArray);
            $name = $request['name'];
            $salary = $request['salary'];
            $designation = $request['designation'];

            $_SESSION['form'] = $request;

            $existSql = "SELECT * FROM `emp` WHERE empid = '$empid'";
            $result = mysqli_query($this->DBconn, $existSql);
            $numExistRows = mysqli_num_rows($result);

            if ($numExistRows > 0) {
                $_SESSION['status'] =  "This Employee ID already exists. Please select a different Employee ID.";                header("Location: form.php");
                $_SESSION['form'];
                return;
            }
            
            if (isset($files['image']) && $files['image']['error'] == 0) {
                $img_loc = $files['image']['tmp_name'];     
                $img_name = basename($files['image']['name']);  
                $file_extension = pathinfo($img_name, PATHINFO_EXTENSION);
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                    $_SESSION['status'] = "Invlid File extention ,file extention must be only jpg , png , jpeg and gif";
                    header("Location: form.php");
                    return;
                }
                
                $unique_name = uniqid('img_') . '.' . $file_extension;

                $img_dest = "uploads/" . $unique_name;    
                

                if (move_uploaded_file($img_loc, $img_dest)) {
                    echo "Image uploaded successfully.<br>";
                } else {
                    $_SESSION['status'] = "Failed to upload the image.<br>";
                    header("Location: form.php");

                    return;
                }
            } else {
                $_SESSION['status'] = "No image uploaded or error with the file.<br>";
                header("Location: form.php");
                return;
            }
            $original_image = $img_name;


                $insertQuery = "INSERT INTO `emp`(`empid`, `name`, `designation`, `salary`, `image`,`original_image`,`created_at`) 
                                VALUES ('$empid','$name', '$designation', '$salary', '$img_dest','$original_image',current_timestamp() )";
                $result = mysqli_query($this->DBconn, $insertQuery);

                if ($result) {
                    $lastInsertedId = mysqli_insert_id($this->DBconn);
                    echo "The ID of the inserted record is: " . $lastInsertedId;
                    foreach($depidArray as  $dep_id)  {
                    
                        
                        $empdepquery = "INSERT INTO `emp_dep`(`emp_id`,`dep_id`)
                                        VALUES ('$lastInsertedId', '$dep_id')"; 
                        $empdepdata = mysqli_query($this->DBconn, $empdepquery);
                        echo $empdepdata;
                        if(!$empdepdata){
                            $dltempquery = "DELETE FROM `emp` WHERE `empid` = '$empid'";
                            $dltempdata = mysqli_query($this->DBconn, $dltempquery);
                            unlink($img_dest);
                            echo "Error inserting in  relationship data " . mysqli_error($this->DBconn);
                            header("Location:form.php");
                            return;
                        }
                    }    
                    unset($_SESSION['form']);  
                    $_SESSION['status'] = "Data inserted successfully!";
                    header("Location: delete_data.php");
                }else{
                    if($img_dest != ""){
                        unlink($img_dest);
                    }
                    $_SESSION['status'] = "Error inserting data: " . mysqli_error($this->DBconn);
                    header("Location: form.php");
                }
                    
                }
            }
    $demo = new Demo($conn);

    if (isset($_POST['empid'])) {
        $demo->insertdata($_POST, $_FILES);
    }

    ?>
