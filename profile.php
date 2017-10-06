<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>

    <body>
        <?php include "navbar.php" ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo "<h1>" . $_SESSION['SESS_USERNAME'] . "'s Profile</h1>" ?>

                    <?php
                    // prompt message
                    include "displayAlertMessage.php";

                    $connection = new Mysql_Driver();
                    $connection->connect();

                    $queryUser = "SELECT * 
                                FROM account 
                                WHERE accountID =" . $_SESSION['SESS_ACC_ID'];
                    $resultUser = $connection->query($queryUser);
                    $user = $connection->fetch_array($resultUser);
                    ?>

                    <div class="row">
                        <div class="col-sm-6">
                            <form action="functions/doUpdateProfile.php" method="POST">
                                <h4>My Profile</h4>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input name="inputName" type="text" class="form-control" placeholder="Full Name" value="<?php echo $user['name'] ?>">

                                    <label for="">Email address</label>
                                    <input name="inputEmail" type="text" class="form-control" placeholder="Email Address" value="<?php echo $user['email'] ?>">

                                    <label for="">Mobile number</label>
                                    <input name="inputMobile" type="number" class="form-control" placeholder="Mobile Number" minlength="8" value="<?php echo $user['phone'] ?>">
                                </div>
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                            </form>
                        </div>

                        <div class="col-sm-6">
                            <form action="functions/doChangePassword.php" method="POST" >
                                <h4>Change Password</h4>
                                <div class="form-group">
                                    <label for="">Old password</label>
                                    <input name="inputOld" type="password" class="form-control" placeholder="Old password">

                                    <label for="">New password</label>
                                    <input name="inputNew" type="password" class="form-control" placeholder="New password" minlength="8">

                                    <label for="">Confirm password</label>
                                    <input name="inputConfirm" type="password" class="form-control" placeholder="Confirm password">
                                </div>
                                <button type="submit" name="change" class="btn btn-primary">Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>