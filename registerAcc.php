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

                <div class="col-sm-12 registerblock">
                    <div>
                        <form action="checklogin.php" method="post">
                            <?php
                            //Display error message if the login failed
                            if (!empty($_SESSION['login_error_msg'])) {
                                echo '<font color="red"><b>"' . $_SESSION["login_error_msg"] . '</b></font>';
                                unset($_SESSION['login_error_msg']);
                            }
                            ?>

                            <input name="user" type="text" class="form-control" placeholder="Username" required autofocus>

                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                            
                            <input name="email" type="text" class="form-control" placeholder="Email" required>
                            
                            <input name="mobileNumber" type="text" class="form-control" placeholder="Mobile Number" required>

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