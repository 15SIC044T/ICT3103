//Dropzone - FileManager.php
$(function(){
  Dropzone.options.myAwesomeDropzone = {
    maxFilesize: 5, //MB
    addRemoveLinks: true,
    dictResponseError: 'Server not Configured',
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.ico,.mp3,.txt,.sql,.pdf,.docx,.doc,.xlsx,.xls,.csv,.zip,.rar,.7z,.xml,.html,.htm,.php",
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
        dateTables.column(1).visible(false);
    }); 
    
    $('#sharedWithMeBtn').on('click', function () {
        dataTables.columns(0).search("0").draw();
        dateTables.column(1).visible(false);
    }); 
} ); 


/* Dynamically Add or remove textbox */
$(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"fileAction.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  