<?php 
// display error message
if (isset($_SESSION['error_msg'])) { ?>
    <div class="alert alert-danger alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['error_msg']; ?></div>
    </div>

    <?php
    unset($_SESSION['error_msg']);
} 

if (isset($_SESSION['success_msg'])) { // display successful message
    ?>
    <div class="alert alert-success alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['success_msg']; ?></div>
    </div>

    <?php
    unset($_SESSION['success_msg']);
} 

if (isset($_SESSION['neutral_msg'])) {
    ?>
    <div class="alert alert-info alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['neutral_msg']; ?></div>
    </div>

    <?php
    unset($_SESSION['neutral_msg']);
}
?>