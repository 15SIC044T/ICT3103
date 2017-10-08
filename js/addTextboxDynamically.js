$(document).ready(function () {
    var numberIncr = 1; // used to increment the name for the inputs

    function addInput() {
        $('#inputs').append($('<input class="form-control" name="name' + numberIncr + '" />'));
        numberIncr++;
    }

    $('form.commentForm').on('submit', function (event) {

        // adding rules for inputs with class 'comment'
        $('input.comment').each(function () {
            $(this).rules("add",
                    {
                        required: true
                    })
        });

        // prevent default submit action         
        event.preventDefault();

        // test if form is valid 
        if ($('form.commentForm').validate().form()) {
            console.log("validates");
        } else {
            console.log("does not validate");
        }
        
        var answers = [];
        $.each($('.field'), function () {
            answers.push($(this).val());
        });

        if (answers.length == 0) {
            answers = "none";
        }

        $.ajax({
            url: "fileAction.php",
            type: "POST",
            data: {answers: answers},
            success: function (result) {
                alert('success');
            }
        });
        
        
    })

    // set handler for addInput button click
    $("#addInput").on('click', addInput);

    // initialize the validator
    $('form.commentForm').validate();

});

