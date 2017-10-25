<div id="ModalDisplayKey" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content resetmodal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
            <div class="modal-body">
                <div class="resetblock">
                    <h3>Please copy down your private key and keep it confidential.</h3>
                </div>
                
                <p><?php echo $_SESSION['KEY']; ?></p>
            </div>
        </div>
    </div>
</div>