<?php if (isset($_SESSION['ERRMSG'])) { ?>
    <div class="alert alert-danger alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['ERRMSG']; ?></div>
    </div>

    <?php
    unset($_SESSION['ERRMSG']);
} elseif (isset($_SESSION['OKMSG'])) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['OKMSG']; ?></div>
    </div>

    <?php
    unset($_SESSION['OKMSG']);
} elseif (isset($_SESSION['NEUTRALMSG'])) {
    ?>
    <div class="alert alert-info alert-dismissable">
        <a class="panel-close close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></a> 
        <div style="text-align: center;"><?php echo $_SESSION['NEUTRALMSG']; ?></div>
    </div>

    <?php
    unset($_SESSION['NEUTRALMSG']);
}
?>