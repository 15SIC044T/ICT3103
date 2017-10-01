//Dropzone - FileManager.php
$(function(){
  Dropzone.options.myAwesomeDropzone = {
    maxFilesize: 5, //MB
    addRemoveLinks: true,
    dictResponseError: 'Server not Configured',
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.mp3,.txt,.sql",
    init:function(){
      var self = this;
      // config
      self.options.addRemoveLinks = true;
      self.options.dictRemoveFile = "Delete";
      //New file added
      self.on("addedfile", function (file) {
        console.log('new file added ', file);
      });
      // Send file starts
      self.on("sending", function (file) {
        console.log('upload started', file);
        $('.meter').show();
      });
      
      // File upload Progress
      self.on("totaluploadprogress", function (progress) {
        console.log("progress ", progress);
        $('.roller').width(progress + '%');
      });

      self.on("queuecomplete", function (progress) {
        $('.meter').delay(999).slideUp(999);
      });
      
      // On removing file
      self.on("removedfile", function (file) {
        console.log(file);
      });
    }
  };
})

/* Datatable */
$(document).ready(function() {
    var dataTables = $('#fileManagerDataTable').DataTable( {
        'columnDefs' : [ 
        { 'visible': false, 'targets': [0] }
        ],
        "scrollX": true,
        "destroy": true,
        "paging":   true,
        "ordering": false,
        "info":     false,
        
    } ); 
    
    //Default: Show My Documents only
    dataTables.columns(0).search("1").draw();
    
    $('#myDocumentBtn').on('click', function () {
        dataTables.columns(0).search("1").draw();
    }); 
    
    $('#sharedWithMeBtn').on('click', function () {
        dataTables.columns(0).search("0").draw();
    }); 
} ); 