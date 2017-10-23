<?php
//  Initialize the connection file.  
    require_once('/conn.php');  
?>
    

<!-- S E L E C T -->
<!-- using prepare statement to SELECT records and print dynamically  
<!-----start-team---->
    <div class="team" id="trainers">
        <div class="container">
            <div class="team-head text-center">
                <h3>SUPERB TRAINERS</h3>
                <span> </span>
            </div>
            
            <div class="team-grids">
                <?php
                $stmt = $conn->prepare("SELECT * FROM user WHERE accountTypeID IN (SELECT accountTypeID FROM accounttype WHERE accountType = 'trainer') ORDER BY RAND() LIMIT 4");
                $stmt->execute();
                $result = $stmt->get_result();
                $count = 1;
                
                while ($row = $result->fetch_assoc()) {
                ?> 
                 
                <div class="col-md-3 team-grid">
                    <a data-toggle="modal" data-target=".bs-example-modal-md" href="#" class="b-link-stripe b-animate-go  thickbox">
                        <img class="p-img" src="mainHomePage/images/t<?php echo $count; ?>.jpg" /><div class="b-wrapper">
                            <h2 class="b-animate b-from-left    b-delay03 ">
                                <div class="animate-head">
                                    <div class="animate-head-left">
                                        <h3><?php echo $row['name']; ?></h3>
                                        <span><?php echo $row['description']; ?></span>
                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </h2>
                        </div></a>
                    <div class="t-member-info">
                        <h5><?php echo $row['name']; ?></h5>
                        <span>trainer</span>
                    </div>
                </div>
                
                <?php 
                    $count += 1;
                }
                $stmt->close();
                ?>  
            </div>
        </div>
    </div>

-->



<!-- U P D A T E -->
<!-- Update Statement -->
<?php
 
require_once('../conn.php');
session_start();
//
//Update Profile
if (isset($_POST['updateProfile'])) { 
     
    $userID = $_SESSION['userID'];
    $name = $_POST["txtName"];
    $gender = $_POST["ddlGender"];
    $phone = $_POST["txtPhone"]; 
    $dob = $_POST['txtDOB']; 
    
    $subscriptionPlan = $_POST["ddlSubscriptionPlan"];
    $subscriptionDate = $_POST["txtSubscriptionDate"];
    $medicalHistory = $_POST["txtMedicalHistory"];
     
    if ($dob == "")
        $formatDOB = "";
    else
        $formatDOB = date("Y-m-d", strtotime($dob));   
         
    if ($subscriptionDate == "")
        $formatSubscriptionDate = "";
    else
        $formatSubscriptionDate = date("Y-m-d", strtotime($subscriptionDate));  
    
    $stmt = $conn->prepare("UPDATE user SET name=?, gender=?, phoneNumber=?, dateOfBirth=?, subscriptionID=?, subscriptionDate=?, medicalHistory=? WHERE userID = ?");
    $stmt->bind_param('ssisissi',$name,$gender, $phone, $formatDOB, $subscriptionPlan, $formatSubscriptionDate, $medicalHistory, $userID);
    $stmt->execute(); 
    
     $_SESSION['success_msg'] = "Profile has been updated successfully!"; 
      
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

//Change Password
if (isset($_POST['changePassword'])) {
    
    $userID = $_SESSION['userID'];
    $oldPassword = $_POST["oldPassword"];
    $password = $_POST["newPassword"];
    $password2 = $_POST["retypePassword"];
 
    $qry = "SELECT password FROM user WHERE userID = $userID";
    $result = mysqli_query($conn, $qry);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        if (strcmp($password, $password2) == 0) {   //password and retype password matches
            if (strcmp($row["password"], SHA1($oldPassword)) == 0) {
                $stmt = $conn->prepare("UPDATE user SET password=? WHERE userID = ?");
                $stmt->bind_param('si',SHA1($password),$userID);
                $stmt->execute(); 

                $_SESSION['success_msg'] = "Password has been changed successfully!";
                echo "okay";
            } else {
                $_SESSION['error_msg'] = "Password is invalid!";
                echo "invalid";
            }
        } else {
            $_SESSION['error_msg'] = "Password is not matched!";
            echo "nosame";
        }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>



<!-- I N S E R T -->
<?php 


try {
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, dateOfBirth, gender, medicalHistory, phoneNumber, cancelLimit, accountTypeID,accountStatus,subscriptionID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiiss", $name, $email, $passwordSHA, $dob, $gender, $description, $phoneNumber, $cancelLimit, $accountType, $accountStatus, $plan);

    // set parameters and execute
    $name = trim($_POST['usernamesignup']);
    $email = trim($_POST['emailsignup']);
    //https://www.sitepoint.com/hashing-passwords-php-5-5-password-hashing-api/
    //$password = password_hash(trim($_POST['passwordsignup']), PASSWORD_DEFAULT);
    $password = trim($_POST['pass1']);
    $repassword = trim($_POST['pass2']);
    $passwordSHA = SHA1($password);

    $description = trim($_POST['description_Signup']);
    $phoneNumber = trim($_POST['phoneNumber_signup']);

    $dob = $_POST['date'];
    $gender = $_POST['genderSignup'];
    $plan = $_POST['subscriptionPlan_signup'];
    //$code = md5(uniqid(rand()));   

    $cancelLimit = 2;
    if (isset($_POST['role_signup'])) {
        //$accountType = $_POST['role_signup'];
    } else {
        $accountType = 3;
    }
    if ($plan == 1) {
        $accountStatus = "active";//accountStatus
    } else {
        $accountStatus = "inactive"; //accountStatus    
    }


    //after confirming then it becomes active
    $sql = "Select * from user where email='$email'";
    $result = $conn->query($sql);
    $num_rows = $result->fetch_assoc();

    if ($password != $repassword) {
        $_SESSION['err'] = 'passnotsame';
        echo "<script>location='login.php#toregister'</script>";
        exit();
    } elseif ($num_rows) { //email exist
        $_SESSION['err'] = 'registerunsuccessful';
        echo "<script>location='login.php#toregister'</script>";
        exit();
    } else {
        $stmt->execute();
        //send email to confirm registration
        email_content($code, $email, $name);
        $_SESSION['success'] = 'register';
        echo "<script>location='login.php#tologin'</script>";
        exit();
    }
    $conn->close();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
//return to login page
?>




?>