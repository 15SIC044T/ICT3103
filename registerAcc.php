<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <?php include "header.php"; ?>

    <body>
        <div class="container-fluid">
            <div class="row">

                <!-- start of coding the website -->
                <div class="col-sm-12 logoblock">
                    <h1>DropIT Sharing</h1>
                </div>

                <div class="col-sm-12 registerblock">
                    <div>
                        <form action="functions/doRegister.php" method="POST" autocomplete="off">
                            <?php
                            // prompt message
                            include "displayAlertMessage.php";
                            ?>

                            <input name="inputName" type="text" class="form-control" placeholder="Name" required autofocus>

                            <input name="inputPass" type="password" class="form-control" placeholder="Password" minlength="8" required>

                            <input name="inputConfirmPass" type="password" class="form-control" placeholder="Confirm Password" required>

                            <input name="inputEmail" type="email" class="form-control" placeholder="Email Address" required>

                            <input name="inputMobile" type="number" class="form-control" placeholder="Mobile Number" required>

                            <button class="btn btn-lg btn-block" name="register" type="submit">Sign Up</button>
                        </form>
                    </div>

                    <div class="col-sm-12 centerblock">
                        <div class="row">
                            Have an account? <a href="index.php">Sign In!</a>
                        </div>
                    </div>
                </div>
                <!-- end of coding the website -->

            </div>
        </div>  
    </body>
</html>