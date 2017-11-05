<?php
// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 

    // include database connection details 
    require_once('dbConnection.php'); 
} 

// set active to navbar link
function echoActiveClassIfRequestMatches($requestUri) {
    $requestedURL = $_SERVER['PHP_SELF'];
    $current_file_name = basename($requestedURL, ".php");
    if ($current_file_name == $requestUri) {
        echo 'class="active"';
    }
}

// Check if user logged in 
if (! isset($_SESSION["SESS_ACC_ID"])) 
{
	// redirect to login page if the session variable shopperid is not set
	header ("Location: index.php");
	exit;
}

$content1 = "";
?>

<div class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- display content1 on the topBar -->
                <span style="margin-top:13px; margin-right:15px; float:right; z-index:10;">
<?php echo $content1; ?>
                </span> 

                <div class="navbar-header"> <!-- Mobile Display TopBar-->
                    <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".navbar-offcanvas" data-canvas="body" style="margin-top:13px;">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span> 
                    </button><!-- Menu Button -->
                    <a class="navbar-brand" href="fileManager.php"><div class="navbrand">DropIT Sharing</div></a>
                </div>

                <div class="navbar-offcanvas navmenu-fixed-left offcanvas">
                    <!--<form class="navmenu-brand" role="search" action="search.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="txtSearch">
                        </div>
                    </form>-->
                    <ul class="nav navbar-nav">
                        <li <?php echoActiveClassIfRequestMatches("fileManager"); ?>><a href="fileManager.php">File Manager</a></li>
                        <li <?php echoActiveClassIfRequestMatches("profile"); ?>><a href="profile.php">Account</a></li>

                        <li><a href="functions/doLogout.php">Sign Out</a></li>
                    </ul> 
                    
                    <div class="input-group"><input  style="margin-top: 10px; float: right;" type=button class="btn btn-danger" onClick="location.href='script/downloadDecrypter.php'" value='Download Decrypter'></div>
                    <!-- Display Search group on bigger screen, e.g. Desktop -->
                    <!--<form class="navbar-form navbar-right hidden-xs hidden-sm" role="search" action="search.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" name="txtSearch" placeholder="Search">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                            </div>
                        </div>
                    </form>   -->
                </div>
                <!-- nav-collapse -->

                <!-- Display only search button when screen size is reduced -->
                <!--<form class="navbar-form navbar-left hidden-lg hidden-xs hidden-md" role="search" action="search.php" method="post">
                    <a class='btn btn-default' href='search.php' role='button'><span class="glyphicon glyphicon-search"></span></a>
                </form>-->
            </div>
        </div>
    </div>
</div>