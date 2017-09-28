<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>

    <body>
        <div class="container-fluid">
            <div class="row">

                <!-- start of coding the website -->
                <div class="col-sm-7 logoblock">
                    <h1>DropIT Sharing</h1>
                </div>

                <div class="col-sm-5 loginblock">
                    <div>
                        <form action="checkLogin.php" method="post">
                            <?php
                            //Display error message if the login failed
                            if (!empty($_SESSION['login_error_msg'])) {
                                echo '<font color="red"><b>"' . $_SESSION["login_error_msg"] . '</b></font>';
                                unset($_SESSION['login_error_msg']);
                            }
                            ?>

                            <input name="user" type="text" class="form-control" placeholder="Username" required autofocus>

                            <input name="password" type="password" class="form-control" placeholder="Password" required>

                            <button class="btn btn-lg btn-block" name="login" type="submit">Sign In</button>
                        </form>
                    </div>

                    <div class="col-sm-12 centerblock">
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="forgetPassword.php">Forgot your password?</a>
                            </div>

                            <div class="col-sm-6">
                                Don't have an account? <a href="registerAcc.php">Sign Up!</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end of coding the website -->

            </div>
        </div>  
    </body>
</html>