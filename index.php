<?php include "checkSession.php"; ?>

<!DOCTYPE html>
<html lang="en">
    <?php
    include "header.php";
    include "resetPasswordModal.php";
    include "displayKeyModal.php";
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            <?php 
            if (isset($_SESSION['REGISTER_OK']) && ($_SESSION['REGISTER_OK'] == "success")) { ?>
                $('#ModalDisplayKey').modal(
                {
                    backdrop: 'static',
                    keyboard: false
                });

                <?php
                session_destroy();
                unset($_SESSION['registerOK']);
            }
            ?>
        });
    </script>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-7 logoblock">
                    <h1>DropIT Sharing</h1>
                </div>

                <div class="col-sm-5 loginblock">
                    <div>
                        <form action="functions/doLogin.php" method="POST" autocomplete="off">
                            <?php
                            // prompt message
                            include "displayAlertMessage.php";
                            ?>

                            <input name="inputEmail" type="text" class="form-control" placeholder="Email Address" required>

                            <input name="inputPass" type="password" class="form-control" placeholder="Password" required>

                            <button class="btn btn-lg btn-block" name="login" type="submit">Sign In</button>
                        </form>
                    </div>

                    <div class="col-sm-12 centerblock">
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="#" data-target="#resetmodal" data-toggle="modal">Forgot Password?</a>
                            </div>

                            <div class="col-sm-6">
                                Don't have an account? <a href="registerAcc.php">Sign Up!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </body>
</html>