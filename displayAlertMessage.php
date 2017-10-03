
<?php 
//Display Error Message
if (!empty($_SESSION['error_msg'])) {
    ?>
    <div class="alert alert-danger fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><?php echo $_SESSION['error_msg'] ?> 
    </div>
    <?php
    unset($_SESSION['error_msg']);
}
?>


<?php 
//Display Success Message
if (!empty($_SESSION['success_msg'])) {
    ?>
    <div class="alert alert-success fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><?php echo $_SESSION['success_msg'] ?> 
    </div>
    <?php
    unset($_SESSION['success_msg']);
}
?>