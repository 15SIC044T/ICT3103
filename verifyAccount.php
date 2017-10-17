<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <?php include "header.php"; ?>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 logoblock">
                    <h1>DropIT Sharing</h1>
                </div>

                <div class="col-sm-12 registerblock">
                    <div>
                        <form action="functions/doVerifyAccount.php" method="POST">
                            <?php
                            // prompt message
                            include "displayAlertMessage.php";
                            $userId = $_SESSION['SESS_ACC_ID'];
                            ?>

                            <h4>Please check your email for verification code</h4>
                            <input name="inputToken" type="text" class="form-control" placeholder="Enter your verification code" required>

                            <input name="userID" type="hidden" class="form-control" value="<?php echo $userId; ?>">

                            <button class="btn btn-lg btn-block" name="verify" type="submit">Verify Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </body>
</html>