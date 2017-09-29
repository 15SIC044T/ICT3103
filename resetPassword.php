<?php include 'db-connection.php'; ?>

<div id="resetmodal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content resetmodal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
            <div class="modal-body">
                <div class="resetblock">
                    <h1>Password Recovery</h1>
                    <p>Please enter the email address you registered on your account.</p>
                </div>

                <!--forget Password-->
                <div class="resetblock">
                    <form data-toggle="validator" method="post" action="sendResetPasswordEmail.php" class="form-horizontal" role="form" >
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input name="email" type="text" class="form-control" placeholder="Email Address" required>

                                <button class="btn btn-lg btn-block" name="login" type="submit">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!--forget Password end-->
            </div>
        </div>
    </div>
</div>