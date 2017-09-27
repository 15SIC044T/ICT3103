<!DOCTYPE html>
<html lang="en">
<?php include "header.php" ?>
<body>
 
<div class="container-fluid">
    
<?php include "navbar.php" ?>

    <!-- start of coding the website -->

    <div class="col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-7">
        <div class="opcBlock" style="background-color: #F7F7F7; padding: 50px; opacity: 0.9;">
            <form action="checklogin.php" method="post">
                <br><br>
                <h2 class='form-signin-heading'>Login</h2>
                
                         <?php 
                //Display error message if the login failed
                if(! empty($_SESSION['login_error_msg']))
                {
                        echo '<font color="red"><b>"' . $_SESSION["login_error_msg"] . '</b></font>';
                         
                    unset($_SESSION['login_error_msg']);
                }?>  

        
		
        <br><br>
            <label for="lblEmail" class="sr-only">Email address</label>
            <input name="user" type="text" class="form-control" placeholder="Username" required autofocus>
            <label for="lblPassword" class="sr-only">Password</label>
            <input name="password" type="password" class="form-control" placeholder="Password" required><br>
            <button class="btn btn-lg btn-success btn-block" name="login" type="submit">Login</button><br>
          </form>
            
            <hr>
            
            <form action="checklogin.php" method="post"> 
                <h2 class='form-signin-heading'>Register</h2>
                
                         <?php 
                //Display error message if the login failed
                if(! empty($_SESSION['login_error_msg']))
                {
                        echo '<font color="red"><b>"' . $_SESSION["login_error_msg"] . '</b></font>';
                         
                    unset($_SESSION['login_error_msg']);
                }?>  

        
		
        <br><br>
            <label for="lblEmail" class="sr-only">Email address</label>
            <input name="user" type="text" class="form-control" placeholder="Username" required autofocus>
            <label for="lblPassword" class="sr-only">Password</label>
            <input name="password" type="password" class="form-control" placeholder="Password" required><br>
            <button class="btn btn-lg btn-success btn-block" name="login" type="submit">Login</button><br>
          </form>

    </div><br></div>
    
     <!-- end of coding the website -->

</div>  
</body>
</html>