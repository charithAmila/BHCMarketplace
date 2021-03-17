$(document).ready(function(){

    $(document).on("change", "#user-photo", function() {
      var accepted = ['gif', 'png', 'jpg'];
      var ext = $("#user-photo").val().split('.').pop().toLowerCase();
      if ($.inArray(ext, accepted) !== -1) {
        readUploadedPhoto(this, $('.user-logo'));
      }
    });

    $('#preferences-submit').click(function(){
        var count_errors = 0;
        $('.preferences-check').each(function(){
            if($(this).val() == ''){
                count_errors += 1;
                $(this).addClass('emptyVal');
                $(this).siblings('.preferences-error').text('This field is not allowed to be empty');
            }
        });

        if (count_errors == 0) {
            $('.preferences-check').removeClass('emptyVal');
            $('.preferences-error').text('');
            // $('#update-preferences').submit();
        }
    });

    $(document).on('propertychange input', '.preferences-check', function() {
        $(this).removeClass('emptyVal');
        $(this).siblings('.preferences-error').text('');
    });

    

});


function readUploadedPhoto(input, container) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            container.attr('src', e.target.result);
        }
    }
    reader.readAsDataURL(input.files[0]);
}

function launch_toast() {
    var x = document.getElementById("toast")
    x.className = "show";
    setTimeout(function(){ 
        x.className = x.className.replace("show", "");
    }, 5000);
}