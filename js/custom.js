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
    $('#fileManagerDataTable').DataTable( {
        'columnDefs' : [ 
        { 'visible': false, 'targets': [0] }
        ],
        "scrollX": true,
        "destroy": true,
        "paging":   true,
        "ordering": false,
        "info":     false,
        
    } );
    
     // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').click( function() {
        table.draw();
    } );
} );

/* DataTable */
/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#myDocumentBtn').val(), 10 );
        var max = parseInt( $('#shared').val(), 10 );
        var age = parseFloat( data[3] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);