<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $email = $mobile =$dob =$pincode = "";
$name_err = $email_err = $mobile_err =$dob_err = $pincode_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["Id"]) && !empty($_POST["Id"])){
    // Get hidden input value
    $Id = $_POST["Id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
       // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email.";     
    } else{
        $email = $input_email;
    }
    
    // Validate mobile
    $input_mobile = trim($_POST["mobile"]);
    if(empty($input_mobile)){
        $mobile_err = "Please enter the Mobile NO.";     
    } elseif(!ctype_digit($input_mobile)){
        $mobile_err = "Please enter a positive integer value.";
    } else{
        $mobile = $input_mobile;
    }
    // Validate dob
    $input_dob = trim($_POST["dob"]);
    if(empty($input_dob)){
        $dob_err = "Please enter an Date of Birth.";     
    } else{
        $dob = $input_dob;
    }
    
    // Validate pincode
    $input_pincode = trim($_POST["pincode"]);
    if(empty($input_pincode)){
        $pincode_err = "Please enter the PinCode.";     
    } else{
        $pincode = $input_pincode;
   
    
 // Check input errors before inserting in database
   if(empty($name_err) && empty($email_err) && empty($mobile_err)&& empty($dob_err) && empty($pincode_err)){
          // Prepare an update statement
        $sql = "UPDATE DATA SET name=?, email=?, mobile=?,dob=?, pincode=? WHERE Id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_email, $param_mobile, $param_dob, $param_pincode, $param_Id);
            
            // Set parameters
              $param_name = $name;
            $param_email = $email;
            $param_mobile = $mobile;
            $param_dob = $dob;
            $param_pincode = $pincode;
          $param_Id = $Id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: form.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} 
    // Check existence of id parameter before processing further
    if(isset($_GET["Id"]) && !empty(trim($_GET["Id"]))){
        // Get URL parameter
        $id =  trim($_GET["Id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM data WHERE Id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_Id);
            
            // Set parameters
            $param_Id = $Id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $email = $row["email"];                                                                        
                    $mobile = $row["mobile"];
					$dob = $row["dob"];
                    $pincode = $row["pincode"];
                }
                 else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C/DTD HTML 4.01//EN" "http://W3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email ID</label>
                            <input type="mail" name="email" class="form-control" value"<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($mobile_err)) ? 'has-error' : ''; ?>">
                            <label>Mobile NO</label>
                            <input type="number" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                            <span class="help-block"><?php echo $mobile_err;?></span>
                        </div>
						<label>Date Of Birth</label>
                            <input type="date" name="dob" class="form-control" value"<?php echo $dob; ?>">
                            <span class="help-block"><?php echo $dob_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                            <label>PinCode</label>
                            <input type="text" name="pincode" class="form-control" value="<?php echo $pincode; ?>">
                            <span class="help-block"><?php echo $pincode_err;?></span>
                        </div>
                        <input type="hidden" name="Id" value="<?php echo $Id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="update">
                        <a href="form.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
