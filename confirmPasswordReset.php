<?php
// start session
session_start();

$_SESSION['SESS_TOKEN'] = $_GET['t'];
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>

    <body>
        <div class="container-fluid">
            <div class="row">

                <!-- start of coding the website -->
                <div class="col-sm-12 logoblock">
                    <h1>DropIT Sharing</h1>
                </div>

                <div class="col-sm-12 resetblockconfirm">
                    <div>
                        <form action="functions/doResetPassword.php" method="POST" autocomplete="off">
                            <h3>Reset Password Confirmation</h3>

                            <?php
                            // prompt message
                            include "displayAlertMessage.php";
                            ?>

                            <input name="inputPass" type="password" class="form-control" placeholder="Password" minlength="8" required>

                            <input name="inputConfirmPass" type="password" class="form-control" placeholder="Confirm Password" required>

                            <button class="btn btn-lg btn-block" name="reset" type="submit">Reset Password</button>
                        </form>
                    </div>

                    <div class="col-sm-12 centerblock">
                        <div class="row">
                            Remember account? <a href="index.php">Sign In!</a>
                        </div>
                    </div>
                </div>
                <!-- end of coding the website -->

            </div>
        </div>  
    </body>
</html>