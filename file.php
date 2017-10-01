<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>  
    <body>
        <?php include "navbar.php" ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo "<h1>" . $_SESSION['SESS_USERNAME'] . "'s File</h1>" ?>
                    <br>
                    <div class="col-sm-7">
                        <h2>FILE NAME</h2>
                        
                        PREVIEW HERE
                        <br>
                    </div>
                    <div class="col-sm-5">
                        <h2>File Information</h2>
                        File Information Here
                        
                        
                        <br><br><br>
                        <h2>Share File</h2>
                        URL HERE
                        <br>
                    </div>


                </div>
            </div>
        </div>
    </div>  
</body>
</html>