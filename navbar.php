<?php
// start session
session_start();

// include database connection details
include 'db-connection.php';

// set active to navbar link
function echoActiveClassIfRequestMatches($requestUri) {
    $requestedURL = $_SERVER['PHP_SELF'];
    $current_file_name = basename($requestedURL, ".php");
    if ($current_file_name == $requestUri) {
        echo 'class="active"';
    }
}

$content1 = "";
if (!isset($_SESSION['SESS_ACC_ID'])) {
    //Display Login and Register button when shopper has yet to login
    /* $content1 = "<a class='btn btn-default' href='index.php#login' role='button'>Login</a> &nbsp;
      <a class='btn btn-default' href='index.php#register' role='button'>Register</a>"; */
} else {
    //Display a user button with dropdown list containing My Profile, My Feedback, Logout
    //Display shopping cart button
    /* $content1 = "<div class='btn-group'>
      <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
      $_SESSION[accountName] <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' role='menu'>
      <li><a href='profile.php'>My Account</a></li>
      <li class='divider'></li>
      <li><a href='logout.php'>Logout</a></li>
      </ul>
      </div>";

      //Display number of item in the cart in the button
      if (isset($_SESSION["notification"])) {
      $content1 .= "&nbsp;&nbsp;&nbsp;<a class='btn btn-default' href='shoppingcart.php' role='button'>Notification <span class='badge'>$_SESSION[notification]</span></a>";
      } else {
      $content1 .= "&nbsp;&nbsp;&nbsp;<a class='btn btn-default' href='shoppingcart.php' role='button'>Notification</a>";
      } */
}
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
                    <form class="navmenu-brand" role="search" action="search.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search" name="txtSearch">
                        </div>
                    </form>
                    <ul class="nav navbar-nav">
                        <li <?php echoActiveClassIfRequestMatches("fileManager"); ?>><a href="fileManager.php">File Manager</a></li>
                        <li <?php echoActiveClassIfRequestMatches("profile"); ?>><a href="profile.php">Account</a></li>

                        <li <?php echoActiveClassIfRequestMatches("index"); ?>><a href="index.php">Sign Out</a></li>
                    </ul>

                    <!-- Display Search group on bigger screen, e.g. Desktop -->
                    <form class="navbar-form navbar-right hidden-xs hidden-sm" role="search" action="search.php" method="post">
                        <div class="input-group">
                            <input type="text" class="form-control" name="txtSearch" placeholder="Search">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                            </div>
                        </div>
                    </form>   
                </div>
                <!-- nav-collapse -->

                <!-- Display only search button when screen size is reduced -->
                <form class="navbar-form navbar-left hidden-lg hidden-xs hidden-md" role="search" action="search.php" method="post">
                    <a class='btn btn-default' href='search.php' role='button'><span class="glyphicon glyphicon-search"></span></a>
                </form>
            </div>
        </div>
    </div>
</div>